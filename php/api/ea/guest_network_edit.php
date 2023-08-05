<?php
$active_menu = "wireless";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
button_cancel('network?id=wireless');

if ($checks == true) {
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        $url = "http://$serverip/api/wireless/" . $_SESSION['edge_id'];
        $url1 = "http://$serverip/api/wireless";
        $url2 = "http://$serverip/api/status-wireless/" . $_SESSION['edge_id'];
        $dType = array(
            // "none" => "None",
            "psk2" => "PSK2",
            "psk" => "PSK",
            "psk+ccmp" => "PSK+CCMP",
            "psk+aes" => "PSK+AES",
            "psk2+aes" => "PSK2+AES",
            "psk2+ccmp" => "PSK2+CCMP"
        );
        $dStatus = array(
            "0" => "Enable",
            "1" => "Disabled",

        );
        $dRadio = array(
            "default_radio0" => "Radio0",
            "default_radio1" => "Radio1",

        );

        if ($_REQUEST["id"] != "") {
            $id = $_REQUEST["id"];
            $api_result = call_api('GET', $token, $url, false);
            $data = $api_result["data"]['0'];
        }


        if ($_POST['action'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;

            if (!$input_errors) {

                $method = "PATCH";
                $data1 = array(
                    "deviceId" => $_SESSION["edge_id"],
                    "ssid" => $pconfig["ssid"],
                    "key" => $pconfig["password"],
                    "type" => $pconfig["dradio"],
                    "encryption" => $pconfig["dtype"],
                );
                $data2 = array(
                    "type" => $pconfig["dradio"],
                    "disabled" => $pconfig["dstatus"],

                );
                $api_result1 = call_api($method, $token, $url1, json_encode($data1));
                $api_result2 = call_api($method, $token, $url2, json_encode($data2));
                if ($api_result1['message'] && $api_result2['message'] != "null") {
                    $input_errors[] = sprintf(gettext($api_result1['message']));
                    $input_errors[] = sprintf(gettext($api_result2['message']));
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit Wireless successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location: network?id=wireless");
                    exit;
                }
                unset($api_result1);
                unset($api_result2);
            }
        }

?>


        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 1000px;">
                    <section class="content">
                        <div class="col-md-*">
                            <?php
                            if ($input_errors) {
                                print_error_box($input_errors);
                            }

                            if ($_SESSION['input_success']) {
                                print_success_box($_SESSION['input_success']);
                                unset($_SESSION['input_success']);
                            }
                            ?>

                            <div class="card card-info">
                                <div class="card-header with-border" style="text-align: center;">
                                    <h3 class="card-title">WLAN SSID Configuration</h3>
                                </div>

                                <form action="basic_wifi_edit" method="post" class="form-horizontal">
                                    <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Status</label>

                                            <div class="col-sm-8">
                                                <select name="dstatus" id="dstatus" class="form-control " style="width: 100%;">
                                                    <!-- <option selected="selected">None</option> -->
                                                    <?php
                                                    foreach ($dStatus as $cc => $vl) {
                                                        echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="scenrioname" class="col-sm-2 control-label">SSID </label>

                                            <div class="col-sm-8">
                                                <input required="true" type="text" class="form-control" name="ssid" id="ssid">
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Destination Type:</label>

                                            <div class="col-sm-8">
                                                <select name="dtype" id="dtype" class="form-control " style="width: 100%;">
                                                    <!-- <option selected="selected">None</option> -->
                                                    <?php
                                                    foreach ($dType as $cc => $vl) {
                                                        echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                                            <div class="col-sm-8">
                                                <input type="text" required="true" class="form-control" pattern=".{8,}" name="password" id="password">
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Radio Type:</label>

                                            <div class="col-sm-8">
                                                <select name="dradio" id="dradio" class="form-control " style="width: 100%;">
                                                    <!-- <option selected="selected">None</option> -->
                                                    <?php
                                                    foreach ($dRadio as $cc => $vl) {
                                                        echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="Except_Rule" class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Except Rule:</label>

                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="check" name=""> Enable Except Rule.
                                                    </label>
                                                </div>
                                                <input type="text" class="form-control" name="except" disabled id="except" placeholder="Except some id or name ....">
                                            </div>
                                            <div class="col-sm-2">
                                                <!--select-->
                                                <div class="checkbox">
                                                    <label>
                                                     Unit.
                                                    </label>
                                                </div>
                                                <select name="check1" id="check1" class="form-control  " disabled style="width: 100%;">
                                                    <!-- <option selected="selected">None</option> -->
                                                    <?php
                                                    foreach ($dRadio as $cc => $vl) {
                                                        echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                    } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="input-group mb-3">

                                            <div class="col-sm-offset-2 col-sm-4">
                                                <button type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                            </div>
                                            <div class="col-sm-offset-3 col-sm-3 pull-right">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>
                </div>
                </section>




            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>
            <script type="text/javascript">
                document.getElementById('check').onchange = function() {
                    document.getElementById('except').disabled = !this.checked;
                    document.getElementById('check1').disabled = !this.checked;
                };
                // document.getElementById("except").disabled = false;
                <?php if ($id != "") {
                ?>


                    document.getElementById("dstatus").value = "<?= $data["disabled"]; ?>";
                    document.getElementById("ssid").value = "<?= $data["ssid"]; ?>";
                    document.getElementById("password").value = "<?= $data["key"]; ?>";
                    document.getElementById("dradio").value = "<?= $data["type"]; ?>";
                    document.getElementById("dtype").value = "<?= $data["encryption"]; ?>";

                <?php } ?>
            </script>
    <?php }
}
