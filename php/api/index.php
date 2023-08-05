<?php
require_once('functions.inc');

$refPage = $_SERVER['HTTP_REFERER'];
if ($_SESSION['login'] == true) {
    switch ($_SESSION['accounttype']) {
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
if ($_POST['action'] == "signin") {
    // $url = "http://$serverip/api/send/OTP";
    $pconfig = $_POST;
    // if (empty($pconfig['username'])) {
    // 	unset($_POST);
    // 	header("Location: ".$_SERVER['PHP_SELF']);
    // 	exit;
    // } else {
    // 	$data = array(
    // 		"email" => $pconfig['username'],
    // 		"type" => "login"
    // 	);
    //     $_SESSION['email'] = $pconfig['username'];
    // 	$data_json = json_encode($data);
    // 	$api = call_api('POST',false,$url,$data_json);
    // 	$result = get_info_from_login_api($api);
    //     if (!$result) {
    //         print_box('warning',$api['message']);
    //     }
    // }
    $url = "http://$serverip/api/login";
    $data = array(
        "email" => $pconfig['username'],
        "password" => $pconfig['password']
    );
    $api = call_api('POST', false, $url, json_encode($data));
    if ($api['message'] != "otp sent") {
        $errors = $api['message'];
    } else {
        $_SESSION["email"] = $pconfig['username'];
        $_SESSION["password"] = $pconfig['password'];
        $result = get_info_from_login_api($api);
    }
}
include('header.php');
?>
<style>
    i {
        cursor: pointer;
        display: inline-block;
    }

    .copyright {
        margin-top: 520px;
        font-size: 18px;
        margin-left: 300px;
    }

    .coppy-right {
        padding-left: 19ch;
    }

    .form-group1 button[type=submit]:hover {
        background: #12cc72;
        color: rgb(255, 255, 255);
        /* font-size: large; */
        border: 2px solid #096a11;
    }

    .form2 h1 {
        font-size: 80px;
        margin-left: 200px;
        margin-top: 50px;
        padding: 4px 0;
        color: #4285F4;
        font-weight: 700;
        text-transform: uppercase;
    }

    .content11 {
        font-size: 20px;
        color: #1c1e21;
        margin-left: 200px;
        margin-top: 60px;
        padding: 0 0 20px;
    }

    @media (max-width:1200px) {
        .form2 h1 {
            margin-left: 0;
        }

        .content11 {
            margin-left: 0;
        }
    }
</style>
<link rel="stylesheet" href="/api/assets/css/reset.min.css">
<link rel="stylesheet" href="/api/assets/css/style.css">

<body>

    <form class="form12" action="/api/index" method="post" id="form-1">

        <div class="formm">
            <!-- Form-->
            <div class="form2">
                <div class="content">
                    <img style="width: 180px; ;height:90px;" src="/api/assets/images/lancsnet2.png" alt="Logo">
                    <h1>LinkSafe</h1>
                    <div class="content11">
                        <p>Your networking and cybersecurity partner, for today and tomorrow</p>
                        <p>Our mission is to be the networking and cybersecurity partner of choice,</p>
                        <p>protecting and elevating our digital way of life.</p>
                    </div>
                </div>
            </div>
            <div class="formform">
                <div class="form1 form-login">

                    <div class="form-toggle1"></div>
                    <?php
                    if (isset($errors)) {
                        print_box('warning', $errors);
                    }
                    ?>
                    <div class="form-panel1 one">
                        <div class="form-header1">
                            <h1>LinkSafe
                                <!-- <img src="assets\images\lancsnet2.png" alt="" height="68" width="150" style="display:flexbox"></h1> -->
                        </div>
                        <div class="form-content">
                            <form>

                                <div class="form-group1 group1n">
                                    <!-- <label for="username">Email</label> -->

                                    <i class="fa fa-envelope icon"></i>
                                    <input type="text" id="username" name="username" placeholder="Email" style="margin-left: 5px;"></input>
                                    <span class="form-message"></span>
                                </div>
                                <div class="form-group1 group1n">
                                    <!-- <label for="password">Password</label> -->
                                    <i class="fa fa-key icon"></i>
                                    <input type="password" id="password" name="password" placeholder="********" />
                                    <i class="fa fa-eye-slash" id="togglePassword" style="margin-left: -30px; cursor:pointer;  height: 16px;margin-top:15px;"></i>
                                    <span class="form-message"></span>
                                </div>
                                <div class="form-group1">
                                    <label class="form-remember">
                                        <input type="checkbox" />Remember Me
                                    </label><a class="form-recovery" href="/api/forgot">Forgot Password?</a>
                                </div>
                                <div class="form-group1" style="left:50%">
                                    <button style="background-color: #42b72a; " type="submit" name="action" value="signin">Sign In</button>
                                </div>
                                <div class="form-group1" style="padding-top: 50px">
                                    <div class="col-md-12">
                                        <a href="/api/signup" style="font-size: 18px;">Create new account ?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <span class="coppy-right">© 2022<a href="https://www.lancsnet.com"> Lancsnet Js, Inc.</a>| <a href="#">Privacy and Cookies</a></span>
            </div>

        </div>
    </form>
    <script src="/api/assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

        });
        $('#togglePassword').click(function() {
            $(this).toggleClass('fa-eye-slash fa-eye');
        });
    </script>
    <script type="text/javascript">
        //<![CDATA[

        $(document).ready(function() {
            var panelOne = $('.form-panel.two').height(),
                panelTwo = $('.form-panel.two')[0].scrollHeight;

            $('.form-panel.two').not('.form-panel.two.active').on('click', function(e) {
                e.preventDefault();

                $('.form-toggle').addClass('visible');
                $('.form-panel.one').addClass('hidden');
                $('.form-panel.two').addClass('active');
                $('.form').animate({
                    'height': panelTwo
                }, 200);
            });

            $('.form-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).removeClass('visible');
                $('.form-panel.one').removeClass('hidden');
                $('.form-panel.two').removeClass('active');
                $('.form').animate({
                    'height': panelOne
                }, 200);
            });
        });
        //]]>
    </script>
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