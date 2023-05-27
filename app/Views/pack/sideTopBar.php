<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url()."/pack"?>">
        <img src="../img/PIE.jpg" style="width:40px;height:40px;border:solid 1px #CCC">
        <div class="sidebar-brand-text mx-4">Packing System</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <?php
        $active = "";
        $uri = current_url(true);
        if($uri->getSegment(3) == "dashboard"){
            $active = "active";
        }
    ?>
    <li class="nav-item <?=$active?>">
        <a class="nav-link" href="<?=base_url()."/pack"?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">        
    <!-- Nav Item - Search Items -->
    <?php
        $active = "";
        $uri = current_url(true);
        if($uri->getSegment(3) == "searchItems"){
            $active = "active";
        }
    ?>
    <li class="nav-item <?=$active?>">
        <a class="nav-link" href="<?=base_url()."/pack/searchItems"?>">
        <i class="fa-solid fa-fw fa-table-list"></i>
        <span>Search Items</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Add Model -->
    <?php
        $active = "";
        $uri = current_url(true);
        if($uri->getSegment(3) == "addModel"){
            $active = "active";
        }
    ?>
    <li class="nav-item <?=$active?>">
        <a class="nav-link" href="<?=base_url()."/pack/addModel"?>">
        <i class="fa-solid fa-file-circle-plus"></i>
        <span>Add Model</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Edit Model -->
    <?php
        $active = "";
        $uri = current_url(true);
        if($uri->getSegment(3) == "editModel"){
            $active = "active";
        }
    ?>
    <li class="nav-item <?=$active?>">
        <a class="nav-link" href="<?=base_url()."/pack/editModel"?>">
        <i class="fa-solid fa-file-pen"></i>
        <span>Edit Model</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-3 topbar static-top shadow">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarColor02">
                  <ul class="navbar-nav ms-auto mb-2 mb-lg-0"></ul>
                    <ul class="navbar-nav">
                        <?php
                            if($name != null){
                        ?>
                        <li class="nav-item dropdown arrow">
                            <a class="nav-link dropdown-toggle"
                                href="#" id="navbarDropdownMenuLink"
                                role="button" data-mdb-toggle="dropdown"
                                aria-expanded="false">
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">&nbsp;
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$name?></span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="../pack/profile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400">                                        
                                    </i>Profile
                                </a>
                                <a class="dropdown-item" href="../pack/logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400">                                        
                                    </i>Logout
                                </a>
                            </div>
                        </li>
                        <?php
                            }else{
                        ?>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="../pack/login" irole="button"
                                aria-haspopup="true" aria-expanded="false">                        
                                <img class="img-profile rounded-circle" src="../img/undraw_rocket.svg">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span></a>
                        </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End of Topbar -->
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to logout.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="../pack/logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>