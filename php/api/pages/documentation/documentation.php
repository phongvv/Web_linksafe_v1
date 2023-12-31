<?php
  $active_menu = "documentation";
  include_once "header.php";
?>

<body class="hold-transition skin-yellow-light sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <link rel="stylesheet" href="documentation/style.css">

  <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
  <script src="docs.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "topmenu.php"; ?>
    <?php include_once "left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php include_once("documentation/main_header.php") ?>
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>
    <?php // include_once "right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "footer.php" ?>
<script src="documentation/docs.js"></script>
<script src="documentation/script.js"></script>