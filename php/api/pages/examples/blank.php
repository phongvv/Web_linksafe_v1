<?php
  $active_menu = "blank";
  include_once "header.php";
?>

<body class="hold-transition skin-yellow-light sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "topmenu.php"; ?>
    <?php include_once "left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php include_once("blank/main_header.php") ?>
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>
    <?php // include_once "right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "footer.php" ?>
<script src="blank/script.js"></script>