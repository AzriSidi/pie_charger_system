<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

	<!-- Topbar -->
	<nav class="mb-4 static-top shadow">
	</nav>
	<!-- End of Topbar -->

		<!-- Begin Page Content -->
		<div class="container-fluid">

			<!-- Page Heading -->
			<h1 class="h3 mb-2 text-gray-800">Upload CSV</h1>
				<div class="card shadow mb-4">
					<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Upload CSV File</h6>
                        </div>
					<div class="card-body">
					<div class="mt-2">
						<div class="alert alert-class" id="alertMgs">
							<div id="message"></div>
						</div>
						<?php if (session()->has('message')){ ?>
							<!-- <div class="alert <?=session()->getFlashdata('alert-class') ?>">
								<?=session()->getFlashdata('message') ?>
							</div> -->
						<?php } ?>
						<?php $validation = \Config\Services::validation(); ?>
					</div>	
						<form id="upload_csv_form" method="post" enctype="multipart/form-data">
							<div class="form-group mb-3">
								<div class="mb-3">
									<input type="file" name="file" id="file" class="form-control">
								</div>					   
							</div>
							<div class="d-grid">
								<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
							</div>
						</form>
					</div>
				</div>
		</div>
    	<!-- /.container-fluid -->
	</div>
	<!-- End of Main Content -->
<script>  
    $(document).ready(function(){
		$("#alertMgs").hide();
		var url = "<?php echo base_url('import-csv');?>";
        $('#upload_csv_form').on("submit", function(e){
            e.preventDefault();  
            $.ajax({
                url:url,
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success: function(data){					
					$('#alertMgs').show();
					$('#file').val('');
					const obj = JSON.parse(data);
					var alert = obj.alertClass;
					var message = obj.message;
					$('#message').text(message);

					var currentAlert = $("#alertMgs").attr('class');
					if(currentAlert == "alert alert-class"){
						$('.alert').removeClass('alert-class').addClass(alert);					
					}else{
						$('.alert').removeClass(currentAlert).addClass('alert alert-class');
						$('.alert').removeClass('alert-class').addClass(alert);
					}					
                }
            })
        });
    });
</script>  