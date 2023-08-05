<?php
$active_menu = "policy";

require_once('functions.inc');
check_session("/api");
$checks = true;
button_cancel('scenario');
if ($checks == true) {
    $idn = $_REQUEST['idn'];
    if (
        $_REQUEST["idn"] == "1" || $_REQUEST["idn"] == "2"
        || $_REQUEST["idn"] == "3" || $_REQUEST["idn"] == "4" || $_REQUEST["idn"] == "5"
        || $_REQUEST["idn"] == "6" || $_REQUEST["idn"] == "7"
    ) {
        $input_errors[] = sprintf(gettext("You can't edit default groups."));
        $failed = 1;
    }
    $url = "http://$serverip/api/policy-scenario/$idn";
    $url2 = "http://$serverip/api/policy/$idn";
    $url3 = "http://$serverip/api/policy";
    $url1 = "http://$serverip/api/scenario";
    $te = call_api('GET', $token, $url1, false);
    $bfzd = call_api('GET', $token, $url2, false);
    $casdcd = call_api('GET', $token, $url, false);
    $hihi = $casdcd['data']['0']['scenario_id'];
    $tes = $te['data'];

    include_once "head.php";
    $action = array(
        "0" => "Permit",
        "1" => "Block"
    );
    $stype = array(
        "0" => "Device",
        "1" => "User",
        "2" => "Group"
    );
    $dtype = array(
        "0" => "Web Categories",
        "1" => "Web",
        "2" => "App Categories",
        "3" => "App",
        "4" => "Protocol Categories",
        "5" => "Protocol"
    );

    if ($_REQUEST["id"] != "") {
        $id = $_REQUEST["id"];
        $api_result = call_api('GET', $token, $url, false);
        $data = $api_result["data"][$id];
    }
    if ($_POST['actions'] == 'save') {
        $scenarioId = $idn;
        unset($input_errors);
        $pconfig = $_POST;
        $reqdfields = explode(" ", "name");
        $reqdfieldsn = explode(",", gettext("Name"));

        do_input_validation($_POST, $reqdfields, $reqdfieldsn, $input_errors);

        $timestart1 = explode(":", $pconfig['timestart']);
        $timestop1 = explode(":", $pconfig['timestop']);

        $timestart = $pconfig['timestart'];
        $timestop = $pconfig['timestop'];

        if ($timestart1[0] > $timestop1[0]) {
            $input_errors[] = sprintf(gettext("Time start and time stop incorrect."));
        } else if (($timestart1[0] == $timestop1[0]) && ($timestart1[1] > $timestop1[1])) {
            $input_errors[] = sprintf(gettext("Time start and time stop incorrect."));
        }

        if (!$input_errors) {
            $te = call_api('GET', $token, $url1, false);
            $tes = $te['data'];
            foreach ($tes as $test) {
                if ($test['id'] == $pconfig['scenario']) {
                    $pconfig['edge'] = $test['edge_id'];
                    break;
                }
            }
            if ($pconfig['id'] == "") {
                $method = "POST";
                $data = array(
                    "scenario_id" => $pconfig["scenario"],
                    "name" => $pconfig["name"],
                    "source_type" => $pconfig["stype"],
                    "dest_type" => $pconfig["dtype"],
                    "except_rule" => $pconfig["except"],
                    "action" => $pconfig["action"]
                );

                switch ($pconfig['stype']) {
                    case "0":
                        $data['source'] = $pconfig['device'];
                        break;
                    case "1":
                        $data['source'] = $pconfig['user'];
                        break;
                    case "2":
                        $data['source'] = $pconfig['group'];
                        break;
                }

                switch ($pconfig['dtype']) {
                    case "0":
                        $datadtype = $pconfig['webcat'];
                        $checkaa = 1;
                        break;
                    case "1":
                        $test = explode(',', $pconfig['web']);
                        $value = '"' . $test[0] . '"';
                        if (count($test) == 1) {
                            $value = '"' . $test[0] . '"';
                        } else {
                            $dem = count($test);
                            for ($k = 1; $k < $dem; $k++) {
                                $value .= ',"' . $test[$k] . '"';
                            }
                        }
                        $datadtype = $value;
                        $checkaa = 2;
                        break;
                    case "2":
                        $datadtype = $pconfig['appcat'];
                        $checkaa = 1;
                        break;
                    case "3":
                        $datadtype = $pconfig['app'];
                        $checkaa = 1;
                        break;
                    case "4":
                        $datadtype = $pconfig['protocat'];
                        $checkaa = 1;
                        break;
                    case "5":
                        $datadtype = $pconfig['proto'];
                        $checkaa = 1;
                        break;
                }

                if ($checkaa == 1) {
                    foreach ($datadtype as $i => $datadtype2) {
                        if ($i == "0") {
                            $datadtype_input = $datadtype2;
                        } else {
                            $datadtype_input = $datadtype_input . ',' . $datadtype2;
                        }
                    }
                    $data['dest'] = "[$datadtype_input]";
                } else if ($checkaa == 2) {
                    $data['dest'] = "[$datadtype]";
                }

                // $data['dest'] = "[$datadtype]";

                if (isset($pconfig['check'])) {
                    $data['except_rule'] = $pconfig['except'];
                } else {
                    $data['except_rule'] = "";
                }

                // if (isset($pconfig['checks'])) {
                //     $data['schedule'] = "[$timestart[0]-$timestop[0]]";
                // } else {
                //     $data['schedule'] = "[all]";
                // }
            } else {
                $method = "PATCH";
                $pconfig = $_POST;
                $idn =  $pconfig["idn"];
                $url = "http://$serverip/api/policy-scenario/$idn";
                $call_url = call_api('GET', $token, $url, false);
                $vl_url = $call_url['data'][$id];

                $data = array(
                    "scenario_id" => $pconfig["idn"],
                    "id" => $data["id"],
                    "name" => $pconfig["name"],
                    "source_type" => $pconfig["stype"],
                    "dest_type" => $pconfig["dtype"],
                    "except_rule" => $pconfig["except"],
                    "action" => $pconfig["action"]
                );


                switch ($pconfig['stype']) {
                    case "0":
                        $data['source'] = $pconfig['device'];
                        break;
                    case "1":
                        $data['source'] = $pconfig['user'];
                        break;
                    case "2":
                        $data['source'] = $pconfig['group'];
                        break;
                }

                switch ($pconfig['dtype']) {
                    case "0":
                        $datadtype = $pconfig['webcat'];
                        $checkaa = 1;
                        break;
                    case "1":
                        $test = explode(',', $pconfig['web']);
                        $value = '"' . $test[0] . '"';
                        if (count($test) == 1) {
                            $value = '"' . $test[0] . '"';
                        } else {
                            $dem = count($test);
                            for ($k = 1; $k < $dem; $k++) {
                                $value .= ',"' . $test[$k] . '"';
                            }
                        }
                        $datadtype = $value;
                        $checkaa = 2;
                        break;
                    case "2":
                        $datadtype = $pconfig['appcat'];
                        $checkaa = 1;
                        break;
                    case "3":
                        $datadtype = $pconfig['app'];
                        $checkaa = 1;
                        break;
                    case "4":
                        $datadtype = $pconfig['protocat'];
                        $checkaa = 1;
                        break;
                    case "5":
                        $datadtype = $pconfig['proto'];
                        $checkaa = 1;
                        break;
                }

                if ($checkaa == 1) {
                    foreach ($datadtype as $i => $datadtype2) {
                        if ($i == "0") {
                            $datadtype_input = $datadtype2;
                        } else {
                            $datadtype_input = $datadtype_input . ',' . $datadtype2;
                        }
                    }
                    $data['dest'] = "[$datadtype_input]";
                } else if ($checkaa == 2) {
                    $data['dest'] = "[$datadtype]";
                }

                if (isset($pconfig['check'])) {
                    $data['except_rule'] = $pconfig['except'];
                } else {
                    $data['except_rule'] = "";
                }
            }

            if (isset($pconfig['checks'])) {
                $weekdays = "";
                if ($timestart == $timestop) {
                    unset($timestart);
                    unset($timestop);
                }
                if ($pconfig['monday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Mon\"";
                    } else {
                        $weekdays = $weekdays . "\"Mon\"";
                    }
                }
                if ($pconfig['tuesday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Tue\"";
                    } else {
                        $weekdays = $weekdays . ",\"Tue\"";
                    }
                }
                if ($pconfig['thursday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Thu\"";
                    } else {
                        $weekdays = $weekdays . ",\"Thu\"";
                    }
                }
                if ($pconfig['wednesday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Wed\"";
                    } else {
                        $weekdays = $weekdays . ",\"Wed\"";
                    }
                }
                if ($pconfig['saturday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Sat\"";
                    } else {
                        $weekdays = $weekdays . ",\"Sat\"";
                    }
                }
                if ($pconfig['friday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Fri\"";
                    } else {
                        $weekdays = $weekdays . ",\"Fri\"";
                    }
                }
                if ($pconfig['sunday'] == "on") {
                    if ($weekdays == "") {
                        $weekdays = "\"Sun\"";
                    } else {
                        $weekdays = $weekdays . ",\"Sun\"";
                    }
                }
                if ($pconfig['monday'] == "on"  && $pconfig['tuesday'] == "on"  && $pconfig['thursday'] == "on"  && $pconfig['wednesday'] == "on"  && $pconfig['saturday'] == "on"  && $pconfig['friday'] == "on"  && $pconfig['sunday'] == "on") {
                    $weekdays = "";
                }
                $data['schedule'] = "{\"weekdays\":[" . $weekdays . "],\"time_start\":\"" . $timestart . "\",\"time_stop\":\"" . $timestop . "\"}";
            } else {
                $data['schedule'] = "{\"weekdays\":[],\"time_start\":\"\",\"time_stop\":\"\"}";
            }


            $api_result = call_api($method, $token, $url3, json_encode($data));
            if ($api_result['message'] == "null") {
                if ($method == "PATCH") {
                    returnApplyScenarioGlobal($idn);

                    $input_success[] = sprintf(gettext("Edit Policy successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:policy_detail?idn=$idn");
                    exit;
                } else if ($method == "POST") {
                    $scenarioId = $pconfig["scenario"];
                    returnApplyScenarioGlobal($scenarioId);

                    $input_success[] = sprintf(gettext("Add Policy successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location:policy_detail?idn=$scenarioId");
                    exit;
                }
            } else {
                $_SESSION['input_errors'][] = sprintf(gettext($api_result['message']));
                if ($idn && $id) {
                    header("Location:create_policy?idn=$idn&&id=$id");
                    exit;
                } else {
                    header("Location:create_policy");
                    exit;
                }
            }
        }
    }

?>



    <!-- bootstrap time picker -->

    <body class="hold-transition sidebar-mini layout-fixed" onload="myFunction()">

        <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- bootstrap time picker -->
        <script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../plugins/iCheck/icheck.min.js"></script>
        <link rel="stylesheet" href="../plugins/iCheck/all.css">
        <script src="../assets/js/lancsnet.js"></script>

        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 780px;">
                <!-- content-page  -->

                <!-- Main content -->
                <section class="content">
                    <!-- right column -->
                    <div id="loader"></div>
                    <div class="col-md-* animate-bottom" style="display:none;" id="myDiv">
                        <!-- Horizontal Form -->
                        <?php
                        if ($_SESSION['input_errors']) {
                            print_error_box($_SESSION['input_errors']);
                            unset($_SESSION['input_errors']);
                        }
                        if ($input_errors) {
                            print_error_box($input_errors);
                        }

                        ?>
                        <div class="card card-info <?= ($failed == 1) ? "" : "hidden" ?>">
                            <form action="scenario">
                                <div class="input-group mb-3">
                                    <div class="col-sm-offset-4 col-sm-4 pull-right" style="margin-left: 50%;">
                                        <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card card-info <?= ($failed == 1) ? "hidden" : "" ?>">
                            <div class="card-header with-border" style="text-align: center;">
                                <h3 class="card-title">Policy Create</h3>
                            </div>

                            <form action="create_policy" method="post" class="form-horizontal">
                                <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                <input id="idn" type="hidden" name="idn" value="<?= $idn ?>" />
                                <div class="card-body">
                                    <div class="input-group mb-3">
                                        <label for="scenrioname" class="col-sm-2 control-label">Policy Name:</label>

                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="name" required="required" id="name" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="scenrioname" class="col-sm-2 control-label">Action:</label>

                                        <div class="col-sm-8">
                                            <select name="action" id="action" class="form-control" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                foreach ($action as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Scenario:</label>

                                        <div class="col-sm-8">
                                            <!-- <input type="number" class="form-control" name="scenario" id="scenario" placeholder="..."> -->
                                            <select name="scenario" id="scenario" class="form-control select2" style="width: 100%;" readOnly>
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $list = getScenario("all");
                                                $list_arrr = $list['data'];

                                                foreach ($list_arrr as $o) {
                                                    if ($o['type_apply'] == "custom") {
                                                        $list_arrx[] = $o;
                                                    }
                                                }
                                                $name = array_combine(
                                                    array_map(function ($g) {
                                                        return $g['id'];
                                                    }, $list_arrx),
                                                    array_map(function ($g) {
                                                        return $g['name'];
                                                    }, $list_arrx)
                                                );
                                                foreach ($name as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                            <span class="help-card"><a href="create_scenario"><i class="fa fa-plus"></i> Create new scenario.</a>.</span>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Source Type:</label>

                                        <div class="col-sm-8">
                                            <select name="stype" id="stype" required="required" class="form-control" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                foreach ($stype as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Source Group End User:</label>

                                        <div class="col-sm-8">
                                            <select name="group" id="group" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                // global $token, $serverip;
                                                // $edge_id = $_SESSION['edge_id'];
                                                // $url = "http://$serverip/api/groups/" . $edge_id;
                                                // $api_result1 = call_api("GET", $token, $url, false);
                                                $api_result1 = listGroupUser();
                                                $api_result = $api_result1['data'];
                                                foreach ($api_result as $cc => $vl) {
                                                    echo '<option value="' . $vl['groupid'] . '">' . $vl['groupname'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Source End User:</label>

                                        <div class="col-sm-8">
                                            <select name="user" id="user" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $user = showUser(false);
                                                foreach ($user as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Source End Device:</label>

                                        <div class="col-sm-8">
                                            <select name="device" id="device" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $device = getEndDevice($_SESSION['edge_id'], true);
                                                foreach ($device as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination Type:</label>

                                        <div class="col-sm-8">
                                            <select name="dtype" id="dtype" class="form-control " style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                foreach ($dtype as $cc => $vl) {
                                                    echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination Web Categories:</label>

                                        <div class="col-sm-8">
                                            <select name="webcat[]" multiple="multiple" id="webcat" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $webcat = show_web_cat();
                                                if ($data["dest"] != "[]" && $data["dest_type"] == 0) {
                                                    $data["dest"] = conver_string_to_array($data["dest"]);
                                                    foreach ($webcat as $cc => $vl) {
                                                        $check = 0;
                                                        foreach ($data["dest"] as $cc1 => $vl1) {
                                                            if ($vl["id"] == $vl1) {
                                                                $check = 1;
                                                            }
                                                        }
                                                        if ($check == 1) {
                                                            echo '<option selected="selected" value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($webcat as $cc => $vl) {
                                                        echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination Web:</label>

                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="web" id="web" placeholder="www.xxx.xxx">
                                            <!-- <select name="web" id="web" class="form-control select2" style="width: 100%;"> -->
                                            <!-- <option selected="selected">None</option> -->
                                            <?php
                                            // $web = show_web();
                                            // foreach ($web as $cc => $vl) {
                                            //     echo '<option value="' . $vl['id'] . '">' . $vl['domain'] . '</option>';
                                            // } 
                                            ?>
                                            <!-- </select> -->
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination App Categories:</label>
                                        <div class="col-sm-8">
                                            <select name="appcat[]" multiple="multiple" id="appcat" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $appcat = show_app_cat();
                                                if ($data["dest"] != "[]" && $data["dest_type"] == 2) {
                                                    $data["dest"] = conver_string_to_array($data["dest"]);
                                                    foreach ($appcat as $cc => $vl) {
                                                        $check = 0;
                                                        foreach ($data["dest"] as $cc1 => $vl1) {
                                                            if ($vl["id"] == $vl1) {
                                                                $check = 1;
                                                            }
                                                        }
                                                        if ($check == 1) {
                                                            echo '<option selected="selected" value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($appcat as $cc => $vl) {
                                                        echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination App:</label>

                                        <div class="col-sm-8">
                                            <select name="app[]" multiple="multiple" id="app" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $app = show_app();
                                                if ($data["dest"] != "[]" && $data["dest_type"] == 3) {
                                                    $data["dest"] = conver_string_to_array($data["dest"]);
                                                    foreach ($app as $cc => $vl) {
                                                        $check = 0;
                                                        foreach ($data["dest"] as $cc1 => $vl1) {
                                                            if ($vl["id"] == $vl1) {
                                                                $check = 1;
                                                            }
                                                        }
                                                        if ($check == 1) {
                                                            echo '<option selected="selected" value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($app as $cc => $vl) {
                                                        echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination Protocol Categories:</label>

                                        <div class="col-sm-8">
                                            <select name="protocat[]" multiple="multiple" id="protocat" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $protocat = show_proto_cat();
                                                if ($data["dest"] != "[]" && $data["dest_type"] == 4) {
                                                    $data["dest"] = conver_string_to_array($data["dest"]);
                                                    foreach ($protocat as $cc => $vl) {
                                                        $check = 0;
                                                        foreach ($data["dest"] as $cc1 => $vl1) {
                                                            if ($vl["id"] == $vl1) {
                                                                $check = 1;
                                                            }
                                                        }
                                                        if ($check == 1) {
                                                            echo '<option selected="selected" value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($protocat as $cc => $vl) {
                                                        echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Destination Protocol:</label>

                                        <div class="col-sm-8">
                                            <select name="proto[]" multiple="multiple" id="proto" class="form-control select2" style="width: 100%;">
                                                <!-- <option selected="selected">None</option> -->
                                                <?php
                                                $proto = show_proto();
                                                if ($data["dest"] != "[]" && $data["dest_type"] == 5) {
                                                    $data["dest"] = conver_string_to_array($data["dest"]);
                                                    foreach ($proto as $cc => $vl) {
                                                        $check = 0;
                                                        foreach ($data["dest"] as $cc1 => $vl1) {
                                                            if ($vl["id"] == $vl1) {
                                                                $check = 1;
                                                            }
                                                        }
                                                        if ($check == 1) {
                                                            echo '<option selected="selected" value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($proto as $cc => $vl) {
                                                        echo '<option value="' . $vl['id'] . '">' . $vl['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="Except_Rule" class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Except Rule:</label>

                                        <div class="col-sm-8">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="check" name="check"> Enable Except Rule.
                                                </label>
                                            </div>
                                            <input type="text" class="form-control" name="except" disabled id="except" placeholder="Except some id or name ....">
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label for="text" class="col-sm-2 control-label">Schedule</label>
                                        <div class="col-sm-8">
                                            <div class="bootstrap-timepicker">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="checks" id="checks"> Enable Schedule.
                                                    </label>
                                                </div>
                                                <div class="input-group">
                                                    <input type="text" class="form-control timepicker" id="timestart" disabled name="timestart" />
                                                    <div class="input-group-addon">
                                                        <label style="padding: 5px;">To</label>
                                                    </div>
                                                    <input type="text" class="form-control timepicker" id="timestop" disabled name="timestop" />
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="monday" disabled checked id="monday"><b>Monday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="tuesday" disabled checked id="tuesday"><b>Tuesday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="wednesday" disabled checked id="wednesday"><b>Wednesday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="thursday" disabled checked id="thursday"><b>Thursday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="friday" disabled checked id="friday"><b>Friday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="saturday" disabled checked id="saturday"><b>Saturday&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="sunday" disabled checked id="sunday"><b>Sunday&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</b>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3 justify-content-between">
                                        <div class="col-sm-offset-2 col-sm-4">
                                            <button type="submit" class="btn bg-gradient-success" name="actions" value="save">Save</button>
                                        </div>
                                        <div class="col-sm-offset-3 col-sm-3">
                                            <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/.col (right) -->
            </div>
            <!-- /.row -->
            </section>
            <!-- content-page  -->
            <div class="control-sidebar-bg"></div>

        </div>



        <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
        <script type="text/javascript">
            <?php
            if ($_POST) { ?>
                document.getElementById("name").value = "<?= $_POST["name"]; ?>";
                document.getElementById("action").value = "<?= $_POST["action"]; ?>";
                document.getElementById("scenario").value = "<?= $_POST["scenario"]; ?>";
                document.getElementById("stype").value = "<?= $_POST["stype"]; ?>";
                document.getElementById("group").value = "<?= $_POST["group"]; ?>";
                document.getElementById("user").value = "<?= $_POST["user"]; ?>";
                document.getElementById("dtype").value = "<?= $_POST["dtype"]; ?>";
                document.getElementById("web").value = "<?= $_POST["web"]; ?>";
                document.getElementById("webcat").value = "<?= $_POST["webcat"]; ?>";
                document.getElementById("app").value = "<?= $_POST["app"]; ?>";
                document.getElementById("appcat").value = "<?= $_POST["appcat"]; ?>";
                document.getElementById("protocol").value = "<?= $_POST["protocol"]; ?>";
                document.getElementById("protocat").value = "<?= $_POST["protocat"]; ?>";
                document.getElementById("check").value = "<?= $_POST["check"]; ?>";
                document.getElementById("except").value = "<?= $_POST["except"]; ?>";
                document.getElementById("checks").value = "<?= $_POST["checks"]; ?>";
                document.getElementById("timestart").value = "<?= $_POST["timestart"]; ?>";
                document.getElementById("timestop").value = "<?= $_POST["timestop"]; ?>";
            <?php
            } else if (isset($id)) { ?>
                document.getElementById("name").value = "<?= $data["name"]; ?>";
                document.getElementById("action").value = "<?= $data["action"]; ?>";
                document.getElementById("scenario").value = "<?= $data["scenario_id"]; ?>";
                document.getElementById("scenario").disabled = true;
                document.getElementById("stype").value = "<?= $data["source_type"]; ?>";
                <?php
                switch ($data["source_type"]) {
                    case "0":
                ?>
                        document.getElementById("device").value = "<?= $data["source"]; ?>";
                    <?php
                        break;
                    case "1":
                    ?>
                        document.getElementById("user").value = "<?= $data["source"]; ?>";
                    <?php
                        break;
                    case "2":
                    ?>
                        document.getElementById("group").value = "<?= $data["source"]; ?>";
                <?php
                        break;
                } ?>
                document.getElementById("dtype").value = "<?= $data["dest_type"]; ?>";
                <?php
                $datadest = json_decode($data["dest"]);
                $data["dest"] = $datadest[0];
                if ($data["dest_type"] == 1) {
                ?>
                    document.getElementById("web").value = "<?= $data["dest"]; ?>";
                <?php
                }
                if (!empty($data['except_rule'])) {
                ?>
                    document.getElementById("check").checked = true;
                    document.getElementById("except").value = "<?= $data["except_rule"]; ?>";
                    document.getElementById("except").disabled = false;
                <?php
                }
                unset($schedule);
                $schedule = get_schedule_policy($data['schedule']);

                if ($schedule["schedule"] == false) {

                ?>
                    document.getElementById("checks").checked = false;
                <?php
                } else {
                    $times = explode("-", $schedule["time"]);
                    $weekdays = explode(", ", $schedule["weekdays"]);
                ?>
                    document.getElementById("checks").checked = true;
                    document.getElementById("timestart").value = "<?= $times[0]; ?>";
                    document.getElementById("timestop").value = "<?= $times[1]; ?>";
                    document.getElementById("timestart").disabled = false;
                    document.getElementById("timestop").disabled = false
                    document.getElementById("monday").checked = false;
                    document.getElementById("monday").disabled = false;
                    document.getElementById("tuesday").disabled = false;
                    document.getElementById("thursday").disabled = false;
                    document.getElementById("tuesday").disabled = false;
                    document.getElementById("wednesday").disabled = false;
                    document.getElementById("saturday").disabled = false;
                    document.getElementById("friday").disabled = false;
                    document.getElementById("sunday").disabled = false;
                    document.getElementById("tuesday").checked = false;
                    document.getElementById("thursday").checked = false;
                    document.getElementById("tuesday").checked = false;
                    document.getElementById("wednesday").checked = false;
                    document.getElementById("saturday").checked = false;
                    document.getElementById("friday").checked = false;
                    document.getElementById("sunday").checked = false;
                    <?php
                    $day = array(
                        "Monday" => "monday",
                        "Tuesday" => "tuesday",
                        "Wednesday" => "wednesday",
                        "Thursday" => "thursday",
                        "Friday" => "friday",
                        "Saturday" => "saturday",
                        "Sunday" => "sunday"
                    );
                    if ($weekdays[0] == "") {
                        foreach ($day as $cc) {
                    ?>
                            document.getElementById("<?= $cc; ?>").checked = true;
                        <?php
                        }
                    } else {
                        foreach ($weekdays as $cc) {
                            $cc = $day[$cc];
                        ?>
                            document.getElementById("<?= $cc; ?>").checked = true;
                    <?php
                        }
                    }
                    ?>


            <?php
                }
            } ?>
        </script>
        <link rel="stylesheet" href="../plugins/select2/select2.min.css">
        <script src="../plugins/select2/select2.full.min.js"></script>
        <script type="text/javascript">
            function hide_name() {
                disableInput('name', hide);
            }
            hide_name();
        </script>
        <script type="text/javascript">
            $(function() {
                $(".select2").select2();

                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
            });
        </script>
        <script type="text/javascript">
            var myVar;

            function myFunction() {
                myVar = setTimeout(showPage, 1000);
            }

            function showPage() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("myDiv").style.display = "block";
            }
        </script>
        <script type="text/javascript">
            // Khai bao
            function showapp() {
                hideInput('group', $('#stype').prop('value') != '2');
                hideInput('user', $('#stype').prop('value') != '1');
                hideInput('device', $('#stype').prop('value') != '0');
            }
            $('#stype').change(function() {
                showapp();
            });

            function showweb() {
                hideInput('webcat', $('#dtype').prop('value') != '0');
                hideInput('web', $('#dtype').prop('value') != '1');
                hideInput('appcat', $('#dtype').prop('value') != '2');
                hideInput('app', $('#dtype').prop('value') != '3');
                hideInput('protocat', $('#dtype').prop('value') != '4');
                hideInput('proto', $('#dtype').prop('value') != '5');

            }

            $('#dtype').change(function() {
                showweb();
            });

            function hide_radius(hide) {
                disableInput('except', hide);
            }

            $('#check').click(function() {
                hide_radius(!$('#check').prop('checked'));
            });

            function hide_radius_a(hide) {

                disableInput('timestart', hide);
                disableInput('timestop', hide);

                disableInput('monday', hide);
                disableInput('tuesday', hide);
                disableInput('thursday', hide);
                disableInput('tuesday', hide);
                disableInput('wednesday', hide);
                disableInput('saturday', hide);
                disableInput('friday', hide);
                disableInput('sunday', hide);
            }

            $('#checks').click(function() {
                hide_radius_a(!$('#checks').prop('checked'));
            });


            //show
            showapp();
            showweb();
            hide_radius();
            hide_radius_a();

            $('#dtype').change(function() {
                if ($('#dtype').val() === '0' || $('#dtype').val() === '2' || $('#dtype').val() === '4') {
                    $('#Except_Rule').show();
                } else {
                    $('#Except_Rule').hide();
                }
            });
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
        <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#timestart").inputmask("hh:mm", {
                    "placeholder": "0"
                });
                $("#timestop").inputmask("hh:mm", {
                    "placeholder": "0"
                });
            });
        </script>
    <?php
}
