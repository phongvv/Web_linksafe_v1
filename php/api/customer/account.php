<?php
$active_menu = "account";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_ssa_customer(3);
if ($checks == true) {
    include_once "head.php";

    switch ($_REQUEST['id']) {
        case "1":
            $active_menu = "techsupport";
            $type = "Tech-Support";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/service-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "Tech-Support",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api["message"]));
                    $_SESSION['input_success'] = $input_success;
                } else {
                    unset($_SESSION["input_errors"]);
                    $input_success[] = sprintf(gettext("Delete techsupport account successful!"));
                    $_SESSION['input_success'] = $input_success;
                }
                header("Location:account?id=1");
                exit;
            }
            break;
        case "0":
            $active_menu = "callcenter";
            $type = "Call-Center";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/service-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "Call-Center",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api["message"]));
                    $_SESSION['input_success'] = $input_success;
                } else {
                    unset($_SESSION["input_errors"]);
                    $input_success[] = sprintf(gettext("Delete techsupport account successful!"));
                    $_SESSION['input_success'] = $input_success;
                }
                header("Location:account?id=0");
                exit;
            }
            break;
        case "2":
            $active_menu = "customer";
            $type = "Call-Center";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/service-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "Call-Center",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);
                if ($api_result["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api["message"]));
                    $_SESSION['input_success'] = $input_success;
                } else {
                    unset($_SESSION["input_errors"]);
                    $input_success[] = sprintf(gettext("Delete techsupport account successful!"));
                    $_SESSION['input_success'] = $input_success;
                }
                header("Location:account?id=2");
                exit;
            }
            break;
        case "3":
            $active_menu = "stock";
            $type = "Tech-Support";
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/service-account";
                $token = $_SESSION['accesstoken'];
                $data = json_encode(array(
                    "type" => "Tech-Support",
                    "id" => $_REQUEST['idd']
                ));
                $api_result = call_api('DELETE', $token, $url, $data);

                if ($api_result["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api["message"]));
                    $_SESSION['input_success'] = $input_success;
                } else {
                    unset($_SESSION["input_errors"]);
                    $input_success[] = sprintf(gettext("Delete techsupport account successful!"));
                    $_SESSION['input_success'] = $input_success;
                }
                header("Location:account?id=3");
                exit;
            }
            break;
    }

    $url = "http://$serverip/api/get/service-account";
    $data = array(
        "type" => $type,
        // "id" => '31'
    );
    $data_json = json_encode($data, JSON_PRETTY_PRINT);
    $api_result = call_api('GET', $_SESSION['accesstoken'], $url, $data_json);
    // var_dump($api_result);
?>

    
  
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" >
                <!-- content-page  -->



                <?php
                switch ($_REQUEST['id']) {
                    case "0":
                        include_once "ac_call_header.php";
                        break;
                    case "1":
                        include_once "ac_tech_header.php";
                        break;
                    case "2":
                        $api = get_customer();
                        include_once "ac_customer_header.php";
                        break;
                    case "3":
                        include_once "ac_stock_header.php";
                        break;
                }
                ?>
            </div>

            
            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
    </body>
<?php }
