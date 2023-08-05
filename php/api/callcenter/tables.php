<?php
    $active_menu = "account";
    require_once('functions.inc');
    global $token,$serverip;
    check_session("/api");
    $checks = check_permission_callcenter(4);
if ($checks == true ) {
    $type = "Call-Center";
    if (isset($_REQUEST['idd'])) {
        $url = "http://$serverip/api/delete/service-account";
        $token = $_SESSION['accesstoken'];
        $data = json_encode(array(
            "type" => "Call-Center",
            // "id" => $_REQUEST['idd']
        ));
        $api_result = call_api('DELETE',$token,$url,$data);
        header("Location:tables");
        exit;
    }
    $type = "Call-Center";
    $url="http://$serverip/api/get/service-account";
    $data=array(
        "type" => $type,
        // "id" => '31'
    );
    $data_json = json_encode($data,JSON_PRETTY_PRINT);
    $api_result = call_api('GET',$_SESSION['accesstoken'],$url,$data_json);
    // var_dump($api_result);
    // include_once "header";  
?>
<?php
  $active_menu = "data_tables";
  include_once "head.php";
?>

<body class="hold-transition skin-yellow-light sidebar-mini">
    <!-- Put Page-level css and javascript libraries here -->

    <!-- DataTables -->
    <link rel="stylesheet" href="/api/plugins/datatables/dataTables.bootstrap.css">


    <!-- DataTables -->
    <script src="/api/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/api/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <!-- ================================================ -->

    <div class="wrapper">

        <?php include_once "topmenu.php"; ?>
        <?php include_once "left-sidebar.php"; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php include_once("tables_header.php") ?>
        </div><!-- /.content-wrapper -->

        <?php include_once "../copyright.php"; ?>
        <?php // include_once "right-sidebar.php"; ?>

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php include_once "../footer.php" ?>
    <?php }
