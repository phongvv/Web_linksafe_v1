<?php
$active_menu = "network";
require_once("functions.inc");
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    global $serverip, $token;
    $edge_id = $_SESSION['edge_id'];
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        switch ($_REQUEST['id']) {
            default:
                $active_menu = "interface";
                break;
            case "vpn":
                $active_menu = "vpn";
                if (isset($_REQUEST['idd'])) {
                    $url = "http://$serverip/api/vpn/" . $_SESSION['edge_id'];
                    $data = array(
                        "name" => $_REQUEST['idd']
                    );
                    $api_result = call_api("DELETE", $token, $url, json_encode($data));
                    header("Location:network?id=vpn");
                    exit;
                }
                break;
            case "smartfw":
                $active_menu = "smartfw";
                if (isset($_REQUEST['idd'])) {
                    $url = "http://$serverip/api/app-web/";
                    $data = array(
                        "deviceId" => $edge_id,
                        "name" => $_REQUEST['idd']
                    );
                    $api_result = call_api("DELETE", $token, $url, json_encode($data));
                    header("Location:network?id=smartfw");
                    exit;
                }
                break;
        }

        if ($_POST['action_int'] == "save") {
            unset($input_errors);
            $pconfig = $_POST;

            if ($pconfig['ipv4'] == "none" && isset($pconfig['ipaddress']) || $pconfig['ipv4'] == "static" && empty($pconfig['ipaddress'])) {
                $input_errors[] = sprintf(gettext("Ip Address cannot be an empty string."));
            }

            if ($pconfig['ipv4'] == "none" && empty($pconfig['netmask'])) {
                $input_errors[] = sprintf(gettext("Netmask cannot be an empty string."));
            }

            if (!$input_errors) {
                $url = "http://$serverip/api/interface/lan/" . $edge_id;
                $data = array(
                    "ip" => $pconfig["ipaddress"],
                    "netmask" => $pconfig["netmask"]
                );
                // $data = array();
                // $data['ip'] = $pconfig["ipaddress"];
                // $data['netmask'] = $pconfig["netmask"];
                $api_result = call_api("PATCH", $token, $url, json_encode($data));
                header("Location:network");
                exit;
            }
        }
?>
        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 1000px;">
                    <!-- content-page  -->
                    <?php
                    switch ($_REQUEST['id']) {
                        default:
                            include_once "interface_header.php";
                            break;
                        case "vpn":
                            include_once "vpn_header.php";
                            break;
                        case "smartfw":
                            include_once "smartfw_header.php";
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
}
