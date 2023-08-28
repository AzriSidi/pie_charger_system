<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Search SN</h1>
    </div>
    <!-- Search Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Form</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-1 col-form-label">Model</label>
                    <div class="col-md-3 col-xs-5">
                        <select class="form-control form-control-sm" id="model">
                            <option value="">--Select Model--</option>
                            <?php
                                for($i=0;$i<count($model);$i++){                                                                                         
                            ?>
                            <option value="<?=$model[$i]?>"><?=$model[$i]?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-1"></div>
                    <label for="staticEmail" class="col-sm-1 col-form-label">Date</label>
                    <div class="col-sm-3">
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
                    <label for="inputPassword" class="col-sm-1 col-form-label">SN</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="sn" placeholder="Enter SN">
                    </div>
				</div>  
                <div class="col text-center">
                    <button type="button" class="btn btn-primary" id="searchBtn">Search</button>
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
                <table class="table table-bordered" id="getSN" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Model</th>
                            <th>Box Id</th>
                            <th>SN</th>
							<th>Delete Box Id</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<!-- Modal -->
<div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Deleting box id - <span id="box_id">
      </div>
      <div class="modal-footer">
        <button type="button" id="confDel" class="btn btn-danger" data-dismiss="modal">Delete</button>
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<script>  
    $(document).ready(function(){
		function initTable(){
            // datatable default
            $('#getSN').DataTable({
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
            form["model"] = $('#model').val();
            form["boxId"] = $('#boxId').val();
            form["sn"] = $('#sn').val();
            form["date_strt"] = $('#date_strt').val();
            form["date_end"] = $('#date_end').val();     
            if(form != null){               
                $('#getSN').DataTable().destroy();                
                sendTable(form);
			}
        });

        function resetForm(){
            // clear form
            $('#model').val('');
            $('#boxId').val('');
            $('#sn').val('');
            
            // set default today date
            $('#date_strt').val(today);
            $('#date_end').val(today);

            // clear datatable            
            $('#getSN').DataTable().clear().destroy();
            initTable();
        }

        // reset btn
        $('#resetID').click(function(){
			resetForm()
		});        

        function sendTable(form){
            var table = $('#getSN').DataTable({
                "bPaginate": true,
                "bFilter": false,
                "bInfo": true,
                "order": [[ 1, 'desc' ]],
                "processing": true,
				"serverSide": false,
                "pageLength": 10,
                "pagingType": "full_numbers",              
				"ajax": {
					"url":"<?php echo base_url('/pack/searchDataSN');?>",
					"type": "POST",
					"data": {
                        model : form["model"],                                           
                        boxId : form["boxId"],
                        sn : form["sn"],       
                        date_strt : form["date_strt"],
                        date_end : form["date_end"],
                    },
				},
                "language": {
                    "zeroRecords": "No matching records found"
                },				
                "columns": [
                    { "data": "#" },
					{ "data": "model" },
					{ "data": "boxId" },
					{ "data": function(data, type, dataToSet){
                        const sn = data.sn;
                        let result = "";
                        for(let i=0; i<sn.length;i++){
                            result += sn[i].split(',').join('<br/>');
                        }
                        return result;
                    } },
                    {
                        "targets": 4,
                        "data": null,
                        render:function(data, type, row){
                            return '<button type="button" id="deleteId" data-toggle="modal" data-target="#openModal" data-id="'+data["boxId"]+'" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></button>';
                        },                        
                    }
				],
                "columnDefs": [],
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
                "initComplete": function(settings, json) {}
            });

            $('#openModal').on('show.bs.modal', function(e) {
                var boxId = $(e.relatedTarget).data('id');          
                $('#box_id').text(boxId);
                $('#confDel').attr('value', boxId);
            });

            $('#confDel').click(function(){
                var boxId = $(this).val();
                <?php
                    if($name != null){
                ?>
                    var url = "<?php echo base_url('/pack/deleteSN');?>";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: { box_id : boxId },
                        dataType: "json",
                        success:function(res){
                            console.log("res: "+res.msg)
                            if(res.msg == "success"){
                                resetForm();
                            }                    
                        }
                    });
                <?php
                    }else{
                ?>
                    var url = "<?php echo base_url('/pack/login');?>";
                    location.href = url;
                <?php
                    }
                ?>
            });
        }
    });
</script>  