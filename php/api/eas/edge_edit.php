<?php
$active_menu = "Edge";
require_once('functions.inc');
check_session("/api");
if ($_SESSION['accounttype'] != "9" && $_SESSION['accounttype'] != "8") {
    display_warning();
} else {
    $checks = check_permission_ea(8);
    button_cancel('edge');
    if ($checks == true) {
        global $serverip, $token;
        $countries = branch_list();

        if ($_POST['action'] == 'confirm') {
            unset($input_errors);
            $pconfig = $_POST;
            if (!$input_errors) {
                $url = "http://$serverip/api/serial-confirm";
                $data = array(
                    "serial" => $_POST["serial_number"]
                );
                $api_result = call_api('POST', $token, $url, json_encode($data));
                if ($api_result['message'] != "Serial confirm success") {
                    $input_errors[] = sprintf(gettext($api_result['message']));
                } else {
?>
                    <div class="modal fade show" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:block;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content bg-success">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><b>Get Edge Token Success!</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-toggle"></div>
                                        <div class="form-panel one">
                                            <div class="form-header">
                                            </div>
                                            <div class="form-content">
                                                <!-- <form> -->
                                                <div class="input-group mb-3">
                                                    <label for="email">Your EdgeToken is: <br /></label>
                                                    <!-- <div class="col-sm-8"> -->
                                                    <div class="input-group">
                                                        <textarea id="token" class="form-control" rows="3"><?= $api_result['token'] ?></textarea>
                                                        <div class="input-group-addon">
                                                            <button onclick="copyEvent('token')" title="Coppy"><i class="fa fa-copy"></i></button>
                                                        </div>
                                                    </div>
                                                    <!-- </div> -->
                                                </div>
                                                <div class="input-group mb-3">
                                                    <label for="email"><br />Redirect to before in <span id="time">60</span>s ...</label>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-primary" role="button" href="edge">Close</a>
                                                </div>
                                                <!-- </form> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function copyEvent(id) {
                            var str = document.getElementById(id);
                            window.getSelection().selectAllChildren(str);
                            document.execCommand("Copy")
                        }
                    </script>
                    <script>
                        function startTimer(duration, display) {
                            var timer = duration,
                                minutes, seconds;
                            setInterval(function() {
                                seconds = parseInt(timer % 60, 10);
                                seconds = seconds < 10 ? "0" + seconds : seconds;
                                display.textContent = seconds;
                                if (--timer < 0) {
                                    timer = duration;
                                }
                            }, 1000);
                        }

                        window.onload = function() {
                            var fiveMinutes = 10 * 6,
                                display = document.querySelector('#time');
                            startTimer(fiveMinutes, display);
                        };

                        let tID = setTimeout(function() {

                            // redirect page.
                            window.location.href = 'edge';

                            window.clearTimeout(tID); // clear time out.

                        }, 62000);
                    </script>
        <?php
                }
            }
        }
        include_once "head.php";
        ?>



        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 780px;">
                    <!-- content-page  -->
                    <section class="content">
                        <div class="container-fluid" id="myDiv">
                            <div class="row">
                                <div class="col-12">
                                    <?php
                                    if ($input_errors) {
                                        print_error_box($input_errors);
                                    }
                                    ?>
                                    <div class="card card-info">
                                        <div class="card-header" style="text-align: center;">
                                            <h3 class="card-title"><b>Edge Create</b></h3>
                                        </div>
                                        <form action="edge_edit" method="post" class="form-horizontal">
                                            <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                            <div class="card-body">
                                                <div class="input-group mb-3">
                                                    <label for="inputEmail3" class="col-sm-2 control-label">Serial:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="serial_number" id="serial_number" required="required" placeholder="...">
                                                        <span class="help-block">Type serial number to add Edge to system</span>
                                                    </div>
                                                </div>
                                                <!-- <div class="input-group mb-3">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Ip Address:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-laptop"></i>
                                            </div>
                                            <input type="text" class="form-control" id="ipaddress" name="ipaddress" data-inputmask="'alias': 'ip'" data-mask>
                                        </div>
                                    </div>
                                </div> -->
                                                <div class="input-group mb-3">
                                                    <div class="col-sm-offset-2 col-sm-4">
                                                        <button type="submit" class="btn bg-gradient-success" name="action" value="confirm">Confirm</button>
                                                    </div>
                                                    <div class="col-sm-offset-2 col-sm-4 pull-right">
                                                        <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- content-page  -->

                </div>


                <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>
            <script type="text/javascript">
                function copyText() {
                    /* Select text area by id*/
                    var Text = document.getElementById("token");

                    /* Select the text inside text area. */
                    Text.select();

                    /* Copy selected text into clipboard */
                    navigator.clipboard.writeText(Text.value);

                    /* Set the copied text as text for 
                    div with id clipboard */
                    //   document.getElementById("clipboard")
                    //       .innerHTML = Text.value;
                }
            </script>
            <script src="script.js"></script>
            <script type="text/javascript">
                <?php
                if ($_POST) {
                ?>
                    document.getElementById("serial_number").value = "<?= $_POST["serial_number"]; ?>";
                <?php
                } ?>
            </script>
    <?php }
}
