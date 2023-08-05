<?php
require_once('functions.inc');
global $token, $serverip;
check_session("/api");
$checks = true;
if ($checks == true) {
    include_once "head.php";
    if ($_POST['action'] == "update") {
        unset($input_errors);
        unset($input_success);
        $pconfig = $_POST;
        if ($pconfig["password"] == "") {
            $input_errors[] = sprintf(gettext("Please enter a password!"));
        } else if ($pconfig["password"] != $_SESSION["password"]) {
            $input_errors[] = sprintf(gettext("Password incorrect!"));
        }
        if (!$input_errors) {
            switch ($_SESSION["accounttype"]) {
                case "0":
                    $url = "http://$serverip/api/update-info/RSA";
                    break;
                case "1":
                    $url = "http://$serverip/api/update-info/SSA-Tech";
                    break;
                case "2":
                    $url = "http://$serverip/api/update-info/SSA-Business";
                    break;
                case "3":
                    $url = "http://$serverip/api/update-info/SSA-Customer";
                    break;
                case "4":
                    $url = "http://$serverip/api/update-info/Call-Center";
                    break;
                case "5":
                    $url = "http://$serverip/api/update-info/Tech-Support";
                    break;
                case "6":
                    $url = "http://$serverip/api/update-info/HSA";
                    break;
                case "7":
                    $url = "http://$serverip/api/update-info/LSA_BSA";
                    break;
                case "8":
                    $url = "http://$serverip/api/update-info/EA";
                    break;
            }
            $data = json_encode(array(
                "email" => "test@gmail.com",
                "fullname" => $_POST['fullname'],
                "phonenumber" => $_POST['phone'],
                "organization" => $_POST['organization'],
                "department" => $_POST['department'],
                "username" => $_POST['username'],
                "address" => $_POST['address'],
            ));
            $api_result = call_api("PATCH", $token, $url, $data);
            if ($api_result['message'] == "null") {
                $input_success[] = sprintf(gettext("Update Successful!"));
                $_SESSION["fullname"] = $_POST["fullname"];
                $_SESSION["phone"] = $_POST["phone"];
                $_SESSION["organization"] = $_POST["organization"];
                $_SESSION["department"] = $_POST["department"];
                $_SESSION["address"] = $_POST["address"];
                $_SESSION["username"] = $_POST["username"];
            } else {
                $input_errors[] = sprintf(gettext($api_result['message']));
            }
        }
    } else if ($_POST['action'] == "change") {
        unset($input_errors);
        $pconfig = $_POST;
        $type = list_id_account($_SESSION['accounttype']);
        if ($pconfig['npassword'] != $pconfig['cnpassword']) {
            $input_errors[] = sprintf(gettext("New Password and Confirm New Password must be match!"));
        }
        if (!$input_errors) {


            if ($type == "SSA-Tech" || $type == "SSA-Business" || $type == "SSA-Customer" || $type == "RSA") {
                $url = "http://$serverip/api/change-password/SSA-account";
            } else if ($type == "Call-Center" || $type == "Tech-Support") {
                $url = "http://$serverip/api/change-password/service-account";
            } else {
                $url = "http://$serverip/api/change-password/branch-account";
            }
            $data = array(
                "type" => $type,
                "new_password" => $_POST['npassword'],
                "current_password" => $_POST['currentpassword']
            );
            $api_result = call_api('PATCH', $_SESSION['accesstoken'], $url, json_encode($data));
            if ($api_result['message'] == "Not has account") {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else if ($api_result['message'] != 'null') {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else {
                $input_success[] = sprintf(gettext("Change Password Success!"));
                $_SESSION["password"] = $_POST['npassword'];
            }
        }
    } else if ($_POST['action'] == "get_otp") {

        $pconfig = $_POST;
        $url = "http://$serverip/api/send-OTP";
        $data = array(
            "email" => $_SESSION["email"],
        );
        $api_result = call_api('POST', $_SESSION['accesstoken'], $url, json_encode($data));
        if ($api_result["errorCode"] == "200") {
            $change_gmail_login = array(
                "time_sent" => $api_result["data"]["time_sent"],
                "base32secret" => $api_result["data"]["base32secret"],
            );
            $_SESSION["change_gmail_login"] = $change_gmail_login;
            $_SESSION['action'] = $_POST['action'];
            unset($change_gmail_login);
            unset($api_result);
        }
    } else if ($_POST['action'] == "verify") {
        $pconfig = $_POST;
        unset($_POST);
        $url = "http://$serverip/api/check-OTP";
        $data = array(
            "email" => $_SESSION["email"],
            "base32secret" => $_SESSION["change_gmail_login"]["base32secret"],
            "time_sent" => $_SESSION["change_gmail_login"]["time_sent"],
            "otp" => $pconfig["otp"]
        );
        $api_result = call_api('POST', $_SESSION['accesstoken'], $url, json_encode($data));
        if ($api_result["errorCode"] != "200") {
            $pconfig["action"] = "get_otp";
            $input_errors[] = sprintf(gettext($api_result['message']));
        } else if ($api_result["errorCode"] == "200") {
            $change_gmail_login = array(
                "otp" => $pconfig["otp"]
            );
            $_SESSION["change_gmail_login"]["otp"] = $pconfig["otp"];
            // unset($_SESSION["change_gmail_login"]);
            // header("Location: /api/logout");
            // include_once "header";
            // logout_edit_email();
        }
    } else if ($_POST['action'] == "apply") {
        $pconfig = $_POST;
        unset($_POST);
        $url = "http://$serverip/api/confirm-modify-contact-info";
        $data = array(
            "newEmail" => $pconfig["email"],
            "time_sent" => $_SESSION["change_gmail_login"]["time_sent"],
            "otp" => $_SESSION["change_gmail_login"]["otp"],
            "base32secret" => $_SESSION["change_gmail_login"]["base32secret"],
        );
        $api_result = call_api('POST', $_SESSION['accesstoken'], $url, json_encode($data));
        if ($api_result["errorCode"] != "200") {
            $pconfig["action"] = "verify";
            $input_errors[] = sprintf(gettext($api_result['message']));
        } else if ($api_result["errorCode"] == "200") {
            unset($pconfig);
            unset($_SESSION["change_gmail_login"]);
            // header("Location: profile");
            $input_success[] = sprintf(gettext("Change email Success!"));
            $_SESSION["input_success"] = $input_success;
            notification_edit_email();
        }
    }
?>
    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../plugins/password_strength/style.css">
    <link rel="stylesheet" type="text/css" href="../plugins/password_strength/password_strength.css">
    <script type="text/javascript" src="../plugins/password_strength/password_strength_lightweight.js"></script>
    <script>
        $(document).ready(function($) {
            $('#mycurrentpassword').strength_meter({
                inputClass: 'form-control',
                // strengthMeterClass: 'c_strength_meter',
                idClass: 'password',
            });
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
                <!-- content-page  -->
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <?php
                        if ($_POST) {
                            if ($input_errors) {
                                print_error_box($input_errors);
                            } else if ($input_success) {
                                print_success_box($input_success);
                            }
                            unset($input_errors);
                            unset($input_success);
                        }
                        if ($pconfig) {
                            if ($input_errors) {
                                print_error_box($input_errors);
                            } else if ($input_success) {
                                print_success_box($input_success);
                            }
                            unset($input_errors);
                            unset($input_success);
                        }
                        if ($_POST['action'] == "get_otp") {
                        ?>
                            <div class="row">
                                <div class=" col-12">
                                    <div class="card card-primary card-outline card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab1a" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">CHANGE EMAIL:</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab1a">
                                                    <div class="card card-info">
                                                        <?php
                                                        ?>
                                                        <form action="profile" method="post" class="form-horizontal">
                                                            <div class="card-header with-border" style="background-color: #17a2b8;">

                                                                <h5 style="text-align: center;">Click the Get OTP button below to get a verification code via email:&emsp;&emsp;&emsp; </h5>
                                                                <h4 style="text-align: center;" class="card-title"><b><?= $_SESSION["email"] ?>&emsp;&emsp;&emsp;</b></h4>
                                                            </div>
                                                            <style>
                                                                .oidoioi {
                                                                    color: blue;
                                                                }

                                                                .display-getotp {
                                                                    display: none;
                                                                }

                                                                .display-cdtime {
                                                                    display: none;
                                                                }

                                                                .display-getotp1 {
                                                                    cursor: not-allowed;
                                                                    opacity: .5;
                                                                }
                                                            </style>
                                                            <div class="card-body card-danger">

                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">OTP:</label>
                                                                    <div class="col-sm-8">
                                                                        <div>
                                                                            <label class="pull-left" style="width: 85%;">
                                                                                <input type="text" class="form-control" name="otp" id="otp">
                                                                            </label>
                                                                            <label class="pull-right">
                                                                                <div id="display-cdtime">
                                                                                    <button type="submit" class="btn btn-warning btn-start-resume" name="action" value="get_otp">Get
                                                                                        OTP</button>
                                                                                </div>

                                                                                <div class="form-group display-getotp display-getotp1" id="display-getotp">
                                                                                    <button disabled type="submit" class="btn btn-warning btn-start-resume" name="action" value="get_otp">Get OTP <span id="time">30</span>s</button>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class=" modal-footer justify-content-between">
                                                                    <button type="submit" class="btn bg-gradient-success" name="action" value="verify">VERIFY</button>
                                                                    <button type="submit" class="btn bg-gradient-danger" name="action">Return</button>

                                                                </div>


                                                            </div>
                                                            <style>
                                                                .display-getotp {
                                                                    display: none;
                                                                }

                                                                .display-cdtime {
                                                                    display: none;
                                                                }

                                                                .display-getotp1 {
                                                                    opacity: .5;

                                                                }
                                                            </style>
                                                            <script type="text/javascript">
                                                                const btnStart = document.querySelector('.btn-start-resume');
                                                                const secondsEl = document.querySelector('.form-control-lg');
                                                                let interval;
                                                                let pause = false;
                                                                let totalSeconds = 0;
                                                                let totalSecondsBackup = 0;
                                                                var display = document.querySelector('#time');

                                                                // init();

                                                                // function init() {


                                                                //     btnStart.addEventListener('click', () => {
                                                                //         // const seconds = parseInt(secondsEl.value);
                                                                //         const seconds = 30;
                                                                //         totalSecondsBackup = totalSeconds = seconds;
                                                                //         if (totalSeconds < 0) {
                                                                //             return;
                                                                //         }

                                                                //         // startTimer();


                                                                //     });
                                                                //     startTimer();

                                                                // }
                                                                startTimer();

                                                                function startTimer() {
                                                                    const seconds = 30;
                                                                    totalSecondsBackup = totalSeconds = seconds;
                                                                    if (totalSeconds < 0) {
                                                                        return;
                                                                    }

                                                                    var hi1 = document.getElementById("display-cdtime");
                                                                    hi1.classList.add("display-cdtime");
                                                                    var hi2 = document.getElementById("display-getotp");
                                                                    hi2.classList.remove("display-getotp");
                                                                    interval = setInterval(() => {

                                                                        if (pause) return;
                                                                        totalSeconds--;

                                                                        updateInputs();

                                                                        if (totalSeconds <= 0) {
                                                                            stopTimer();
                                                                            hi2.classList.add("display-getotp");
                                                                            hi1.classList.remove("display-cdtime");
                                                                        }
                                                                    }, 1000)
                                                                }

                                                                function stopTimer() {


                                                                    interval = clearInterval(interval);



                                                                }

                                                                function updateInputs() {
                                                                    const seconds = totalSeconds % 60;
                                                                    display;
                                                                    display.textContent = seconds;
                                                                    // secondsEl.value = seconds;

                                                                }
                                                            </script>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>

                            </div>
                        <?php
                        } else if ($_POST["action"] == "change_email") {

                        ?>
                            <div class="row">
                                <div class=" col-12">
                                    <div class="card card-primary card-outline card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab1a" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">CHANGE EMAIL:</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab1a">
                                                    <div class="card card-info">
                                                        <?php
                                                        ?>
                                                        <form action="profile" method="post" class="form-horizontal">
                                                            <div class="card-header with-border" style="background-color: #17a2b8;">

                                                                <h5 style="text-align: center;">Click the Get OTP button below to get a verification code via email:&emsp;&emsp;&emsp; </h5>
                                                                <h4 style="text-align: center;" class="card-title"><b><?= $_SESSION["email"] ?>&emsp;&emsp;&emsp;</b></h4>
                                                            </div>
                                                            <style>
                                                                .oidoioi {
                                                                    color: blue;
                                                                }

                                                                .display-getotp {
                                                                    display: none;
                                                                }

                                                                .display-cdtime {
                                                                    display: none;
                                                                }

                                                                .display-getotp1 {
                                                                    cursor: not-allowed;
                                                                    opacity: .5;
                                                                }
                                                            </style>
                                                            <div class="card-body card-danger">

                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">OTP:</label>
                                                                    <div class="col-sm-8">
                                                                        <div>
                                                                            <label class="pull-left" style="width: 85%;">
                                                                                <input type="text" class="form-control" name="otp" id="otp">
                                                                            </label>
                                                                            <label class="pull-right">
                                                                                <div id="display-cdtime">
                                                                                    <button type="submit" class="btn btn-warning btn-start-resume" name="action" value="get_otp">Get
                                                                                        OTP</button>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class=" modal-footer justify-content-between">
                                                                    <button type="submit" class="btn bg-gradient-success" name="action" value="verify">VERIFY</button>
                                                                    <button type="submit" class="btn bg-gradient-danger" name="action">Return</button>

                                                                </div>


                                                            </div>
                                                            <style>
                                                                .display-getotp {
                                                                    display: none;
                                                                }

                                                                .display-cdtime {
                                                                    display: none;
                                                                }

                                                                .display-getotp1 {
                                                                    opacity: .5;

                                                                }
                                                            </style>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>

                            </div>
                        <?php } else if ($pconfig["action"] == "verify") {
                        ?>

                            <div class="row">
                                <div class=" col-12">
                                    <div class="card card-primary card-outline card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">CHANGE EMAIL:</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <div class="card card-info">
                                                        <!-- <div class="card-header with-border" style="text-align: center;">
                                                        <h3 class="card-title"><b>CHANGE EMAIL:</b></h3>
                                                        
                                                        <div class="card-tools pull-right">
                                                            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                        </div>
                                                    </div> -->
                                                        <?php
                                                        ?>
                                                        <form action="profile" method="post" class="form-horizontal">
                                                            <div>
                                                                <h5 class="card-title">&emsp;&emsp;&emsp;Please enter your email: </h5>
                                                            </div>
                                                            <div class="card-body" style="border-top:1px solid #00c0ef;">
                                                                <div class="input-group mb-3">
                                                                    <label for="inputPassword3" class="col-sm-2 control-label">Email<red>*</red></label>

                                                                    <div class="col-sm-8">
                                                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="true" pattern="^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <div class="col-sm-offset-5 col-sm-4">
                                                                        <button type="submit" class="btn bg-gradient-success" name="action" value="apply">APPLY</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>

                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="row">
                                <div class="col-md-7 col-sm-offset-2">
                                    <!-- Custom Tabs -->
                                    <div class="nav-tabs-custom">

                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Information</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Change Password</a>
                                                </li>
                                            </ul>
                                        </div>


                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <div class="card card-info">
                                                        <div class="card-header with-border">
                                                            <h3 class="card-title text-black"><b>Profile Settings:</b></h3>
                                                        </div>
                                                        <?php
                                                        // require_once("functions.inc");
                                                        // global $serverip, $token;
                                                        // $url = "http://$serverip/api/edge-EA";
                                                        // $api_result = call_api("GET", $token, $url, false);
                                                        // $api = $api_result['data'][0];
                                                        ?>
                                                        <form action="profile" method="post" class="form-horizontal">
                                                            <div class="card-body" style="border-top:1px solid #00c0ef;">
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label"> Username:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="username" id="username" required="required" value="<?= $_SESSION["username"] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Full Name:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="fullname" id="fullname" required="required" value="<?= $_SESSION["fullname"] ?>">
                                                                    </div>
                                                                </div>
                                                                <!-- ------------------- -->
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Email:</label>
                                                                    <div class="col-sm-8">
                                                                        <div>
                                                                            <label class="pull-left" style="width: 85%;">
                                                                                <!-- <input type="text" class="form-control" disabled value="<?= $_SESSION["email"] ?>"> -->
                                                                                <input type="text" class="form-control" name="email" id="email" disabled value="<?= $_SESSION["email"] ?>">
                                                                            </label>
                                                                            <label class="pull-right">
                                                                                <!-- <a class="form-control btn bg-gradient-success" role="button" title="Update">Send OTP</a> -->
                                                                                <button type="submit" class="btn btn-warning" name="action" value="change_email">Change</button>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- ------------------- -->
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Phone Number:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="phone" id="phone" required="required" value="<?= $_SESSION["phone"] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Address:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="address" id="address" value="<?= $_SESSION["address"] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Organization:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="organization" id="organization" value="<?= $_SESSION["organization"] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Department:</label>

                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="department" id="department" value="<?= $_SESSION["department"] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-3">
                                                                    <div class="col-sm-offset-5 col-sm-4 modal-footer justify-content-between">
                                                                        <a class="btn bg-gradient-success" role="button" title="DELETE" data-toggle="modal" data-target="#modal-info29" aria-hidden="false">Update</a>
                                                                        <a href="dashboard" class="btn bg-gradient-danger" type="submit">Return</a>
                                                                    </div>

                                                                </div>
                                                                <div class="modal fade" id="modal-info29">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content bg-info">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title"><b> Update <?= $res['name'] ?></b></h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="card card-primary">
                                                                                            <form role="form">
                                                                                                <div class="card-body">
                                                                                                    <div class="input-group mb-3">
                                                                                                        <label class="" for="form-control">PASSWORD</label>
                                                                                                        <label></label>
                                                                                                        <input type="password" class="form-control" name="password" id="password" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button style="width: 80px; height: 40px; pointer-events: auto" type="button" class="btn bg-gradient-danger pull-left" data-dismiss="modal">Cancel</button>
                                                                                <button style="width: 80px; height: 40px; pointer-events: auto" type="submit" class="btn bg-gradient-success btn-sm" name="action" value="update">Update</button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- <div class="modal fade" id="myModal1" role="dialog">
                                                                    <div class="modal-dialog modal-info" role="document">
                                                                        <form role='form_edit' action="profile" method="get">
                                                                            <div>
                                                                                <div class="modal-header">
                                                                                    <h3 class="card-title"><b> Update <?= $res['name'] ?></b>
                                                                                    </h3>
                                                                                </div>
                                                                                <div style="background-color: #ffffff !important; color: #444 !important" class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="card card-primary">
                                                                                                <form role="form">
                                                                                                    <div class="card-body">
                                                                                                        <div class="input-group mb-3">
                                                                                                            <label class="" for="exampleInputEmail1">PASSWORD</label>
                                                                                                            <label></label>
                                                                                                            <input type="password" class="form-control" name="password" id="password" placeholder="">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button style="width: 80px; height: 40px; pointer-events: auto" type="button" class="btn bg-gradient-danger pull-left" data-dismiss="modal">Cancel</button>
                                                                                    <button style="width: 80px; height: 40px; pointer-events: auto" type="submit" class="btn bg-gradient-success btn-sm" name="action" value="update">Update</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>

                                                                </div> -->
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                    <div class="card card-info">
                                                        <div class="card-header with-border" style="text-align: center;">
                                                            <h3 class="card-title"><b>Change Password</b></h3>

                                                        </div>

                                                        <form action="profile" method="post" class="form-horizontal">
                                                            <div class="card-body" style="border-top:1px solid #00c0ef;">
                                                                <div class="col-sm-9">
                                                                    <div class="col-sm-4">
                                                                    </div>
                                                                    <div class="form-group col-sm-8">
                                                                        <label for="inputEmail3" class="control-label">Current Password:</label>

                                                                        <div id="mycurrentpassword">
                                                                            <input type="password" required class="form-control" name="currentpassword" id="currentpassword" placeholder="Current Password">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <div class="col-sm-4">
                                                                    </div>
                                                                    <div class="form-group col-sm-8">
                                                                        <label for="inputPassword3" class="control-label">New Password:</label>
                                                                        <div id="mySecondPassword">
                                                                            <input type="password" class="form-control" name="npassword" id="npassword" required="required" placeholder="Password" onkeyup='check();'>
                                                                            </input>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <div class="col-sm-4">
                                                                    </div>
                                                                    <div class="form-group col-sm-8" <?= ($id != "") ? "hidden" : " " ?>>
                                                                        <label for="inputPassword3" class="control-label">Confirm New Password:</label>
                                                                        <input type="password" class="form-control" name="cnpassword" id="cnpassword" required="required" placeholder="Confirm Password" onkeyup='check();'>
                                                                        <span class="badge badge-primary" id='message' style="display:none;"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-offset-5 col-sm-4 modal-footer justify-content-between">
                                                                        <button type="submit" class="btn bg-gradient-success" name="action" value="change">Change</button>
                                                                        <a href="dashboard" type="submit" class="btn bg-gradient-danger">Return</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                            ?>
                            </div>
                </section>

                <!-- content-page  -->
                <div class="control-sidebar-bg"></div>

            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>
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
        <?php include_once "../src.php"; ?>
        <script type="text/javascript">
            var password = document.getElementById('npassword');
            var confirm_password = document.getElementById('cnpassword');
            var message = document.getElementById('message');

            var check = function() {
                if (password.value == confirm_password.value) {
                    message.style.color = 'green';
                    message.style.display = 'inline';
                    message.innerHTML = 'Matching';
                } else {
                    message.style.display = 'inline';
                    message.style.color = 'red';
                    message.innerHTML = 'Not Matching';
                }
            }
        </script>
        <script src="../pages/tables/data_tables/script.js"></script>
        <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#phone").inputmask("9999999999", {
                    "onincomplete": function() {
                        alert('invalid phone number');
                    }
                });
                $("#otp").inputmask("999999");
            });

            // $(document).ready(function() {
            //     $("#phone").inputmask({ "mask": "9999999[999]", "repeat": 10, "greedy": false });
            // });
        </script>
        <!-- <script src="../pages/tables/data_tables/script.js"></script> -->
    <?php }
