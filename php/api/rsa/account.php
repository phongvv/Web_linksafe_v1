<?php
$active_menu = "account";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_rsa(0);
if ($checks == true) {
    switch ($_REQUEST['id']) {
        default:
            $active_menu = "tech";
            $type = "SSA-Tech";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/SSA-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "SSA-Tech",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] == "null") {
                    $input_success[] = sprintf(gettext("Delete SSA Tech successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:account?id=ssa-tech");
                    exit;
                }
            }
            break;
        case "ssa-business":
            $active_menu = "business";
            $type = "SSA-Business";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/SSA-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "SSA-Business",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] == "null") {
                    $input_success[] = sprintf(gettext("Delete SSA Business successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:account?id=ssa-business");
                    exit;
                }
            }
            break;
        case "ssa-customer":
            $active_menu = "customer";
            $type = "SSA-Customer";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/SSA-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "SSA-Customer",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] == "null") {
                    $input_success[] = sprintf(gettext("Delete SSA Customer successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:account?id=ssa-customer");
                    exit;
                }
            }
            break;
    }
    $url = "http://$serverip/api/get/SSA-account";
    $data = array(
        "type" => $type
    );
    $data_json = json_encode($data, JSON_PRETTY_PRINT);
    $api_result = call_api('GET', $_SESSION['accesstoken'], $url, $data_json);
    include_once "head.php";
?>

    <!--  -->
    

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 1000px;">
                <!-- content-page  -->
                <?php
                switch ($_REQUEST['id']) {
                    default:
                        include_once "account_header_tech.php";
                        break;
                    case "ssa-business":
                        include_once "account_header_business.php";
                        break;
                    case "ssa-customer":
                        include_once "account_header_customer.php";
                        break;
                }
                ?>
            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
<?php }
