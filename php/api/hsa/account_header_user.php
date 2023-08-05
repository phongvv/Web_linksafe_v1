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
                        <h3 class="card-title"><b>End User List</b></h3>
                        <!-- <a href="account?id=user&&sd=true" role="button" class="btn bg-gradient-info btn-sm"><i class="fa fa-rotate-right icon-embed-btn"></i> Refesh</a> -->
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?= gettext("User ID") ?></th>
                                    <th><?= gettext("User Name") ?></th>
                                    <th><?= gettext("Group Name") ?></th>
                                    <th><?= gettext("Description") ?></th>
                                    <th><?= gettext("Status") ?></th>
                                    <th style="text-align:center;"><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $api = $api_result['data'];
                                unset($_SESSION['user']);
                                $_SESSION['user'] = $api;
                                if ($api != null) {
                                    foreach ($api as $i => $result) {
                                        if (!empty($result)) {
                                ?>
                                            <tr>
                                                <td><?= htmlspecialchars($result['userid']); ?></td>
                                                <td><?= htmlspecialchars($result['username']); ?></td>
                                                <td><?= htmlspecialchars($result['groupname']); ?></td>
                                                <td><?= htmlspecialchars(($result['description'] != "") ? $result['description'] : 'None'); ?></td>
                                                <?php
                                                if ($result['active'] == 0) {
                                                ?><td><span class="badge badge-danger rounded-pill d-inline">InActive</span></td><?php
                                                                                                                                } else {
                                                                                                                                    ?><td><span class="badge badge-success rounded-pill d-inline">Active</span></td><?php
                                                                                                                                                                                                                }
                                                                                                                                                                                                                    ?>
                                                <?php
                                                if ($result['userid'] != "1") {
                                                ?>
                                                    <td>
                                                        <a style="width: 25px" class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="create_enduser?id=<?= $result['userid'] ?>"></a>
                                                        <!-- <a class="fa fa-trash no-confirm"	title="<?= $gettext_array['del'] ?>"	role="button" href="account?id=user&&idd=<?= $result['userid'] ?>"></a> -->
                                                        <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['userid'] ?>"></a>
                                                        <?php
                                                        $link = "account?id=user&&";
                                                        $id = $result['userid'];
                                                        $string = 'Are you sure to delete User "' . $result['username'] . '".<br/><h4 style="display:inline-block;text-decoration: underline;"><b style="color:#f9c851;">Warning: </b></h4> All Data will be delete if you type "Submit" Button.';
                                                        confirm_delete($link, $id, $string);
                                                        ?>
                                                    </td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                            </tr>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <nav class="action-buttons">
                            <div style="text-align:right;">
                                <a href="create_enduser" role="button" class="btn bg-gradient-success btn-sm">
                                    <i class="fa fa-plus icon-embed-btn"></i>
                                    Add</a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>