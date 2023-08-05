<?php
$active_menu = "scenario";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";
    $edge_id = $_SESSION["edge_id"];
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        $list = get_ea_info();
        $list_arrr = $list['data'];
        $res[0] = json_decode($_SESSION['description']);
        foreach ($list_arrr as $o) {
            if ($o['admin_id'] == $_SESSION['id']) {
                $list_arr[] = $o;
            }
        }
        $url_1 = "http://$serverip/api/apply-scenario/" . $_SESSION['edge_id'];
        $api_result_default = call_api("GET", $token, $url_1, false);
        $id_scenario_apply = $api_result_default['data']["scenarioNow"];

        $api_result = $api_result_default["data"]["customScenario"];

        $nameid = $idname = array();

        if ($list_arr != null) {
            $idname = array_combine(
                array_map(function ($g) {
                    return $g['id'];
                }, $list_arr),
                array_map(function ($g) {
                    return $g['name'];
                }, $list_arr)
            );
        }

        if ($_REQUEST['idd']) {
            $url = "http://$serverip/api/scenario/" . $_SESSION['edge_id'];
            $data = array(
                "id" => $_REQUEST['idd']
            );
            $api_result = call_api('DELETE', $token, $url, json_encode($data));
            if ($api_result['success'] != "true") {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else {
                $input_success[] = sprintf(gettext("Delete Scenario successful!"));
                $_SESSION['input_success'] = $input_success;
                header('Location:scenario');
                exit;
            }
            unset($api_result);
        }
        if ($_REQUEST['idb'] || $_REQUEST['idoff']) {
            $url = "http://$serverip/api/scenario-config";
            if (!empty($_REQUEST['idoff'])) {
                $method = "DELETE";
                $edge_id = $_SESSION['edge_id'];
                $data = array(
                    "scenario_id" => $_REQUEST['idoff'],
                    "edge_id" => "$edge_id"
                );
            } else {
                $method = "POST";
                $edge_id = $_SESSION['edge_id'];
                $data = array(
                    "scenario_id" => $_REQUEST['idb'],
                    "edge_id" => "$edge_id"
                );
            }
            $api_result = call_api($method, $token, $url, json_encode($data));
            if ($api_result['success'] != "true") {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else {
                if (!empty($_REQUEST['idoff'])) {
                    $input_success[] = sprintf(gettext("Dissmis Scenario successful!"));
                } else {
                    $input_success[] = sprintf(gettext("Apply Scenario successful!"));
                }

                $_SESSION['input_success'] = $input_success;
                header('Location:scenario');
                exit;
            }
            unset($api_result);
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
                <div class="content-wrapper" style="min-height: 1000px;">


                    <!-- content-page  -->

                    <section class="content1">
                        <div class="container-fluid" id="myDiv1">
                            <div class="row">
                                <div class="col-12">
                                    <?php
                                    if ($_SESSION['input_errors']) {
                                        print_error_box($_SESSION['input_errors']);
                                        unset($_SESSION['input_errors']);
                                    }
                                    if ($_SESSION['input_success']) {
                                        print_success_box($_SESSION['input_success']);
                                        unset($_SESSION['input_success']);
                                    }
                                    ?>
                                    <div class="card">
                                        <div class="card-header" style="text-align: center;">
                                            <h3 class="card-title"><b>Scenario List Global</b></h3>
                                        </div>
                                        <div class="card-body">
                                            <table id="example10" class="table table-bordered table-hover">
                                                <thead>

                                                    <tr>
                                                        <th><?= gettext("Scenario Name") ?></th>
                                                        <th><?= gettext("Description") ?></th>
                                                        <th><?= gettext("Status") ?></th>
                                                        <th><?= gettext("Actions") ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $url_glb = "http://$serverip/api/scenario" ;
                                                    $api_resultgl = call_api("GET", $token, $url_glb, false);
                                                    $apigl = $api_resultgl['data'];
                                                    if ($apigl != "null") {
                                                        foreach ($apigl as $i => $resultgl) {
                                                            if (!empty($resultgl)) {
                                                                if ($resultgl['apply_global'] == 1) {

                                                    ?>
                                                                    <tr>
                                                                        <!-- <td><?= htmlspecialchars($resultgl['id']); ?></td> -->
                                                                        <td><?= htmlspecialchars($resultgl['name']); ?></td>
                                                                        <td><?= htmlspecialchars($resultgl['description']); ?></td>
                                                                        <td>
                                                                            <Button style="background-color:#D50000 !important" data-toggle="modal" class="btn bg-gradient text-white btn-sm">APPLIED TO GLOBAL</Button>
                                                                        </td>
                                                                        <td>
                                                                            <a data-toggle="modal" data-target="#modal-infogl<?= $resultgl['id'] ?>" role="button" <?= $result_default['id'] ?> class="btn bg-gradient-info btn-sm">SHOW</a>
                                                                            <div class="modal fade" id="modal-infogl<?= $resultgl['id'] ?>" role="dialog">
                                                                                <div class="modal-dialog modal-xl modal-info" role="document">

                                                                                    <div style="width: 100% !important;" class="modal-content bg-info">
                                                                                        <div class="modal-header">
                                                                                            <h3 class="modal-title"><b><?= $result_default['name'] ?> Scenario</b></h3>
                                                                                        </div>

                                                                                        <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">

                                                                                            <div class="row">
                                                                                                <div>
                                                                                                    <div class="card">
                                                                                                        <div class="card-body">
                                                                                                            <table id="examplegl" class="table table-bordered table-striped">
                                                                                                                <thead>
                                                                                                                    <tr>
                                                                                                                        <th><?= htmlspecialchars("Policy Name"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Policy Action"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Source Type"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Source Name"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Destination Type"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Destination Name"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Except"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Day(s)"); ?></th>
                                                                                                                        <th><?= htmlspecialchars("Time(s)"); ?></th>
                                                                                                                    </tr>
                                                                                                                </thead>
                                                                                                                <tbody>
                                                                                                                    <?php
                                                                                                                    $id_sample_scenariogl = $resultgl['id'];
                                                                                                                    $url_1gl = "http://$serverip/api/scenario-policy/" . $id_sample_scenariogl;
                                                                                                                    $api_result1gl = call_api("GET", $token, $url_1gl, false);
                                                                                                                    $apigl = $api_result1gl['data'];
                                                                                                                    if ($apigl != "null") {
                                                                                                                        foreach ($apigl as $i => $resulttgl) {
                                                                                                                            if (!empty($resulttgl)) {
                                                                                                                                unset($schedule);
                                                                                                                                $schedule = get_schedule_policy($resulttgl['schedule']);
                                                                                                                    ?>
                                                                                                                                <tr>
                                                                                                                                    <td><?= htmlspecialchars($resulttgl['name']); ?></td>
                                                                                                                                    <td><?= htmlspecialchars($action[$resulttgl['action']]); ?></td>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($stype[$resulttgl['source_type']]); ?></td>
                                                                        <?php
                                                                                                                                switch ($resulttgl['source_type']) {
                                                                                                                                    case "0":
                                                                        ?>
                                                                                <td><?= htmlspecialchars($test = get_host_name($resulttgl['source'])['hostname'] . '(' . $resulttgl['source'] . ')'); ?></td>
                                                                            <?php
                                                                                                                                        break;
                                                                                                                                    case "1":
                                                                            ?>
                                                                                <td><?= htmlspecialchars($test = get_one_user($resulttgl['source'])); ?></td>
                                                                            <?php
                                                                                                                                        break;
                                                                                                                                    case "2":
                                                                            ?>
                                                                                <td><?= htmlspecialchars($tee = get_one_group($resulttgl['source'])); ?></td>
                                                                        <?php
                                                                                                                                        break;
                                                                                                                                }
                                                                        ?>
                                                                        <td><?= htmlspecialchars($dtype[$resulttgl['dest_type']]); ?></td>
                                                                        <?php
                                                                                                                                switch ($resulttgl['dest_type']) {
                                                                                                                                    case "0":
                                                                                                                                        $nane = "";
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                                                                                        $num = json_decode($resulttgl['dest']);
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
                                                                        <td><?= htmlspecialchars(($resulttgl['except_rule'] != '') ? $resulttgl['except_rule'] : "None"); ?>
                                                                        </td>
                                                                        <td><?= htmlspecialchars((isset($schedule['weekdays'])) ? $schedule['weekdays'] : "All Days of Week") ?></td>
                                                                        <td><?= htmlspecialchars((isset($schedule['time'])) ? $schedule['time'] : "All Times") ?></td>
                                                                    </tr>
                                                        <?php
                                                                                                                            }
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


                </div>
            </div>
            </div>
            </td>
            </tr>
<?php
                                                                }
                                                            }
                                                        }
                                                    }
?>
</tbody>
</table>
</div>
<!-- /.card-body -->
</div>
<!-- /.box -->
</div>
</section>




<!-- content-page  -->


<section class="content">
    <div class="container-fluid" id="myDiv">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="card-title"><b>Scenario List</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?= gettext("Scenario Name") ?></th>
                                    <th><?= gettext("Description") ?></th>
                                    <th style="width:30%;padding-right: 15px;"><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $api = $api_result;
                                if ($api != "null") {
                                    foreach ($api as $i => $result) {
                                        if (!empty($result)) {
                                ?>
                                            <tr>
                                                <!-- <td><?= htmlspecialchars($result['id']); ?></td> -->
                                                <td><?= htmlspecialchars($result['name']); ?></td>
                                                <td><?= htmlspecialchars($result['description']); ?></td>
                                                <td>
                                                    <?php if ($result['local'] == 0) { ?>
                                                        <a style="width: 80px" href="scenario?idb=<?= $result['id'] ?>" role="button" class="btn bg-gradient-success btn-sm">APPLY</a>
                                                    <?php } else if ($result['local'] == 1) { ?>
                                                        <a style="width: 100px" href="scenario?idoff=<?= $result['id'] ?>" role="button" class="btn bg-gradient-danger btn-sm">DISMISS</a>
                                                    <?php } ?>


                                                    <a data-toggle="modal" data-target="#modal-info<?= $result['id'] ?>" role="button" <?= $result_default['id'] ?> class="btn bg-gradient-primary btn-sm">SHOW</a>


                                                    <a role="button" class="btn bg-gradient-warning btn-sm" title="<?= $gettext_array['edit'] ?>" href="create_scenario?id=<?= $result['id'] ?>"> EDIT</a>
                                                    <!-- <a role="button" class="btn bg-gradient-danger btn-sm" title="DELETE" href="scenario?idd=<?= $result['id'] ?>">DELETE</a> -->
                                                    <!-- <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#modal-info<?= $result['userid'] ?>" ></a> -->


                                                    <div class="modal fade" id="modal-info<?= $result['id'] ?>" role="dialog">
                                                        <div class="modal-dialog modal-xl modal-info" role="document">

                                                            <div style="width: 100% !important;" class="modal-content bg-info">
                                                                <div class="modal-header">
                                                                    <h3 class="modal-title"><b><?= $result_default['name'] ?> Scenario</b></h3>
                                                                </div>

                                                                <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">

                                                                    <div class="row">
                                                                        <div>
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <table id="example" class="table table-bordered table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th><?= htmlspecialchars("Policy Name"); ?></th>
                                                                                                <th><?= htmlspecialchars("Policy Action"); ?></th>
                                                                                                <th><?= htmlspecialchars("Source Type"); ?></th>
                                                                                                <th><?= htmlspecialchars("Source Name"); ?></th>
                                                                                                <th><?= htmlspecialchars("Destination Type"); ?></th>
                                                                                                <th><?= htmlspecialchars("Destination Name"); ?></th>
                                                                                                <th><?= htmlspecialchars("Except"); ?></th>
                                                                                                <th><?= htmlspecialchars("Day(s)"); ?></th>
                                                                                                <th><?= htmlspecialchars("Time(s)"); ?></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $id_sample_scenario = $result['id'];
                                                                                            $url_1 = "http://$serverip/api/scenario-policy/" . $id_sample_scenario;
                                                                                            $api_result1 = call_api("GET", $token, $url_1, false);

                                                                                            // $dataStatus = array(
                                                                                            //     "scenario_id" => $id_sample_scenario
                                                                                            // );
                                                                                            // $url_2 = "http://$serverip/api/scenario-status-edge/" . $edge_id;
                                                                                            // $api_result2 = call_api("POST", $token, $url_2, json_encode($dataStatus));
                                                                                            $statusApply = checkStatusApply($id_sample_scenario, $edge_id);
                                                                                            // $statusApply  = $api_result2['data']['local'];
                                                                                            $api = $api_result1['data'];
                                                                                            if ($api != "null") {
                                                                                                foreach ($api as $i => $resultt) {
                                                                                                    if (!empty($resultt)) {
                                                                                                        unset($schedule);
                                                                                                        $schedule = get_schedule_policy($resultt['schedule']);
                                                                                            ?>
                                                                                                        <tr>
                                                                                                            <td><?= htmlspecialchars($resultt['name']); ?></td>
                                                                                                            <td><?= htmlspecialchars($action[$resultt['action']]); ?></td>
                                                </td>
                                                <td><?= htmlspecialchars($stype[$resultt['source_type']]); ?></td>
                                                <?php
                                                                                                        switch ($resultt['source_type']) {
                                                                                                            case "0":
                                                ?>
                                                        <td><?= htmlspecialchars($test = get_host_name($resultt['source'])['hostname'] . '(' . $resultt['source'] . ')'); ?></td>
                                                    <?php
                                                                                                                break;
                                                                                                            case "1":
                                                    ?>
                                                        <td><?= htmlspecialchars($test = get_one_user($resultt['source'])); ?></td>
                                                    <?php
                                                                                                                break;
                                                                                                            case "2":
                                                    ?>
                                                        <td><?= htmlspecialchars($tee = get_one_group($resultt['source'])); ?></td>
                                                <?php
                                                                                                                break;
                                                                                                        }
                                                ?>
                                                <td><?= htmlspecialchars($dtype[$resultt['dest_type']]); ?></td>
                                                <?php
                                                                                                        switch ($resultt['dest_type']) {
                                                                                                            case "0":
                                                                                                                $nane = "";
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                                                                                $num = json_decode($resultt['dest']);
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
                                                <td><?= htmlspecialchars(($resultt['except_rule'] != '') ? $resultt['except_rule'] : "None"); ?>
                                                </td>
                                                <td><?= htmlspecialchars((isset($schedule['weekdays'])) ? $schedule['weekdays'] : "All Days of Week") ?></td>
                                                <td><?= htmlspecialchars((isset($schedule['time'])) ? $schedule['time'] : "All Times") ?></td>
                                            </tr>
                                <?php
                                                                                                    }
                                                                                                }
                                                                                            }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span id="create_pl1" class="help-box pull-right"><a href="create_policy"><i class="fa fa-plus"></i> Create new Policy</a>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span id="create_pl2" class="help-box pull-right"><a href="policy_detail?idn=<?= $result['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Custom Policy</a>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer justify-content-between">
        <button type="button" class="btn bg-gradient-danger pull-left" data-dismiss="modal">Cancel</button>
        <?php
                                            if ($statusApply == 0) { ?>
            <a style="width: 80px" href="scenario?idb=<?= $id_sample_scenario ?>" role="button" class="btn bg-gradient-success btn-sm">APPLY</a>
        <?php } else if ($statusApply == 1) { ?>
            <a style="width: 100px" href="scenario?idoff=<?= $id_sample_scenario ?>" role="button" class="btn bg-gradient-danger btn-sm">DISMISS</a>
        <?php } ?>
    </div>
    </div>
    </div>
    </div>
    <a role="button" class="btn bg-gradient-danger btn-sm" <?php if ($api) {
                                                                echo "title='This Scenario cannot be deleted because there is a policy attached to it' disabled";
                                                            } else {
                                                            } ?> title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>">DELETE</a>

    <?php
                                            $link = "scenario?";
                                            $id = $result['id'];
                                            $string = 'Are you sure to delete Scenario "' . $result['name'] . '"';
                                            confirm_delete($link, $id, $string);
    ?>
    </td>
    </tr>
<?php
                                        }
                                    }
                                }
?>
</tbody>
</table>
<nav class="action-buttons">
    <div style="text-align:right;">
        <a href="create_scenario" role="button" class="btn bg-gradient-success btn-sm">
            <i class="fa fa-plus icon-embed-btn"></i>
            Add</a>
    </div>
</nav>
</div>
<!-- /.card-body -->
</div>
<!-- /.box -->
</div>
</section>

<!-- content-page  -->



<section class="content">
    <div class="container-fluid" id="myDiv2">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="card-title"><b>Sample Scenario List</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example3" class="table table-bordered table-hover">



                            <thead>
                                <tr>
                                    <th><?= gettext("No") ?></th>
                                    <th><?= gettext("Scenario Name") ?></th>
                                    <th><?= gettext("Description") ?></th>
                                    <th><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $dem1 = 1;
                                $api_default = $api_result_default['data']["defaultScenario"];
                                $id_scenario_apply = $api_result_default['data']["scenarioNow"];
                                if ($api_default != "null") {
                                    foreach ($api_default as $i => $result_default) {
                                        if (!empty($result_default)) {
                                            // $url_2 = "http://$serverip/api/scenario-status-edge/" . $edge_id;
                                            // $dataStatus = array(
                                            //     "scenario_id" => $result_default['id']
                                            // );
                                            // $api_result2 = call_api("POST", $token, $url_2, json_encode($dataStatus));
                                            // $statusApply11  = $api_result2['data']['local'];
                                            $statusApply11 = checkStatusApply($result_default['id'], $edge_id);

                                ?>
                                            <tr>
                                                <td><?= htmlspecialchars($dem1++); ?></td>
                                                <td><?= htmlspecialchars($result_default['name']); ?></td>
                                                <td><?= htmlspecialchars($result_default['description']); ?></td>
                                                <td>
                                                    <?php if ($statusApply11 == 0) { ?>
                                                        <a style="width: 80px" href="scenario?idb=<?= $result_default['id'] ?>" role="button" class="btn bg-gradient-success btn-sm">APPLY</a>
                                                    <?php } else if ($statusApply11 == 1) { ?>
                                                        <a style="width: 100px" href="scenario?idoff=<?= $result_default['id'] ?>" role="button" class="btn bg-gradient-danger btn-sm">DISMISS</a>
                                                    <?php } ?>
                                                    <!-- <button type="button" class="btn bg-gradient-info" data-toggle="modal" data-target="#modal-info <?= $result_default['id'] ?>" <?= $result_default['id'] ?>>
                                                        SHOW
                                                    </button> -->
                                                    <a data-toggle="modal" data-target="#modal-info<?= $result_default['id'] ?>" role="button" <?= $result_default['id'] ?> class="btn bg-gradient-info btn-sm">SHOW</a>

                                                    <div class="modal fade" id="modal-info<?= $result_default['id'] ?>" role="dialog">
                                                        <div class="modal-dialog modal-info" role="document">

                                                            <div style="width: 850px !important;" class="modal-content bg-info">
                                                                <div class="modal-header">
                                                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                                                                    <h3 class="modal-title"><b><?= $result_default['name'] ?> Scenario</b></h3>
                                                                </div>

                                                                <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">

                                                                    <div class="row">
                                                                        <div>
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <table id="example3" class="table table-bordered table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th><?= htmlspecialchars("Policy Action"); ?></th>
                                                                                                <th><?= htmlspecialchars("Source Type"); ?></th>
                                                                                                <th><?= htmlspecialchars("Source Name"); ?></th>
                                                                                                <th><?= htmlspecialchars("Destination Type"); ?></th>
                                                                                                <th><?= htmlspecialchars("Destination Name"); ?></th>
                                                                                                <th><?= htmlspecialchars("Except"); ?></th>
                                                                                                <th><?= htmlspecialchars("Day(s)"); ?></th>
                                                                                                <th><?= htmlspecialchars("Time(s)"); ?></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $id_sample_scenario = $result_default['id'];
                                                                                            $url_1 = "http://$serverip/api/scenario-policy/" . $id_sample_scenario;
                                                                                            $api_result_default = call_api("GET", $token, $url_1, false);
                                                                                            // $dataStatus = array(
                                                                                            //     "scenario_id" => $id_sample_scenario
                                                                                            // );
                                                                                            // $url_2 = "http://$serverip/api/scenario-status-edge/" . $edge_id;
                                                                                            // $api_result2 = call_api("POST", $token, $url_2, json_encode($dataStatus));
                                                                                            // $statusApply  = $api_result2['data']['local'];
                                                                                            $statusApply = checkStatusApply($id_sample_scenario, $edge_id);


                                                                                            $api = $api_result_default['data'];
                                                                                            if ($api != "null") {
                                                                                                foreach ($api as $i => $result) {
                                                                                                    if (!empty($result)) {
                                                                                                        unset($schedule);
                                                                                                        $schedule = get_schedule_policy($result['schedule']);
                                                                                            ?>
                                                                                                        <tr>
                                                                                                            <td><?= htmlspecialchars($action[$result['action']]); ?></td>
                                                </td>
                                                <td><?= htmlspecialchars($stype[$result['source_type']]); ?></td>
                                                <?php
                                                                                                        switch ($result['source_type']) {
                                                                                                            case "0":
                                                                                                                $test = "<td><?=htmlspecialchars(";
                                                                                                                $test .= $result['device'];
                                                                                                                $test .= ");?></td>";
                                                                                                                echo $test;
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
                                                                                                                $test = show_web_cat();
                                                                                                                foreach ($num as $i => $na) {
                                                                                                                    foreach ($test as $res) {
                                                                                                                        if ($res['id'] == $na) {
                                                                                                                            $nan = $res['name'];
                                                                                                                            if ($i == 0) {
                                                                                                                                $nane .= $nan;
                                                                                                                            } else {
                                                                                                                                $nane .= ', ' . $nan;
                                                                                                                            }
                                                                                                                        }
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
                                                                                                                    $nan = $nan = get_one_app($na);
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
                                                <td><?= htmlspecialchars(($result['exceptRule'] != '') ? $result['exceptRule'] : "None"); ?></td>
                                                <td><?= htmlspecialchars((isset($schedule['weekdays'])) ? $schedule['weekdays'] : "All Days of Week") ?></td>
                                                <td><?= htmlspecialchars((isset($schedule['time'])) ? $schedule['time'] : "All Times") ?></td>
                                            </tr>
                                <?php
                                                                                                    }
                                                                                                }
                                                                                            }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- <span class="help-box pull-right"><a href="create_policy"><i class="fa fa-plus"></i> Create new Policy</a>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between ">
        <button type="button" class="btn bg-gradient-danger pull-left" data-dismiss="modal">Cancel</button>
        <?php if ($statusApply == 0) { ?>
            <a style="width: 80px" href="scenario?idb=<?= $result_default['id'] ?>" role="button" class="btn bg-gradient-success">APPLY</a>
        <?php } else if ($statusApply == 1) { ?>
            <a style="width: 100px" href="scenario?idoff=<?= $result_default['id'] ?>" role="button" class="btn bg-gradient-danger">DISMISS</a>
        <?php } ?>
    </div>
    </div>
    </div>
    </div>

    </td>
    </tr>
<?php
                                        }
                                    }
                                }
?>
</tbody>
</table>

</div>
<!-- /.card-body -->
</div>
<!-- /.box -->
</div>
</section>


</div>

<?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

</div>
<?php include_once "../src.php"; ?>
<script src="../pages/tables/data_tables/script.js"></script>
<?php }
}
