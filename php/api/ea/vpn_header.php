<div class="content" id="myDiv">
    <div class="container-fluid">
        <div class="row">
            <section class="col-xl-12 connectedSortable">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">VPN List</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i ></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?= gettext("VPN Name") ?></th>
                                    <th><?= htmlspecialchars("Local Address"); ?></th>
                                    <th><?= htmlspecialchars("Local ID"); ?></th>
                                    <th><?= htmlspecialchars("Local Subnet"); ?></th>
                                    <th><?= htmlspecialchars("Remote Address"); ?></th>
                                    <th><?= htmlspecialchars("Remote ID"); ?></th>
                                    <th><?= htmlspecialchars("Remote Subnet"); ?></th>
                                    <th style="text-align:center;"><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $api = get_list_vpn();
                                foreach ($api as $i => $result) {
                                    if (!empty($result)) {
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($i); ?></td>
                                            <td><?= htmlspecialchars($result[$i . "_tunnel"]['local_leftip']); ?></td>
                                            <td><?= htmlspecialchars($result[$i]['local_identifier']); ?></td>
                                            <td><?= htmlspecialchars($result[$i . "_tunnel"]['local_subnet']); ?></td>
                                            <td><?= htmlspecialchars($result[$i]['gateway']); ?></td>
                                            <td><?= htmlspecialchars($result[$i]['remote_identifier']); ?></td>
                                            <td><?= htmlspecialchars($result[$i . "_tunnel"]['remote_subnet']); ?></td>
                                            <td>
                                                <a style="width: 25px" class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="create_vpn?id=<?= $i ?>"></a>
                                                <!-- <a class="fa fa-trash no-confirm"	title="<?= $gettext_array['del'] ?>"	role="button" href="network?id=vpn&&idd=<?= $i ?>"></a> -->
                                                <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $i ?>"></a>
                                                <?php
                                                $link = "network?id=vpn&&";
                                                $id = $i;
                                                $string = 'Are you sure to delete VPN "' . $i . '"';
                                                confirm_delete($link, $id, $string);
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <nav class="action-buttons">
                        <div style="text-align:right;">
                            <a href="create_vpn" role="button" class="btn bg-gradient-success btn-sm">
                                <i class="fa fa-plus icon-embed-btn"></i>
                                Add</a>
                        </div>
                    </nav>
                    <!-- /.card-body -->
                    <!-- /.card-footer-->
                </div>
            </section>
        </div>
    </div>
</div>
