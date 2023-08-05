<?php
$active_menu = "account_hsa_ea";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_hsa(6);
if ($checks == true) {
    global $serverip;
    // phpsession_begin();
    $countries = edge_list();
    $countries1 = bsa_list();
    $branch_name = branch_management_list();
    $token = $_SESSION["accesstoken"];
    if (isset($_REQUEST['idd'])) {
        $url = "http://$serverip/api/delete/branch-account";
        $data = array(
            "id" => $_REQUEST["idd"],
            "type" => "EA"
        );
        $api = call_api('DELETE', $token, $url, json_encode($data));
        if ($api["message"] != "null") {
            $input_errors[] = sprintf(gettext($api["message"]));
            $_SESSION['input_errors'] = $input_errors;
            header("Location: account_hsa_ea");
            exit;
        } else {
            $input_success[] = sprintf(gettext("Delete account ea successful!"));
            $_SESSION['input_success'] = $input_success;
            header("Location: account_hsa_ea");
            exit;
        }
    } else {
        $url = "http://$serverip/api/get/EA-HQ";
        $api_result = call_api('GET', $token, $url, false);
    }
?>
    <?php include_once "head.php"; ?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper">
                <!-- content-page  -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <?php
                        if ($_SESSION['input_errors']) {
                            print_error_box($_SESSION['input_errors']);
                            unset($_SESSION['input_errors']);
                        }
                        if ($_SESSION['input_success']) {
                            print_success_box($_SESSION['input_success']);
                            unset($_SESSION['input_success']);
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header" style="text-align: center;">
                                        <h3 class="card-title"><b>EA List</b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>User name</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Organization</th>
                                                    <th>Address</th>
                                                    <th>BSA name</th>
                                                    <th>Branch</th>
                                                    <th>Edge</th>
                                                    <th>Phone</th>
                                                    <th>Status</th>
                                                    <th style="width: 50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dem = 0;
                                                if ($api_result != null) {
                                                    foreach ($api_result as $i => $result) {
                                                        if ($i == "data") {
                                                            foreach ($result as $j => $res) {
                                                                if ($res['description'] == "[]") {
                                                                    $edge = "";
                                                                } else {
                                                                    $a5 = string_to_aray($res['description']);
                                                                    foreach ($a5 as $i => $result) {
                                                                        $edge_name = $countries["true"][$result];
                                                                        if ($i == "0") {
                                                                            $edge = $edge_name;
                                                                        } else {
                                                                            $edge = $edge . ',' . $edge_name;
                                                                        }
                                                                    }
                                                                }
                                                ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($dem++); ?></td>
                                                                    <td><?= htmlspecialchars(($res['username'] != "") ? $res['username'] : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($res['fullname'] != "") ? $res['fullname'] : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($res['email'] != "") ? $res['email'] : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($res['organization'] != "") ? $res['organization'] : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($res['address'] != "") ? $res['address'] : 'None'); ?></td>
                                                                    <td style="color:#<?= $color ?>;"><?= htmlspecialchars(($countries1["true"][$res['account_option']] != "") ? $countries1["true"][$res['account_option']] : 'None'); ?></td>
                                                                    <td style="color:#<?= $color ?>;"><?= htmlspecialchars(($res['department'] != "") ? $res['department'] : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($edge != "") ? $edge : 'None'); ?></td>
                                                                    <td><?= htmlspecialchars(($res['phonenumber'] != "") ? $res['phonenumber'] : 'None'); ?></td>
                                                                    <?php
                                                                    if ($res['description'] == "[]") {
                                                                    ?><td><span class="badge badge-danger rounded-pill d-inline">InActive</span></td><?php
                                                                                                                                                    } else {
                                                                                                                                                        ?><td><span class="badge badge-success rounded-pill d-inline">Active</span></td><?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                        ?>
                                                                    <td style="text-align:center;">
                                                                        <a style="width: 25px" class="fa fa-pen" role="button" title="EDIT" href="account_hsa_ea_edit?id=<?= $j ?>"></a>
                                                                        <!-- <a style="width: 25px" class="fa fa-trash no-confirm" role="button" name="action" value="del" href="account_hsa_ea?idd=<?= $res['id'] ?>" ></a> -->
                                                                        <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $res['id'] ?>"></a>
                                                                        <?php
                                                                        $link = "account_hsa_ea?";
                                                                        $id = $res['id'];
                                                                        $string = 'Are you sure to delete ea account "' . $res["username"] . '"';
                                                                        confirm_delete($link, $id, $string);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <!-- <tfoot>
                <tr>
                    <th>User name</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Organization</th>
                    <th>Address</th>
                    <th>BSA name</th>
                    <th>Edge</th>
                    <th>Phone</th>
                    <th>Active</th>
                    <th style="width: 50px">Action</th>
                </tr>
                </tfoot> -->
                                        </table>
                                        <?php $edge_available = edge_available_list();
                                        if ($branch_name != null) { ?>
                                            <nav class="action-buttons">
                                                <div style="text-align:right;">
                                                    <a href="account_hsa_ea_edit" role="button" class="btn bg-gradient-success btn-sm">
                                                        <i class="fa fa-plus icon-embed-btn"></i>
                                                        <?= gettext("Add") ?>
                                                    </a>
                                                </div>
                                            </nav>
                                        <?php } ?>
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
    <?php }
