<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
<div class="content" id="myDiv">
    <div class="container-fluid">
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
            $dem = 0;
            ?>
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="card-title"><b>End Device List</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%"><?= gettext("No") ?></th>
                                    <th style="width: 10%"><?= gettext("IP Address") ?></th>
                                    <th style="text-align:center;"><?= gettext("User Name") ?></th>
                                    <th style="text-align:center;"><?= gettext("Edge Name") ?></th>
                                    <th style="text-align:center;"><?= gettext("Group Name") ?></th>
                                    <th style="text-align:center;"><?= gettext("Hostname") ?></th>
                                    <th style="text-align:center;"><?= gettext("Status") ?></th>
                                    <th style="text-align:center;"><?= gettext("Session Start") ?></th>
                                    <!-- <th style="text-align:center;"><?= gettext("Session Stop") ?></th> -->
                                    <th class="sorting" tabindex="0" aria-controls="example3" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 80px;">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                unset($_SESSION['device']);
                                $_SESSION['device'] = $api;
                                if ($api != null) {
                                        foreach ($api as $i => $result) {
                                        if (!empty($result)) {
                                ?>
                                            <tr>
                                                <td><?= htmlspecialchars($dem++); ?></td>
                                                <td><?= htmlspecialchars(($result['ip'] != "") ? $result['ip'] : 'None'); ?></td>
                                                <td><?= htmlspecialchars(($result['user_name'] != "") ? $result['user_name'] : 'None'); ?>
                                                    <?php
                                                    if ($result['state'] != "Preauthenticated") {
                                                    ?><a data-toggle="modal" data-target="#myModal1<?= $i ?>" role="button" title="Edit" <?= $result_default['id'] ?> class="fa-solid fa-pencil btn-sm"></a><?php
                                                                                                                                                                                                        } else {
                                                                                                                                                                                                            ?><a role="button" title="No Edit" class="disabled">
                                                            <span class="fa-stack fa-lg">
                                                                <i class="fa fa-pen fa-stack-1x" style="font-size: 14px;"></i>
                                                                <i class="fa fa-ban fa-stack-1x text-danger"></i>
                                                            </span></a><?php
                                                                                                                                                                                                        }
                                                                        ?>

                                                </td>

                                                <td><?= htmlspecialchars(($result['edge_name'] != "") ? $result['edge_name'] : 'None'); ?></td>
                                                <td><?= htmlspecialchars(($result['group_name'] != "") ? $result['group_name'] : 'None'); ?></td>
                                                <td><?= htmlspecialchars(($result['hostname'] != "") ? $result['hostname'] : 'Unknown'); ?></td>
                                                <?php
                                                if ($result['state'] == "Preauthenticated") {
                                                ?><td><span class="badge badge-danger rounded-pill d-inline">Unauthenticated</span></td><?php
                                                                                                                                    } else {
                                                                                                                                        ?><td><span class="badge badge-success rounded-pill d-inline">Authenticated</span></td><?php
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                ?>
                                                <td><?= htmlspecialchars(($result['session_start'] != "") ? date('H:i d-m-Y', $result['session_start']) : ''); ?></td>
                                                <!-- <td><?= htmlspecialchars(($result['session_end'] != "") ? date('H:i d-m-Y', $result['session_end']) : ''); ?></td> -->
                                                <td>
                                                    <?php
                                                    if ($result['state'] == "Preauthenticated") {
                                                        // if (true) {
                                                    ?>
                                                        <a title="Authenticated" href="account?id=device&&idb=<?= $i ?>" role="button" class="fa fa-circle-check"></a>
                                                    <?php } else { ?>
                                                        <a title="Unauthenticated" href="account?id=device&&idd=<?= $i ?>" role="button" class="fa fa-times-circle"></a>
                                                        <i class=""></i>
                                                    <?php } ?>
                                                    <!-- <a data-toggle="modal" data-target="#myModal1<?= $result['id'] ?>" role="button" title="EDIT" <?= $result_default['id'] ?> class="fa-solid fa-pencil btn-sm"></a> -->

                                                    <!-- <a role="button" class="fa-solid fa-pencil" title="EDIT" href="create_scenario?id=<?= $i ?>"> </a> -->

                                                    <div class="modal fade" id="myModal1<?= $i ?>" role="dialog">
                                                        <div class="modal-dialog modal-info" role="document">
                                                            <div class="modal-content bg-info">
                                                                <form role='form_edit' action="account?id=device&&idc=<?= $i ?>" method="get">
                                                                    <div>
                                                                        <div class="modal-header">
                                                                            <h3 class="card-title"><b><?= $result['hostname'] ?></b>
                                                                            </h3>
                                                                        </div>
                                                                        <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="card card-primary">
                                                                                        <form role="form">
                                                                                            <div class="card-body">
                                                                                                <div class="input-group mb-3">
                                                                                                    <label for="exampleInputEmail1">User name</label>
                                                                                                    <label></label>
                                                                                                    <input id="id" type="hidden" name="id" value="<?= $_REQUEST["id"] ?>"/>
                                                                                                    <input id="idc" type="hidden" name="idc" value="<?= $i ?>" />
                                                                                                    <select name="user" id="user" class="form-control select2" style="width: 100%;">
                                                                                                        <?php
                                                                                                        $countries = show_user(false);
                                                                                                        foreach ($countries as $cc => $name) {
                                                                                                            echo '<option value="' . $cc . '">' . $name . '</option>';
                                                                                                        } ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn bg-gradient-danger pull-left" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn bg-gradient-success btn-sm">Authenticated</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                <?php
                                        }
                                    }
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
<script type="text/javascript">
    function form_submit() {
        document.getElementById("paymentitrform").submit();
    }
</script>
