<?php
require_once("functions.inc");
global $token, $serverip, $edge_type;
if ($_REQUEST['id'] != '') {
    $id = $_REQUEST['id'];
    $api_result1 = get_ea_info();
    $api = $api_result1['data'][$id];
    if ($api['serviceType'] == null) {
        $api['serviceType'] = 0;
    }
    $service = get_services();
}
?>
<style>
    #title {
        text-decoration: underline;
        font-weight: bold;
    }
</style>


<section class="content">
    <div class="container-fluid" id="myDiv">
        <div class="row">
            <div class="col-6">
                <div class="card card-info">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="box-title"><b>Edge Profile</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <!-- <tr>
                                <th clospan="2">Information </th>
                            </tr> -->
                            </thead>
                            <tbody>

                                <tr>
                                    <td id="title">Edge Name:</td>
                                    <td><?= htmlspecialchars($api['name']); ?></td>
                                </tr>
                                <tr>
                                    <td id="title">Edge Type:</td>
                                    <td><?= htmlspecialchars($edge_type[$api['edgeType']]); ?></td>
                                </tr>
                                <tr>
                                    <td id="title">Serial Number:</td>
                                    <td><?= htmlspecialchars($api['serial']); ?></td>
                                </tr>
                                <!-- <tr>
                                <td>Date of Active:</td>
                                <td><?= htmlspecialchars($api['dataOfActive']); ?></td>
                            </tr> -->
                                <tr>
                                    <td id="title">Apply Services:</td>
                                    <td><?= htmlspecialchars($service[$api['serviceType']]); ?></td>
                                </tr>
                        </table>
                        <a class="btn btn-block bg-gradient-primary btn-lg" role="button" href="forward?id=<?= $api['id'] ?>">Access Edge</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="col-xs-12">
                    <a style="width:50%;" class="btn btn-block bg-gradient-danger btn-lg" role="button" href="/api/techsupport/infor_edge">Return Edge Infomation</a>
                </div>
                <!-- <div class="col-md-12">
                <a href="account?id=2" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-info float-right" name="action" value="update">Update</button> -->
            </div>
        </div>
    </div>
</section>