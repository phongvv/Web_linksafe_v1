<?php
$active_menu = "scenario";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
button_cancel('scenario');
$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";

    $idn = $_REQUEST['idn'];
    $url = "http://$serverip/api/policy-scenario/$idn";
    $url1 = "http://$serverip/api/policy";
    $url2 = "http://$serverip/api/scenario";
    $api_result = call_api('GET', $token, $url, false);
    $api_result2 = call_api('GET', $token, $url2, false);
    $hehe = $api_result2['data'];
    if ($idn == 1 || $idn == 2 || $idn == 3 || $idn == 4 || $idn == 5 || $idn == 6 || $idn == 7) {
        $checkEditDelete = 1;
    }
    foreach ($hehe as $h) {
        if ($h['id'] == $idn && $h['type_apply'] == "custom") {
            $namee = $h['name'];
        };
    }

    // $id_sample_scenario = id_sample_scenario_list()["true"];

    if ($_REQUEST['idb']) {
        switch ($_SESSION['accounttype']) {
            case "8":
            case "9":
                $url = "http://$serverip/api/scenario-config";
                break;
        }
        $data = array(
            "scenario_id" => $_REQUEST['idb'],
            "edge_id" => $_SESSION['edge_id']

        );
        call_api('POST', $token, $url, json_encode($data));
        header('Location:policy');
        exit;
    }

    if ($_REQUEST['idd']) {
        $idd = $_REQUEST['idd'];
        $data = array(
            "id" => $_REQUEST['idd']
        );
        $api = call_api("DELETE", $token, $url1, json_encode($data));

        $a = $api_result["data"];
        foreach ($a as $ixcf => $ixcfw) {
            if ($_REQUEST['idd'] == $ixcfw["id"]) {
                returnApplyScenarioGlobal($ixcfw["scenario_id"]);
            }
        }
        $input_success[] = sprintf(gettext("Delete policy successful!"));
        $_SESSION['input_success'] = $input_success;
        header("Location:policy_detail?idn=$idn");
        exit;
    }
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
                            <?php
                            $tee = get_one_group(2);
                            $_SESSION;
                            if ($_SESSION['input_errors']) {
                                print_error_box($_SESSION['input_errors']);
                                unset($_SESSION['input_errors']);
                            }
                            if ($_SESSION['input_success']) {
                                print_success_box($_SESSION['input_success']);
                                unset($_SESSION['input_success']);
                            }
                            ?>
                        
                            <div class="col-12">
                                <div class="card card-info ">
                                    <div class="card-header">
                                        <h3 class="card-title"><b style="text-align: center!important;"><u>List of Policies in <?= htmlspecialchars($namee); ?> Scenario</u></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?= htmlspecialchars("No"); ?></th>
                                                    <th><?= htmlspecialchars("Policy Name"); ?></th>
                                                    <th><?= htmlspecialchars("Policy Action"); ?></th>
                                                    <th><?= htmlspecialchars("Scenario"); ?></th>
                                                    <th><?= htmlspecialchars("Source Type"); ?></th>
                                                    <th><?= htmlspecialchars("Source Name"); ?></th>
                                                    <th><?= htmlspecialchars("Destination Type"); ?></th>
                                                    <th><?= htmlspecialchars("Destination Name(s)"); ?></th>
                                                    <th><?= htmlspecialchars("Except"); ?></th>
                                                    <th><?= htmlspecialchars("Day(s)"); ?></th>
                                                    <th><?= htmlspecialchars("Time(s)"); ?></th>
                                                    <th style="text-align:center;"><?= htmlspecialchars("Actions"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $api = $api_result['data'];
                                                if ($api != "null") {
                                                    foreach ($api as $iii => $result) {
                                                        if (!empty($result)) {
                                                            unset($schedule);
                                                            $schedule = get_schedule_policy($result['schedule']);
                                                ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($iii); ?></td>
                                                                <td><?= htmlspecialchars($result['name']); ?></td>
                                                                <td><?= htmlspecialchars($action[$result['action']]); ?></td>
                                                                <td><?= htmlspecialchars($test = getScenario(true)[$result['scenario_id']]); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($stype[$result['source_type']]); ?></td>
                                                                <?php
                                                                switch ($result['source_type']) {
                                                                    case "0":
                                                                ?>
                                                                        <td><?= htmlspecialchars($test = getEndDevice(true)[$result['source']]); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "1":
                                                                    ?>
                                                                        <td><?= htmlspecialchars($test = get_one_user($result['source'])); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "2":
                                                                    ?>
                                                                        <td><?= htmlspecialchars($tee = get_one_group($result['source'])); ?></td>
                                                                <?php
                                                                        break;
                                                                }
                                                                ?>
                                                                <td><?= htmlspecialchars($dtype[$result['dest_type']]); ?></td>
                                                                <?php
                                                                switch ($result['dest_type']) {
                                                                    case "0":
                                                                        $nane = "";
                                                                        $num = json_decode($result['dest']);
                                                                        foreach ($num as $i => $na) {
                                                                            $nan = get_one_web_cat($na);
                                                                            if ($i == 0) {
                                                                                $nane .= $nan;
                                                                            } else {
                                                                                $nane .= ', ' . $nan;
                                                                            }
                                                                        }
                                                                ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "1":
                                                                        $nane = "";
                                                                        $num = json_decode($result['dest']);
                                                                        $dem = count($num);
                                                                        $nane = $num[0];
                                                                        if ($dem == 1) {
                                                                            $nane = $num[0];
                                                                        } else {
                                                                            for ($k = 1; $k < $dem; $k++) {
                                                                                $nane .= ',' . $num[$k];
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "2":
                                                                        $num = json_decode($result['dest']);
                                                                        $nane = "";
                                                                        foreach ($num as $i => $na) {
                                                                            $nan = get_one_app_cat($na);
                                                                            if ($i == 0) {
                                                                                $nane .= $nan;
                                                                            } else {
                                                                                $nane .= ', ' . $nan;
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "3":
                                                                        $num = json_decode($result['dest']);
                                                                        $nane = "";
                                                                        foreach ($num as $i => $na) {
                                                                            $nan = get_one_app($na);
                                                                            if ($i == 0) {
                                                                                $nane .= $nan;
                                                                            } else {
                                                                                $nane .= ', ' . $nan;
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "4":
                                                                        $num = json_decode($result['dest']);
                                                                        $nane = "";
                                                                        foreach ($num as $i => $na) {
                                                                            $nan = get_one_proto_cat($na);
                                                                            if ($i == 0) {
                                                                                $nane .= $nan;
                                                                            } else {
                                                                                $nane .= ', ' . $nan;
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                    <?php
                                                                        break;
                                                                    case "5":
                                                                        $num = json_decode($result['dest']);
                                                                        $nane = "";
                                                                        foreach ($num as $i => $na) {
                                                                            $nan = get_one_proto($na);
                                                                            if ($i == 0) {
                                                                                $nane .= $nan;
                                                                            } else {
                                                                                $nane .= ', ' . $nan;
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <td><?= htmlspecialchars($nane); ?></td>
                                                                <?php
                                                                        break;
                                                                }
                                                                ?>
                                                                <td><?= htmlspecialchars(($result['except_rule'] != '') ? $result['except_rule'] : "None"); ?></td>
                                                                <td><?= htmlspecialchars((isset($schedule['weekdays'])) ? $schedule['weekdays'] : "All Days of Week") ?></td>
                                                                <td><?= htmlspecialchars((isset($schedule['time'])) ? $schedule['time'] : "All Times") ?></td>
                                                                <?php
                                                                if ($checkEditDelete != 1) {
                                                                ?>
                                                                <td>
                                                                    <a class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="create_policy?idn=<?= $idn ?>&&id=<?= $iii ?>"></a>
                                                                    <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>"></a>
                                                                    <?php
                                                                    $link = "policy_detail?";
                                                                    $hehe = $_REQUEST['idn'];
                                                                    $id = $result['id'];
                                                                    $string = 'Are you sure to delete Policy "' . $result["name"] . '"';
                                                                    confirmDelete($link, $id, $string, $hehe);
                                                                    ?>
                                                                </td>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <td></td>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tr>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <form action="policy_detail" method="post" class="form-horizontal">
                                        <div class="input-group mb-3 justify-content-between">
                                            <div class="col-sm-offset-2 col-sm-4">
                                                <!-- <button type="submit" class="btn bg-gradient-success" name="actions" value="save"></button> -->
                                                <a href="create_policy" role="button" class="btn bg-gradient-success btn-sm-3">
                                                    <i class="fa fa-plus icon-embed-btn"></i>
                                                    Add Policy</a>
                                            </div>
                                            <div class="col-sm-offset-3 col-sm-3">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Return Scenario</button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- /.card-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                </section>

                <!-- content-page  -->

            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
    <?php
}
