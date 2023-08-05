<?php
$active_menu = "Edge";
require_once('functions.inc');
check_session("/api");
if ($_SESSION['accounttype'] != "9" && $_SESSION['accounttype'] != "8") {
    display_warning();
} else {
    $checks = check_permission_ea(8);
    if ($checks == true) {
        global $token, $serverip;
        $countries = branch_list();
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
            syn_edge();
            $url = "http://$serverip/api/edge-EA";
            $api_result = call_api("GET", $token, $url, false);
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
                                            <h3 class="card-title"><b>Edge List</b></h3>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Edge Name</th>
                                                        <th>Account Name</th>
                                                        <th>IP</th>
                                                        <th style="width: 8%">Status</th>
                                                        <!-- <th style="width: 8%">Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // $a=$api_result["data"]["0"]["total edge"];
                                                    if ($api_result["data"] != null) {
                                                        foreach ($api_result["data"] as $j => $res) {
                                                            $stust = get_status_edge($res['id']);
                                                    ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars(($res['name'] != "") ? $res['name'] : 'None'); ?></td>
                                                                <td><?= htmlspecialchars(($res['root_username'] != "") ? $res['root_username'] : 'None'); ?></td>
                                                                <td><?= htmlspecialchars(($res['ip'] != "") ? $res['ip'] : 'None'); ?></td>
                                                                <td><?= ($stust['status'] == "Active") ? '<span class="badge badge-success rounded-pill d-inline">Active</span>' : '<span class="badge badge-danger rounded-pill d-inline">Inactive</span>' ?></td>
                                                                <!-- <td style="text-align:center;"> -->
                                                                <!-- <a style="width: 25px" class="fa fa-pencil"	title="Edit"	role="button" href="devices_hsa_edge_edit?id=<?= $j ?>" ></a> -->
                                                                <!-- <a class="fa fa-trash no-confirm"	title="Delete"	role="button" id="del-<?= $j ?>"></a> -->
                                                                <!-- </td> -->
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <?php if ($_SESSION['accounttype'] != "9" && $_SESSION['accounttype'] != "8") { ?>

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
            <?php } else { { ?>
                <nav class="action-buttons">
                    <div style="text-align:right;">
                        <a href="edge_edit" role="button" class="btn bg-gradient-success btn-sm">
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
                                        }
                                    }
