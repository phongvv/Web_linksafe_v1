<?php
// phpsession_begin();
$active_menu = "devices_hsa_branch";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_hsa(6);
if ($checks == true) {
    global $serverip, $token;
    $countries = bsa_list();

    if (isset($_REQUEST['idd'])) {
        $url = "http://$serverip/api/branch";
        $data = array(
            "id" => $_REQUEST["idd"],
        );
        $api = call_api('DELETE', $token, $url, json_encode($data));
        if ($api["message"] != "null") {
            $_SESSION["input_errors"] = sprintf(gettext($api["message"]));
            header("Location: devices_hsa_branch");
            exit;
        } else {
            unset($_SESSION["input_errors"]);
            $input_success[] = sprintf(gettext("Delete branch successful!"));
            $_SESSION['input_success'] = $input_success;
            header("Location: devices_hsa_branch");
            exit;
        }
    } else {
        $url = "http://$serverip/api/branch";
        $api_result = call_api('GET', $token, $url, false);
    }
    include_once "head.php";
?>



    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper">
                <!-- content-page  -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <div class="row">
                            <?php
                            if ($_SESSION["input_errors"]) {
                                print_error_box_1($_SESSION["input_errors"]);
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
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header" style="text-align: center;">
                                        <h3 class="card-title"><b>Branch List</b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Address</th>
                                                    <th>BSA Account</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dem = 0;
                                                if ($api_result["data"] != "null") {
                                                    foreach ($api_result["data"] as $j => $res) {
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($dem++); ?></td>
                                                            <td><?= htmlspecialchars(($res['name'] != "") ? $res['name'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['address'] != "") ? $res['address'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($countries["true"][$res['branch_admin']] != "") ? $countries["true"][$res['branch_admin']] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['description'] != "") ? $res['description'] : 'None'); ?></td>
                                                            <?php if ($countries["true"][$res['branch_admin']] != "") { ?>
                                                                <td><span class="badge badge-success rounded-pill d-inline">Active</span></td>
                                                            <?php } else { ?>
                                                                <td><span class="badge badge-primary rounded-pill d-inline">Pending</span></td>
                                                            <?php } ?>
                                                            <td style="text-align:center;">
                                                                <a style="width: 25px" class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="devices_hsa_branch_edit?id=<?= $j ?>"></a>
                                                                <!-- <a style="width: 25px" class="fa fa-trash no-confirm" role="button" name="action" value="del" href="devices_hsa_branch?idd=<?= $res['id'] ?>"></a> -->
                                                                <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $res['id'] ?>"></a>
                                                                <?php
                                                                $link = "devices_hsa_branch?";
                                                                $id = $res['id'];
                                                                $string = 'Are you sure to delete branch "' . $res["name"] . '"';
                                                                confirm_delete($link, $id, $string);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <nav class="action-buttons">
                                            <div style="text-align:right;">
                                                <a href="devices_hsa_branch_edit" role="button" class="btn bg-gradient-success btn-sm">
                                                    <i class="fa fa-plus icon-embed-btn"></i>
                                                    <?= gettext("Add") ?>
                                                </a>
                                            </div>
                                        </nav>
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
    <?php } ?>