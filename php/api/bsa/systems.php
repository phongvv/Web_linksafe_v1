<?php
require_once('functions.inc');
global $serverip, $token;
check_session("/api");
if ($_SESSION['accounttype'] == 6) {
    $name_hq = $_SESSION["username"];
    $branch = branch_list();
    $edge = edge_list();
    $data = array();
    $name_branch = $branch["true"][$_SESSION["branch_id"]];
    // ---------branch---------------

    $url = "http://$serverip/api/branch";
    $api_result = call_api('GET', $token, $url, false);
    $api_result = $api_result["data"];
    foreach ($api_result as $j => $res) {
        if ($res["branch_admin"] != null) {
            $data[$name_hq]["branch"][$res["id"]]["name"] = $res["name"];
        } else if ($res["branch_admin"] == null) {
            $data[$name_hq]["branch_Availble"][$res["id"]] = $res["name"];
        }
    }
    // -----------EA-----------------------

    $api_result = "";
    $url = "";
    $url = "http://$serverip/api/get/EA-HQ";
    $api_result = call_api('GET', $token, $url, false);
    $api_result = $api_result["data"];
    foreach ($api_result as $j => $res) {
        $branch_id = $branch["false"][$res["department"]];
        if ($res["description"] == "[]") {
            $data[$name_hq]["branch"][$branch_id]["ea_Availble"][$res["id"]] = $res["username"];
        } else {
            $edge_id = json_decode($res["description"]);

            foreach ($edge_id as $jj => $resj) {
                // $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["name"]=$res["username"];
                // $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["edge"][$resj]=$edge["true"][$resj];
                $check = get_status_edge($resj);
                $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["name"] = $res["username"];
                $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["edge"][$resj]["name"] = $edge["true"][$resj];
                $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["edge"][$resj]["status"] = $check['status'];
                $data[$name_hq]["branch"][$branch_id]["ea"][$res["id"]]["edge"][$resj]["type"] = $check['type'];
            }
        }
    }

    $res = "";
    $res = array();
    $res = $data[$name_hq]["branch"][$_SESSION["branch_id"]];
} else if ($_SESSION['accounttype'] == 7) {
    $name_branch = $_SESSION["department"];
    $res = array();
    //---------Edge-----------
    $url = "http://$serverip/api/edge-BSA";
    $api_result = call_api('GET', $token, $url, false);
    foreach ($api_result["data"][2]["edge_BSA"] as $j => $ress) {
        $data[$ress["id"]] = $ress["name"];
    }
    // -----------EA-----------------------
    $url = "http://$serverip/api/get/EA-account";
    $api_result = call_api('GET', $token, $url, false);
    $api_result = $api_result["data"];
    foreach ($api_result as $j => $res1) {
        if ($res1["description"] == "[]") {
            $res["ea_Availble"][$res1["id"]] = $res1["username"];
        } else {
            $edge_id = json_decode($res1["description"]);

            foreach ($edge_id as $jj => $resj) {
                $check = get_status_edge($resj);
                $res["ea"][$res1["id"]]["name"] = $res1["username"];
                $res["ea"][$res1["id"]]["edge"][$resj]["name"] = $data[$resj];
                $res["ea"][$res1["id"]]["edge"][$resj]["status"] = $check['status'];
                $res["ea"][$res1["id"]]["edge"][$resj]["type"] = $check['type'];
            }
        }
    }
}
// ----------------------------------

unlink("static/data/12/collapsable.js");
$myfile = fopen("static/data/12/collapsable.js", "w");
$txt = "var chart_config = {
        chart: {
            container: \"#collapsable-example\",
            animateOnInit: true,
            rootOrientation: 'NORTH',
            scrollbar: 'fancy',
            node: {
                collapsable: true
            },
    
            connectors: {
                type: 'bCurve',
                style: {
                    stroke: '#17a2b8'
                }
            },
    
            animation: {
                nodeAnimation: \"easeInOutBounce\",
                nodeSpeed: 600,
                connectorsAnimation: \"backOut\",
                connectorsSpeed: 700
            }
        },
    nodeStructure: {
        image: \"/api/hsa/static/images/img/server.svg\",
        text: {
            title: \"$name_branch\"
        },
        \"children\": [
";
fwrite($myfile, $txt);

foreach ($res["ea_Availble"] as $ij => $resjj) {
    $txt = "{\"id\": \"$ij\",\"image\": \"/api/hsa/static/images/img/user.svg\",text: {name: '$resjj'}},\n";
    fwrite($myfile, $txt);
}

foreach ($res["ea"] as $ij => $resjj) {
    $resjj_name_ea = $resjj["name"];
    $txt = "{\"id\": \"$ij\",\"image\": \"/api/hsa/static/images/img/user.svg\",text: {name: '$resjj_name_ea'},\"children\":\n [";
    fwrite($myfile, $txt);
    foreach ($resjj["edge"] as $ijj => $resjjj) {
        $name_edge = $resjjj["name"];
        if ($resjjj['type'] == 1) {
            if ($resjjj["status"] == "Active") {
                $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/router5.svg\",text: {name: '$name_edge'},link: {href: \"../ea/dashboard?edge_id=$ijj\"}},";
                unset($name_edge);
            } else if ($resjjj["status"] == "Inactive") {
                $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/router5.svg\",text: {name: '$name_edge'}},";
                unset($name_edge);
            }
            fwrite($myfile, $txt);
        }
        if ($resjjj['type'] == 2) {
            if ($resjjj["status"] == "Active") {
                $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'},link: {href: \"../ea/dashboard?edge_id=$ijj\"}},";
                unset($name_edge);
            } else if ($resjjj["status"] == "Inactive") {
                $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'}},";
                unset($name_edge);
            }
            fwrite($myfile, $txt);
        }
        if ($resjjj["status"] == "Inactive") {
            $txt = "{\"dev_id\": \"$ijj\",\"image\": \"/api/hsa/static/images/img/wifi.svg\",text: {name: '$name_edge'}},";
            unset($name_edge);
            fwrite($myfile, $txt);
        }
    }
    $txt = "\n],},";
    fwrite($myfile, $txt);
}

$txt = "\n]}};";
fwrite($myfile, $txt);
fclose($myfile);
