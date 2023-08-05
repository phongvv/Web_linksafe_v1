<?php
require_once("functions.inc");
global $serverip,$token;
check_session("/api");
$checks = check_permission_techsupport(5);
if ($checks == true ) {
    if ($_REQUEST['id']) {
        $id = $_REQUEST['id'];
        $_SESSION['edge_id'] = $id;
        header("Location:../edge/dashboard");
        exit;
    }
}
?>