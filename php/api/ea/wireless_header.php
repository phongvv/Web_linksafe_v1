<?php
$active_menu = "wireless";
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {

    }
?>

    <div class="content" id="myDiv">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
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
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><b>Wireless Interfaces</b></h3>
                            <!-- <a href="account?id=user&&sd=true" role="button" class="btn bg-gradient-info btn-sm"><i class="fa fa-rotate-right icon-embed-btn"></i> Refesh</a> -->
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?= gettext("SSID") ?></th>
                                        <th><?= gettext("Encryption") ?></th>
                                        <th><?= gettext("Password") ?></th>
                                        <th><?= gettext("Status") ?></th>
                                        <th style="text-align:center;"><?= gettext("Actions") ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $url = "http://$serverip/api/wireless/" . $_SESSION['edge_id'];
                                    $api_result = call_api('GET', $token, $url, false);
                                    $data = $api_result["data"]['0'];
                                    $api = $data;
                                    if ($api != null) {


                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($api['ssid']); ?></td>
                                            <?php
                                            switch ($api['encryption']) {
                                                case "psk": ?>
                                                    <td>PSK</td>
                                                    <?php
                                                    break;
                                                    ?>
                                                <?php
                                                case "psk2": ?>
                                                    <td>PSK2</td>
                                                    <?php
                                                    break;
                                                    ?>
                                                <?php
                                                case "psk+ccmp": ?>
                                                    <td>PSK+CCMP</td>
                                                    <?php
                                                    break;
                                                    ?>
                                                <?php
                                                case "psk+aes": ?>
                                                    <td>PSK+AES</td>
                                                    <?php
                                                    break;
                                                    ?>
                                                <?php
                                                case "psk2+ccmp": ?>
                                                    <td>PSK2+CCMP</td>
                                                    <?php
                                                    break;
                                                    ?>
                                                <?php
                                                case "psk2+aes": ?>
                                                    <td>PSK2+AES</td>
                                                    <?php
                                                    break;
                                                    ?>
                                            <?php
                                            }
                                            ?>
                                            <td><?= htmlspecialchars("●●●●●●●"); ?></td>
                                            <?php
                                            if ($api['disabled'] == 1) {
                                            ?><td><span class="badge badge-danger rounded-pill d-inline">Disable</span></td><?php
                                                                                                                        } else {
                                                                                                                            ?><td><span class="badge badge-success rounded-pill d-inline">Enable</span></td><?php
                                                                                                                                                                                                    }
                                                                                                                                                                                                        ?>


                                            <td>
                                                <a style="width: 25px" class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="basic_wifi_edit?id=0"></a>

                                            </td>
                                        </tr>
                                    <?php

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
<?php
}
?>