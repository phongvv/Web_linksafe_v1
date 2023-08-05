<!-- Content Header (Page header) -->
<?php
// phpsession_begin();
error_reporting(E_ALL ^ E_NOTICE);
$active_menu = "account_hsa_bsa";
require_once('functions.inc');
global $token, $serverip;

if ($_SESSION['accounttype'] == 7) {
    $countries = edge_bsa_list(false);
    $number_edge = count($countries);
    $name_branch = $_SESSION["department"];
    $url = "http://$serverip/api/edge-BSA";
    $api_result = call_api('GET', $token, $url, false);
    $number_edge_total = count($api_result["data"][0]["Total edge"]);
    $number_edge = count($api_result["data"][2]["edge_BSA"]);
} else if ($_SESSION['accounttype'] == 6) {
    $countries = branch_list()["true"];
    $countries1 = edge_list()["true"];
    foreach ($countries as $i => $ress) {
        $data1[$i] = array();
    }
    $url = "http://$serverip/api/get/edge";
    $token = $_SESSION["accesstoken"];
    $api_result = call_api('GET', $token, $url, false);
    $number_edge = 0;
    foreach ($api_result["data"] as $i => $resss) {
        if ($_SESSION['branch_id'] == $resss['branch_id']) {
            $number_edge++;
        }
    }
    $name_branch = $countries[$_SESSION["branch_id"]];
}
?>
<link rel="stylesheet" href="BSA.css">
<link rel="stylesheet" href="AdminLTE.css">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<title> Collapsable example </title>
<link rel="stylesheet" href="/api/hsa/static/css/Treant.css">
<link rel="stylesheet" href="/api/hsa/static/css/collapsable.css">
<link rel="stylesheet" href="/api/hsa/static/css/perfect-scrollbar.css">
<!-- Main content -->
<section class="content animate-bottom">
    <!-- Small cardes (Stat card) -->
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <!-- small card -->
            <div class="small-box bg-indigo">
                <div class="inner">
                    <h3><?= htmlspecialchars(($name_branch != "") ? $name_branch : '0'); ?></h3>
                    <h4><b> BRANCH NAME</b></h4>
                </div>
                <div class="icon">
                    <i class="ion ion-connection-bars"></i>
                </div>
                <?= ($_SESSION['accounttype'] == "7") ? "<a href=\"#\" class=\"small-box-footer\">More info <i class=\"fa fa-arrow-circle-right\"></i></a>" : " " ?>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small card -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= htmlspecialchars($number_edge); ?> Edge</td>
                    </h3>
                    <h4><b>TOTAL EDGE</b></h4>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-monitor"></i>
                </div>
                <?= ($_SESSION['accounttype'] == "7") ? "<a href=\"devices_bsa_edge\" class=\"small-box-footer\">More info <i class=\"fa fa-arrow-circle-right\"></i></a>" : " " ?>
            </div>
        </div>
  
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="col-md-7">
        <div class="col">
            <div class="card card-info">
                <div class="card-header with-border" style="text-align: center;">
                    <h3 class="card-title" style="font-weight: 600;">Topology: </h3>
                </div>
                <div class="card-body">
                    <div class="chart" id="collapsable-example"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-5">
        <div class="col">
            <div class="card card-success">
                <div class="card-header with-border" style="text-align: center;">
                    <h3 class="card-title" style="font-weight: 600;">List Edge </h3>
                </div>
                <div class="card-body">
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="font-weight: bold;"><?= gettext("No") ?></th>
                                <th style="font-weight: bold;"><?= gettext("Edge Name") ?></th>
                                <th style="font-weight: bold;"><?= gettext("Status") ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $aaaa = $res['ea'];
                            $no = 0;
                            $stauts = array();
                            foreach ($aaaa as $l => $ll) {
                                foreach ($ll['edge'] as $egde) {
                                    $stauts[] = $egde;
                                }
                            }

                            foreach ($stauts as $di => $name) {
                                if (!empty($name)) {
                            ?>
                                    <tr style="text-align: center;">
                                        <td><?= htmlspecialchars($no++) ?></td>
                                        <td><?= htmlspecialchars($name['name']) ?></td>
                                        <td><?= ($name['status'] == "Active") ? "<span class=\"badge badge-success rounded-pill d-inline\">" . $name['status'] . "</span>" : "<span class=\"badge badge-danger rounded-pill d-inline\">" . $name['status'] . "</span>" ?></td>
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
    <div class="row">
        <!-- <div class="col-lg-6">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title" style="font-weight: 600;">Total Bandwidth: </h3>
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
        </div>
        <div class="col-lg-6">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title" style="font-weight: 600;">Information</h3>
                </div>
                <div class="card-body">
                    <div>
                        <span style="font-size:20px">Branch: </span>
                        <span style="font-size:20px">Hà nội </span>
                    </div>

                    <div>
                        <span style="font-size:20px">BSA: </span>
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