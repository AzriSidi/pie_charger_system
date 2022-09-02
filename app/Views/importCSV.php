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

			<div class="container mt-5">
				<div class="card">
					<div class="card-header text-center">
						<strong>Upload CSV File</strong>
					</div>
					<div class="card-body">
					<div class="mt-2">
						<?php if (session()->has('message')){ ?>
							<div class="alert <?=session()->getFlashdata('alert-class') ?>">
								<?=session()->getFlashdata('message') ?>
							</div>
						<?php } ?>
						<?php $validation = \Config\Services::validation(); ?>
					</div>	
						<form action="<?=site_url('import-csv') ?>" method="post" enctype="multipart/form-data">
							<div class="form-group mb-3">
								<div class="mb-3">
									<input type="file" name="file" class="form-control" id="file">
								</div>					   
							</div>
							<div class="d-grid">
								<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
    	<!-- /.container-fluid -->
	</div>
	<!-- End of Main Content -->