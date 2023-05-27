<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
        $uri = current_url(true);
        if($uri->getSegment(3) == "dashboard"){
            $title = "Dashboard";
        }
        if($uri->getSegment(3) == "searchItems"){
            $title = "Search Items";
        }
        if($uri->getSegment(3) == "addModel"){
            $title = "Add Model";
        }
        if($uri->getSegment(3) == "editModel"){
            $title = "Edit Model";
        }
        if($uri->getSegment(3) == "uploadInfo"){
            $title = "Upload Info";
        }
        if($uri->getSegment(3) == "login"){
            $title = "Login";
        }
        if($uri->getSegment(3) == "register"){
            $title = "Register";
        }
        if($uri->getSegment(3) == "profile"){
            $title = "Profile";
        }
    ?>
    <title>Pack SYS - <?=$title?></title>
    <link rel="icon" type='image/x-icon' href='../img/pie.ico'>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/fonts-googleapis.css" rel="stylesheet">
    
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for datatables -->    
    <link href="../vendor/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../vendor/datatables/Buttons-2.2.3/css/buttons.bootstrap4.min.css" rel="stylesheet">

    <!-- mdbootstrap -->
    <link href="../vendor/mdbootstrap/css/mdb.min.css" rel="stylesheet">    
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">