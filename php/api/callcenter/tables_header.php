<!-- Main content -->
<style>
    .card {
        min-width: 100%;
    }
</style>
<section class="content">
    <div class="row">
        <!-- /.card -->

        <div class="card card-info">
            <div class="card-header" style="text-align:center">
                <h3 class="card-title">Customer List</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <div class="card-body__top">
                            <tr style=" width: 1163px;height: 42px;background-color: #33aaff;overflow: visible;">
                                <th><?= htmlspecialchars("No"); ?></th>
                                <th><?= htmlspecialchars("Full Name"); ?></th>
                                <th><?= htmlspecialchars("Email"); ?></th>
                                <th><?= htmlspecialchars("Address"); ?></th>
                                <th><?= htmlspecialchars("Phone Number"); ?></th>
                                <th><?= htmlspecialchars("Sale Contract Code"); ?></th>
                                <th><?= htmlspecialchars("Actions"); ?></th>
                            </tr>
                        </div>
                    </thead>
                    <tbody>
                        <?php
                        require_once("functions.inc");
                        global $serverip, $token;
                        $api = get_customer();
                        //  var_dump($api);
                        foreach ($api as $i => $result) {
                            if (!empty($result)) {
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($i); ?></td>
                                    <td><?= htmlspecialchars($result['fullname']); ?></td>
                                    <td><?= htmlspecialchars($result['email']); ?></td>
                                    <td><?= htmlspecialchars($result['address']); ?></td>
                                    <td><?= htmlspecialchars($result['phone']); ?></td>
                                    <td><?= htmlspecialchars($result['contractcode']); ?></td>
                                    <td>
                                        <a class="btn btn-block btn-primary btn-sm" role="button" href="details?id=<?= $result['id'] ?>">Details</a>
                                    </td>
                                </tr>

                        <?php
                            }
                        }
                        ?>

                        </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->