<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="mb-4 static-top shadow"></nav>
        <!-- End of Topbar -->
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Overview By Today</h1>
                <!-- Content Row -->
                <div class="row">
                    <!-- Overview Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Test</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><spam id="total_test"><spam></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Pass</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><spam id="total_pass"><spam></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Fail Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Fail</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><spam id="total_fail"><spam></div>
                                                </div>                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Yield Rate</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><spam id="yield_rate"><spam>%</div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-warning" id="progress-bar" role="progressbar"
                                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa-solid fa-percent fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Content Column -->
                        <div class="col-lg-12 mb-5">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-5">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Test By Model</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Model</th>
                                            <th scope="col">Total Test</th>
                                            <th scope="col">Total Pass</th>
                                            <th scope="col">Total Fail</th>
                                            <th scope="col">Yield Rate</th>                                            
                                            <th scope="col">View Fail</th>
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
            </div>
            <!-- End of Main Content -->
            <!-- Modal -->
            <div class="modal fade" id="failModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">View Test Fail -  <span id="model"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="body" class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
<script>
$(document).ready(function(){
    var ip = "<?php echo $_SERVER['SERVER_ADDR']; ?>";
    var socket = io.connect('http://'+ip+':4000');
    socket.on('users connected', function(data){
        console.log('Users connected: ' + data)
    })

    socket.on('users disconnect', function(data){
        console.log('Users disconnect: ' + data)
    })

    socket.on('total_test', function(data){
        var total = ''
        for (var i = 0; i < data.length; i++){
            total = data[i].total
        }
        $('#total_test').html(total)
    })

    socket.on('total_pass', function(data){
        var total_pass = ''
        for (var i = 0; i < data.length; i++){
            total_pass = data[i].total_pass
        }
        $('#total_pass').html(total_pass)
    })

    socket.on('total_fail', function(data){
        var total_fail = ''
        for (var i = 0; i < data.length; i++){
            total_fail = data[i].total_fail
        }
        $('#total_fail').html(total_fail)
    })

    socket.on('yield_rate', function(data){
        var yieldRate = ''
        for (var i = 0; i < data.length; i++){
            yieldRate = data[i]
        }
        $('#yield_rate').html(yieldRate)            
        $("#progress-bar")
            .css("width", yieldRate + "%")
            .attr("aria-valuenow", yieldRate);
    })

    socket.on('total_test_byModel', function(data){
        $("#testRow").empty()
        var model = ""
        var total = ""
        var total_pass = "" 
        var total_fail = ""
        var style = "" 
        var j = 1;
        for (var i = 0; i < data.length; i++){                     
            model = data[i].model
            total = data[i].total_test
            total_pass = data[i].total_pass
            total_fail = data[i].total_fail
            let totalTest = parseInt(total)
            let totalPass = parseInt(total_pass)
            let yr = (totalPass / totalTest) * 100
            let yield_rate = yr.toFixed(2)
            if(total_fail == 0){
                style = "disabled"
            }else{
				style = "enabled"
			}
            $("#testRow").append("<tr><th scope='row'>"+j+"</th><td>"+model+"</td><td>"+total+"</td>"+
                "<td>"+total_pass+"</td><td>"+total_fail+"</td><td>"+yield_rate+"%</td>"+
                "<td><button type='button' id='btnFail' value='"+model+"' "+style+
                " class='btn btn-danger' data-toggle='modal' data-target='#failModal'>View</button></td></tr>")
            j++
        }
    })
        
    $('.table').on('click', '#btnFail', function(){        
        var url = "<?php echo base_url('/scan/viewFailByModel');?>"
        var model = $(this).val();
        var fail_name = ''
        var ftn = ''
        var colorCode = ''
        $('#model').text(model)
        $("#body").empty()
        $.ajax({
            type: "POST",
            url: url,
            data: {'model':model},
            dataType: "json",
            cache: false,
            success: function(res){
                if(res != null && res !=""){
                    for (var i = 0; i < res.ftn.length; i++){
                        fail_name = res.failed_test_name[i]
                        ftn = res.ftn[i]
                        if(ftn > 0 && ftn < 6){
                            colorCode = "success";
                        }else if(ftn > 5 && ftn < 11){
                            colorCode = "warning";
                        }else if(ftn > 10){
                            colorCode = "danger";
                        }
                        $("#body").append("<h4 class='small font-weight-bold'>"+
                        "<spam>"+fail_name+"</spam><spam class='float-right'>"+ftn+"</spam></h4>"+
                        "<div class='progress mb-4'>"+
                        "<div class='progress-bar bg-"+colorCode+"' role='progressbar'"+
                        "style='width: "+ftn+"%' aria-valuenow='"+ftn+"'></div>")
                    }
                }
            }
        })
    });        
});
</script>
<style>
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        height: 80vh;
        overflow-y: auto;
    }
</style>