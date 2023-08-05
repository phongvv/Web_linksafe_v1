<?php
$active_menu = "policy";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    
    include_once "head.php";
    $url = "http://$serverip/api/scenario";
    $api_result = call_api('GET', $token, $url, false);

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
                                        <h3 class="card-title"><b style="text-align: center!important;"><u>Scenario List</u></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?= htmlspecialchars("No"); ?></th>
                                                    <th><?= htmlspecialchars("Scenario Name"); ?></th>
                                                    <th><?= htmlspecialchars("Discription"); ?></th>
                                                    <!-- <th><?= htmlspecialchars("List Existing Policy"); ?></th> -->

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
                                                            $hihi[] = $result['description'];

                                                ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($iii); ?></td>
                                                                <td><?= htmlspecialchars($result['name']); ?></td>
                                                                <td><?= htmlspecialchars($result['description']); ?></td>
                                                                <td> <a href="policy_detail?idn=<?= $result['id'] ?>" role="button" class="btn bg-gradient-primary btn-sm-3">
                                                                        Details</a></td>
                                                            </tr>
                                                <?php
                                                        }
                                                    }
                                                    // $haha = implode(", ", $hihi);
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <nav class="action-buttons">
                                            <div style="text-align:right;">
                                                <a href="create_policy" role="button" class="btn bg-gradient-success btn-sm-3">
                                                    <i class="fa fa-plus icon-embed-btn"></i>
                                                    Add Policy</a>
                                            </div>
                                        </nav>
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
