<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Search Items</h1>
    </div>
    <!-- Search Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Form</h6>
        </div>
        <div class="card-body">
            <form id="searchForm">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-1 col-form-label">Cust No</label>
                    <div class="col-md-3 col-xs-5">
                        <select class="form-control form-control-sm" id="custNo">
                            <option value="">--Select Cust No--</option>
                            <?php
                                for($i=0;$i<count($custNo);$i++){                                                                                         
                            ?>
                            <option value="<?=$custNo[$i]?>"><?=$custNo[$i]?></option>
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
                    <label for="inputPassword" class="col-sm-1 col-form-label">Box Id</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="boxId" placeholder="Enter Box Id">
                    </div>
                    <div class="col-sm-1"></div>
                    <label for="inputPassword" class="col-sm-1 col-form-label">Line No</label>
                    <div class="col-md-3 col-xs-4">
                        <select class="form-control form-control-sm" id="lineNo">
                            <option value="">--Select Line No--</option>                            
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>                            
                            <option value="9">9</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">Packed By</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="packedBy" placeholder="Enter Packed By">
                    </div>
                    <div class="col-sm-1"></div>
                    <label for="inputPassword" class="col-sm-1 col-form-label">Shift</label>
                    <div class="col-md-3 col-xs-4">
                        <select class="form-control form-control-sm" id="shift">
                            <option value="">--Select Shift--</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                </div>
                <div class="col text-center">                        
                    <input type="button" id="searchBtn" class="btn btn-primary" value="Search">
                    <input type="button" id="resetID" class="btn btn-dark" value="Reset">
                </div>
            </form>
        </div>
    </div>
    <!-- Search Result -->
    <div class="card shadow mb-4" id="tableCard">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Result</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablePack" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cust No</th>
                            <th>Box Id</th>
                            <th>Quantity Per Box</th>
                            <th>Gross Weight</th>
                            <th>Plant Id</th>
                            <th>Line No</th>
                            <th>Shift</th>                                            
                            <th>Packed By</th>
                            <th>Date Time</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>    
<!-- /.container-fluid -->
<script>
    $(document).ready(function() {       
        // datatable default
        $('#tablePack').DataTable({
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false
        });

        // set default today date
        var today = new Date().toISOString().split('T')[0];
        $('#date_strt').val(today);
        $('#date_end').val(today);

        // search btn
        $('#searchBtn').click(function(){
            var form = [];
            form["custNo"] = $('#custNo').val();
            form["boxId"] = $('#boxId').val();
            form["lineNo"] = $('#lineNo').val();   
            form["date_strt"] = $('#date_strt').val();
            form["date_end"] = $('#date_end').val();
            form["packedBy"] = $('#packedBy').val();
            form["shift"] = $('#shift').val();
            if(form != null){                
                $('#tablePack').DataTable().destroy();
                sendTable(form);
			}     
		});

        // reset btn
        $('#resetID').click(function() {
			$('#custNo').val('');
            $('#boxId').val('');
            $('#lineNo').val('');
            $('#packedBy').val('');
            $('#shift').val('');

            // set default today date
            $('#date_strt').val(today);
            $('#date_end').val(today);

            $('#tablePack').DataTable().clear().draw();
		});

        function sendTable(form = ''){
            $('#tablePack').DataTable({
                "bPaginate": true,
                "bFilter": false,
                "bInfo": true, 
                "order": [[ 1, 'desc' ]],
                "processing": true,
				"serverSide": false,
                "pageLength": 10,
                "pagingType": "full_numbers",              
				"ajax": {
					"url":"<?php echo base_url('/pack/searchData');?>",
					"type": "POST",
					"data": {
                        custNo : form["custNo"],
                        boxId : form["boxId"],                     
                        lineNo : form["lineNo"],               
                        date_strt : form["date_strt"],
                        date_end : form["date_end"],
                        packedBy : form["packedBy"],
                        shift : form["shift"],
                    },
				},
                "language": {
                    "zeroRecords": "No matching records found"
                },				
                "columns": [
                    { "data": "#" },
					{ "data": "cust_no" },
					{ "data": "box_id" },
					{ "data": "quantity_per_box" },
					{ "data": "gross_weight" },
					{ "data":"plant_id" },
					{ "data":"line_no" },
					{ "data":"shift" },                    
					{ "data":"packed_by" },
					{ "data":"date_time" },
				],
                "columnDefs": [
                    {
                        "targets": 9,
                        "data": null,
                        // "defaultContent": '',
                    },
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
                    // tableInit(json);
                }
            });
        }
    });
</script>
<style>
    .modal-dialog{
        position: relative;
        display: table;
        overflow-y: auto;
        overflow-x: auto;
		width: auto;
		position: fixed;
		margin: 0;
		padding: 0;
    }
    .modal-body{
        height: 750px;
        width: auto;
        overflow: auto;
        padding-top:0 !important;
    }
	.modal-content {
	  position: fixed;
	  color: black;
	  background-color: #fefefe;
	  margin: 10px;
	  padding: 10px;
	  border: 1px solid #888;
	  height: calc(100% - 20px);
	  width: calc(100% - 20px);
	  top:0;
	  left:0;
	  right: 10px;
	  botton: 10px;
	}
    #results {
        overflow: auto;
    }
    #results thead th{
        position: sticky;
        top: 0; 
        z-index: 1;
        box-shadow: 0 1px #D8D9DE, -1px 1px #D8D9DE;
        background-color: white;
    }
    #results tbody{
        height: auto;
        position: sticky;
        left: 0;
    }
	.fix {
	  position:sticky;
	  background: white;
	}
	.fix:first-child {
		left:0;
		width:180px;
	}
</style>