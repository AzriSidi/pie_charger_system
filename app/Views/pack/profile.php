<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">My Profile</h1>
    </div>
    <section class="vh-76">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-left align-items-left h-100">
                <div class="col-md-12 col-xl-4">
                    <div class="card" style="border-radius: 15px;">
                        <div class="card-body text-center">
                            <div class="mt-3 mb-4">
                                <img src="../img/undraw_profile.svg" 
                                    class="rounded-circle img-fluid" style="width: 100px;" />
                            </div>
                            <h4 class="mb-2"><?=$name?></h4>
                            <p class="text-muted mb-4"><?=$role?> <span class="mx-2">|</span> <?=$batchNo?></p>
                            <button type="button" class="btn btn-primary btn-rounded btn-lg">
                               Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<style>
.vh-76{
    height: 76vh;
}
</style>