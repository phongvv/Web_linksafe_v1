<?php
$active_menu = "scenario";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {

    include_once "head.php";
    $idn = $_REQUEST['id'];
    $url_2 = "http://$serverip/api/scenario-private/$idn";
    $api_result_default2 = call_api("GET", $token, $url_2, false);
    $hehe = $api_result_default2['data'];
    foreach ($hehe as $h) {
        if ($h['id'] == $idn) {
            $namee = $h['name'];
        }
    }

?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper" style="height: 780px;">
                <!-- content-page -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <div class="row">
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
                            <div class="col-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><b style="text-align: center!important;"><u>List of Edges using <?= htmlspecialchars($namee); ?> Scenario</u></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?= htmlspecialchars("No"); ?></th>
                                                    <th><?= htmlspecialchars("Edge Name"); ?></th>
                                                    <th><?= htmlspecialchars("Type"); ?></th>
                                                    <th style="text-align:center;"><?= htmlspecialchars("Actions"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $api = $hehe;
                                                if ($api != "null") {
                                                    foreach ($api as $iii => $result) {
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($iii++); ?></td>
                                                            <td><?= htmlspecialchars($result['edge_name']); ?></td>
                                                            <?php
                                                            if ($result['type'] == 2) {

                                                            ?>
                                                                <td>Wifi</td>

                                                            <?php

                                                            } else if ($result['type'] == 1) {
                                                            ?>
                                                                <td>SmartRouter</td>

                                                            <?php

                                                            } else {
                                                            ?>
                                                                <td>hihi</td>

                                                            <?php
                                                            }
                                                            ?>

                                                            <td> <a href="../ea/dashboard?edge_id=<?= $result['id'] ?>" role="button" class="btn bg-gradient-primary btn-sm-3">
                                                                    Go To Config Detail</a></td>
                                                        </tr>
                                                <?php

                                                    }
                                                    // $haha = implode(", ", $hihi);
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

                <!-- content-page -->

            </div>

            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
    <?php
}
