<?php
$active_menu = "scenario";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
button_cancel('scenario');
if ($checks == true) {
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        $url1 = "http://$serverip/api/apply-scenario/" . $_SESSION['edge_id'];
        $url = "http://$serverip/api/scenario/" . $_SESSION['edge_id'];

        if ($_REQUEST["id"] != "") {
            $id = $_REQUEST["id"];
            $api_result = call_api('GET', $token, $url1, false);
            $data = $api_result["data"]['customScenario'];
            foreach ($data as $i => $value) {
                if ($value['id'] ==  $id) {
                    $nameScen =  $value['name'];
                    $descriptionScen =  $value['description'];
                }
            }
        }
        if ($_POST['action'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;

            if (!$input_errors) {
                if ($pconfig['id'] == "") {
                    $method = "POST";
                    $data = array(
                        "name" => $_POST["name"],
                        "description" => $_POST["des"]
                    );
                } else {
                    $method = "PATCH";
                    $data = array(
                        "id" => $id,
                        "name" => $pconfig["name"],
                        "description" => $pconfig["des"]
                    );
                }
                $api_result = call_api($method, $token, $url, json_encode($data));
                if ($api_result['message'] != "null") {
                    $input_errors[] = sprintf(gettext($api_result['message']));
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit Scenario successful!"));
                    } else if ($method == "POST") {
                        $input_success[] = sprintf(gettext("Add Scenario successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location: scenario");
                    exit;
                }
            }
        }

?>





        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 1000px;">
                    <!-- content-page  -->



                    <!-- Main content -->
                    <section class="content">
                        <!-- right column -->
                        <div class="col-md-*">
                            <!-- Horizontal Form -->
                            <?php
                            if ($input_errors) {
                                print_error_box($input_errors);
                            }
                            ?>

                            <div class="card card-info">
                                <div class="card-header with-border" style="text-align: center;">
                                    <h3 class="card-title">Scenario Create</h3>
                                </div>

                                <form action="create_scenario" method="post" class="form-horizontal">
                                    <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <label for="scenrioname" class="col-sm-2 control-label">Scenario Name:</label>

                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="name" id="name" placeholder="...">
                                            </div>
                                        </div>
                                        <!-- <div class="input-group mb-3">
                                    <label for="text" class="col-sm-2 control-label">Edge:</label>

                                    <div class="col-sm-8">
                                        <select name="edge" id="edge" class="form-control select2" style="width: 100%;">
                                            <?php
                                            // $name=get_ea_name(true);
                                            // foreach ($name as $cc => $vl) {
                                            //     echo '<option value="'.$cc.'">' . $vl . '</option>';
                                            // } 
                                            ?>
                                        </select>
                                    </div>
                                </div> -->
                                        <div class="input-group mb-3">
                                            <label for="inputPassword3" class="col-sm-2 control-label">Description:</label>

                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="des" id="des" placeholder="...">
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <!-- <div class="col-sm-offset-2 col-sm-4">
                                        <button type="submit" class="btn bg-gradient-success" name="action" value="save" style="width:40%">Save</button>
                                    </div> -->
                                            <div class="col-sm-offset-2 col-sm-4">
                                                <button type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                            </div>
                                            <div class="col-sm-offset-3 col-sm-3 pull-right">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- /.card-body -->
                                    <!-- /.card-footer -->
                                </form>
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
            <script type="text/javascript">
                <?php if ($_POST) { ?>
                    document.getElementById("name").value = "<?= $_POST["name"]; ?>";
                    document.getElementById("des").value = "<?= $_POST["des"]; ?>";
                <?php } else if (isset($id)) {
                ?>
                    document.getElementById("name").value = "<?= $nameScen; ?>";
                    document.getElementById("des").value = "<?= $descriptionScen; ?>";
                <?php } ?>
            </script>
    <?php }
}
