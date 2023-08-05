<?php
$active_menu = "user";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_ea(8);
button_cancel('account?id=user');
$failed = 0;
if ($checks == true) {
    $edge_id = $_SESSION['edge_id'];
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        if ($_REQUEST["id"] != "" && $_REQUEST["id"] != 0) {
            $id = $_REQUEST["id"];
            $url = "http://$serverip/api/user/" . $edge_id;
            $data1 = json_encode(array(
                "userid" => $id
            ));
            $api_result = call_api('GET', $token, $url, $data1);
            if ($api_result['message'] != "null") {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else {
                $data = $api_result["data"];
            }
        } else if ($_REQUEST["id"] == "0") {
            $input_errors[] = sprintf(gettext("You can't edit default user."));
            $failed = 1;
        }



        if ($_POST['action'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;
            // if ($pconfig['status'] == "Active") {
            //     $_POST['status'] = "enable";
            // } else {
            //     $_POST['status'] = "disable";
            // }

            if ($pconfig['id'] == "" && !preg_match("/^[a-zA-Z0-9._]+$/", $pconfig["username"])) {
                $input_errors[] = sprintf(gettext("Username is not false format"));
            }

            // if (strlen($pconfig['password']) < 6) {
            //     $input_errors[] = sprintf(gettext("Password too short."));
            // }
            if (!$input_errors) {
                if ($pconfig['id'] == "") {
                    $method = "POST";
                    $data_json = array(
                        "username" => $pconfig["username"],
                        "password" => $pconfig["password"],
                        "groupid" => $_POST['group'],
                        "active" => $pconfig["status"],
                        "description" => $pconfig["description"]

                    );
                } else {
                    $method = "PATCH";
                    $data_json = array(
                        "userid" => $id,
                        "username" => $data["username"],
                        "password" => $data["password"],
                        "groupid" => $_POST['group'],
                        "active" => $pconfig["status"],
                        "description" => $pconfig["description"]

                    );
                }
                $url = "http://$serverip/api/user/" . $edge_id;
                $api_result = call_api($method, $token, $url, json_encode($data_json));
                if ($api_result['message'] != "null") {
                    $input_errors[] = sprintf(gettext($api_result['message']));
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit User successful!"));
                    } else if ($method == "POST") {
                        $input_success[] = sprintf(gettext("Add User successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location: account?id=user");
                    exit;
                }
            }
        }

?>

        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper">
                    <!-- content-page  -->



                    <section class="content">
                        <!-- right column -->
                        <div class="row">
                            <!-- Horizontal Form -->

                            <div class="col-md-12">
                                <?php
                                if ($input_errors) {
                                    print_error_box($input_errors);
                                }
                                ?>
                                <div class="card card-info <?= ($failed != 1) ? "hidden" : "" ?>">
                                    <form action="create_enduser" method="post" class="form-horizontal">

                                        <div class="input-group mb-3">
                                            <div class="col-sm-offset-4 col-sm-4 pull-right" style="margin-left: 50%;">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card card-info <?= ($failed == 1) ? "hidden" : "" ?>">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title">End User Create</h3>
                                    </div>

                                    <form action="create_enduser" method="post" class="form-horizontal">
                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">User Name:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="..." required="required">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Password:</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" minlength="6" name="password" id="password" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Group Name:</label>
                                                <div class="col-sm-8">
                                                    <select name="group" id="group" class="form-control select2" style="width: 100%;">
                                                        <?php
                                                        $api_result1 = list_group_user();
                                                        $api_result = $api_result1['data'];
                                                        foreach ($api_result as $cc => $name) {
                                                            echo '<option value="' . $name['groupid'] . '">' . $name['groupname'] . '</option>';
                                                        } ?>
                                                    </select>
                                                    <span class="help-card"><a href="create_endgroup"><i class="fa fa-plus"></i> Create new one</a>.</span>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Status:</label>
                                                <div class="col-sm-8">
                                                    <select name="status" id="status" class="form-control" style="width: 100%;">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Description:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="description" id="description" placeholder="...">
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <!-- <div class="col-sm-offset-2 col-sm-4">
                                        <button type="submit" class="btn bg-gradient-success" name="action" value="save" style="width:40%">Save</button>
                                    </div> -->
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                                </div>
                                                <div class="col-sm-offset-2 col-sm-4 pull-right">
                                                    <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <!-- /.card-footer -->
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!--/.col (right) -->
                </div>
                <!-- /.row -->
                </section>




            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>



            <script src="../assets/js/lancsnet.js"></script>
            <script type="text/javascript">
                <?php if ($_POST) { ?>
                    document.getElementById("username").value = "<?= $_POST["username"]; ?>";
                    document.getElementById("status").value = "<?= $_POST["status"]; ?>";
                    document.getElementById("group").value = "<?= $_POST["groupname"]; ?>";
                    document.getElementById("description").value = "<?= $_POST["description"]; ?>";
                <?php } else if (isset($id)) { ?>
                    document.getElementById("username").value = "<?= $data["username"]; ?>";
                    document.getElementById("status").value = "<?= $data["active"] ?>";
                    document.getElementById("group").value = "<?= $data["groupid"]; ?>";
                    document.getElementById("description").value = "<?= $data["description"]; ?>";
                    disableInput("username", true);
                <?php } ?>
            </script>
            <link rel="stylesheet" href="../plugins/select2/select2.min.css">
            <script src="../plugins/select2/select2.full.min.js"></script>
            <script type="text/javascript">
                $(function() {
                    $(".select2").select2();
                });
            </script>
    <?php }
}
