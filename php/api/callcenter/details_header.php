<?php
require_once("functions.inc");
global $serverip, $token;
$api = get_customer();
?>
<section class="content">
    <div class="row">
        <!-- <div class="col-md-6"> -->
        <div class="card card-info">
            <div class="card-header with-border" style="text-align:center;background-color:blue;">
                <h3 class="card-title" style="color:white;">Contract Profile</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">

                    <tr>
                        <td>Contract Name:</td>
                        <td><?= htmlspecialchars($api[$i]['contract']['contractName']); ?></td>
                    </tr>
                    <tr>
                        <td>Contract Type:</td>
                        <td><?= htmlspecialchars($api[$i]['contract']['contractType']); ?></td>
                    </tr>
                    <tr>
                        <td>Sale contract code:</td>
                        <td><?= htmlspecialchars($api[$i]['contract']['contractCode']); ?></td>
                    </tr>
                    <!-- /.table -->
                </table>
            </div>

        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- </div> -->

        <!-- /.col -->
        <!-- <div class="col-md-6"> -->
        <div class="card card-info">
            <div class="card-header" style="text-align:center;background-color:blue;">
                <h3 class="card-title" style="color:white;">Customer Profile</h3>

                <div class="card-tools">
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body no-padding">
                <table class="table">
                    <tr>

                    </tr>
                    <tr>
                        <td>Fullname:</td>
                        <td><?= htmlspecialchars($api[$i]['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Date of birth:</td>
                        <td><?= htmlspecialchars($api[$i]['dateOfBirth']); ?></td>
                    </tr>

                </table>
            </div>
            <!-- /.card-body -->
        </div>



        <!-- /.col -->
        <!-- </div> -->
        <!-- <div class="col-md-6"> -->
        <div class="card card-info">
            <?php
            foreach ($api[$i]['inforEdge'] as $j => $val) {
            ?>
                <div class="card-header with-border" style="text-align:center;background-color:blue;">
                    <h3 class="card-title" style="color:white;">Edge Profile</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>

                        </tr>
                        <tr>
                            <td>Edge Name:</td>
                            <td><?= htmlspecialchars($val['edgeName']); ?></td>
                        </tr>
                        <tr>
                            <td>Edge Type:</td>
                            <td><?= htmlspecialchars($val['edgeType']); ?></td>
                        </tr>
                        <tr>
                            <td>Serial Number:</td>
                            <td><?= htmlspecialchars($val['seri']); ?></td>
                        </tr>
                        <tr>
                            <td>Serial Number:</td>
                            <td><?= htmlspecialchars($val['seri']); ?></td>
                        </tr>
                        <tr>
                            <td>Start At:</td>
                            <td><?= htmlspecialchars($val['startAt']); ?></td>
                        </tr>
                        <tr>
                            <td>Date of Active:</td>
                            <td><?= htmlspecialchars($val['dataOfActive']); ?></td>
                        </tr>
                        <tr>
                            <td>Service Type:</td>
                            <td><?= htmlspecialchars($val['serveice']); ?></td>
                        </tr>
                    </table>
                    <!-- </div> -->
                </div>
        </div>


    <?php
            }
    ?>
    <div class="row">
        <div class="col-xs-12">
            <a style="width:30%;" class="btn btn-block btn-primary btn-lg" role="button" href="tables">Return</a>
        </div>
    </div>
</section>