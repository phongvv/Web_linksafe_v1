<?php
// phpsession_begin();
$active_menu = "devices_hsa_edge";
require_once('functions.inc');
check_session("/api");
$checks = true;
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
        $url = "http://$serverip/api/edge-Available";
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
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header" style="text-align: center;">
                                        <h3 class="card-title"><b>Edge List</b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Account Name</th>
                                                    <th>Name</th>
                                                    <th>IP</th>
                                                    <th>Branch</th>
                                                    <th style="width: 8%">Status</th>
                                                    <!-- <th style="width: 8%">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dem = 0;
                                                // $a=$api_result["data"]["0"]["total edge"];
                                                if ($api_result["data"]["0"]["total edge"] != null) {
                                                    foreach ($api_result["data"]["0"]["total edge"] as $j => $res) {
                                                        $stust = get_status_edge($res['id']);
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($dem++); ?></td>
                                                            <td><?= htmlspecialchars(($res['root_username'] != "") ? $res['name'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['name'] != "") ? $res['name'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($res['ip'] != "") ? $res['ip'] : 'None'); ?></td>
                                                            <td><?= htmlspecialchars(($countries["true"][$res['branch_id']] != "") ? $countries["true"][$res['branch_id']] : 'None'); ?></td>
                                                            <?php
                                                            if ($stust['status']['id'] == null) {
                                                            ?><td><span class="badge badge-danger rounded-pill d-inline">Inactive</span></td><?php
                                                                                                                                            } else {
                                                                                                                                                ?><td><span class="badge badge-success rounded-pill d-inline">Active</span></td><?php
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                ?>

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
        <script type="text/javascript">
            var settings = {
                "url": "127.0.0.1:5000/api/change-password/service-account",
                "method": "PATCH",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "type": "Call-Center",
                    "new_password": "1",
                    "current_password": "2"
                }),
            };

            $.ajax(settings).done(function(response) {
                console.log(response);
            });
        </script>
    <?php }
