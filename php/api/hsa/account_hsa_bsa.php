<?php
$active_menu = "account_hsa_bsa";
require_once("functions.inc");
check_session("/api");
$checks = check_permission_hsa(6);
if ($checks == true) {
    include_once "head.php";
    global $token, $serverip;
    $countries = branch_list();
    $branch_available = branch_available_list();
        $token = $_SESSION["accesstoken"];
    if (isset($_REQUEST['idd'])) {
        $url = "http://$serverip/api/delete/branch-account";
        $data = array(
            "id" => $_REQUEST["idd"],
            "type" => "LSA_BSA"
        );
        $api = call_api('DELETE', $token, $url, json_encode($data));
        if ($api["message"] != "null") {
            $_SESSION["input_errors"] = sprintf(gettext($api["message"]));
            header("Location: account_hsa_bsa");
            exit;
        } else {
            unset($_SESSION["input_errors"]);
            $input_success[] = sprintf(gettext("Update successful!"));
            $_SESSION['input_success'] = $input_success;
            header("Location: account_hsa_bsa");
            exit;
        }
    } else {
        $url = "http://$serverip/api/get/branch-account";
        $data = json_encode(array(
            "type" => "LSA_BSA"
        ));
        $api_result = call_api('GET', $token, $url, $data);
    }
?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 780px;">
                <!-- content-page  -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <?php
                        if ($_SESSION["input_errors"]) {
                            print_error_box($_SESSION["input_errors"]);
                            unset($_SESSION["input_errors"]);
                        }

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
                                        <h3 class="card-title"><b>BSA List</b></h3>
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
                                                    <th>Branch</th>
                                                    <th>Phone</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $arayr = array();
                                                $dem = 0;
                                                if ($api_result["data"] != "null") {
                                                    foreach ($api_result["data"] as $j => $res) {
                                                        $arayr[$j] = $res['email'];
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($dem++); ?></td>
                                                            <td><?= htmlspecialchars(($res['username'] != "") ? $res['username'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['fullname'] != "") ? $res['fullname'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['email'] != "") ? $res['email'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['organization'] != "") ? $res['organization'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['address'] != "") ? $res['address'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['department'] != "") ? $res['department'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['phonenumber'] != "") ? $res['phonenumber'] : 'None'); ?></td>
                                                            <?php
                                                            if ($res['department'] == null) {
                                                            ?><td><span class="badge badge-danger rounded-pill d-inline">InActive</span></td><?php
                                                                                                                                            } else {
                                                                                                                                                ?><td><span class="badge badge-success rounded-pill d-inline">Active</span></td><?php
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                ?>
                                                            <td style="text-align:center;">
                                                                <a style="width: 25px" class="fa fa-pen" role="button" title="EDIT" href="account_hsa_bsa_edit?id=<?= $j ?>"></a>
                                                                <!-- <a style="width: 25px" class="fa fa-trash no-confirm" role="button" name="action" value="del" href="account_hsa_bsa?idd=<?= $res['id'] ?>"></a> -->
                                                                <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $res['id'] ?>"></a>
                                                                <?php
                                                                $link = "account_hsa_bsa?";
                                                                $id = $res['id'];
                                                                $string = 'Are you sure to delete bsa account "' . $res["username"] . '"';
                                                                confirm_delete($link, $id, $string);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                // var_dump($arayr);
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php if ($branch_available != null) { ?>
                                            <nav class="action-buttons">
                                                <div style="text-align:right;">
                                                    <a href="account_hsa_bsa_edit" role="button" class="btn bg-gradient-success btn-sm">
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
    <?php
} ?>