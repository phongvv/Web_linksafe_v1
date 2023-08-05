<?php
// phpsession_begin();
$active_menu = "devices_bsa_edge";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_bsa(7);
if ($checks == true) {
    global $token, $serverip;
    switch ($_SESSION['accounttype']) {
        case "7":
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete-edge";
                $data = array(
                    "id" => $_REQUEST["idd"],
                );
                $api = call_api('DELETE', $token, $url, json_encode($data));
                header("Location: account_bsa_ea");
                exit;
            } else {
                $api_result = get_bsa_edge_list();
            }
            break;
        case "6":
            if ($_REQUEST['idd'] == 'del') {
                $pconfig = $_POST;
                if (!$input_errors) {
                    $url = "http://$serverip/api/branch";
                    $token = $_SESSION["accesstoken"];
                    $data = array(
                        "address" => $_POST["address"],
                        "department" => $_POST["department"],
                        "email" => $_POST["email"],
                        "fullname" => $_POST["fullname"],
                        "name" => $_POST["name"],
                        "organization" => $_POST["organization"],
                        "password" => $_POST["password"],
                        "phone" => $_POST["phone"],
                        "type" => "LSA_BSA"
                    );
                    $api = call_api('POST', $token, $url, json_encode($data));
                    header("Location: account_hsa_bsa");
                    exit;
                }
            } else {
                $url = "http://$serverip/api/edge-Available";
                $token = $_SESSION["accesstoken"];
                $api_result1 = call_api('GET', $token, $url, false);
                if ($api_result1["data"][0]["total edge"] != null) {
                    if ($api_result1["data"][1]["Available"] != null) {
                        foreach ($api_result1["data"][1]["Available"] as $j => $res) {
                            $api_result[$j] = $res;
                            $api_result[$j]["Status"] = "Pending";
                        }
                    }

                    if ($api_result1["data"][2]["edge_BSA"] != null) {
                        foreach ($api_result1["data"][2]["edge_BSA"] as $i => $res) {
                            if ($res["branch_id"] == $_SESSION["branch_id"]) {
                                $dem = count($api_result);
                                $api_result[$dem]["id"] = $res["id"];
                                $api_result[$dem]["ip"] = $res["ip"];
                                $api_result[$dem]["password"] = $res["password"];
                                $api_result[$dem]["name"] = $res["name"];
                                $api_result[$dem]["root_username"] = $res["root_username"];
                                $api_result[$dem]["port"] = $res["port"];
                                $api_result[$dem]["mac"] = $res["mac"];
                                $api_result[$dem]["admin_id"] = $res["admin_id"];
                                $api_result[$dem]["serial"] = $res["serial"];
                                $api_result[$dem]["branch_id"] = $res["branch_id"];
                                $api_result[$dem]["Status"] = "Active";
                            }
                        }
                    }
                }
            }
            break;
    }
    include_once "head.php";

?>
    

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 780px;">
                <!-- content-page  -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-info">
                                    <div class="card-header" style="text-align: center;">
                                        <h3 class="card-title">Edge List</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>IP</th>
                                                <th>Account Name</th>
                                                <th>Status</th>
                                                <!-- <th style="width: 50px">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dem = 0;
                                            if ($api_result != null) {
                                                foreach ($api_result as $j => $res) {
                                                    $stust = get_status_edge($res['id']);
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($dem++); ?></td>
                                                    <td><?= htmlspecialchars(($res['name'] != "") ? $res['name'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['ip'] != "") ? $res['ip'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['root_username'] != "") ? $res['root_username'] : 'None'); ?></td>
                                                    <td><?= ($stust['status'] == "Active") ? '<span class="badge badge-success rounded-pill d-inline">Active</span>' : '<span class="badge badge-danger rounded-pill d-inline">Inactive</span>' ?></td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                            ?>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- content-page  -->

            </div>

            
            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>

    </body>

<?php }
