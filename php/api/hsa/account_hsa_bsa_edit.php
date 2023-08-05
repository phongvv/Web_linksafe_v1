<?php
$active_menu = "account_hsa_bsa";
require_once('functions.inc');
check_session("/api/index");
$checks = check_permission_hsa(6);
button_cancel('account_hsa_bsa');
if ($checks == true) {
    global $token, $serverip;
    $countries = branch_available_list();
    $countries1 = branch_list();

    if ($countries == null && $_REQUEST["id"] == "") {
        header("Location: account_hsa_bsa");
        exit;
    } else {
        if ($_REQUEST["id"] != "") {
            $id = $_REQUEST["id"];
            $url = "http://$serverip/api/get/branch-account";
            $data1 = json_encode(array(
                "type" => "LSA_BSA"
            ));
            $api_result = call_api('GET', $token, $url, $data1);
            $data = $api_result["data"][$id];
        }
        if ($_POST['action'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;

            if (!preg_match("/^[a-zA-Z0-9._]+$/", $pconfig["username"])) {
                $input_errors[] = sprintf(gettext("Username is not false format"));
            }
            if ($pconfig['password'] != $pconfig['confirm_password']) {
                $input_errors[] = sprintf(gettext("Passwords Don't Match"));
            }
            if (!preg_match("/^[a-zA-ZvxyỳọáầảấờễàạằệếýộậốũứĩõúữịỗìềểẩớặòùồợãụủíỹắẫựỉỏừỷởóéửỵẳẹèẽổẵẻỡơôưăêâđVXYỲỌÁẦẢẤỜỄÀẠẰỆẾÝỘẬỐŨỨĨÕÚỮỊỖÌỀỂẨỚẶÒÙỒỢÃỤỦÍỸẮẪỰỈỎỪỶỞÓÉỬỴẲẸÈẼỔẴẺỠƠÔƯĂÊÂĐĐ\'\-\040]+$/", $pconfig["fullname"])) {
                $input_errors[] = sprintf(gettext("Invalid Fullname Details"));
            }
            if (!$input_errors) {
                if ($pconfig["id"] != "") {
                    $url = "http://$serverip/api/modify/branch-account";
                    $method = "PATCH";
                    $data = array(
                        "current_email" => $data["email"],
                        "new_email" => $pconfig["email"],
                        "username" => $pconfig["username"],
                        "type" => "LSA_BSA",
                        "fullname" => $pconfig["fullname"],
                        "phonenumber" => $pconfig["phone"],
                        "address" => $pconfig["address"],
                        "organization" => $pconfig["organization"],
                        "department" => $data["department"],
                        "password" => $pconfig["password"],
                    );
                } else {
                    $url = "http://$serverip/api/create/HQ-account";
                    $method = "POST";
                    $data = array(
                        "address" => $pconfig["address"],
                        "department" => $pconfig["branch"],
                        "email" => $pconfig["email"],
                        "fullname" => $pconfig["fullname"],
                        "username" => $pconfig["username"],
                        "organization" => $pconfig["organization"],
                        "password" => $pconfig["password"],
                        "phonenumber" => $pconfig["phone"],
                        "type" => "LSA_BSA"
                    );
                }
                // $a=json_encode($data);
                $api = call_api($method, $token, $url, json_encode($data));

                if ($api["message"] == "LSA_BSA account already exits") {
                    $input_errors[] = sprintf(gettext('Email "' . $pconfig["email"] . '" already exists'));
                    // } else if($api["message"] != "null") {
                    //     $input_errors[] = sprintf(gettext($api["message"]));
                } else if ($api["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api['message']));
                    $_SESSION['input_errors'] = $input_errors;
                    header("Location: account_hsa_bsa?id=$id");
                    exit;
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit Account BSA successful!"));
                    } else if ($method == "POST") {
                        $input_success[] = sprintf(gettext("Add Account BSA successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location: account_hsa_bsa");
                    exit;
                }
            } else {
                $_SESSION['input_errors'] = $input_errors;
                header("Location: account_hsa_bsa?id=$id");
                exit;
            }
        }
    }
    include_once "head.php";
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



    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>

            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($_SESSION['input_errors']) {
                                    print_error_box($_SESSION['input_errors']);
                                    unset($_SESSION['input_errors']);
                                }
                                ?>
                                <!-- content-page  -->
                                <div class="animate-bottom">
                                    <div class="alert alert-info">
                                        <strong>Note :</strong> - Please fill out all required fields (marked with an <span style="color:red;font-weight:bold;">*</span>). </br>- After that, click <strong>"Save"</strong> at the bottom of form to save data.
                                    </div>
                                </div>
                                <div class="card card-info">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title"><b>Account BSA Create</b></h3>
                                    </div>
                                    <form action="account_hsa_bsa_edit" method="post" class="form-horizontal">
                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Username<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="username" id="username" required="required" placeholder="User name" minlength="5">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Password<?= ($id == "") ? "<red>*</red>" : " " ?></label>

                                                <div class="col-sm-8">
                                                    <div id="mySecondPassword">
                                                        <input type="password" class="form-control" name="password" id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" <?php if ($id == "") {
                                                                                                                                                                                                                        echo "required='required'";
                                                                                                                                                                                                                    } ?> placeholder="Password" onkeyup='check();'>
                                                        </input>
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
                                                    <span class="badge badge-primary" id='message' style="display:none;"></span>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="text" class="col-sm-2 control-label">Full name<red>*</red></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="fullname" id="fullname" required="required" placeholder="....">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Email<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="true" pattern="^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Phone number<red>*</red></label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="phone" id="phone" required="required" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Address</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="address" id="address" required="required" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Branch</label>
                                                <div class="col-sm-8">
                                                    <select name="branch" required="required" id="branch" class="form-control select2" style="width: 100%;" <?php if ($id != "") {
                                                                                                                                                                echo "disabled";
                                                                                                                                                            } ?>>
                                                        <option selected="selected">None</option>
                                                        <?php foreach ($countries["true"] as $cc => $name) {
                                                            // echo '<option ' . $name . ' </option>';
                                                            echo '<option>' . $name . '</option>';
                                                        }
                                                        if ($id != "") {
                                                            echo '<option>' . $data["department"] . '</option>';;
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">Organization</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="organization" id="organization" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button onclick="verifyPassword()" onclick="check()" type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
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

        <script src="script.js"></script>
        <script type="text/javascript">
            <?php if ($input_errors) { ?>
                document.getElementById("address").value = "<?= $_POST["address"]; ?>";
                document.getElementById("branch").value = "<?= $data["department"]; ?>";
                document.getElementById("email").value = "<?= $_POST["email"]; ?>";
                document.getElementById("fullname").value = "<?= $_POST["fullname"]; ?>";
                document.getElementById("username").value = "<?= $_POST["username"]; ?>";
                document.getElementById("organization").value = "<?= $_POST["organization"]; ?>";
                document.getElementById("phone").value = "<?= $_POST["phone"]; ?>";
            <?php } else if (isset($id)) { ?>
                document.getElementById("address").value = "<?= $data["address"]; ?>";
                document.getElementById("branch").value = "<?= $data["department"]; ?>";
                document.getElementById("email").value = "<?= $data["email"]; ?>";
                document.getElementById("fullname").value = "<?= $data["fullname"]; ?>";
                document.getElementById("username").value = "<?= $data["username"]; ?>";
                document.getElementById("organization").value = "<?= $data["organization"]; ?>";
                document.getElementById("phone").value = "<?= $data["phonenumber"]; ?>";
            <?php } ?>
        </script>
        <link rel="stylesheet" href="../plugins/select2/select2.min.css">
        <script src="../plugins/password_strength/password.js"></script>
        <script type="text/javascript">
            $(function() {
                $(".select2").select2();
            });
        </script>
        <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#phone").inputmask("9999999999", {
                    "onincomplete": function() {
                        alert('invalid phone number');
                    }
                });
            });
            // $(document).ready(function() {
            //     $("#phone").inputmask({ "mask": "9999999[999]", "repeat": 10, "greedy": false });
            // });
        </script>
        <script src="../plugins/select2/select2.full.min.js"></script>
    <?php
}
