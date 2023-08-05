<?php
require_once('functions.inc');
global $serverip, $token;
check_session("/api");

$name_EA = $_SESSION["username"];
if ($_SESSION['accounttype'] != 9) {
    $url = "http://$serverip/api/edge-EA";
} else {
    $url = "http://$serverip/api/device-inventory";
}
$api_result = call_api('GET', $token, $url, false);
$api_result = $api_result["data"];

if ($api_result != "") {
    foreach ($api_result as $j => $res) {
        $data[$res["id"]]['name'] = $res["name"];
    }
}

// ----------------------------------

unlink("static/data/12/collapsable.js");
$myfile = fopen("static/data/12/collapsable.js", "w");
$txt = "var chart_config = {
    chart: {
        container: \"#collapsable-example\",
        animateOnInit: true,
        node: {
            collapsable: true
        },
        animation: {
            nodeAnimation: \"easeOutBounce\",
            nodeSpeed: 700,
            connectorsAnimation: \"bounce\",
            connectorsSpeed: 700
        }
    },
    nodeStructure: {
        image: \"/api/hsa/static/images/img/server.svg\",
        text: {
            title: \"$name_EA\"
        },
        \"children\": [
";
fwrite($myfile, $txt);

foreach ($_SESSION["edge_ids"] as $ijj => $resjjj) {
    $name_edge = $data[$resjjj]['name'];

    $check = get_status_edge($resjjj);
    if ($check['type'] === 1) {
        if ($check['status'] == "Active") {
            $txt = "{\"dev_id\": \"$resjjj\",\"image\": \"/api/hsa/static/images/img/router5.svg\",text: {name: '$name_edge'},link: {href: \"../ea/dashboard?edge_id=$resjjj\"}},\n";
            unset($name_edge);
        } else if ($check['status'] == "Inactive") {
            $txt = "{\"dev_id\": \"$resjjj\",\"image\": \"/api/hsa/static/images/img/router5.svg\",text: {name: '$name_edge'}},\n";
            unset($name_edge);
        }
        fwrite($myfile, $txt);
    }
    if ($check['type'] === 2) {
        if ($check['status'] == "Active") {
            $txt = "{\"dev_id\": \"$resjjj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'},link: {href: \"../ea/dashboard?edge_id=$resjjj\"}},\n";
            unset($name_edge);
        } else if ($check['status'] == "Inactive") {
            $txt = "{\"dev_id\": \"$resjjj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'}},\n";
            unset($name_edge);
        }
        fwrite($myfile, $txt);
    }
    if ($check["status"] == "Inactive") {
        $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'}},";
        unset($name_edge);
        fwrite($myfile, $txt);
    }
}

$txt = "\n]}};";
fwrite($myfile, $txt);
fclose($myfile);
