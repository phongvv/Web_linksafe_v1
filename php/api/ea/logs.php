<?php
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        if (isset($_REQUEST['id'])) {
            $ids = $_REQUEST['id'];
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
                        <div class="container-fluid" id="myDiv">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-header" style="text-align: center;">
                                            <h3 class="card-title"><b><u>Activity Logs of <b style="color: #1b26ae;"><?= get_one_user($ids) ?></b></u></b></h3>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><?= htmlspecialchars("No"); ?></th>
                                                        <th><?= htmlspecialchars("Device Name"); ?></th>
                                                        <th><?= htmlspecialchars("MacAddress"); ?></th>
                                                        <th><?= htmlspecialchars("Active"); ?></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // if ($_REQUEST['logs'] == true) {
                                                    $app = get_logs_user($ids, false, false);
                                                    // $app1 = get_logs_user($ids,false);
                                                    if ($app != null) {
                                                        $i = 1;
                                                        foreach ($app as $iii => $result) {
                                                            if (get_host_name($iii) != "0") {
                                                    ?>
                                                                <tr>
                                                                    <td style="width: 10%;"><?= htmlspecialchars($i); ?></td>
                                                                    <td style="width: 40%;"><?= ($aaa = get_host_name($iii)['hostname'] != "") ? htmlspecialchars(get_host_name($iii)['hostname']) : "Unknown"; ?></td>
                                                                    <td style="width: 40%;"><?= htmlspecialchars($iii); ?></td>
                                                                    <td style="width: 10%;">
                                                                        <a style="width:70% ;justify-content: center;" class="btn btn-block btn-primary btn-sm" role="button" href="log?id=<?= $ids ?>&idd=<?= $iii ?>">Details</a>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                                $i++;
                                                            }
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
                                    category: "<?= $apps ?>",
                                    value: <?= $val ?>
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
