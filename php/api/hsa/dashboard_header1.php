<!-- Content Header (Page header) -->
<?php
$active_menu = "account_hsa_bsa";
require_once('functions.inc');
global $token, $serverip;
$countries = branch_list();
$countries1 = edge_list();
if ($countries != "null") {
    if ($countries["true"] != null) {
        $number_branch = count($countries["true"]);
    }
}
if ($countries1 != "null") {
    $number_edge = count($countries1["true"]);
}
$url = "http://$serverip/api/edge-Available";
$api_result = call_api('GET', $token, $url, false);
$number_edge_total = count($api_result["data"][0]["total edge"]);
?>
<link rel="stylesheet" href="BSA.css">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="static/css/Treant.css">
<link rel="stylesheet" href="static/css/collapsable.css">
<link rel="stylesheet" href="static/css/perfect-scrollbar.css">
<script src="/api/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Main content -->
<div class="content animate-bottom">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?= htmlspecialchars(($number_branch != "") ? $number_branch : '0'); ?> Branch</h3>
                    <b>
                        <h4>TOTAL BRANCH
                    </b></td>
                    </h4>
                </div>
                <div class="icon">
                    <!-- <i class="fa-solid fa-code-branch"></i> -->
                    <i class="ion ion-connection-bars"></i>
                </div>
                <a href="account_hsa_bsa" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= htmlspecialchars(($number_edge_total != "") ? $number_edge_total : '0'); ?> Edge</h3>
                    <h4><b>TOTAL EDGE</b></h4>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-monitor"></i>
                </div>
                <a href="account_hsa_ea" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-md-12" style="height: 800px;">
            <div class="card card-info">
                <div class="card-header with-border" style="text-align: center;">
                    <h3 class="card-title" style="font-weight: 600;">Topology: </h3>
                </div>
                <div class="card-body">
                    <div class="chart" id="collapsable-example"></div>
                </div>
            </div>

        </div>

        <!-- <div class="col-lg-6">
            <div class="box box-success">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title" style="font-weight: 600;">Total Bandwidth: </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->


</div>

<!-- <div class="col-lg-6">
            <div class="box box-success">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title" style="font-weight: 600;">Information</h3>
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

</div>
<script src="static/js/raphael.js"></script>
<script src="static/js/Treant.js"></script>
<script src="static/js/jquery.min.js"></script>
<script src="static/js/jquery.easing.js"></script>
<script src="static/data/12/collapsable.js"></script>
<script>
    tree = new Treant(chart_config);
</script>