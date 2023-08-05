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
            ?>
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <p>
                                Group End User List
                            </p>
                        </h3>
                        <!-- <a href="account?id=group&&sd=true" role="button" class="btn bg-gradient-info btn-sm"><i class="fa fa-rotate-right icon-embed-btn"></i> Refesh</a> -->
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%"><?= gettext("Group ID") ?></th>
                                    <th style="text-align:center;"><?= gettext("Group Name") ?></th>
                                    <th style="text-align:center;"><?= gettext("Description") ?></th>
                                    <th style="text-align:center;"><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $api = $api_result['data'];
                                unset($_SESSION['group']);
                                $_SESSION['group'] = $api;
            
                                if ($api != null) {
                                    foreach ($api as $i => $result) {
                                        if (!empty($result)) {
                                ?>
                                            <tr>
                                                <td style="text-align:center;"><?= htmlspecialchars($result['id']); ?></td>
                                                <td style="text-align:center;"><?= htmlspecialchars($result['groupname']); ?></td>
                                                <!-- <td style="text-align:center;"><?= htmlspecialchars($result['description']); ?></td> -->
                                                <td style="text-align:center;"><?= htmlspecialchars(($result['description'] != "") ? $result['description'] : 'None'); ?></td>
                                                <?php
                                                if ($result['id'] != "1" && $result['id'] != "2" && $result['id'] != "3"
                                                && $result['id'] != "4"&& $result['id'] != "5"&& $result['id'] != "6"
                                                && $result['id'] != "7"&& $result['id'] != "8"&& $result['id'] != "9"&& $result['id'] != "10") {
                                                ?>
                                                    <td>
                                                        <a style="width: 25px" class="fa fa-pen" title="<?= $gettext_array['edit'] ?>" role="button" href="create_endgroup?id=<?= $result['groupid']  ?>"></a>
                                                        <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>"></a>
                                                        <?php
                                                        // $url = "http://$serverip/api/check-group-policy/".$_SESSION['edge_id'];
                                                        // $dataaa = array(
                                                        //     "id" => $result['id']
                                                        // );
                                                        // $app = call_api("POST",$token,$url,json_encode($dataaa));
                                                        // if ($app['data']['Group'] != "Group is not applied policy") {
                                                        //     $noti = $app['data']['Group'];
                                                        // } else if (count($app['data']['policy_user']) != "0") {
                                                        //     $noti = $app['data']['policy_user'];
                                                        // }
                                                        $link = "account?id=group&&";
                                                        $id = $result['id'];
                                                        $string = 'Are you sure to delete Group "' . $result['groupname'] . '".<br/><h4 style="display:inline-block;text-decoration: underline;"><b style="color:#f9c851;">Warning: </b></h4> All Data will be delete if you type "Submit" Button.';
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
                                <a href="create_endgroup" role="button" class="btn bg-gradient-success btn-sm">
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
