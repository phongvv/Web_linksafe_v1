<?php
  $active_menu = "morris";
  include_once "header.php";
?>

<body class="hold-transition skin-yellow-light sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- Morris chart -->
  <link rel="stylesheet" href="../../plugins/morris/morris.css">

  <!-- Morris.js charts -->
  <script src="../../plugins/morris/morris.min.js"></script>

  <script src="../../plugins/raphael/raphael-min.js"></script> 


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "topmenu.php"; ?>
    <?php include_once "left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- <section class="content-header">
        <h1>
          Dashboard 
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section> -->

      <!-- Main content -->
      <section class="content">

        <?php include_once("morris/main_header.php") ?>
        
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>
    <?php // include_once "right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "footer.php" ?>
<script src="morris/script.js"></script>