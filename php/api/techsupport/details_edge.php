<?php
$active_menu = "account";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_techsupport(5);
if ($checks == true) {
    $type = "Tech-Support";
    if (isset($_REQUEST['idd'])) {
        $url = "http://$serverip/api/delete/service-account";
        $token = $_SESSION['accesstoken'];
        $data = json_encode(array(
            "type" => "Tech-Support",
            // "id" => $_REQUEST['idd']
        ));
        $api_result = call_api('DELETE', $token, $url, $data);
        header("Location:infor_edge");
        exit;
    }
    $type = "Tech-Support";
    $url = "http://$serverip/api/get/service-account";
    $data = array(
        "type" => $type,
        // "id" => '31'
    );
    $data_json = json_encode($data, JSON_PRETTY_PRINT);
    $api_result = call_api('GET', $_SESSION['accesstoken'], $url, $data_json);
    // var_dump($api_result);
?>
    <?php
    $active_menu = "simple_tables";
    include_once "head.php";
    ?>
    

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper">
                <!-- content-page  -->
                <?php include_once("details_edge_header.php") ?>
            </div>

            
            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
<?php }
