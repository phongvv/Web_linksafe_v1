<?php
$active_menu = "dashboard";
require_once('functions.inc');
check_session("/api");
if (isset($_REQUEST["edge_id"])) {
    $_SESSION["edge_id"] = $_REQUEST["edge_id"];
    header("Location: dashboard");
    exit;
}
$checks = check_permission_ea(8);
if ($checks == true) {
    global $token, $serverip;
    include_once "head.php";
    if ($_SESSION['accounttype'] == "9") {
        $_SESSION['active'] = true;
    }
    if ($_SESSION['active'] == true) {
        $url_1 = "http://$serverip/api/sync-individual-EA";
        $test = call_api("GET", $token, $url_1, false);
        syn_edge();
?>
        <style>
            p {
                text-align: center;
            }
        </style>

        <body class="hold-transition sidebar-mini layout-fixed">
            <div class="wrapper">
                <?php include_once "systems.php"; ?>
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 780px;">
                    <!-- content-page  -->
                    <?php include_once("dashboard_header.php") ?>
                    <!-- content-page  -->

                </div>


                <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>
            <?php
        } else {
            if ($_POST['action'] == "change") {
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
                        "current_password" => $_SESSION["password"]
                    );
                    $api_result = call_api('PATCH', $_SESSION['accesstoken'], $url, json_encode($data));
                    if ($api_result['message'] == "Not has account") {
                        $input_errors[] = sprintf(gettext($api_result['message']));
                    } else if ($api_result['message'] != "null") {
                        $input_errors[] = sprintf(gettext($api_result['message']));
                    } else {
                        $_SESSION["password"] = $_POST['npassword'];
                        $_SESSION['active'] = "true";
            ?>
                        <link rel="stylesheet" href="/api/assets/css/reset.min.css">
                        <link rel="stylesheet" href="/api/assets/css/style.css">
                        <form action="../information" method="post">
                            <!-- Form-->
                            <div class="form1">
                                <div class="form-toggle1"></div>
                                <?php
                                if (isset($unotexits) && isset($_POST)) {
                                    print_box(false, 'User not exist. Sign Up and try again');
                                }
                                ?>
                                <div class="form-panel1 one">
                                    <div class="form-header1">
                                        <h1>User Profile</h1>
                                        <h3> Check your information and replace them if it is incorrect. If not, click the skip button. </h3>
                                    </div>
                                    <div class="form-content">
                                        <form>
                                            <div class="form-group1">
                                                <label for="username">Full name</label>
                                                <input type="text" id="fullname" name="fullname" required="required"></input>
                                            </div>
                                            <div class="form-group1">
                                                <label for="password">Phone Number</label>
                                                <input type="text" id="phone" name="phone" required="required" />
                                            </div>
                                            <!-- <div class="form-group1">
                            <label for="password">Email</label>
                            <input type="email" id="email" name="email" required="required"/>
                            </div> -->
                                            <div class="form-group1">
                                                <label for="password">Organization</label>
                                                <input type="text" id="organization" name="organization" required="required" />
                                            </div>
                                            <div class="form-group1">
                                                <label for="password">Department</label>
                                                <input type="text" id="department" name="department" required="required" />
                                            </div>
                                            <div class="form-group1">
                                                <div class="col-sm-5">
                                                    <button type="submit" name="action" value="update">Update</button>
                                                </div>
                                                <div class="col-sm-5">
                                                    <button type="submit" name="action" value="skip" style="background-color:chocolate">Skip</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            document.getElementById("fullname").value = "<?= $_SESSION["fullname"]; ?>";
                            document.getElementById("phone").value = "<?= $_SESSION["phone"]; ?>";
                            // document.getElementById("email").value="<?= $_SESSION["email"]; ?>";
                            document.getElementById("organization").value = "<?= $_SESSION["organization"]; ?>";
                            document.getElementById("department").value = "<?= $_SESSION["department"]; ?>";
                        </script>
            <?php
                        exit;
                    }
                }
            }
            ?>

            <body>
                <link rel="stylesheet" href="/api/assets/css/reset.min.css">
                <link rel="stylesheet" href="/api/assets/css/style.css">
                <form action="dashboard" method="post">
                    <!-- Form-->
                    <div class="form1">
                        <div class="form-toggle1"></div>
                        <?php
                        if (isset($input_errors)) {
                            print_error_box($input_errors);
                        }
                        ?>
                        <div class="form-panel1 one">
                            <div class="form-header1">
                                <h1>Linksafe</h1>
                                <h3>You must change password of username <h2 style="display:inline;color:tomato"><?= $_SESSION['email'] ?></h2> for first login!</h3>
                            </div>
                            <div class="form-content">
                                <form>
                                    <div class="form-group1">
                                        <label for="password">New Password</label>
                                        <input type="password" id="npassword" name="npassword" required="required" />
                                    </div>
                                    <div class="form-group1">
                                        <label for="password">Confirm New Password</label>
                                        <input type="password" id="cnpassword" name="cnpassword" required="required" />
                                    </div>
                                    <div class="form-group1">
                                        <button type="submit" name="action" value="change">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
                <script src="/api/assets/js/jquery.min.js"></script>
                <script type="text/javascript">
                    var close = document.getElementsByClassName("closebtn");
                    var i;

                    for (i = 0; i < close.length; i++) {
                        close[i].onclick = function() {
                            var div = this.parentElement;
                            div.style.opacity = "0";
                            setTimeout(function() {
                                div.style.display = "none";
                            }, 300);
                        }
                    }
                </script>
            </body>
        </html>
<?php
        }
    }
