<?php
include_once("functions.inc");
global $serverip,$token;
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
if ($_POST['action'] == "signup") {
    $pconfig = $_POST;
    //session_start();
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&_])[A-Za-z\d@#$!%*?&_]{8,}$/", $pconfig["password"])) {
        $input_errors[] = sprintf(gettext("A password must be eight characters including one uppercase letter, one special character as @,#,$,&,*,_ and alphanumeric characters"));
    }
    $url = "http://$serverip/api/signup";
    if (!$input_errors) {
        if (empty($pconfig['username'])) {
            unset($_POST);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            $data = array(
                "email" => $pconfig['email'],
                "password" => $pconfig['password'],
                "username" => $pconfig['username'],
                "phonenumber" => $pconfig['phone']
            );
            $data_json = json_encode($data);
            $api_result = call_api('POST',false,$url,$data_json);
            if ($api_result['message'] != "OTP sent successfully") {
                $input_errors[] = sprintf(gettext($api_result['message']));
            } else {
                $_POST['base32secret'] = $api_result['data']['base32secret'];
                $_POST['time_sent'] = $api_result['data']['time_sent'];
                $_SESSION = $_POST;
                $tete = header("Location:/api/sendotp_signup");
                exit;
            }
        }
    }
} else if ($_POST['action'] == "return") {
    header("Location:/api");
    exit;
}
include_once("header.php");
?>
 <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<link rel="stylesheet" href="/api/assets/css/reset.min.css">
<link rel="stylesheet" href="/api/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="/api/plugins/password_strength/style.css">
<link rel="stylesheet" type="text/css" href="/api/plugins/password_strength/password_strength.css">
<script type="text/javascript" src="/api/plugins/password_strength/password_strength_lightweight.js"></script>
<script>
    $(document).ready(function($) {
        $('#mySecondPassword').strength_meter({
            inputClass: 'form-control',
            strengthMeterClass: 'c_strength_meter',
            idClass: 'password',
        });
    });
</script>


<style>
    #red {
        color: #ff0000;
        display: inline;
    }
</style>

<body>
    <div class="form1 formone">
        <?php
        if ($input_errors) {
            print_error_box($input_errors);
        }
        ?>
        <div class="form-panel1 one">
            <div class="form-header1">
                <h2>create a new account</h2>
                <div class="return-login">
                    Already have an account? <a style="display: inline;" href="/api">Sign In</a>
                </div>
            </div>
            <div class="form-content">
                <form action="/api/signup" method="post">
                    <div class="form-group1">
                        <label for="username">Username<div id="red">*</div></label>
                        <input type="text" id="username" name="username" required="required" />
                    </div>
                    <div class="form-group1 formpw" id="mySecondPassword">
                        <label for="password">Password<div id="red">*</div></label>
                        <input type="password"  id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" name="password" required="required" minlength="8" onkeyup='check();' />
                    </div>
                    <div class="form-group1">
                        <label for="confirm_password">Confirm Password<div id="red">*</div></label>
                        <input type="password" id="confirm_password" name="confirm_password" required="required" minlength="8" onkeyup='check();' />
                        <span class="badge badge-primary" id='message' style="display:none;"></span>
                    </div>
                    <div class="form-group1">
                        <label for="fullname">Full Name<div id="red">*</div></label>
                        <input type="text" id="fullname" name="fullname" required="required" />
                    </div>
                    <div class="form-group1">
                        <label for="email">Email<div id="red">*</div></label>
                        <input type="email" id="email" name="email" required="true" pattern="^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$"/>
                    </div>
                    <div class="form-group1">
                        <label for="phone">Phone Number<div id="red">*</div></label>
                        <input type="text" id="phone" name="phone" required="required"/>
                    </div>
                    <div class="form-group1">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address"/>
                    </div>
                    <div class="form-group1">
                        <label for="salecode">Sale Contract code<div id="red">*</div></label>
                        <input type="text" id="salecode" name="salecode" required="required"/>
                    </div>
                    <div class="form-group1">
                        <label for="acctype">Account Type<div id="red">*</div></label>
                        <select class="atype" id="atype" name="atype">
                            <option value="customer">Customer</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div class="form-group1" id="organization1">
                        <label for="salecode">Organization</label>
                        <input type="text" id="organization" name="organization" />
                    </div>
                    <div class="form-group1" id="role1">
                        <label for="role">Department</label>
                        <input type="text" id="role" name="role" />
                    </div>
                    <div class="form-group1">
                        <label class="form-remember">
                            <input type="checkbox" required="required" />By signing up I agree with <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    <div class="form-group1">
                        <button type="submit" name="action" value="signup">Sign Up</button>
                    </div>
                    <div class="form-group1" style="display:none;">
                        <a href="/api">Return to Sign In</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="form-panel1 two">
        </div>
    </div>
    <script src="/api/plugins/password_strength/password.js"></script>
    <script src="/api/assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

        });
        $('i').click(function() {
            $(this).toggleClass('fa-eye-slash fa-eye');
        });
    </script>
    <script src="assets/js/lancsnet.js"></script>
<script type="text/javascript">
  function showtype() {
   if ($('#atype').prop('value') == 'customer') {
        document.getElementById('organization1').style.display="none";
        document.getElementById('role1').style.display="none";
    } else {
        document.getElementById('organization1').style.display="inherit";
        document.getElementById('role1').style.display="inherit";
    }
  }

  $('#atype').change(function() {
    showtype();
  });

  showtype();
</script>
<script type="text/javascript">
    <?php if ($_POST != "") { ?>
        document.getElementById("username").value = "<?= $_POST["username"]; ?>";
        document.getElementById("fullname").value = "<?= $_POST["fullname"]; ?>";
        document.getElementById("email").value = "<?= $_POST["email"]; ?>";
        document.getElementById("address").value = "<?= $_POST["address"]; ?>";
        document.getElementById("phone").value = "<?= $_POST["phone"]; ?>";
        document.getElementById("atype").value = "<?= $_POST["atype"]; ?>";
        document.getElementById("salecode").value = "<?= $_POST["salecode"]; ?>";
        document.getElementById("organization").value = "<?= $_POST["organization"]; ?>";
        document.getElementById("role").value = "<?= $_POST["role"]; ?>";
    <?php } ?>
</script>
<script src="assets/js/jquery.inputmask.bundle.min.js"></script>
<script>
    $(document).ready(function() {
    $("#phone").inputmask("9999999999",{ "onincomplete": function(){ alert('invalid phone number'); } });
    });
    // $(document).ready(function() {
    //     $("#phone").inputmask({ "mask": "9999999[999]", "repeat": 10, "greedy": false });
    // });
</script>
</body>
