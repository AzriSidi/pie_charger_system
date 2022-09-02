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
                <h1 class="h3 mb-2 text-gray-800">Search Items</h1>
                    <!-- Search Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search Form</h6>
                        </div>
                        <div class="card-body">
                            <form id="searchForm">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-1 col-form-label">Model</label>
                                    <div class="col-md-3 col-xs-5">
                                        <select class="form-control form-control-sm" id="model">
                                            <option value="">--Select Model--</option>
                                            <?php
                                                for($i=0;$i<count($model);$i++){                                                                                             
                                            ?>
                                            <option value="<?= $model[$i]?>"><?= $model[$i]?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">Date</label>
                                    <div class="col-sm-4">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-sm" id="date_strt" type="date" value="" autocomplete="off">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="font-size:13px">To</span>
                                        </div>
                                            <input class="form-control form-control-sm" id="date_end" type="date" value="" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Serial Number</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="sn" placeholder="Enter Serial Number">
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Result</label>
                                    <div class="col-md-3 col-xs-4">
                                        <select class="form-control form-control-sm" id="result">
                                            <option value="">--Select Result--</option>
                                            <option value="Pass">Pass</option>
                                            <option value="Fail">Fail</option>
                                            <option value="Abort">Abort</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Unique ID</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="uniqueId" placeholder="Enter Unique ID">
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Process Name</label>
                                    <div class="col-md-3 col-xs-4">
                                        <select class="form-control form-control-sm" id="processName">
                                            <option value="">--Select Process Name--</option>
                                            <?php
                                                for($i=0;$i<count($process_name);$i++){                                                                                             
                                            ?>
                                            <option value="<?= $process_name[$i]?>"><?= $process_name[$i]?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">                               
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Station ID</label>
                                    <div class="col-md-3 col-xs-4">
                                        <select class="form-control form-control-sm" id="stationId">
                                            <option value="">--Select Station ID--</option>
                                            <?php
                                                for($i=0;$i<count($station_id);$i++){                                                                                             
                                            ?>
                                            <option value="<?= $station_id[$i]?>"><?= $station_id[$i]?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label for="inputPassword" class="col-sm-1 col-form-label">Printed Label</label>
                                    <div class="col-md-3 col-xs-4">
                                        <select class="form-control form-control-sm" id="printedLabel">
                                            <option value="">--Select Printed Label--</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col text-center">                                    
                                    <input type="button" id="searchBtn" class="btn btn-primary" value="Search">
                                    <input type="button" class="btn btn-secondary" id="resetID" value="Reset">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" id="tableCard">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search Result</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Model</th>
                                            <th>Serial Number</th>
                                            <th>Unique Id</th>
                                            <th>Test Time</th>
                                            <th>Total Time</th>
                                            <th>Process Name</th>
                                            <th>Operator Id</th>
                                            <th>Station Id</th>
                                            <th>Fixture</th>
                                            <th>Result</th>
                                            <th>Failed Test Name</th>
                                            <th>Printed Label</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Content Column -->
                    <div id="ftnCard" class="col-md-4 mb-4">
                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Failed Test Name By Model - <span id="ftnModel"></span></h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableFTN" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Failed Test Name</th>
                                                <th>Count Failed</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>    
            <!-- /.container-fluid -->  
        </div>
        <!-- End of Main Content -->
<script>
    $(document).ready(function() {       
        // datatable default
        $('#dataTable').DataTable({
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false
        });

        $('#ftnCard').hide();

        // set default today date
        var today = new Date().toISOString().split('T')[0];
        $('#date_strt').val(today);
        $('#date_end').val(today);

        // search btn
        $('#searchBtn').click(function(){
            var form = [];
            form["model"] = $('#model').val();
            form["sn"] = $('#sn').val();
            form["stationId"] = $('#stationId').val();       
            form["date_strt"] = $('#date_strt').val();
            form["date_end"] = $('#date_end').val();
            form["result"] = $('#result').val();
            form["uniqueId"] = $('#uniqueId').val();
            form["processName"] = $('#processName').val();
            form["printedLabel"] = $('#printedLabel').val();
            // $("#searchForm").trigger("reset");

            if(form != null){                
                $('#dataTable').DataTable().destroy();
                $('#dataTableFTN').DataTable().destroy();
                sendTable(form);
			}

            if($('#model').val() != "" && 
                $('#result').val()=="Fail" ||
                $('#result').val()=="Abort"){              
                $('#ftnCard').show();
                $('#ftnModel').text(form["model"]);
            }else{
                $('#ftnCard').hide();
            }        
		});

        // reset btn
        $('#resetID').click(function() {
			$('#model').val('');
            $('#sn').val('');
            $('#stationId').val('');
            $('#result').val('');
            $('#uniqueId').val('');
            $('#processName').val('');
            $('#printedLabel').val('');

            // set default today date
            $('#date_strt').val(today);
            $('#date_end').val(today);

            $('#dataTable').DataTable().clear().draw();
            $('#ftnCard').hide();
		});

        function sendTable(form = ''){
            $('#dataTable').dataTable({
                "bPaginate": true,
                "bFilter": false,
                "bInfo": true,       
                "order": [[ 1, 'desc' ]],
                "processing": true,
				"serverSide": false,
                "pageLength": 10,
                "pagingType": "full_numbers",              
				"ajax": {
					"url":"<?php echo base_url('searchData');?>",
					"type": "POST",
					"data": {
                        model : form["model"],
                        sn : form["sn"],                        
                        stationId : form["stationId"],                    
                        date_strt : form["date_strt"],
                        date_end : form["date_end"],
                        result : form["result"],
                        uniqueId : form["uniqueId"],
                        processName : form["processName"],
                        printedLabel : form["printedLabel"],
                    },
				},
                "language": {
                    "zeroRecords": "No matching records found"
                },				
                "columns": [
                    { "data": "#" },
					{ "data": "model" },
					{ "data": "sn" },
					{ "data": "unique_id" },
					{ "data": "test_time" },
					{ "data":"total_time" },
					{ "data":"process_name" },
					{ "data":"operator_id" },
					{ "data":"station_id" },
					{ "data":"fixture" },
                    { "data":"result" },
                    { "data":"failed_test_name" },
                    { "data":"printed_label" },
				],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        "extend": 'pageLength',
                        "className": 'btn btn-dark'
                    },
                    {
                        "extend": 'excel',
                        "title": 'Data Search Result',
                        "className": 'btn btn-success'
                    },                     
                ],
                "drawCallback": function() {
                var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
                    $('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                    $('.buttons-collection')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                },
                "initComplete": function(settings, json) {
                    tableInit(json);
                }
            });
        }

        function tableInit(json){
            $('#dataTableFTN').dataTable({
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "processing": true,
				"serverSide": false,
                "data": json.dataFtn,
                "columns": [
                    { "data": "#" },
					{ "data": "failed_test_name" },
					{ "data": "ftn" },
				],
            });
        }
    });
</script>