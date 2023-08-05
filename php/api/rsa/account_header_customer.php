<section class="content">
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
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header with-border" style="text-align: center;">
                    <h3 class="card-title">SSA Customer List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?=gettext("No")?></th>
                            <th><?=gettext("UserName")?></th>
                            <th><?=gettext("Full Name")?></th>
                            <th><?=gettext("Email")?></th>
                            <th><?=gettext("Address")?></th>
                            <th><?=gettext("Phone Number")?></th>
                            <th><?=gettext("Organization")?></th>
                            <th><?=gettext("Department")?></th>
                            <th style="text-align:center;"><?=gettext("Actions")?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $api = $api_result['data'];
                            foreach($api as $i => $result) {
                                if(!empty($result)) {
                            ?>
                            <tr>
                                <td><?=htmlspecialchars($i);?></td>
                                <td><?=htmlspecialchars($result['username']);?></td>
                                <td><?=htmlspecialchars($result['fullname']);?></td>
                                <td><?=htmlspecialchars($result['email']);?></td>
                                <td><?=htmlspecialchars($result['address']);?></td>
                                <td><?=htmlspecialchars($result['phonenumber']);?></td>
                                <td><?=htmlspecialchars($result['organization']);?></td>
                                <td><?=htmlspecialchars($result['department']);?></td>
                                <td>
                                    <a class="fa fa-pencil"	title="<?=$gettext_array['edit']?>"	role="button" href="create_ssa_customer?id=<?=$i?>" ></a>
                                    <a class="fa fa-trash no-confirm"	title="<?=$gettext_array['del']?>"	role="button" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>"></a>
                                    <?php 
                                    $link="account?id=ssa-customer&&";
                                    $id=$result['id'];
                                    $string='Are you sure to delete account "'.$result["username"].'"';
                                    confirm_delete($link,$id,$string);
                                    ?>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <nav class="action-buttons">
                        <div style="text-align:right;">
                            <a  href="create_ssa_customer" role="button" class="btn bg-gradient-success btn-sm">
                                <i class="fa fa-plus icon-embed-btn"></i>
                                Add</a>
                        </div>
                    </nav>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.box -->
        </div>
</section>
