<?php

$active_menu = "account";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    $edge_id = $_SESSION['edge_id'];
    //include_once "header.php";
    include_once "head.php";

    $statuss = check_status_edge($edge_id);
    if ($statuss == true) {
        // $url_syn = "http://$serverip/api/sync-config";
        // $data = array(
        //     "edgeId" => $_SESSION['edge_id']
        // );
        // $test = call_api('POST', $token, $url_syn, json_encode($data));
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
                        $url = "http://$serverip/api/group/" . $_SESSION['edge_id'];
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
                    // $url_syn = "http://$serverip/api/sync-config";
                    // $data = array(
                    //     "edgeId" => $_SESSION['edge_id']
                    // );
                    // $test = call_api('POST',$token,$url_syn,json_encode($data));
                    header("Location:account?id=group");
                    exit;
                }
                break;
            case "user":
                $active_menu = "user";
                if (isset($_REQUEST['idd'])) {
                    if ($_REQUEST['idd'] == "0") {
                        $input_errors[] = sprintf(gettext("Can't Delete this User!"));
                    } else {
                        $url = "http://$serverip/api/user/" . $_SESSION['edge_id'];
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
                    // $url_syn = "http://$serverip/api/sync-config";
                    // $data = array(
                    //     "edgeId" => $_SESSION['edge_id']
                    // );
                    // $test = call_api('POST',$token,$url_syn,json_encode($data));
                    header("Location:account?id=user");
                    exit;
                }
                break;
            case "device":
                $active_menu = "device";

                // $url_syn = "http://$serverip/api/sync-config";
                // $data = array(
                //     "edgeId" => $_SESSION['edge_id']
                // );
                // call_api('POST', $token, $url_syn, json_encode($data));
                // unset($data);
                // unset($url_syn);

                if (isset($_REQUEST['idd'])) {
                    // $url1 = "http://$serverip/api/sync-config/".$edge_id;
                    // call_api('GET', $token, $url1, false);
                    $api = get_end_device($_SESSION["edge_id"], false);
                    $resuff = $api[$_REQUEST['idd']];
                    $ip = $resuff["ip"];
                    $url = "http://$serverip/api/deauth-device/" . $_SESSION['edge_id'];
                    $data = json_encode(array(
                        "lobby" => [$ip],
                    ));
                    $api_result = call_api('POST', $token, $url, $data);
                    sleep(1);
                    // $api_result = call_api('POST',$token,$url,$data);
                    $input_success[] = sprintf(gettext("Deauthenticated Device successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:account?id=device");
                    exit;
                }
                if (isset($_REQUEST['idb'])) {
                    $api = get_end_device($_SESSION["edge_id"], false);
                    $resuff = $api[$_REQUEST['idb']];
                    $ip = $resuff["ip"];
                    $url = "http://$serverip/api/accept-device/" . $_SESSION['edge_id'];
                    $data = json_encode(array(
                        "userid" => $resuff["user_id"],
                        "lobby" => [$ip],
                    ));
                    $api_result = call_api('POST', $token, $url, $data);
                    sleep(1);
                    $input_success[] = sprintf(gettext("Authenticated Device successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:account?id=device");
                    exit;
                }
                if (isset($_REQUEST['idc'])) {

                    $api = get_end_device($_SESSION["edge_id"], false);
                    $resuff = $api[$_REQUEST['idc']];
                    $ip = $resuff["ip"];
                    $url = "http://$serverip/api/accept-device/" . $_SESSION['edge_id'];
                    $data = json_encode(array(
                        "userid" => $_REQUEST["user"],
                        "lobby" => [$ip],
                    ));
                    $api_result = call_api('POST', $token, $url, $data);
                    sleep(1);
                    // $api_result = call_api('POST',$token,$url,$data);
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
                            $url = "http://$serverip/api/groups/" . $_SESSION['edge_id'];
                            $api_result = call_api("GET", $token, $url, false);
                            include_once "account_header_group.php";
                            break;
                        case "user":
                            if ($_SESSION['input_errors']) {
                                print_error_box($_SESSION['input_errors']);
                                unset($_SESSION['input_errors']);
                            }
                            $url = "http://$serverip/api/users/" . $edge_id;
                            $api_result = call_api("GET", $token, $url, false);
                            include_once "account_header_user.php";
                            break;
                        case "device":
                            $api = get_end_device($_SESSION["edge_id"], false);
                            $_SESSION['device'] = $api;
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
    <?php }
}
