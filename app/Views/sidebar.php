    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url()?>">
            <img src="img/PIE.jpg" style="width:40px;height:40px;border:solid 1px #CCC">    
            <div class="sidebar-brand-icon rotate-n-15">
                <link rel='shortcut icon' type='image/x-icon' href='img/pie.ico' />
            </div>
            <div class="sidebar-brand-text mx-4">Scanning System</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <?php
            $active = "";
            $uri = current_url(true);
            if($uri->getSegment(1) == ""){
                $active = "active";
            }
        ?>
        <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=base_url()?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Search Items -->
        <?php
            $active = "";
            $uri = current_url(true);
            if($uri->getSegment(1) == "searchItems"){
                $active = "active";
            }
        ?>
        <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=base_url()."/searchItems"?>">
            <i class="fa-solid fa-fw fa-table-list"></i>
            <span>Search Items</span></a>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Printed Label -->
        <?php
            $active = "";
            $uri = current_url(true);
            if($uri->getSegment(1) == "printLabel"){
                $active = "active";
            }
        ?>
        <!-- <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=base_url()."/printLabel"?>">
            <i class="fa-solid fa-fw fa-print"></i>
            <span>Printed Label</span></a>
        </li>

        <hr class="sidebar-divider my-0"> -->

        <!-- Nav Item - ImportCSV -->
        <?php
            $active = "";
            $uri = current_url(true);
            if($uri->getSegment(1) == "importCSV"){
                $active = "active";
            }
        ?>
        <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=base_url()."/importCSV"?>">
                <i class="fas fa-fw fa-file-import"></i>
                <span>Upload CSV</span>
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->