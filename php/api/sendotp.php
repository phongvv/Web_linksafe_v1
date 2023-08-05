<?php
require_once('functions.inc');
// session_destroy();
if (!empty($_SESSION)) {
if ($_SESSION['login'] == true) {
    switch($_SESSION['accounttype']) {
    case "0":
        header("Location: /api/rsa/dashboard");
        exit;
    case "1":
        header("Location: /api/tech/dashboard");
        exit;
    case "2":
        header("Location: /api/business/dashboard");
        exit;
    case "3":
        header("Location: /api/customer/dashboard");
        exit;
    case "4":
        header("Location: /api/callcenter/dashboard");
        exit;
    case "5":
        header("Location: /api/techsupport/dashboard");
        exit;
    case "6":
        header("Location: /api/hsa/dashboard");
        exit;
    case "7":
        header("Location: /api/bsa/dashboard");
        exit;
    case "8":
        header("Location: /api/eas/dashboard");
        exit;
    case "9":
        header("Location: /api/eas/dashboard");
        exit;
    }
}
if ($_POST['action'] == "submit") {
    $otp = $_POST['otp'];
    $url = "http://$serverip/api/confirm-login";
    $data = array(
        "email" => $_SESSION['email'],
        "password" => $_SESSION['password'],
        "base32secret" => $_SESSION['base32secret'],
        "otp" => $otp,
        "time_sent" => $_SESSION['time_sent']
    );
    $data_json = json_encode($data);
    $api_result = call_api('POST',false,$url,$data_json);

    switch ($api_result['message']) {
    case "Invalid OTP":
        $invalotp = true;
        break;
    case "OTP expired":
        $otpexp = true;
        break;
    case "Email or password incorrect":
        $login = true;
        break;
    case "null":
    $ui = "success";
    foreach($api_result['data'] as $j => $val) {
        switch ($j) {
            case "accessToken":
                $_SESSION['accesstoken'] = $val;
                break;
            case "fullname":
                $_SESSION['fullname'] = $val;
                break;
            case "id":
                $_SESSION['id'] = $val;
                break;
            case "username":
                $_SESSION['username'] = $val;
                break;
            case "accountType":
                $_SESSION['accounttype'] = $val;
                break;
            case "active":
                $_SESSION['active'] = $val;
                break;
            case "phone":
                $_SESSION['phone'] = $val;
                break;
            case "organization":
                $_SESSION['organization'] = $val;
                break;
            case "department":
                $_SESSION['department'] = $val;
                break;
            case "description":
                if ($val == "[]") {
                    $_SESSION['edge_id'] = "true";
                } else {
                    $_SESSION['edge_id'] = '';
                }
                $_SESSION['edge_ids'] = json_decode($val);
                break;
            case "email":
                $_SESSION['email'] = $val;
                break;
            case "address":
                $_SESSION['address'] = $val;
                break;
            case "create_at":
                $_SESSION['create_at'] = date("F j. Y",$val);
                break;
        }
    }
    break;
    }
    if (isset($_SESSION['accesstoken'])){
        $_SESSION['login'] = true;
        switch($_SESSION['accounttype']) {
            case "0":
                header("Location: /api/rsa/dashboard");
                exit;
            case "1":
                header("Location: /api/rsa/dashboard");
                exit;
            case "2":
                header("Location: /api/business/dashboard");
                exit;
            case "3":
                header("Location: /api/customer/dashboard");
                exit;
            case "4":
                header("Location: /api/callcenter/dashboard");
                exit;
            case "5":
                header("Location: /api/techsupport/dashboard");
                exit;
            case "6":
                header("Location: /api/hsa/dashboard");
                exit;
            case "7":
                header("Location: /api/bsa/dashboard");
                exit;
            case "8":
                header("Location: /api/eas/dashboard");
                exit;
            case "9":
                header("Location: /api/eas/dashboard");
                exit;
        }
    }
} else if ($_POST['action'] == "cancel") {
    header("Location: /api");
} else if ($_POST['action'] == "resend") {
    $url = "http://$serverip/api/login";
    $data = array(
        "email" => $_SESSION['username'],
        "password" => $_SESSION['password']
    );
    $api = call_api('POST',false,$url,json_encode($data));
    $_SESSION["base32secret"]=$api["data"]["base32secret"];
    $_SESSION["time_sent"]=$api["data"]["time_sent"];
    header("Location: /api/sendotp");
}
// if (empty($_SESSION)) {
//     header("Location: /api/index");
// }
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/api/assets/images/lancs.ico">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons'>
<link rel="stylesheet" href="/api/assets/css/reset.min.css">
<link rel="stylesheet" href="/api/assets/css/style.css">
</script>
</head>
<body>
<div class="form1">
  <div class="form-toggle1"></div>
  <?php
    if (isset($invalotp)) {
        print_box(false,"Invalid OTP");
    } else if (isset($otpexp)) {
        print_box(false,"OTP expired");
    } else if (isset($login)) {
        print_box(false,"Email or password incorrect");
    }
  ?>
  <div class="form-panel1 one">
    <div class="form-header1">
      <h1>LinkSafe</h1>
    </div>
    <div class="form-content">
      <form action="/api/sendotp" method="post">
        <div class="form-group1">
          <label for="email">One Time Password (OTP) has been sent to your mobile, Please enter the field below.</label>
          <input type="text" id="otp" name="otp"/>
        </div>
        <div class="form-group1">
          <button type="submit" name="action" value="submit">Submit</button>
        </div>
        <div class="form-group1">
          <button type="submit" name="action" value="resend">Resend</button>
        </div>
        <div class="form-group1">
          <button type="submit" name="action" value="cancel" style="background-color:red">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  </div>
</body>
<script type="text/javascript">
    var close = document.getElementsByClassName("closebtn");
    var i;

    for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function(){ div.style.display = "none"; }, 300);
    }
    }
</script>
<?php
} else {
    header("Location:/api");
}
?>