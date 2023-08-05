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
                <div class="card-header with-border">
                    <h3 class="card-title">Customer List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?=gettext("No")?></th>
                            <th><?=gettext("User Name")?></th>
                            <th><?=gettext("Full Name")?></th>
                            <th><?=gettext("Phone Number")?></th>
                            <th><?=gettext("Address")?></th>
                            <th><?=gettext("Email")?></th>
                            <th><?=gettext("Account Type")?></th>
                            <!-- <th><?=gettext("Sale Contract Code")?></th> -->
                            <th style="text-align:center;"><?=gettext("Actions")?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            foreach($api as $i => $result) {
                                if(!empty($result)) {
                            ?>
                            <tr>
                                <td><?=htmlspecialchars($i);?></td>
                                <td><?=htmlspecialchars($result['username']);?></td>
                                <td><?=htmlspecialchars($result['fullname']);?></td>
                                <td><?=htmlspecialchars($result['phone'] == "" ? "" : $result['phone']);?></td>
                                <td><?=htmlspecialchars($result['address'] == "" ? "" : $result['address']);?></td>
                                <td><?=htmlspecialchars($result['email']);?></td>
                                <td><?=htmlspecialchars($result['accountType'] == "9" ? "Home" : "Enterprise");?></td>
                                <!-- <td><?=htmlspecialchars($result['salecontractcode'] == "" ? "" : $result['salecontractcode']);?></td> -->
                                <td>
                                    <a class="fa fa-pen" style="width: 25px"	title="<?=$gettext_array['edit']?>"	role="button" href="add_customer?id=<?=$result['id']?>" ></a>
                                    <!-- <a class="fa fa-trash no-confirm"	title="<?=$gettext_array['del']?>"	role="button" href="account?id=2&&idd=<?=$result['id']?>"></a> -->
                                    <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['id'] ?>" ></a>
                                    <?php 
                                    $link="account?id=2&&";
                                    $id=$result['id'];
                                    $string='Are you sure to delete Customer Account "'.$result["username"].'"';
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
                            <a  href="add_customer" role="button" class="btn bg-gradient-success btn-sm">
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
