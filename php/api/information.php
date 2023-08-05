<?php
require_once('functions.inc');
global $serverip,$token;
check_session("/api");
if ($_POST['action'] == "update") {
    switch($_SESSION['accounttype']) {
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
    $api_result = call_api("PATCH",$token,$url,$data);
    if ($api_result['message'] == "null") {
        $input_success[] = sprintf(gettext("Update Successful!"));
        $_SESSION["fullname"] = $_POST["fullname"];
        $_SESSION["phone"] = $_POST["phone"];
        $_SESSION["organization"] = $_POST["organization"];
        $_SESSION["department"] = $_POST["department"];
        $_SESSION["address"] = $_POST["address"];
        $_SESSION["username"] = $_POST["username"];
        $_SESSION['active'] = true;
    }
    $_SESSION['active'] = true;
    session_destroy();
    header("Location:/api");
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
    exit;
} else if ($_POST['action'] == "skip") {
    $_SESSION['active'] = true;
    session_destroy();
    header("Location:/api");
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
    exit;
}
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
?>
