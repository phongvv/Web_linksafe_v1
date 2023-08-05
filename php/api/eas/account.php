<?php

$active_menu = "account";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_eas($_SESSION['accounttype']);
if ($checks == true) {
    include_once "head.php";
    switch ($_REQUEST['id']) {
        default:
            $active_menu = "group";
            if (isset($_REQUEST['idd'])) {
                if (
                    $_REQUEST['idd'] == "1" || $_REQUEST['idd'] == "2" || $_REQUEST['idd'] == "3"
                    || $_REQUEST['idd'] == "4" || $_REQUEST['idd'] == "5" || $_REQUEST['idd'] == "6"
                    || $_REQUEST['idd'] == "7" || $_REQUEST['idd'] == "8" || $_REQUEST['idd'] == "9" || $_REQUEST['idd'] == "10"
                ) {
                    $input_errors[] = sprintf(gettext("Can't Delete this Group!"));
                } else {
                    $url = "http://$serverip/api/group";
                    $data = json_encode(array(
                        "id" => $_REQUEST['idd']
                    ));
                    $api_result = call_api('DELETE', $token, $url, $data);
                    if ($api_result['message'] != "RPCError" && $api_result['message'] != "null") {
                        $input_errors[] = sprintf(gettext($api_result['message']));
                        $_SESSION['input_errors'] = $input_errors;
                    } else {
                        $input_success[] = sprintf(gettext("Delete Group successful!"));
                        $_SESSION['input_success'] = $input_success;
                        header("Location:account?id=group");
                        exit;
                    }
                }
            } else if (isset($_REQUEST['sd'])) {
                header("Location:account?id=group");
                exit;
            }
            break;
        case "user":
            $active_menu = "user";
            if (isset($_REQUEST['idd'])) {
                if ($_REQUEST['idd'] == "1") {
                    $input_errors[] = sprintf(gettext("Can't Delete this User!"));
                } else {
                    $url = "http://$serverip/api/user";
                    $data = json_encode(array(
                        "userid" => $_REQUEST['idd']
                    ));
                    $api_result = call_api('DELETE', $token, $url, $data);
                    if ($api_result['message'] != "RPCError" && $api_result['message'] != "null") {
                        $input_errors[] = sprintf(gettext($api_result['message']));
                        $_SESSION['input_errors'] = $input_errors;
                    } else {
                        $input_success[] = sprintf(gettext("Delete User successful!"));
                        $_SESSION['input_success'] = $input_success;
                        header("Location:account?id=user");
                        exit;
                    }
                }
            } else if (isset($_REQUEST['sd'])) {
                header("Location:account?id=user");
                exit;
            }
            break;
        case "device":
            $active_menu = "device";
            if (isset($_REQUEST['idd'])) {
                $api = getEndDevice(false);
                $resuff = $api[$_REQUEST['idd']];
                $ip = $resuff["ip"];
                $url = "http://$serverip/api/deauth-device";
                $data = json_encode(array(
                    "lobby" => [$ip],
                ));
                sleep(1);
                $api_result = call_api('POST',$token,$url,$data);
                $input_success[] = sprintf(gettext("Deauthenticated Device successful!"));
                $_SESSION['input_success'] = $input_success;
                header("Location:account?id=device");
                exit;
            }
            if (isset($_REQUEST['idb'])) {
                $api = getEndDevice( false);
                $resuff = $api[$_REQUEST['idb']];
                $ip = $resuff["ip"];
                $url = "http://$serverip/api/accept-device";
                $data = json_encode(array(
                    "userid" => $resuff["user_id"],
                    "lobby" => [$ip],
                ));
                sleep(1);
                $api_result = call_api('POST', $token, $url, $data);
                $input_success[] = sprintf(gettext("Authenticated Device successful!"));
                $_SESSION['input_success'] = $input_success;
                header("Location:account?id=device");
                exit;
            }
            if (isset($_REQUEST['idc'])) {

                $api = getEndDevice(false);
                $resuff = $api[$_REQUEST['idc']];
                $ip = $resuff["ip"];
                $url = "http://$serverip/api/accept-device";
                $data = json_encode(array(
                    "userid" => $_REQUEST["user"],
                    "lobby" => [$ip],
                ));
                sleep(1);
                $api_result = call_api('POST',$token,$url,$data);
                $input_success[] = sprintf(gettext("Edit User device and Authenticated Device successful!"));
                $_SESSION['input_success'] = $input_success;
                header("Location:account?id=device");
                exit;
            }
            break;
    }

?>




    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 1000px;">
                <!-- content-page  -->

                <?php
                $token = $_SESSION['accesstoken'];
                switch ($_REQUEST['id']) {
                    default:
                        if ($_SESSION['input_errors']) {
                            print_error_box($_SESSION['input_errors']);
                            unset($_SESSION['input_errors']);
                        }
                        $url = "http://$serverip/api/groups";
                        $api_result = call_api("GET", $token, $url, false);
                        include_once "account_header_group.php";
                        break;
                    case "user":
                        if ($_SESSION['input_errors']) {
                            print_error_box($_SESSION['input_errors']);
                            unset($_SESSION['input_errors']);
                        }
                        $url = "http://$serverip/api/users";
                        $api_result = call_api("GET", $token, $url, false);
                        include_once "account_header_user.php";
                        break;
                    case "device":
                        $api = getEndDevice(false);
                        $_SESSION['device'] = $api;
                        global $serverip, $token;
                        sleep(1);
                        $url = "http://$serverip/api/local-device";
                        $api_result = call_api('GET', $token, $url, false);
                        $api_result1 = $api_result["data"];
                        include_once "account_header_device.php";
                        break;
                }
                ?>
                <!-- content-page  -->

            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
    <?php
}
