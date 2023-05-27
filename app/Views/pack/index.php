<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Overview By Today</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="note note-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h3 mb-0 font-weight-bold mb-1">Total Packed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <spam id="total_pack"></spam>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="note note-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h3 mb-0 font-weight-bold mb-1">Total GrossWeight</div>
                            <div class="h5 mb-0 text-gray-800">
                                <spam id="total_gross"></spam> Kg
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight-hanging fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Column -->
        <div class="col-lg-12 mb-5">
            <!-- Project Card Example -->
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Total Packed By Cust No</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Cust No</th>
                                <th style="text-align:center;">Quantity Per Box</th>
                                <th style="text-align:center;">Line No</th>
                                <th style="text-align:center;">Shift</th>
                                <th style="text-align:center;">Packed By</th>                                                
                                <th style="text-align:center;">Total Packed</th>
                                <th>Total GrossWeight</th>
                            </tr>
                        </thead>
                        <tbody id="testRow">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<script>
$(document).ready(function(){
    var ip = "<?php echo $_SERVER['SERVER_ADDR']; ?>";
    var socket = io.connect('http://'+ip+':4000');
    socket.on('users connected',function(data){
        console.log('Users connected: ' + data)
    })

    socket.on('users disconnect',function(data){
        console.log('Users disconnect: ' + data)
    })

    socket.on('total_pack',function(data){
        var total = ''
        for (var i = 0; i < data.length; i++){
            total = data[i].total_pack
        }
        $('#total_pack').html(total)
    })

    socket.on('total_gross',function(data){
        $('#total_gross').html(data)
    })

    socket.on('total_pack_line_no', function(data){
        $("#testRow").empty()
        var j = 1;
        for (var i = 0; i < data.length; i++){
            var custNo = data[i].cust_no
            var quantityBox = data[i].quantity_per_box
            var lineNo = data[i].line_no
            var shift = data[i].shift
            var packedBy = data[i].packed_by
            var totalPacked = data[i].total_packed
            var grossWeight = data[i].gross_weight
            var fixedGrossWeight = grossWeight.toFixed(3)
            $("#testRow").append("<tr><th scope='row'>"+j+"</th><td>"+custNo+"</td>"+
                "<td style='text-align:center;'>"+quantityBox+"</td>"+
                "<td style='text-align:center;'>"+lineNo+"</td>"+
                "<td style='text-align:center;'>"+shift+"</td>"+
                "<td style='text-align:center;'>"+packedBy+"</td>"+
                "<td style='text-align:center;'>"+totalPacked+"</td>"+
                "<td>"+fixedGrossWeight+" Kg</td></tr>")
            j++
        }
    });
});
</script>