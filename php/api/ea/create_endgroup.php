<?php
$active_menu = "group";
require_once('functions.inc');
check_session("/api");
global $serverip, $token;
$checks = true;
button_cancel('account?id=group');
$failed = 0;
if ($checks == true) {
    $edge_id = $_SESSION['edge_id'];
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        if ($_REQUEST["id"] != "" && $_REQUEST["id"] != "1"&& $_REQUEST["id"] != "2"
        && $_REQUEST["id"] != "3"&& $_REQUEST["id"] != "4"&& $_REQUEST["id"] != "5"
        && $_REQUEST["id"] != "6"&& $_REQUEST["id"] != "7"&& $_REQUEST["id"] != "8"&& $_REQUEST["id"] != "9"&& $_REQUEST["id"] != "10") {
            $id = $_REQUEST["id"];
            $url = "http://$serverip/api/group/" . $edge_id;
            $token = $_SESSION['accesstoken'];
            $dataa = array(
                "groupid" => $id
            );

            $data_json = json_encode($dataa, JSON_PRETTY_PRINT);
            $api_result = call_api('GET', $token, $url, $data_json);
            $data = $api_result['data'];

        } else if ($_REQUEST["id"] == "1" || $_REQUEST["id"] == "2"
        || $_REQUEST["id"] == "3"|| $_REQUEST["id"] == "4"|| $_REQUEST["id"] == "5"
        || $_REQUEST["id"] == "6"|| $_REQUEST["id"] == "7"|| $_REQUEST["id"] == "8"|| $_REQUEST["id"] == "9"|| $_REQUEST["id"] == "10") {
            $input_errors[] = sprintf(gettext("You can't edit default groups."));
            $failed = 1;    
        }

        if ($_POST['action'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;

            // if ($_REQUEST["id"] != "") {
            //     if ($pconfig['id'] != $data['groupid']) {
            //         $input_errors[] = sprintf(gettext("You can't change Group ID when Edit Group End User."));
            //     }
            // }

            if (!preg_match("/^[a-zA-Z0-9._]+$/", $pconfig["groupname"])) {
                $input_errors[] = sprintf(gettext("Invalid GroupName Details"));
            }

            if (!$input_errors) {
                if ($pconfig['id'] == "") {
                    $urls = "http://$serverip/api/groups/" . $edge_id;
                    $group = call_api("GET", $token, $urls, false);
                    // $pconfig['groupid'] = $group['data'][count($group['data'])-1]['groupid'] + 1;
                    $dem = $group['data'][0]['id'];
                    foreach ($group['data'] as $i => $valu) {
                        if ($valu['groupid'] >= $dem) {
                            $dem = $valu['groupid'];
                        } else {
                            $dem = $dem;
                        }
                    }
                    $pconfig['groupid'] = $dem + 1;
                    $method = "POST";
                    $data = array(
                        "groupname" => $pconfig["groupname"],
                        "groupid" => $pconfig["groupid"],
                        "description" => $pconfig["description"],
                    );
                    $url = "http://$serverip/api/group/" . $edge_id;
                } else {
                    $method = "PATCH";
                    $url = "http://$serverip/api/group/" . $edge_id;
                    $data = array(
                        "groupid" => $pconfig["id"],
                        "groupname" => $pconfig['groupname'],
                        "description" => $pconfig["description"],
                    );
                }
                $api_result = call_api($method, $token, $url, json_encode($data));
                if ($api_result['message'] != "null") {
                    $input_errors[] = sprintf(gettext($api_result['message']));
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit Group successful!"));
                    } else if ($method == "POST") {
                        $input_success[] = sprintf(gettext("Add Group successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location: account?id=group");
                    exit;
                }
            }
        }

?>


        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 780px;">
                    <!-- content-page  -->
                    <section class="content">
                        <div class="row">


                            <div class="col-md-12">
                                <?php
                                if ($input_errors) {
                                    print_error_box($input_errors);
                                }
                                ?>
                                <div class="card card-info <?= ($failed == 1 ) ? "" : "hidden" ?>">
                                    <form action="create_endgroup" method="post" class="form-horizontal">

                                        <div class="input-group mb-3">
                                            <div class="col-sm-offset-4 col-sm-4 pull-right" style="margin-left: 50%;">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card card-info <?= ($failed1 == 1 ) ? "" : "hidden" ?>">
                                    <form action="create_endgroup" method="post" class="form-horizontal">

                                        <div class="input-group mb-3">
                                            <div class="col-sm-offset-4 col-sm-4 pull-right" style="margin-left: 50%;">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card card-info <?= ($failed == 1) ? "hidden" : "" ?>">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title">Group End User Create</h3>
                                    </div>

                                    <form action="create_endgroup" method="post" class="form-horizontal">
                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Group Name:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="groupname" id="groupname" placeholder="..." required="required">
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Description:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="description" id="description" placeholder="...">
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                                </div>
                                                <div class="col-sm-offset-2 col-sm-4 pull-right">
                                                    <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- /.card-body -->
                                        <!-- /.box-footer -->
                                    </form>
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
                <?php if ($_POST) { ?>
                    document.getElementById("groupname").value = "<?= $_POST["groupname"]; ?>";
                    document.getElementById("description").value = "<?= $_POST["description"]; ?>";
                <?php } else { ?>
                    document.getElementById("groupname").value = "<?= $data["groupname"]; ?>";
                    document.getElementById("description").value = "<?= $data["description"]; ?>";
                <?php } ?>
            </script>
    <?php }
}
