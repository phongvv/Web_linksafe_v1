<?php
  $active_menu = "inbox";
  include_once "header.php";
?>

<body class="hold-transition skin-yellow-light sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/flat/blue.css">

  <!-- iCheck -->
  <script src="../../plugins/iCheck/icheck.min.js"></script>


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "topmenu.php"; ?>
    <?php include_once "left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php include_once("inbox/main_header.php") ?>
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>
    <?php // include_once "right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "footer.php" ?>
<script src="inbox/script.js"></script>