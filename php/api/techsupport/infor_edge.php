<?php
$active_menu = "account";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_techsupport(5);
if ($checks == true) {
    $type = "Tech-Support";
    // unset($_SESSION['input_errors']);
    // unset($_SESSION['input_success']);
    if (isset($_REQUEST['idd'])) {
        $id = $_REQUEST['idd'];
        $url = "http://$serverip/api/delete-edge/$id";
        $api_result = call_api('DELETE', $token, $url, false);
        if ($api_result['success'] != "true") {
            $_SESSION['input_errors'] = sprintf(gettext($api_result['message']));
        } else {
            $_SESSION['input_success'] = sprintf(gettext("Delete Edge Successful!"));
        }
        header("Location:infor_edge");
        exit;
    }
?>
    <?php
    $active_menu = "data_tables";
    include_once "head.php";

    ?>
    


    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 1000px;">
                <!-- content-page  -->
                <?php include_once("infor_edge_header.php") ?>

            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
<?php }
