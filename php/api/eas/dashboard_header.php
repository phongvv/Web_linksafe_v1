<!-- Content Header (Page header) -->
<?php
$active_menu = "account_hsa_bsa";
require_once('functions.inc');
global $token, $serverip;

if ($_SESSION['accounttype'] == 8) {
    $countries = edge_hsa_bsa_available_list(1);
    if ($countries["true"] != null) {
        $number_edge = count($countries["true"]);
    }
    $name_branch = $_SESSION["department"];
    $url = "http://$serverip/api/edge-EA";
    $api_result = call_api('GET', $token, $url, false);
    if ($api_result['data'] == "null") {
        $number_edge_total = $number_edge_mana = 0;
    } else {
        $number_edge_total = count($api_result["data"]);
        $number_edge_mana = count($api_result["data"]);
    }
} else if ($_SESSION['accounttype'] == 6) {
    $countries = branch_list()["true"];
    $countries1 = edge_list()["true"];
    foreach ($countries as $i => $res) {
        $data[$i] = array();
    }
    $url = "http://$serverip/api/get/edge";
    $token = $_SESSION["accesstoken"];
    $api_result = call_api('GET', $token, $url, false);
    if ($api_result['data'] == "null") {
        $name_branch = $countries[$_SESSION["branch_id"]];
        $number_edge = 0;
    } else {
        foreach ($api_result["data"] as $i => $res) {
            if ($data[$res["branch_id"]]["0"] == "") {
                unset($data[$res["branch_id"]]["0"]);
            }
            $data[$res["branch_id"]][$res["id"]] = $res["name"];
        }
        $name_branch = $countries[$_SESSION["branch_id"]];
        $number_edge = count($data[$_SESSION["branch_id"]]);
    }
} else {
    $number_edge = count($_SESSION["edge_ids"]);
}
?>
<link rel="stylesheet" href="BSA.css">
<link rel="stylesheet" href="AdminLTE.css">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="/api/hsa/static/css/Treant.css">
<link rel="stylesheet" href="/api/hsa/static/css/collapsable.css">
<link rel="stylesheet" href="/api/hsa/static/css/perfect-scrollbar.css">
<!-- Main content -->
<section class="content">
    <!-- Small cardes (Stat card) -->
    <div class="row">
        <?php if ($_SESSION['accounttype'] != "9") { ?>
            <div class="col-lg-6 col-xs-6">
                <!-- small card -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>
                            <td style="color:black"><?= htmlspecialchars(($name_branch != "") ? $name_branch : '0'); ?></td>
                        </h3>
                        <h4><b>BRANCH NAME</b></h4>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <?php if ($_SESSION['accounttype'] != "8") { ?>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small card -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= ($_SESSION['accounttype'] != "9") ? htmlspecialchars($number_edge_mana) : htmlspecialchars($number_edge) ?> EDGE
                    </h3>
                    <h4><b>TOTAL EDGE</b></h4>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-monitor"></i>
                </div>
                <?php if ($_SESSION['accounttype'] != "8") { ?>
                    <a href="edge" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                <?php } ?>
                <!-- <a href="devices_bsa_edge" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
    
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- <div class="col-lg-6"> -->
    <div class="card card-info">
        <div class="card-header with-border" style="text-align: center;">
            <h3 class="card-title" style="font-weight: 600;">Topology: </h3>
        </div>
        <div class="card-body">
            <div class="chart" id="collapsable-example"></div>
        </div>
    </div>
    <!-- </div> -->

    <!-- <div class="col-lg-6">
        <div class="card card-success">
            <div class="card-header with-border" style="text-align: center;">
                <h3 class="card-title" style="font-weight: 600;">Total Bandwidth: </h3>
                <div class="card-tools pull-right">
                    <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <span style="font-size:20px">Input Bandwidth: </span>
                    <span style="font-size:20px">abc </span>
                </div>

                <div>
                    <span style="font-size:20px">Output Bandwidth: </span>
                    <span style="font-size:20px">abc </span>
                </div>
            </div>
        </div>
    </div> -->
    </div>

</section>
<script src="static/js/raphael.js"></script>
<script src="static/js/Treant.js"></script>
<script src="static/js/jquery.min.js"></script>
<script src="static/js/jquery.easing.js"></script>
<script src="static/data/12/collapsable.js"></script>
<script>
    tree = new Treant(chart_config);
</script>
<!-- /.content -->