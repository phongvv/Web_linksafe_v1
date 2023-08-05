<?php
    require_once('functions.inc');
    check_session("/api");
    if ($_SESSION['branch_id'] != "null"){
        unset($_SESSION['branch_id']);
    }
    if ($_SESSION['edge_id'] != "null"){
        $_SESSION['edge_id'] == true;
    }
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
?>