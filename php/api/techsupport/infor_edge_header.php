<?php
require_once("functions.inc");
// $json = file_get_contents('dataCallCeter.json');

// $decoded_json = json_decode($json, true);
// //  print_r($decoded_json);
// // trich xuat thong tin tu mang person 
// $person = $decoded_json['person'];
// // print_r($person);

if ($_SESSION['input_errors']) {
    print_error_box($_SESSION['input_errors']);
    unset($_SESSION['input_errors']);
} else if ($_SESSION['input_success']) {
    print_success_box($_SESSION['input_success']);
    unset($_SESSION['input_success']);
}

?>

<section class="content">
    <div class="container-fluid" id="myDiv">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="card-title"><b><u>Edge Information</u></b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <div class="card-body__top">
                                    <tr>
                                        <th><?= htmlspecialchars("No"); ?></th>
                                        <th><?= htmlspecialchars("Edge Name"); ?></th>
                                        <th><?= htmlspecialchars("Serial Number"); ?></th>
                                        <th><?= htmlspecialchars("Service"); ?></th>
                                        <th><?= htmlspecialchars("Status"); ?></th>
                                        <th><?= htmlspecialchars("Actions"); ?></th>
                                    </tr>
                                </div>
                            </thead>
                            <tbody>
                                <?php
                                $api_result = get_ea_info();
                                $api = $api_result['data'];
                                $service = get_services();
                                foreach ($api as $i => $result) {
                                    if (!empty($result)) {
                                        if ($result['serviceType'] == null) {
                                            $result['serviceType'] = 0;
                                        }
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($i); ?></td>
                                            <td><?= htmlspecialchars($result['name']); ?></td>
                                            <td><?= htmlspecialchars($result['serial']); ?></td>
                                            <td><?= htmlspecialchars($service[$result['serviceType']]); ?></td>
                                            <?php
                                            $status = get_status_edge($result['id']);
                                            if ($status['status'] == "Active") {
                                            ?>
                                                <td><span class="badge badge-success rounded-pill d-inline">Connected</span></td>
                                            <?php } else { ?>
                                                <td><span class="badge badge-danger rounded-pill d-inline">Disconnected</span></td>
                                            <?php } ?>
                                            <td>
                                                <a cstyle="width: 25px" class="fas fa-info-circle" title="Details" role="button" href="details_edge?id=<?= $i ?>"></a>
                                                <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="Delete Edge Here" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>"></a>
                                                <?php
                                                $link = "infor_edge?";
                                                $id = $result['id'];
                                                $string = 'Are you sure to delete Edge "' . $result['name'] . '".<br/><h4 style="display:inline-block;text-decoration: underline;"><b style="color:#f9c851;">Warning: </b></h4> All Data will be delete if you type "Submit" Button.';
                                                confirm_delete($link, $id, $string);
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                                </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>