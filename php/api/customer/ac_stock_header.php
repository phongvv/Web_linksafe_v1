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
                    <h3 class="card-title">Inventory List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?=gettext("Serial Number")?></th>
                            <th><?=gettext("Device Type")?></th>
                            <th><?=gettext("Vendor")?></th>
                            <th><?=gettext("Status")?></th>
                            <th style="text-align:center;"><?=gettext("Actions")?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <nav class="action-buttons">
                        <div style="text-align:right;">
                            <a  href="add_stock" role="button" class="btn bg-gradient-success btn-sm">
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