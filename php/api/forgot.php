<?php
require_once('functions.inc');
// phpsession_begin();
if ($_SESSION['login'] == true) {
    switch($_SESSION['accounttype']) {
    case "0":
        header("Location: /api/rsa/");
        exit;
    case "1":
        header("Location: /api/tech/");
        exit;
    case "2":
        header("Location: /api/business/");
        exit;
    case "3":
        header("Location: /api/customer/");
        exit;
    case "4":
        header("Location: /api/callcenter/");
        exit;
    case "5":
        header("Location: /api/techsupport/");
        exit;
    case "6":
        header("Location: /api/hsa/");
        exit;
    case "7":
        header("Location: /api/bsa/");
        exit;
    case "8":
        header("Location: /api/eas/");
        exit;
    case "9":
        header("Location: /api/eas/");
        exit;
    }
}
include('header.php');
?>
<link rel="stylesheet" href="/api/assets/css/reset.min.css">
<link rel="stylesheet" href="/api/assets/css/style.css">
<?php
function change_pass_forgot() {
?>
<body>
    <form action="/api/forgot" method="post">
        <div class="form1">
        <?php
            if ($_SESSION["input_errors"]) {
                $input_errors=$_SESSION["input_errors"];
                unset($_SESSION["input_errors"]);

                print_error_box_1($input_errors);
            }
        ?>
            <div class="form-toggle1"></div>
            <div class="form-panel1 one">
                <div class="form-header1">
                    <h1>Change Your Password
                </div>
                <div class="form-content">
                    <form>
                        <div class="form-group1">
                            <label for="user">New Password:</label>
                            <input type="password"  id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" name="password" required="required" minlength="8"></input>
                        </div>
                        <div class="form-group1">
                            <label for="user">Confirm new Password:</label>
                            <input type="password" id="cpassword" name="cpassword" required="required" ></input>
                        </div>
                        <div class="form-group1">
                            <button type="submit" name="action" value="change">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
</body>
<?php 
}
function confirm_otp_forgot () {
?>
<body>
    <form action="/api/forgot" method="post">
        <div class="form1">
        <?php
                if ($_SESSION["input_errors"]) {
                    $input_errors=$_SESSION["input_errors"];
                    unset($_SESSION["input_errors"]);

                    print_error_box_1($input_errors);
                }
            ?>
            <div class="form-toggle1"></div>
            <div class="form-panel1 one">
                <div class="form-header1">
                    <h1>We sent your OTP
                </div>
                <div class="form-content">
                        <div class="form-group1">
                            <label for="user">Please the confirmation otp below</label>
                            <input type="text" id="otp" name="otp" placeholder="One Time Password"></input>
                        </div>
                        <div class="form-group1">
                            <button type="submit" name="action" value="confirm">Send</button>
                        </div>
                        <div class="form-group1">
                            <button type="submit" name="action" value="resend">ReSend</button>
                        </div>
                        <div class="form-group1">
                            <button type="submit" name="action" value="" style="background-color:red">Cancel</button>
                        </div>
                </div>
            </div>
        </div>
    </form>
</body>
<?php
}
function sendotp_forgot() {
    ?>
        <body>
            <form action="forgot" method="post">
                <div class="form1">
                    <?php
                        if ($_SESSION["input_errors"]) {
                            $input_errors=$_SESSION["input_errors"];
                            unset($_SESSION["input_errors"]);
                            print_error_box_1($input_errors);
                        }
                    ?>
                    <div class="form-toggle1"></div>
                    <div class="form-panel1 one">
                        <div class="form-header1">
                            <h1>Forgot Password
                            <!-- <img src="assets\images\lancsnet2.png" alt="" height="68" width="150" style="display:flexbox"></h1> -->
                        </div>
                        <label for="user">Lost your password? Please enter your email address. You will receive an OTP to create a new password via email.</label>
                        <div class="form-content">
                            <form>
                                <div class="form-group1">
                                    <label for="user">  </label>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="form-content"> -->
                                <div class="form-group1">
                                    <label for="user">Email:</label>
                                    <input type="text" id="email" name="email"></input>
                                </div>
                                <div class="form-group1">
                                    <button type="submit" name="action" value="send">Send</button>
                                </div>
                                <div class="form-group1">
                                    <button type="button" onclick="location.href='/api'" style="background-color:red">Cancel</button>
                                </div>
                        <!-- </div> -->
                    </div>
                </div>
            </form>
        </body>
    <?php
}

function return_login() {
    ?>
<style>
    #card {
        position: relative;
        width: 320px;
        display: block;
        margin: 40px auto;
        text-align: center;
        font-family: 'Source Sans Pro', sans-serif;
    }

    #upper-side {
        padding: 2em;
        background-color: #0ed8ff;
        display: block;
        color: #fff;
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
        margin-top: 10px;
    }

    #checkmark {
        font-weight: lighter;
        fill: #fff;
        margin: -3.5em auto auto 20px;
    }

    #status {
        font-weight: lighter;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 1em;
        margin-top: -.2em;
        margin-bottom: 0;
    }

    #lower-side {
        padding: 2em 2em 5em 2em;
        background: #fff;
        display: block;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    #message {
        margin-top: -.5em;
        color: #757575;
        letter-spacing: 1px;
    }

    #contBtn {
        position: relative;
        top: 1.5em;
        text-decoration: none;
        background: #0ed8ff;
        color: #fff;
        margin: auto;
        padding: .8em 3em;
        -webkit-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
        -moz-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
        box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
        border-radius: 25px;
        -webkit-transition: all .4s ease;
        -moz-transition: all .4s ease;
        -o-transition: all .4s ease;
        transition: all .4s ease;
    }

    #contBtn:hover {
        -webkit-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
        -moz-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
        box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
        -webkit-transition: all .4s ease;
        -moz-transition: all .4s ease;
        -o-transition: all .4s ease;
        transition: all .4s ease;
        font-size: medium;
    }
</style>
<body>
    <section>
        <div class="rt-container">
            <div class="col-rt-12">
                <div class="Scriptcontent">

                    <!-- partial:index.partial.html -->
                    <div id='card' class="animated fadeIn">
                        <div id='upper-side'>
                            <?xml version="1.0" encoding="utf-8"?>
                            <!-- Generator: Adobe Illustrator 17.1.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                            <!DOCTYPE svg
                                PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                            <svg version="1.1" id="checkmark" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" xml:space="preserve">
                                <path d="M131.583,92.152l-0.026-0.041c-0.713-1.118-2.197-1.447-3.316-0.734l-31.782,20.257l-4.74-12.65
                                    c-0.483-1.29-1.882-1.958-3.124-1.493l-0.045,0.017c-1.242,0.465-1.857,1.888-1.374,3.178l5.763,15.382
                                    c0.131,0.351,0.334,0.65,0.579,0.898c0.028,0.029,0.06,0.052,0.089,0.08c0.08,0.073,0.159,0.147,0.246,0.209
                                    c0.071,0.051,0.147,0.091,0.222,0.133c0.058,0.033,0.115,0.069,0.175,0.097c0.081,0.037,0.165,0.063,0.249,0.091
                                    c0.065,0.022,0.128,0.047,0.195,0.063c0.079,0.019,0.159,0.026,0.239,0.037c0.074,0.01,0.147,0.024,0.221,0.027
                                    c0.097,0.004,0.194-0.006,0.292-0.014c0.055-0.005,0.109-0.003,0.163-0.012c0.323-0.048,0.641-0.16,0.933-0.346l34.305-21.865
                                    C131.967,94.755,132.296,93.271,131.583,92.152z" />
                                <circle fill="none" stroke="#ffffff" stroke-width="5" stroke-miterlimit="10"
                                    cx="109.486" cy="104.353" r="32.53" />
                            </svg>
                            <h3 id='status'>
                                Success
                            </h3>
                        </div>
                        <div id='lower-side'>
                            <p id='message'>
                            Congratulations, your password has been successfully change.
                            </p>
                            <a href="/api" id="contBtn">Return Login</a>
                        </div>
                    </div>
                    <!-- partial -->


                </div>
            </div>
        </div>
    </section>
</body>
    <?php
}

// if($_POST["action"] == "resend" ){
//     $url="http://$serverip/api/forgot-password";
//     $method = "POST";
//     $data=array(
//         "email" => $_SESSION["email"],
//     );
//     $api = call_api($method,false,$url,json_encode($data));
    
//     if ($api["message"] == "otp sent") {
//         $_SESSION["time_sent"]=$api["data"]["time_sent"];
//         $_SESSION["base32secret"]=$api["data"]["base32secret"];
//         $_SESSION["email"]=$pconfig["email"];
//         $_POST["action"]="send";
//     }
// }

if(empty($_REQUEST) &&  empty($_POST)){
    unset($_SESSION);
    unset($_SESSION["email"]);
    session_destroy();
    sendotp_forgot();
} else {
    switch ($_POST["action"]) {
        case "send":
            if($_SESSION["input_errors"]){
                $unset($_SESSION["input_errors"]);
            }
            $pconfig = $_POST;
            if($pconfig["email"] == "" ){
                $_SESSION["input_errors"] = sprintf(gettext("The field email is required"));
            }
            if(!$_SESSION["input_errors"]){
                $url="http://$serverip/api/forgot-password";
                $method = "POST";
                $data=array(
                    "email" => $pconfig["email"],
                );
                $api = call_api($method,false,$url,json_encode($data));
            }

            if($api["message"] == "User not exist"){
                $_SESSION["input_errors"] = sprintf(gettext('Email "'.$pconfig["email"].'" not exists'));
                sendotp_forgot();
            } else if ($api["message"] == "otp sent") {
                $_SESSION["time_sent"]=$api["data"]["time_sent"];
                $_SESSION["base32secret"]=$api["data"]["base32secret"];
                $_SESSION["email"]=$pconfig["email"];
                confirm_otp_forgot();
            } else {
                sendotp_forgot();
            }
        break;
        case "confirm":
            $pconfig = $_POST;
            $url="http://$serverip/api/check-OTP";
            $method = "POST";
            $data=array(
                "email" => $_SESSION["email"],
                "base32secret" => $_SESSION["base32secret"],
                "time_sent" => $_SESSION["time_sent"],
                "otp" => $pconfig["otp"],
            );
            $api = call_api($method,false,$url,json_encode($data));

            if($api["message"] == "Invalid OTP"){
                $_SESSION["input_errors"] = sprintf(gettext('Invalid OTP'));
                confirm_otp_forgot();
            } else if ($api["message"] == "null") {
                change_pass_forgot();
            }
        break;

        case "change":
            unset($_SESSION["input_errors"]);
            $pconfig = $_POST;
            if($pconfig['password'] != $pconfig['cpassword'] ){
                $_SESSION["input_errors"] = sprintf(gettext("Passwords Don't Match"));
            }
            if(!$_SESSION["input_errors"]){
                $url="http://$serverip/api/change-password";
                $method = "POST";
                $data=array(
                    "email" => $_SESSION["email"],
                    "base32secret" => $_SESSION["base32secret"],
                    "new_password" => $pconfig["password"],
                );
                $api = call_api($method,false,$url,json_encode($data));
                if ($api["message"] == "null") {
                    unset($_SESSION["username"]);
                    session_destroy();
                    return_login();
                } else {
                    unset($_POST["action"]);
                    $_SESSION["input_errors"] = sprintf(gettext($api['message']));
                    change_pass_forgot();
                }
            } else {
                change_pass_forgot();
            }
        break;

        case "resend":
            unset($_SESSION["time_sent"]);
            unset($_SESSION["base32secret"]);
            
            $url="http://$serverip/api/forgot-password";
            $method = "POST";
            $data=array(
                "email" => $_SESSION["email"],
            );
            $api = call_api($method,false,$url,json_encode($data));
            
            if ($api["message"] == "otp sent") {
                $_SESSION["time_sent"]=$api["data"]["time_sent"];
                $_SESSION["base32secret"]=$api["data"]["base32secret"];
                $_POST["action"]="send";
                confirm_otp_forgot();
            }
        break;
        default:
        sendotp_forgot();
    }
}
?>
