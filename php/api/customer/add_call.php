<?php
$active_menu = "callcenter";

require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ssa_customer(3);
button_cancel('account?id=0');
if ($checks == true) {
    include_once "head.php";
    if ($_REQUEST["id"] != '') {
        $id = $_REQUEST["id"];
        $url = "http://$serverip/api/get/service-account";
        $token = $_SESSION['accesstoken'];
        $data = array(
            "type" => 'Call-Center'
        );
        $data_json = json_encode($data, JSON_PRETTY_PRINT);
        $api_result = call_api('GET', $token, $url, $data_json);
        $data = $api_result["data"][$id];
    }
    if ($_POST['action'] == 'save') {
        unset($input_errors);
        $pconfig = $_POST;
        if ($pconfig['password'] != $pconfig['confirm_password']) {
            $input_errors[] = sprintf(gettext("Password and Confirm Password must be match!"));
        }

        if (!preg_match("/^[a-zA-ZvxyỳọáầảấờễàạằệếýộậốũứĩõúữịỗìềểẩớặòùồợãụủíỹắẫựỉỏừỷởóéửỵẳẹèẽổẵẻỡơôưăêâđVXYỲỌÁẦẢẤỜỄÀẠẰỆẾÝỘẬỐŨỨĨÕÚỮỊỖÌỀỂẨỚẶÒÙỒỢÃỤỦÍỸẮẪỰỈỎỪỶỞÓÉỬỴẲẸÈẼỔẴẺỠƠÔƯĂÊÂĐĐ\'\-\040]+$/", $_POST["full_name"])) {
            $input_errors[] = sprintf(gettext("Name is not valid! It must not contain numbers or special characters!"));
        }

        if (!$input_errors) {
            if ($pconfig['id'] == "") {
                $method = "POST";
                $url = "http://$serverip/api/create/service-account";
                $data = array(
                    "email" => $_POST["email"],
                    "password" => $_POST["password"],
                    "username" => $_POST["username"],
                    "fullname" => $_POST["full_name"],
                    "address" => $_POST["address"],
                    "phonenumber" => $_POST["number_phone"],
                    "organization" => $_POST["organization"],
                    "department" => $_POST["department"],
                    "type" => "Call-Center"
                );
            } else {
                $method = "PATCH";
                $url = "http://$serverip/api/modify/service-account";
                $data = array(
                    "current_email" => $data["email"],
                    "new_email" => $_POST["email"],
                    "username" => $_POST["username"],
                    "fullname" => $_POST["full_name"],
                    "address" => $_POST["address"],
                    "phonenumber" => $_POST["number_phone"],
                    "organization" => $_POST["organization"],
                    "department" => $_POST["department"],
                    "password" => $_POST["password"],
                    "type" => "Call-Center",
                );
            }
            $api_result = call_api($method, $token, $url, json_encode($data));

            if ($api_result["message"] != "null") {
                $input_errors[] = sprintf(gettext($api_result["message"]));
                $_SESSION['input_errors'] = $input_errors;
                header("Location: add_call?id=$id");
                exit;
            } else {
                if ($method == "PATCH") {
                    $input_success[] = sprintf(gettext("Edit Account Call Center successful!"));
                } else if ($method == "POST") {
                    $input_success[] = sprintf(gettext("Add Account  Call Center successful!"));
                }
                $_SESSION['input_success'] = $input_success;
                header("Location: account?id=0");
                exit;
            }
        } else {
            $_SESSION['input_errors'] = $input_errors;
            header("Location: add_call?id=$id");
            exit;
        }
    }

?>

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../plugins/password_strength/style.css">
    <link rel="stylesheet" type="text/css" href="../plugins/password_strength/password_strength.css">
    <script type="text/javascript" src="../plugins/password_strength/password_strength_lightweight.js"></script>
    <script>
        $(document).ready(function($) {
            $('#mySecondPassword').strength_meter({

                inputClass: 'form-control',
                strengthMeterClass: 'c_strength_meter',
                idClass: 'password',
            });
        });
    </script>




    <!-- <script type="text/javascript" src="../plugins/password_strength/password_strength_lightweight.js"></script> -->

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <?php
                        if ($_SESSION['input_errors']) {
                            print_error_box($_SESSION['input_errors']);
                            unset($_SESSION['input_errors']);
                        }
                        ?>
                        <div class="row">
                            <!-- content-page  -->
                            <div class="col-md-12 animate-bottom">
                                <div class="alert alert-info">
                                    <strong>Note :</strong> - Please fill out all required fields (marked with an <span style="color:red;font-weight:bold;">*</span>). </br>- After that, click <strong>"Save"</strong> at the bottom of form to save data.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header" style="text-align: center;">
                                        <h3 class="card-title">Create Call Center Account</h3>
                                    </div>
                                    <div class="card-content">
                                        <form action="add_call" method="post" class="form-horizontal">
                                            <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Username<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="User name" required="true" minlength="5">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="password" class="col-sm-2 control-label">Password<?= ($id == "") ? "<red>*</red>" : " " ?> </label>

                                                <div class="col-sm-8">
                                                    <div id="mySecondPassword">
                                                        <input type="password" class="form-control" name="password" id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" <?php if ($id == "") {
                                                                                                                                                                                                                        echo "required='required'";
                                                                                                                                                                                                                    } ?> placeholder="Password" onkeyup='check();'>
                                                    </div>
                                                    <span id="messagepw" style="color:red"> </span>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Confirm Password<?= ($id == "") ? "<red>*</red>" : " " ?></label>

                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" <?php if ($id == "") {
                                                                                                                                                    echo "required='required'";
                                                                                                                                                } ?> placeholder="Confirm Password" onkeyup='check();'>
                                                </div>
                                            </div>
                                            <span class="badge badge-primary checkpw" id='message' style="display:none;"></span>
                                            <div class="input-group mb-3 decopw">
                                                <label for="text" class="col-sm-2 control-label">Full Name<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" required="true" name="full_name" id="full_name" placeholder="....">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Email<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" required="true" name="email" id="email" placeholder="Email" required="true" pattern="^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Phone Number<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" required="true" name="number_phone" id="number_phone" placeholder="....">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Address:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="address" id="address" placeholder="....................">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Organization:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="organization" id="organization" placeholder="....................">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Department:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="department" id="department" placeholder="....................">
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button onclick="verifyPassword()" onclick="check()" type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                                </div>
                                                <div class="col-sm-offset-2 col-sm-3 pull-right">
                                                    <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- /.card-body -->
                                    <!-- /.box-footer -->

                                    <!-- </div> -->

                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>

        </div>


        <!-- content-page  -->

        </div>


        <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        <!-- </div> -->
        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-36251023-1']);
            _gaq.push(['_setDomainName', 'jqueryscript.net']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <script src="../assets/js/lancsnet.js"></script>
        <script type="text/javascript">
            <?php if ($_POST) { ?>
                document.getElementById("username").value = "<?= $_POST["username"]; ?>";
                document.getElementById("full_name").value = "<?= $_POST["full_name"]; ?>";
                document.getElementById("email").value = "<?= $_POST["email"]; ?>";
                document.getElementById("number_phone").value = "<?= $_POST["number_phone"]; ?>";
                document.getElementById("organization").value = "<?= $_POST["organization"]; ?>";
                document.getElementById("department").value = "<?= $_POST["department"]; ?>";
                document.getElementById("address").value = "<?= $_POST["address"]; ?>";
            <?php } else if (isset($id)) { ?>
                document.getElementById("username").value = "<?= $data["username"]; ?>";
                document.getElementById("full_name").value = "<?= $data["fullname"]; ?>";
                document.getElementById("email").value = "<?= $data["email"]; ?>";
                document.getElementById("address").value = "<?= $data["address"]; ?>";
                document.getElementById("number_phone").value = "<?= $data["phonenumber"]; ?>";
                document.getElementById("organization").value = "<?= $data["organization"]; ?>";
                document.getElementById("department").value = "<?= $data["department"]; ?>";
            <?php } ?>
        </script>
        <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#number_phone").inputmask("9999999999", {
                    "onincomplete": function() {
                        alert('invalid phone number');
                    }
                });
            });
            // $(document).ready(function() {
            //     $("#phone").inputmask({ "mask": "9999999[999]", "repeat": 10, "greedy": false });
            // });
        </script>
        <script src="../plugins/password_strength/password.js"></script>
    <?php }
