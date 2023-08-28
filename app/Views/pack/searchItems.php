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
            <form>
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
                    <button type="button" class="btn btn-primary" id="searchBtn">Search</button>                    
                    <!-- <input type="button" id="searchBtn" class="btn btn-primary" value="Search"> -->
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
                            <th>Gross Weight (Kg)</th>
                            <th>Plant Id</th>
                            <th>Line No</th>
                            <th>Shift</th>                                            
                            <th>Packed By</th>
                            <th>Date Time</th>
                            <th>View SN</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="snModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Box ID - <span id="box_id"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="results" class="table table-bordered">
                    <thead>
                        <tr>
                            <td style='text-align:center; vertical-align:middle' class="fix">#</td>
                            <td>SN</td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>      
    </div>
</div>
<!-- /.container-fluid -->
<script>
    $(document).ready(function() {
        function initTable(){    
            // datatable default
            $('#tablePack').DataTable({
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false
            });
        }

        initTable();

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
			// clear form
            $('#custNo').val('');
            $('#boxId').val('');
            $('#lineNo').val('');
            $('#packedBy').val('');
            $('#shift').val('');
            
            // set default today date
            $('#date_strt').val(today);
            $('#date_end').val(today);

            // clear datatable
            var tablePack = $('#tablePack').DataTable();
            tablePack.clear().destroy();
            initTable();
		});

        $('#snModal').on('hidden.bs.modal', function () {
            $('#results tbody').empty();
        });

        function sendTable(form){
            $url = "<?php echo base_url('/pack/testDatatables');?>"
            var table = $('#tablePack').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pagingType: 'full_numbers',
                responsive: true,
                order: [[1, 'asc']],
                ajax: {
                    url: $url,
                    method: 'POST',
                    data: {
                        custNo : form["custNo"],
                        boxId : form["boxId"],                     
                        lineNo : form["lineNo"],               
                        date_strt : form["date_strt"],
                        date_end : form["date_end"],
                        packedBy : form["packedBy"],
                        shift : form["shift"],
                    },
                },
                columns: [
                    { "data": null,
                        "sortable": false, 
                        render: function(data, type, row, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
					{ "data": "cust_no" },
					{ "data": "box_id" },
					{ "data": "quantity_per_box" },
					{ "data": "gross_weight" },
					{ "data":"plant_id" },
					{ "data":"line_no" },
					{ "data":"shift" },
					{ "data":"packed_by" },
					{ "data":"date_time" },
                    {
                        "targets": 10,
                        "data": null,
                        render:function(data, type, row){
                            return '<button type="button" class="btn btn-info" data-toggle="modal" id="viewSN'+data["box_id"]+'" data-target="#snModal">View</button>';
                        },                        
                    }
				],
                columnDefs: [
                    {
                        targets: [ 0, 3, 5, 6, 7, 8 ],
                        className: "text-center"
                    }
                ],
                language: {
                    zeroRecords: "No matching records found"
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        "extend": 'pageLength',
                        "className": 'btn btn-dark'
                    },
                    {
                        "extend": 'excel',
                        "title": 'Data Packing Result',
                        "className": 'btn btn-success',
                        "action": newexportaction
                    },                     
                ],
                drawCallback: function(settings) {                          
                    var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
                    $('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                    $('.buttons-collection')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                    checkSN(settings.json);                  
                },
                initComplete: function(settings, json) {}
            });

            function checkSN(json){
                var url = "<?php echo base_url('/pack/checkSN');?>";
                var data = json.data;
                let boxId = data.map(u=>u.box_id);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {box_id: boxId},
                    success:function(response){
                        var data = response.data;                        
                        for (let i = 0; i < data.length; i++){                
                            if(data[i].sn == ""){                      
                                $("#viewSN"+data[i].box_id).attr("disabled", true);
                            }
                        }
                    }
                });
            }

            $('#tablePack tbody').on('click', 'button', function() {
                var url = "<?php echo base_url('/pack/searchSN');?>";
                var row = table.row($(this).parents('tr')).data();
                // console.log("row: "+JSON.stringify(row))
                const body = $('#results tbody');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: row,
                    dataType: "json",
                    success:function(response){
                        $('#box_id').text(row.box_id);
                        var data = response.data;                 
                        var sn = [];
                        let j = 1;                        
                        for(let i=0;i<data.length;i++){                            
                            sn = data[i].sn;                                                    
                            if(sn != ""){
                                body.append(
                                    $(`<tr>`),
                                    $(`<td style='text-align:center;vertical-align:middle;'>${j}</td>`),
                                    $(`<td>${sn}</td>`),
                                    $(`</tr>`),
                                );
                            }
                            j++;                       
                        }
                    }
                });
            });

            function newexportaction(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                dt.one('preXhr', function (e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = table.page.info().recordsTotal;
                    dt.one('preDraw', function (e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-copy') >= 0) {
                            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        }
                        dt.one('preXhr', function (e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });
                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 0);
                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });
                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            };
        }
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
        border-collapse: collapse;
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