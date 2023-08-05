<?php
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        if (isset($_GET['id'])) {
            $ids = $_GET['id'];
            $device_id = $_GET['idd'];
            $active_menu = "user" . $ids;
        }
?>


      
        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 780px;">
                    <!-- content-page  -->
                    <section class="content">
                        <div class="col-md-*" id="myDiv">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Custom Tabs -->
                                    <div class="card card-primary card-outline card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Table</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Graph</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <div class="col-12">
                                                        <div class="card card-info">
                                                            <div class="card-header with-border" style="text-align: center;">
                                                                <h3 class="card-title"><b><u>Activity Logs of <b style="color: #1b26ae;"><?= get_one_user($ids) ?></b></u></b></h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <table id="example1" class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><?= htmlspecialchars("No"); ?></th>
                                                                            <th><?= htmlspecialchars("Detected App"); ?></th>
                                                                            <th><?= htmlspecialchars("Total Time Used"); ?></th>
                                                                            <th><?= htmlspecialchars("Last Time Access"); ?></th>
                                                                            <th><?= htmlspecialchars("Total"); ?></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                    // if ($_REQUEST['logs'] == true) {
                                                                        $dem = 0;
                                                                    $app = get_logs_user($ids, true, $device_id);
                                                                    // $app1 = get_logs_user($ids,false);
                                                                    if ($app != null) {

                                                                        foreach ($app as $iii => $result) {

                                                                            // if ($iii != "Unknown") {
                                                                    ?>
                                                                            <tr>
                                                                                <td><?= htmlspecialchars($dem++); ?></td>
                                                                                <td><?= htmlspecialchars($result['detected']); ?></td>
                                                                                <td><?= ($result['up_time'] > 1000) ? htmlspecialchars(get_uptime($result['up_time'] / 1000)) : "0 Seconds" ?></td>
                                                                                <td><?= htmlspecialchars($result['first_seen_at']); ?></td>
                                                                                <?php if ($result['total_bytes'] <= 1024) { ?>
                                                                                    <td><?= htmlspecialchars($result['total_bytes']) . " " . "Kb"; ?></td>
                                                                                <?php } else if ($result['total_bytes'] > 1024 && $result['total_bytes'] <= 1024 * 1024) { ?>
                                                                                    <td><?= htmlspecialchars(round($result['total_bytes'] / 1024, 1)) . " " . "Mb"; ?></td>
                                                                                <?php } else if ($result['total_bytes'] > 1024 * 1024 && $result['total_bytes'] <= 1024 * 1024 * 1024) { ?>
                                                                                    <td><?= htmlspecialchars(round($result['total_bytes'] / (1024 * 1024), 1)) . " " . "Gb"; ?></td>
                                                                                <?php } ?>
                                                                            </tr>
                                                                    <?php
                                                                            // $jj = $iii;
                                                                            // }
                                                                        }
                                                                    }
                                                                    // }
                                                                    ?>
                                                                </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                    <div class="col-12">
                                                        <div class="card card-info">
                                                            <div class="card-header with-border" style="text-align: center;">
                                                                <h3 class="card-title"><b><u>FLows App</b></u></b></h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <div id="result" style="width: 100%;height: 500px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- nav-tabs-custom -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <div class="back" style="width: 200px; height: 50px;">
                                <a style=" height: 50px; text-align:center;" class="btn btn-block bg-gradient-primary btn-sm" role="button" href="logs?id=<?= $ids ?>">
                                    <p style="font-size: 20px;">Return User Log</p>
                                </a>
                            </div>
                    </section>

                    <!-- content-page  -->

                </div>


                <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>
            
            <script src="../assets/js/index.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
            <!-- Chart code -->
            <script>
                am5.ready(function() {

                    // Create root element
                    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                    var root = am5.Root.new("result");

                    // Set themes
                    // https://www.amcharts.com/docs/v5/concepts/themes/
                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);

                    // Create chart
                    // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
                    var chart = root.container.children.push(
                        am5percent.PieChart.new(root, {
                            endAngle: 270
                        })
                    );

                    // Create series
                    // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
                    var series = chart.series.push(
                        am5percent.PieSeries.new(root, {
                            valueField: "value",
                            categoryField: "category",
                            endAngle: 270
                        })
                    );

                    series.states.create("hidden", {
                        endAngle: -90
                    });

                    // Set data
                    // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
                    series.data.setAll([
                        <?php
                        $log = $app;
                        foreach ($log as $apps => $val) {
                            if (!empty($val) && $apps != "Unknown") {

                        ?> {
                                    category: "<?= $val['detected'] ?>",
                                    value: <?= $val['up_time'] ?>
                                },
                        <?php
                            }
                        }
                        ?>
                    ]);

                    series.appear(1000, 100);


                }); // end am5.ready()
            </script>
<?php
    }
}
?>
