const app = require('express')();
const http = require('http').Server(app);
const io = require('socket.io')(http,{
  cors: {
    origin: '*',
  }
});

var ip = require('ip');
var getIP = ip.address();

const port = process.env.PORT || 4000;
app.get('/', (req, res) => res.send('Hello World! from Node.js'))

// Define/initialize our global vars
var socketCount = 0

var dsn = {
  connectionLimit: 1000,
  host: 'localhost',
  user: 'root',
  password: 'root',
  database: 'motorola_charger_db',
}

//MySQL npm install socket.io mysql
var mysql = require('mysql');
var db = mysql.createConnection(dsn)
const MySQLEvents = require('@rodrigogs/mysql-events');

// Log any errors connected to the db
db.connect(function(err){
  if (err) console.log("db err :"+err);
  else console.log('db connected');
})

db.on('error', function(err){
  if(err.code === 'PROTOCOL_CONNECTION_LOST'){
    console.log('this for handle disconnect');
  }if(err.code === 'ECONNRESET'){
    console.log('Connection ECONNRESET.')
    reconnect()
  }if(err.code === 'ECONNREFUSED'){
    console.log('Connection ECONNREFUSED!')
  }else{
    console.log('db error: ', err);
    reconnect()
  }
});

http.listen(port, () => {
  console.log(`Socket.IO server running at http://${getIP}:${port}/`);
})

io.on('connection', (socket) => {
  socketCount++    
  socket.emit('users connected', socketCount)
  console.log('Users connected: ' + socketCount)
  db_query_model_test()
  db_query_pack_data()
    
  const program = async () => {
    const instance = new MySQLEvents(dsn,{
      startAtEnd: true,
      excludedSchemas: {
        mysql: true,
      },
    });

    await instance.start();      
    instance.addTrigger({
      name: 'Insert database events from model_test_result',
      expression: 'motorola_charger_db.model_test_result',
      statement: MySQLEvents.STATEMENTS.INSERT,
      onEvent: () => {
        db_query_model_test()
      },
    });
    
    instance.addTrigger({
      name: 'Insert database events from packing_data',
      expression: 'motorola_charger_db.packing_data',
      statement: MySQLEvents.STATEMENTS.INSERT,
      onEvent: () => {
        db_query_pack_data()
      },
    });
    
    instance.on(MySQLEvents.EVENTS.CONNECTION_ERROR, console.error);
    instance.on(MySQLEvents.EVENTS.ZONGJI_ERROR, console.error);
  };

  program()
    .then(() => console.log('Waiting for database events...'))
    .catch(console.error);

  socket.on('disconnect', function() {
    socketCount--
    io.sockets.emit('users disconnect', socketCount)
    console.log('users disconnect: '+socketCount)    
  })
});

// Listen to 'error' events
io.on('error', error => {
  if (error.code === 'ECONNRESET') {
    console.log('Connection ECONNRESET.')
  } else if (error.code === 'ECONNREFUSED') {
    console.log('Connection ECONNREFUSED!')
  } else console.log("Error io: "+error.code)
});

function reconnect(){
  // Listen to 'reconnecting' event
  io.on('reconnecting', err => {
    if (io.status === 'reconnecting')
      console.log('Reconnecting to server...');
    else console.log('Error reconnecting...'+err);
  });

  // Listen to 'reconnect' event
  io.on('reconnect', err => {
    if (io.status === 'reconnect')
      console.log('Reconnect to server...');
    else console.log('Error reconnect...'+err);
  });
}

function db_query_model_test(){
  var notes = []
  var yield = []
  var byModel = []

  db.query("SELECT COUNT(*) as total FROM model_test_result where date(timestamp) = CURDATE()"
  ).on('result', function(data){    
    notes.push(data)
  }).on('end', function(){
    io.sockets.emit('total_test', notes)
  })

  db.query("SELECT COUNT(*) as total_pass FROM model_test_result where result='Pass' and date(timestamp) = CURDATE()"
  ).on('result', function(data){            
    notes.push(data)
  }).on('end', function(){
    io.sockets.emit('total_pass', notes)
  })

  db.query("SELECT COUNT(*) as total_fail FROM model_test_result where result in ('Fail','Abort') and date(timestamp) = CURDATE()"
  ).on('result', function(data){
    notes.push(data)
  }).on('end', function(){
    io.sockets.emit('total_fail', notes)
  })

  db.query("SELECT (SELECT COUNT(*) FROM model_test_result where date(timestamp) = CURDATE()) as total, "+
    "(SELECT COUNT(*) FROM  model_test_result WHERE result = 'Pass' and date(TIMESTAMP) = CURDATE()) as total_pass"
  ).on('result', function(data){
    let total = parseInt(data.total)
    let totalPass = parseInt(data.total_pass)
    let yr = (totalPass / total) * 100
    let yieldRate = yr.toFixed(2)
    yield.push(yieldRate)
    console.log("yieldRate: "+yieldRate)
  }).on('end', function(){
    io.sockets.emit('yield_rate', yield)
  })

  db.query("SELECT t1.model,t1.total_test,COALESCE(t2.total_pass,0) as "+
            "total_pass,COALESCE(t3.total_fail,0) as total_fail FROM "+
            "(SELECT DISTINCT(a.model),COUNT(*) AS total_test "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE b.TYPE IN(SELECT DISTINCT(b.type) "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE a.type=b.type AND date(b.timestamp) = CURDATE()) "+
            "AND a.type=b.type and DATE(b.timestamp) = CURDATE() "+
            "GROUP BY b.type) t1 "+
            "LEFT JOIN "+
            "(SELECT DISTINCT(a.model),COUNT(*) AS total_pass "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE b.TYPE IN(SELECT DISTINCT(b.type) "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE a.type=b.type AND date(b.timestamp) = CURDATE()) "+
            "AND a.type=b.type and DATE(b.timestamp) = CURDATE() "+
            "AND b.result='Pass' "+
            "GROUP BY b.type) t2 "+
            "ON t1.model=t2.model "+
            "LEFT JOIN "+
            "(SELECT DISTINCT(a.model),COUNT(*) AS total_fail "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE b.TYPE IN(SELECT DISTINCT(b.type) "+
            "FROM info_charger_scanning a, model_test_result b "+
            "WHERE a.type=b.type AND date(b.timestamp) = CURDATE()) "+
            "AND a.type=b.type and DATE(b.timestamp) = CURDATE() "+
            "AND b.result IN ('Fail','Abort') "+
            "GROUP BY b.type) AS t3 "+
            "ON t1.model=t3.model ORDER BY t1.total_test DESC"
  ).on('result', function(data){
    byModel.push(data)
  }).on('end', function(){
    io.sockets.emit('total_test_byModel', byModel)
  })
}

function db_query_pack_data(){
  var totalPack = []
  var totalGross = []
  var totalPackByLineNo = []

  db.query("SELECT COUNT(*) as total_pack FROM packing_data where date(timestamp) = CURDATE()"
  ).on('result', function(data){  
    totalPack.push(data)
    console.log("total pack: "+data.total_pack)
  }).on('end', function(){
    io.sockets.emit('total_pack',totalPack)
  })

  db.query("SELECT sum(gross_weight) as total_gross FROM packing_data where date(timestamp) = CURDATE()"
  ).on('result', function(data){
    if(data.total_gross != null){
      let grossWeight = data.total_gross.toFixed(3)
      totalGross.push(grossWeight)
    }
  }).on('end', function(){
    io.sockets.emit('total_gross',totalGross)
  })

  db.query("SELECT cust_no,quantity_per_box,line_no,shift,packed_by,COUNT(*) AS total_packed, "+
    "sum(gross_weight) AS gross_weight FROM packing_data WHERE date(timestamp) = CURDATE() "+
	  "GROUP BY cust_no ORDER BY line_no ASC"
  ).on('result', function(data){
    totalPackByLineNo.push(data)
  }).on('end', function(){
    io.sockets.emit('total_pack_line_no',totalPackByLineNo)
  })
}