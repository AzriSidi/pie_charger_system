<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
    
        <!-- Topbar -->
        <nav class="mb-4 static-top shadow">
        </nav>
    
            <!-- Begin Page Content -->
            <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Overview By Today</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Test</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTest;?></div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPass;?></div>
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
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Fail
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $totalFail;?></div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $yieldRate;?>%</div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        style="width: <?= $yieldRate;?>%" aria-valuenow="<?= $yieldRate;?>" aria-valuemin="0"
                                                        aria-valuemax="100">
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
                        <div class="col-lg-8 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $j=1;
                                                if(!empty($getModel)){
                                                    for($i=0;$i<count($getModel['model']);$i++){
                                                        $testYieldRate[$i] = "";
                                                        try {
                                                            $testYieldRate[$i] = number_format(($testTotalPass[$i]/$testTotalByModel[$i]) * 100,2);
                                                        } catch (\Exception $e) {
                                                            // secho($e->getMessage());
                                                        }
                                            ?>
                                            <tr>
                                                <th scope="row"><?=$j?></th>
                                                <td><?=$getModel['model'][$i]?></td>
                                                <td><?=$testTotalByModel[$i]?></td>
                                                <td><?=$testTotalPass[$i]?></td>
                                                <td><?=$testTotalFail[$i]?></td>
                                                <td><?=$testYieldRate[$i]?>%</td>
                                            </tr>
                                            <?php
                                                    $j++;
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Content Column -->
                        <div class="col-md-4 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Failed Test Name</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                        if(!empty($getFtn)){
                                            for($i=0;$i<count($getFtn['failed_test_name']);$i++){
                                                if($getFtn['ftn'][$i] > 0 && $getFtn['ftn'][$i] < 5){
                                                    $colorCode = "success";
                                                }elseif($getFtn['ftn'][$i] > 5 && $getFtn['ftn'][$i] < 8){
                                                    $colorCode = "warning";
                                                }elseif($getFtn['ftn'][$i] > 8){
                                                    $colorCode = "danger";
                                                }                                                         
                                    ?>
                                    <h4 class="small font-weight-bold"><?=$getFtn['failed_test_name'][$i]?><span
                                        class="float-right"><?=$getFtn['ftn'][$i]?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-<?=$colorCode?>" role="progressbar" style="width: <?=$getFtn['ftn'][$i]?>%"
                                            aria-valuenow="<?=$getFtn['ftn'][$i]?>"></div>
                                    </div>
                                    <?php 
                                            }
                                        }
                                    ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->