<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once('phpsessionmanager.inc');
phpsession_begin();

$token = $_SESSION['accesstoken'];
$serverip = "192.168.1.116:1337";
// $serverip = "192.168.1.12:1337";
$edge_type = array(
    "1" => "Smart Router",
    "2" => "Wifi"
);

$day = array(
    "Mon" => "Monday",
    "Tue" => "Tuesday",
    "Wed" => "Wednesday",
    "Thu" => "Thursday",
    "Fri" => "Friday",
    "Sat" => "Saturday",
    "Sun" => "Sunday"
);

date_default_timezone_set("Asia/Ho_Chi_Minh");
$avatar = "../assets/images/male-user-manager-svgrepo-com.svg";

function isActive($menu, $mode = "full")
{
    global $active_menu, $avatar;
    if ($mode == "partial")
        echo ($active_menu == $menu ? "menu-open" : "");
    else
        echo ($active_menu == $menu ? "active" : "");
}

function call_api($method, $token, $url, $data)
{
    $curl = curl_init($url);

    $headers = array();
    $headers[] = "Content-Type:application/json";
    if ($token) {
        $headers[] = "Authorization: Bearer {$token}";
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => $headers,
    ));

    // EXECUTE:
    $resultt = curl_exec($curl);
    if (!$resultt) {
        die("Connection Failure");
    }
    curl_close($curl);
    $result = arrayCastRecursive(json_decode($resultt));
    return $result;
}

function sessionn_auth()
{
    global $_SESSION, $serverip;
    $_SESSION['email'] = $_POST['username'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['base32secret'] = $_REQUEST['base32secret'];
    $_SESSION['time_sent'] = $_REQUEST['time_sent'];
    if (count($_SESSION) != 4) {
        return false;
    } else {
        return true;
    }
}

function print_box($status, $text)
{
    echo "<div class=\"alert $status\">";
    echo '<span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span> ';
    echo "<strong>$text</strong>";
    echo '</div>';
}

function print_error_box($text)
{
    echo "<div class=\"alert alert-danger alert-dismissible\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    echo "<h4><i class=\"fa fa-ban\"></i> ERROR!</h4>";
    echo '<ul>';
    foreach ($text as $i => $result) {
        echo "<li><strong>$result</strong></li> <br/>";
    }
    echo '</ul>';
    echo '</div>';
}

function print_error_box_1($text)
{
    echo "<div class=\"alert alert-danger alert-dismissible\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    echo "<h4><i class=\"fa fa-ban\"></i> ERROR!</h4>";
    echo '<ul>';
    echo "<li><strong>$text</strong></li> <br/>";
    echo '</ul>';
    echo '</div>';
}

function print_warning_box($text)
{
    echo "<div class=\"col-md-12\">";
    echo "<div class=\"alert alert-danger alert-dismissible\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    echo "<h4><i class=\"fa fa-ban\"></i> WARNING!</h4>";
    echo '<ul>';
    foreach ($text as $i => $result) {
        echo "<li><strong>$result</strong></li> <br/>";
    }
    echo '</ul>';
    echo '</div>';
}

function print_success_box($text)
{
    echo "<div class=\"col-md-12\">";
    echo "<div class=\"alert alert-success alert-dismissible\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    echo "<h5><i class=\"fa fa-check\"></i> SUCCESS!</h5>";
    echo '<ul>';
    foreach ($text as $i => $result) {
        echo "<li><strong>$result</strong></li> <br/>";
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

function get_info_from_login_api($api_result)
{
    if ($api_result) {
        foreach ($api_result as $i => $value) {
            if ($i == "message") {
                switch ($value) {
                    case "User not exist":
                        $unotexits = true;
                        break;
                    case "email or password incorrect":
                        break;
                    case "otp sent":
                        sessionn_auth();
                        header("Location: /api/sendotp");
                        exit;
                    default:
                        break;
                }
                break;
            } else if ($i == "data" && $value != null) {
                foreach ($value as $j => $val) {
                    if ($j == "base32secret") {
                        $_REQUEST['base32secret'] = $val;
                    } else if ($j == "time_sent") {
                        $_REQUEST['time_sent'] = $val;
                    }
                }
                continue;
            }
        }
    } else {
        return false;
    }
}

function arrayCastRecursive($array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = arrayCastRecursive($value);
            }
            if ($value instanceof stdClass) {
                $array[$key] = arrayCastRecursive((array)$value);
            }
        }
    }
    if ($array instanceof stdClass) {
        return arrayCastRecursive((array)$array);
    }
    return $array;
}

function check_session($path)
{
    global $_SESSION, $serverip, $token;
    if ($_SESSION['login'] != true || empty($_SESSION['login'])) {
        session_destroy();
        header("Location:$path");
        exit;
    }
    if (isset($_SESSION['accesstoken'])) {
        $url = "http://$serverip/api/token-checker";
        $result = call_api('GET', $_SESSION['accesstoken'], $url, false);
        // $result['success'] = "false";
        if ($result['success'] == "false") {
            session_destroy();
?>
            <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:block;">
                <div class="modal-dialog modal-primary" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"><b>LinkSafe</b></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="form-toggle"></div>
                                <div class="form-panel one">
                                    <div class="form-header">
                                    </div>
                                    <div class="form-content">
                                        <form action="/api" method="post">
                                            <div class="form-group">
                                                <label for="email">Your session has expired. Please resignin to renew your session. <br />Redirect to signin in <span id="time">30</span>s ...</label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary" role="button" href="/api">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function startTimer(duration, display) {
                    var timer = duration,
                        minutes, seconds;
                    setInterval(function() {
                        seconds = parseInt(timer % 60, 10);
                        seconds = seconds < 10 ? "0" + seconds : seconds;
                        display.textContent = seconds;
                        if (--timer < 0) {
                            timer = duration;
                        }
                    }, 1000);
                }

                window.onload = function() {
                    var fiveMinutes = 10 * 3,
                        display = document.querySelector('#time');
                    startTimer(fiveMinutes, display);
                };

                let tID = setTimeout(function() {

                    // redirect page.
                    window.location.href = '<?= $path ?>';

                    window.clearTimeout(tID); // clear time out.

                }, 32000);
            </script>
        <?php
        }
    }
}

function check_status_edge($edge_id)
{
    global $serverip, $token;
    $url = "http://$serverip/api/edge-connection-status/$edge_id";
    $api_result = call_api("GET", $token, $url, false);
    switch ($api_result['message']) {
        case "Inactive":
        ?>
            <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:block;">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content .modal-content-warning" style="background-color: #dc3545;">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"><b style="color:white">LinkSafe</b></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="form-toggle"></div>
                                <div class="form-panel one">
                                    <div class="form-header">
                                    </div>
                                    <div class="form-content">
                                        <form action="../index.php" method="post">
                                            <div class="form-group">
                                                <label for="email" style="color:white">This Edge has been disconnected to server. <br /></label>
                                            </div>
                                        </form>
                                    </div>
                                    <p>Redirect to dashboard in <span id="time">30</span>s ...</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-success" role="button" href="../index.php">Return to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function startTimer(duration, display) {
                    var timer = duration,
                        minutes, seconds;
                    setInterval(function() {
                        seconds = parseInt(timer % 60, 10);
                        seconds = seconds < 10 ? "0" + seconds : seconds;
                        display.textContent = seconds;
                        if (--timer < 0) {
                            timer = duration;
                        }
                    }, 1000);
                }

                window.onload = function() {
                    var fiveMinutes = 10 * 3,
                        display = document.querySelector('#time');
                    startTimer(fiveMinutes, display);
                };

                let tID = setTimeout(function() {

                    // redirect page.
                    // window.location.href = '../index.php';

                    window.clearTimeout(tID); // clear time out.

                }, 32000);
            </script>
        <?php
            return false;
            break;
        case "Edge not exist":
        ?>
            <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:block;">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content modal-content-warning">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"><b>LinkSafe</b></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="form-toggle"></div>
                                <div class="form-panel one">
                                    <div class="form-header">
                                    </div>
                                    <div class="form-content">
                                        <form action="../index.php" method="post">
                                            <div class="form-group">
                                                <label for="email">This Edge not exist or has been deleted. <br />Redirect to dashboard in <span id="time">30</span>s ...</label>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-success" role="button" href="../index.php">Return to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function startTimer(duration, display) {
                    var timer = duration,
                        minutes, seconds;
                    setInterval(function() {
                        seconds = parseInt(timer % 60, 10);
                        seconds = seconds < 10 ? "0" + seconds : seconds;
                        display.textContent = seconds;
                        if (--timer < 0) {
                            timer = duration;
                        }
                    }, 1000);
                }

                window.onload = function() {
                    var fiveMinutes = 10 * 3,
                        display = document.querySelector('#time');
                    startTimer(fiveMinutes, display);
                };

                let tID = setTimeout(function() {

                    // redirect page.
                    // window.location.href = '../index.php';

                    window.clearTimeout(tID); // clear time out.

                }, 32000);
            </script>
    <?php
            return false;
            break;
        default:
            return true;
            break;
    }
}

// asdasdsad
function branch_list()
{
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/branch";
    $token = $_SESSION["accesstoken"];
    $api_result = call_api('GET', $token, $url, false);
    if ($api_result["data"] != null) {
        foreach ($api_result["data"] as $j => $res) {
            $data["true"][$res["id"]] = $res["name"];
            $data["false"][$res["name"]] = $res["id"];
        }
    }
    return $data;
}

function list_id_account($id)
{
    $idd = array(
        "0" => "RSA",
        "1" => "SSA-Tech",
        "2" => "SSA-Business",
        "3" => "SSA-Customer",
        "4" => "Call-Center",
        "5" => "Tech-Support",
        "6" => "HSA",
        "7" => "LSA_BSA",
        "8" => "EA",
        "9" => "Homecustom",

    );
    return $idd[$id];
}

function get_ea_info()
{
    global $serverip, $token;
    $url = "http://$serverip/api/get/edge";
    $api = call_api('GET', $token, $url, false);
    return $api;
}

function list_group_user()
{
    global $token, $serverip;
    $edge_id = $_SESSION['edge_id'];
    $url = "http://$serverip/api/groups/" . $edge_id;
    $api_result = call_api("GET", $token, $url, false);
    return  $api_result;
}
function listGroupUser()
{
    global $token, $serverip;
    $url = "http://$serverip/api/groups";
    $api_result = call_api("GET", $token, $url, false);
    return  $api_result;
}



function list_group_end_user($check, $value)
{
    #This function for who want to use 2 flow id to name or name to id
    #________________________________________________________
    #|                  |                   |               |
    #|      check       |       value       |     result    |
    #|__________________|___________________|_______________|
    #|      true        |       id          |       name    |
    #|      true        |       false       |    id -> name |
    #|      false       |       name        |       id      |
    #|      false       |       false       |    name-> id  |
    #|======================================================|

    global $token, $serverip;
    $edge_id = $_SESSION['edge_id'];
    $url = "http://$serverip/api/groups/" . $edge_id;
    $api_result = call_api("GET", $token, $url, false);
    $array = array();
    $a_result = "";
    if ($check) {
        if ($value) {
            foreach ($api_result['data'] as $result) {
                if ($value == $result['groupid']) {
                    $a_result = $result['groupname'];
                    break;
                }
            }
            return $a_result;
        } else {
            foreach ($api_result['data'] as $result) {
                $array[$result['groupid']] = $result['groupname'];
            }
            return $array;
        }
    } else {
        if ($value) {
            foreach ($api_result['data'] as $result) {
                if ($value == $result['groupname']) {
                    $a_result = $result['groupid'];
                    break;
                }
            }
            return $a_result;
        } else {
            foreach ($api_result['data'] as $result) {
                $array[$result['groupname']] = $result['groupid'];
            }
            return $array;
        }
    }
}
function get_scenario($check)
{
    global $serverip, $token;
    $url = "http://$serverip/api/scenario/" . $_SESSION['edge_id'];
    $api = call_api("GET", $token, $url, false);
    $api_result = $api['data'];
    if ($check == "true") {
        $name = array_combine(
            array_map(function ($g) {
                return $g['id'];
            }, $api_result),
            array_map(function ($g) {
                return $g['name'];
            }, $api_result)
        );
        return $name;
    } else if ($check == "all") {
        return $api;
    } else {
        $name = array_combine(
            array_map(function ($g) {
                return $g['name'];
            }, $api_result),
            array_map(function ($g) {
                return $g['id'];
            }, $api_result)
        );
        return $name;
    }
}

function getScenario($check)
{
    global $serverip, $token;
    $url = "http://$serverip/api/scenario";
    $api = call_api("GET", $token, $url, false);
    $api_result = $api['data'];
    if ($check == "true") {
        $name = array_combine(
            array_map(function ($g) {
                return $g['id'];
            }, $api_result),
            array_map(function ($g) {
                return $g['name'];
            }, $api_result)
        );
        return $name;
    } else if ($check == "all") {
        return $api;
    } else {
        $name = array_combine(
            array_map(function ($g) {
                return $g['name'];
            }, $api_result),
            array_map(function ($g) {
                return $g['id'];
            }, $api_result)
        );
        return $name;
    }
}

function get_ea_name($check)
{
    global $serverip, $token;
    $list = get_ea_info();
    $list_arrr = $list['data'];
    foreach ($list_arrr as $o) {
        if ($o['admin_id'] == $_SESSION['id']) {
            $list_arr[] = $o;
        }
    }
    if ($check) {
        $name = array_combine(
            array_map(function ($g) {
                return $g['id'];
            }, $list_arr),
            array_map(function ($g) {
                return $g['name'];
            }, $list_arr)
        );
        return $name;
    } else {
        $id = array_combine(
            array_map(function ($g) {
                return $g['name'];
            }, $list_arr),
            array_map(function ($g) {
                return $g['id'];
            }, $list_arr)
        );
        return $id;
    }
}

function show_app()
{
    global $serverip, $token;
    $url = "http://$serverip/api/get/app";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function show_app_cat()
{
    global $serverip, $token;
    $url = "http://$serverip/api/get/app-category";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function show_web()
{
    global $serverip, $token;
    $url = "http://$serverip/api/get/web";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function show_web_cat()
{
    global $serverip, $token;
    $url = "http://$serverip/api/get/web-category";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function show_group($k)
{
    global $serverip, $token;
    $api_result = $_SESSION['group'];
    if ($k) {
        $id = array_combine(
            array_map(function ($g) {
                return $g['groupname'];
            }, $api_result),
            array_map(function ($g) {
                return $g['groupid'];
            }, $api_result)
        );
    } else {
        $id = array_combine(
            array_map(function ($g) {
                return $g['groupid'];
            }, $api_result),
            array_map(function ($g) {
                return $g['groupname'];
            }, $api_result)
        );
    }
    return $id;
}

function get_one_user($id)
{
    if ($id  == null) {
        $name = "All";
        return $name;
    }
    foreach ($_SESSION['user'] as $i => $res) {
        if ($id == $res["userid"]) {
            $name = $res["username"];
        }
    }
    return $name;
}

function get_one_group($id)
{

    if ($id  != null) {
        foreach ($_SESSION['group'] as $i => $res) {
            if ($id == $res["groupid"]) {
                $name = $res["groupname"];
            }
        }
    } else {
        $name = "All";
        return $name;
    }

    return $name;
}

function getOneGroup($id)
{
    global $serverip, $token;

    if ($id  != null) {
        $data = array(
            "groupid" => $id
        );
        $url = "http://$serverip/api/group";
        $api = call_api("GET", $token, $url, json_encode($data));
        $api_result = $api['data']['groupname'];
        $name = $api_result;
    } else {
        $name = "All";
        return $name;
    }

    return $name;
}


function get_one_device($id)
{

    global $serverip, $token;
    $url = "http://$serverip/api/one-end-device/" . $id;
    $api_result = call_api("GET", $token, $url, false);
    $name = $api_result;
    if ($api_result['success'] == "false") {
        $name = array(
            'name' => "Unknown",
            'mac' => "0"
        );
    } else {
        $name = $api_result['data'];
    }
    return $name;
}

function show_user($k)
{
    global $serverip, $token;
    // $list = get_ea_info();
    // $list_arr = $list['data'];
    // foreach ($list_arr as $re) {
    //     if ($_SESSION['id'] == $re['admin_id']) {
    $edge_id = $_SESSION['edge_id'];
    //         break;
    //     }
    // }

    $api_result = $_SESSION['user'];
    if ($k) {
        $id = array_combine(
            array_map(function ($g) {
                return $g['username'];
            }, $api_result),
            array_map(function ($g) {
                return $g['userid'];
            }, $api_result)
        );
    } else {
        $id = array_combine(
            array_map(function ($g) {
                return $g['userid'];
            }, $api_result),
            array_map(function ($g) {
                return $g['username'];
            }, $api_result)
        );
    }
    return $id;
}

function showUser($k)
{
    global $serverip, $token;
    $api_result = $_SESSION['user'];
    if ($k) {
        $id = array_combine(
            array_map(function ($g) {
                return $g['username'];
            }, $api_result),
            array_map(function ($g) {
                return $g['userid'];
            }, $api_result)
        );
    } else {
        $id = array_combine(
            array_map(function ($g) {
                return $g['userid'];
            }, $api_result),
            array_map(function ($g) {
                return $g['username'];
            }, $api_result)
        );
    }
    return $id;
}


function do_input_validation($postdata, $reqdfields, $reqdfieldsn, &$input_errors)
{

    /* check for bad control characters */
    foreach ($postdata as $pn => $pd) {
        if (is_string($pd) && preg_match("/[\\x00-\\x08\\x0b\\x0c\\x0e-\\x1f]/", $pd)) {
            $input_errors[] = sprintf(gettext("The field %s contains invalid characters."), $pn);
        }
    }

    if (is_array($reqdfields)) {
        for ($i = 0; $i < count($reqdfields); $i++) {
            if ($postdata[$reqdfields[$i]] == "") {
                $input_errors[] = sprintf(gettext("The field %s is required."), $reqdfieldsn[$i]);
            }
        }
    }
}

function edge_list()
{
    global $_SESSION, $serverip, $token;
    $url = "http://$serverip/api/edge-Available";
    $api_result = call_api('GET', $token, $url, false);
    foreach ($api_result["data"][0]["total edge"] as $j => $res) {
        $data["true"][$res["id"]] = $res["name"];
        $data["false"][$res["name"]] = $res["id"];
    }
    return $data;
}

function edge_hsa_bsa_list($branch_id)
{
    global $_SESSION, $serverip, $token;
    $url = "http://$serverip/api/edge-Available";
    $api_result = call_api('GET', $token, $url, false);
    foreach ($api_result["data"][0]["total edge"] as $j => $res) {
        if ($branch_id == $res["branch_id"]) {
            $data["true"][$res["id"]] = $res["name"];
            $data["false"][$res["name"]] = $res["id"];
        }
    }
    return $data;
}

function ea_list()
{
    global $_SESSION, $serverip;
    $token = $_SESSION["accesstoken"];
    $url = "http://$serverip/api/get/branch-account";
    $data = json_encode(array(
        "type" => "LSA_BSA"
    ));
    $datahsa = call_api('GET', $token, $url, $data);
    foreach ($datahsa as $i => $result) {
        if ($i == "data") {
            if ($result != "null") {
                foreach ($result as $j => $res) {
                    if (empty($idbsa[0])) {
                        $idbsa[0] = $res["id"];
                    } else {
                        if ($idbsa != null) {
                            $idbsa[count($idbsa)] = $res["id"];
                        }
                    }
                }
            }
        }
    }
    $data = json_encode(array(
        "id" => $idbsa,
        "type" => "EA"
    ));
    $api_result = call_api('GET', $token, $url, $data);
    if ($api_result["data"] != null) {
        foreach ($api_result["data"] as $i => $res) {
            $data1["true"][$res["id"]] = $res["username"];
            $data1["false"][$res["username"]] = $res["id"];
        }
    }
    return $data1;
}

function bsa_list()
{
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/get/branch-account";
    $token = $_SESSION["accesstoken"];
    $data1 = json_encode(array(
        "type" => "LSA_BSA"
    ));
    $api_result = call_api('GET', $token, $url, $data1);
    if ($api_result["data"] != "null") {
        foreach ($api_result["data"] as $i => $res) {
            $data["true"][$res["id"]] = $res["username"];
            $data["false"][$res["username"]] = $res["id"];
        }
    }
    return $data;
}

function string_to_aray($a)
{
    // $a1=explode("{",$a);
    // $a2=explode("}",$a1[1]);
    // $a3='['.$a2[0].']';
    $a4 = json_decode($a);
    return $a4;
}

function edge_available_list()
{
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/edge-Available";
    $token = $_SESSION["accesstoken"];
    $api_result1 = call_api('GET', $token, $url, false);

    $api_result = $api_result1["data"]["1"]["Available"];
    if ($api_result != null) {
        foreach ($api_result as $i => $res) {
            $data["true"][$res["id"]] = $res["name"];
            $data["false"][$res["name"]] = $res["id"];
        }
    }
    return $data;
}
function edge_hsa_bsa_available_list($branch_id)
{
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/edge-Available";
    $token = $_SESSION["accesstoken"];
    $api_result1 = call_api('GET', $token, $url, false);
    $api_result = $api_result1["data"]["1"]["Available"];
    if ($api_result["data"] != "") {
        foreach ($api_result["data"] as $j => $res) {
            if ($branch_id == $res["branch_id"]) {
                $data["true"][$res["id"]] = $res["name"];
                $data["false"][$res["name"]] = $res["id"];
            }
        }
        return $data;
    } else {
        return false;
    }
}

function edgeId()
{
    global $serverip, $token;
    $list = get_ea_info();
    $list_arr = $list['data'];
    $edge_id = array();
    foreach ($list_arr as $i => $re) {
        if ($_SESSION['id'] == $re['admin_id']) {
            $edge_id[] = $re['id'];
            continue;
        }
    }
    return $edge_id;
}

function show_proto()
{
    global $serverip, $token;
    $url = "http://$serverip/api/protocol";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function show_proto_cat()
{
    global $serverip, $token;
    $url = "http://$serverip/api/protocol-category";
    $api = call_api("GET", false, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function branch_available_list()
{
    $a = branch_list();
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/get/branch-account";
    $token = $_SESSION["accesstoken"];
    $data1 = json_encode(array(
        "type" => "LSA_BSA"
    ));
    $api_result = call_api('GET', $token, $url, $data1);
    if ($a["true"] != null) {
        foreach ($a["true"] as $i) {
            $check = "0";
            foreach ($api_result["data"] as $j => $res) {
                if ($i == $res["department"]) {
                    $check = "1";
                }
            }
            if ($check != "1") {
                $b = $a["false"][$i];
                $data["true"][$b] = $i;
                $data["false"][$i] = $b;
            }
        }
    }
    return $data;
}

function edge_bsa_list()
{
    global $serverip, $token;
    $url = "http://$serverip/api/edge-BSA";
    $api_result = call_api('GET', $token, $url, false);
    if ($api_result["data"][0]["Total edge"] != "") {
        foreach ($api_result["data"][0]["Total edge"] as $j => $res) {
            $data["true"][$res["id"]] = $res["name"];
            $data["false"][$res["name"]] = $res["id"];
        }
        return $data;
    } else {
        return false;
    }
}

function edge_bsa_available_list()
{
    global $serverip, $token;
    $url = "http://$serverip/api/edge-BSA";
    $api_result = call_api('GET', $token, $url, false);
    foreach ($api_result["data"][1]["Available"] as $j => $res) {
        $data["true"][$res["id"]] = $res["name"];
        $data["false"][$res["name"]] = $res["id"];
    }
    return $data;
}

function get_status_wireless()
{
    global $serverip, $token;
    global $serverip, $token;
    $url = "http://$serverip/api/wireless/" . $_SESSION['edge_id'];
    $api_result = call_api('GET', $token, $url, false);
    $data = $api_result["data"]['0'];
    return $data;
}


function get_list_vpn()
{
    global $serverip, $token;
    $edge_id = edgeId();
    // $url = "http://$serverip/api/vpn/" . $edge_id[0];
    $url = "http://$serverip/api/vpn/" . $_SESSION['edge_id'];
    $api_result = call_api("GET", $token, $url, false);
    $api = $api_result['data'];
    $test = array();
    foreach ($api as $i => $result) {
        list($j) = explode("_", $i);
        $a[$j] = $j;
        $array = array_unique($a);
        if (!empty($array)) {
            foreach ($array as $id => $val) {
                if ($id == $j) {
                    $test[$id][$i] = $result;
                }
            }
        }
    }
    return $test;
}

function checkPermissionHsa($acctype)
{
    if ($acctype) {
        if ($acctype != "6") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
}

function checkPermissionBsa($acctype)
{
    if ($acctype) {
        if ($acctype != "7" && $acctype != "6") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
}

function checkPermissionEa($acctype)
{
    if ($acctype) {
        if ($acctype != "8" && $acctype != "7" && $acctype != "6") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
}

function check_permission_ea($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "8" && $_SESSION['accounttype'] != "7" && $_SESSION['accounttype'] != "6" && $_SESSION['accounttype'] != "9" && $_SESSION['accounttype'] != "5") {
            display_warning();
            return false;
        } else {
            // if ($_SESSION['accounttype'] == "9") {
            //     if ($_SESSION['accounttype'] != "9" && $_SESSION['edge_id'] == "true") {
            //         display_warning();
            //         return false;
            //     } else {
            return true;
            //     }
            // }
        }
    }
}


function check_permission_eas($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "9") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
}

function check_permission_bsa($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "7" && $_SESSION['accounttype'] != "6") {
            display_warning();
            return false;
        } else if ($_SESSION['accounttype'] == "6") {
            if (empty($_SESSION['branch_id'])) {
                display_warning();
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}

function check_permission_hsa($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "6") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_rsa($acctype)
{
    if (isset($acctype)) {
        if ($_SESSION['accounttype'] != "0") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_ssa_tech($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "1") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_ssa_business($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "2") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_ssa_customer($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "3") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_callcenter($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "4") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function check_permission_techsupport($acctype)
{
    if ($acctype) {
        if ($_SESSION['accounttype'] != "5") {
            display_warning();
            return false;
        } else {
            return true;
        }
    }
    // if (empty($_SESSION['edge_id'])) {
    //     display_warning();
    //     return false;
    // } else {
    //     return true;
    // }
}

function display_warning()
{
    $color = "af2200";
    include("head.php");
    ?>

    <body class="hold-transition skin-yellow-light sidebar-mini">
        <div class="wrapper">
            <?php include_once "layout/topmenu.php"; ?>
            <?php include_once "layout/left-sidebar.php"; ?>
            <div class="content-wrapper">
                <style>
                    .warning .modal {
                        position: relative;
                        top: 10%;
                        bottom: 10%;
                        right: 10%;
                        left: auto;
                        display: block;
                        z-index: 1;
                    }

                    .warning .modal {
                        background: transparent !important;
                    }

                    .modal-warning1 {
                        background-color: gray !important;
                    }

                    .modal-title {
                        color: whitesmoke;
                    }

                    .modal-content {
                        border-radius: 10px;
                    }

                    .modal-body {
                        background-color: <?= '#' . $color ?> !important;
                        color: whitesmoke;
                    }

                    .modal-header {
                        background-color: #ff001d !important;
                        border-bottom: 2px solid;
                        border-top-left-radius: 10px;
                        border-top-right-radius: 10px;
                        color: whitesmoke;
                    }

                    .modal-footer {
                        background-color: #ff001d !important;
                        border-top: 2px solid;
                        border-bottom-left-radius: 10px;
                        border-bottom-right-radius: 10px;
                        color: #d2d6de;
                    }
                </style>
                <section class="content-header">
                </section>
                <section class="content">
                    <div class="warning">
                        <div class="modal modal-warning1">
                            <div class="modal-dialog">
                                <div class="modal-content .modal-content-warning">
                                    <div class="modal-header">
                                        <h1 class="modal-title">
                                            <center><i class="fa fa-exclamation-circle" style="color: #d2d6de;"></i><b> Permissions Denied</b></center>
                                        </h1>
                                    </div>
                                    <div class="modal-body">
                                        <h2>
                                            <center>You don't have permission to access this page.</center>
                                        </h2>
                                        <h2>
                                            <center>If you want, please return to Dashboard and Logout your Account.</center>
                                        </h2>
                                        <h2>
                                            <center>Thank you!!!</center>
                                        </h2>
                                        <h3 style="text-align: right;"><br /><small style="color:whitesmoke">Redirect to dashboard in <span id="time">30</span>s ...</small></h3>
                                    </div>
                                    <!-- <form>
                        <div style="width: 125%" class="modal-body">
                        
                        </div>
                    </form> -->
                                    <div class="modal-footer justify-content-between">

                                        <a class="btn btn-outline pull-left" role="button" href="/api/logout.php" style="background-color: #76000d; color: white,"><i class="fa fa-sign-out"></i>Sign Out</a>
                                        <a class="btn btn-outline" role="button" name="action" href="../index.php" value="del" style="background-color: #00bc25"><i class="fa fa-rotate-left"></i> Return dashboard</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <script>
                    function startTimer(duration, display) {
                        var timer = duration,
                            minutes, seconds;
                        setInterval(function() {
                            seconds = parseInt(timer % 60, 10);
                            seconds = seconds < 10 ? "0" + seconds : seconds;
                            display.textContent = seconds;
                            if (--timer < 0) {
                                timer = duration;
                            }
                        }, 1000);
                    }

                    window.onload = function() {
                        var fiveMinutes = 10 * 3,
                            display = document.querySelector('#time');
                        startTimer(fiveMinutes, display);
                    };

                    let tID = setTimeout(function() {

                        // redirect page.
                        window.location.href = '../index.php';
                        window.clearTimeout(tID);
                    }, 32000);
                </script>
            </div>
            <?php include_once "../copyright.php"; ?>
            <?php //include_once "right-sidebar.php"; 
            ?>
            <div class="control-sidebar-bg"></div>
        </div>
        <?php include_once "../footer.php" ?>
    <?php
}

function convert_branch()
{
    global $_SESSION, $serverip, $token;
    $url = "http://$serverip/api/all-branch";
    $api_result = call_api('GET', false, $url, false);
    if ($api_result["data"] != null) {
        foreach ($api_result["data"] as $j => $res) {
            $data["true"][$res["id"]] = $res["name"];
            $data["false"][$res["name"]] = $res["id"];
        }
    }

    return $data;
}

function get_bsa_edge_list()
{
    global $_SESSION, $serverip, $token;
    $url = "http://$serverip/api/edge-BSA";
    $api_result = call_api('GET', $token, $url, false);
    $data_edge_bsa = array();
    $bsa_branch_id = convert_branch()["false"][$_SESSION["department"]];

    if ($api_result["data"][1]["Available"] != null) {
        foreach ($api_result["data"][1]["Available"] as $j => $res) {
            $data_edge_bsa[$j] = $res;
            $data_edge_bsa[$j]["Status"] = "Pending";
        }
    }

    if ($api_result["data"][2]["edge_BSA"] != null) {
        foreach ($api_result["data"][2]["edge_BSA"] as $i => $res) {
            if ($res["branch_id"] == $bsa_branch_id) {
                $dem = count($data_edge_bsa);
                $data_edge_bsa[$dem]["id"] = $res["id"];
                $data_edge_bsa[$dem]["ip"] = $res["ip"];
                $data_edge_bsa[$dem]["password"] = $res["password"];
                $data_edge_bsa[$dem]["name"] = $res["name"];
                $data_edge_bsa[$dem]["root_username"] = $res["root_username"];
                $data_edge_bsa[$dem]["port"] = $res["port"];
                $data_edge_bsa[$dem]["mac"] = $res["mac"];
                $data_edge_bsa[$dem]["admin_id"] = $res["admin_id"];
                $data_edge_bsa[$dem]["serial"] = $res["serial"];
                $data_edge_bsa[$dem]["branch_id"] = $res["branch_id"];
                $data_edge_bsa[$dem]["Status"] = "Active";
            }
        }
    }
    return $data_edge_bsa;
}

function convert_idbsa_branch_name()
{
    global $_SESSION, $serverip, $token;
    $url = "http://$serverip/api/get/branch-account";
    $data1 = json_encode(array(
        "type" => "LSA_BSA"
    ));
    $api_result = call_api('GET', $token, $url, $data1);
    foreach ($api_result["data"] as $j => $res) {
        $data["true"][$res["id"]] = $res["department"];
        $data["false"][$res["department"]] = $res["id"];
    }
    return $data;
}

function branch_management_list()
{
    global $_SESSION, $serverip;
    $url = "http://$serverip/api/branch";
    $token = $_SESSION["accesstoken"];
    $api_result = call_api('GET', $token, $url, false);
    if ($api_result["data"] != null) {
        foreach ($api_result["data"] as $j => $res) {
            if ($res["branch_admin"] != null) {
                $data["true"][$res["id"]] = $res["name"];
                $data["false"][$res["name"]] = $res["id"];
            }
        }
    }
    return $data;
}
// function id_sample_scenario_list()
// {
//     global $_SESSION, $serverip,$token;
//     $url_1 = $url_1 = "http://$serverip/api/default-scenario/". $_SESSION['edge_id'];
//     $api_result = call_api("GET", $token, $url_1, false);
//     if ($api_result["data"]["listScenario"] != null) {
//         foreach ($api_result["data"]["listScenario"] as $j => $res) {
//             $data["true"][$res["id"]] = $res["name"];
//             $data["false"][$res["name"]] = $res["id"];
//         }
//     }
//     return $data;
// }

function conver_timer($test)
{
    $a1 = explode("[", $test);
    $a2 = explode("]", $a1[1]);
    $a3 = $a2[0];
    $time = "";

    if ($a3 == "all") {
        $time = "all";
    } else {
        $t = explode(',', $a3);
        foreach ($t as $i => $result) {
            list($j1, $j2) = explode("-", $result);
            $uae = $j1 . ":00-" . $j2 . ":00";
            if ($i == "0") {
                $time = $uae;
            } else {
                $time = $time . ',' . $uae;
            }
        }
    }
    return $time;
}

function get_one_app($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM app where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['name'];
    }
    $db->close();
    return $name;
}

function get_one_app_cat($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM app_category where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['name'];
    }
    $db->close();
    return $name;
}

function get_one_web($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM web where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['domain'];
    }
    $db->close();
    return $name;
}

function get_one_web_cat($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM web_category where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['name'];
    }
    $db->close();
    return $name;
}

function get_one_proto($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM protocol where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['name'];
    }
    $db->close();
    return $name;
}

function get_one_proto_cat($id)
{
    $db = new SQLite3('/usr/www/api/test.db');
    $result = $db->query("SELECT name FROM protocol_category where id = $id");
    while ($row = $result->fetchArray()) {
        $name = $row['name'];
    }
    $db->close();
    return $name;
}

function get_end_device($edge_id, $aa)
{
    global $serverip, $token;
    // $url_syn = "http://$serverip/api/sync-config";
    // $data = array(
    //     "edgeId" => $_SESSION['edge_id']
    // );
    // call_api('POST',$token,$url_syn,json_encode($data));
    sleep(1);
    // call_api('POST',$token,$url_syn,json_encode($data));
    // call_api('GET', $token, $url1, false);

    $url = "http://$serverip/api/local-device/" . $edge_id;
    $api_result = call_api('GET', $token, $url, false);
    $api_result1 = $api_result["data"];
    if ($aa) {
        $api_result2 =  array_combine(
            array_map(function ($g) {
                return $g['mac'];
            }, $api_result1),
            array_map(function ($g) {
                return ($g['hostname'] != "") ? $g['hostname'] . ' (' . $g['mac'] . ')' : "Unknown" . ' (' . $g['mac'] . ')';
            }, $api_result1)
        );
        return $api_result2;
    } else {
        return $api_result1;
    }
}

function getEndDevice($aa)
{
    global $serverip, $token;
    sleep(1);
    $url = "http://$serverip/api/local-device";
    $api_result = call_api('GET', $token, $url, false);
    $api_result1 = $api_result["data"];
    if ($aa) {
        $api_result2 =  array_combine(
            array_map(function ($g) {
                return $g['mac'];
            }, $api_result1),
            array_map(function ($g) {
                return ($g['hostname'] != "") ? $g['hostname'] . ' (' . $g['mac'] . ')' : "Unknown" . ' (' . $g['mac'] . ')';
            }, $api_result1)
        );
        return $api_result2;
    } else {
        return $api_result1;
    }
}


function confirm_delete($link, $id, $string)
{
    $href = $link . 'idd=' . $id;
    ?>
        <!-- <div class="modal fade" id="myModal<?= $id ?>" tabindex="" role="dialog">
            <div class="modal-dialog modal-info modal-delete" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">LinkSafe</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-toggle">
                            <div class="form-panel one">
                                <div class="form-header">
                                </div>
                                <div class="form-content">
                                    <form>
                                        <div class="form-group">
                                            <label for="email"><?= $string ?></label>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color:red">Cancel</button>
                                            <a class="btn btn-primary" name="action" value="del" href="<?= $href ?>">Submit</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="modal fade" id="myModal<?= $id ?>">
            <div class="modal-dialog">
                <div class="modal-content bg-info">
                    <div class="modal-header">
                        <h4 class="modal-title" style="text-align: center;">LinkSafe</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b><?= $string ?></b></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-gradient-danger" data-dismiss="modal">Close</button>
                        <a class="btn bg-gradient-primary" name="action" value="del" href="<?= $href ?>">Submit</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    <?php
}

function confirmDelete($link, $id, $string, $hehe)
{
    $href = $link . 'idd=' . $id . '&&idn=' . $hehe;
    ?>
        <div class="modal fade" id="myModal<?= $id ?>">
            <div class="modal-dialog">
                <div class="modal-content bg-info">
                    <div class="modal-header">
                        <h4 class="modal-title" style="text-align: center;">LinkSafe</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b><?= $string ?></b></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-gradient-danger" data-dismiss="modal">Close</button>
                        <a class="btn bg-gradient-primary" name="action" value="del" href="<?= $href ?>">Submit</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    <?php
}

function get_customer()
{
    global $serverip, $token;
    $url = "http://$serverip/api/query-all-customer";
    $api = call_api("GET", $token, $url, false);
    $api_result = $api['data'];
    return $api_result;
}

function conver_string_to_array($test)
{
    $a1 = explode("[", $test);
    $a2 = explode("]", $a1[1]);
    $a3 = $a2[0];
    $t = explode(',', $a3);
    return $t;
}

function get_services()
{
    global $serverip, $token;
    $url = "http://$serverip/api/service-index";
    $api_result = call_api("GET", $token, $url, false);
    $api = $api_result['data'];
    return $api;
}

function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function syn_edge()
{
    global $serverip, $token;
    switch ($_SESSION['accounttype']) {
        case "9":
            $_SESSION['edge_ids'] = array();
            $url = "http://$serverip/api/device-inventory";
            unset($_SESSION['edge_ids']);
            $api_result = call_api('GET', $token, $url, false);
            foreach ($api_result['data'] as $i => $edge) {
                if (count($_SESSION['edge_ids']) == 0) {
                    $_SESSION['edge_ids'][0] = $edge['id'];
                } else {
                    $_SESSION['edge_ids'][count($_SESSION['edge_ids'])] = $edge['id'];
                }
            }
            break;
        default:
            $url = "http://$serverip/api/edge-EA";
            $api_result = call_api('GET', $token, $url, false);
            unset($_SESSION['edge_ids']);
            foreach ($api_result['data'] as $i => $edge) {
                if (count($_SESSION['edge_ids']) == 0) {
                    $_SESSION['edge_ids'][0] = $edge['id'];
                } else {
                    $_SESSION['edge_ids'][count($_SESSION['edge_ids'])] = $edge['id'];
                }
            }
            break;
    }
    return true;
}
$url_2 = "http://$serverip/api/check-status-apply-scenario/" . $id_sample_scenario;
$api_result2 = call_api("GET", $token, $url_2, false);

function checkStatusApplyScenarioGlobal($idScenario)
{
    global $serverip, $token;
    $url_2 = "http://$serverip/api/check-status-apply-scenario/" . $idScenario;
    $api_result2 = call_api("GET", $token, $url_2, false);
    $statusApply = $api_result2['data']['apply_global'];
    return $statusApply;
}
function checkStatusApply($value, $edge_id)
{
    global $serverip, $token;
    $dataStatus = array(
        "scenario_id" => $value
    );
    $url_2 = "http://$serverip/api/scenario-status-edge/" . $edge_id;
    $api_result2 = call_api("POST", $token, $url_2, json_encode($dataStatus));
    $statusApply  = $api_result2['data']['local'];

    return $statusApply;
}

function return_apply_scenario($scenario)
{
    global $serverip, $token;
    $url_1x = "http://$serverip/api/apply-scenario/" . $_SESSION['edge_id'];
    $api_result_defaultx = call_api("GET", $token, $url_1x, false);
    $id_scenario_applyx = $api_result_defaultx['data']["scenarioNow"];

    if ($id_scenario_applyx == $scenario) {
        $url = "http://$serverip/api/scenario-config";
        $edge_id = $_SESSION['edge_id'];
        $data = array(
            "scenario_id" => $id_scenario_applyx,
            "edge_id" => "$edge_id"
        );
        call_api('POST', $token, $url, json_encode($data));
    } else {
        return false;
    }
}

function returnApplyScenarioGlobal($scenario)
{
    global $serverip, $token;
    $url = "http://$serverip/api/check-changed-apply-scenario/$scenario";
    call_api('POST', $token, $url, false);
}

function get_status_edge($edge_id)
{
    global $serverip, $token;
    $url = "http://$serverip/api/edge-connection-status/$edge_id";
    $api_result = call_api("GET", $token, $url, false);
    $type = $api_result['data']['type'];
    $result = '';
    switch ($api_result['message']) {
        case "Inactive":
            $result = "Inactive";
            break;
        case "Active":
            $result = "Active";
            break;
        default:
            $result = $api_result['message'];
            break;
    }
    return $result1 = array(
        "status" => $result,
        "type" => $type
    );
}

function get_info_system($edge_id)
{
    global $serverip, $token;
    $url = "http://$serverip/api/edge-interfaces/" . $_SESSION['edge_id'];
    $url2 = "http://$serverip/api/network-device/info/" . $_SESSION['edge_id'];
    $url3 = "http://$serverip/api/system/" . $edge_id;
    $api_result = call_api("GET", $token, $url, false);
    $api_result2 = call_api("GET", $token, $url2, false);
    $api_result3 = call_api("GET", $token, $url3, false);
    $test = $api_result['data'];
    $test3 = $api_result3['data'];
    $test2 = $api_result2['data'];

    $sysinfo_items = array();

    if ($test3[0]['cfg01e48a']['hostname'] != "null") {
        $sysinfo_items['hostname'] = $test3[0]['cfg01e48a']['hostname'];
    } else {
        $sysinfo_items['hostname'] = "";
    }

    if ($test3[0]['cfg01e48a']['timezone'] != "null") {
        $sysinfo_items['timezone'] = $test3[0]['cfg01e48a']['timezone'];
    } else {
        $sysinfo_items['timezone'] = "";
    }

    if ($test3[1]['load'] != "null") {
        $sysinfo_items['load'] = $test3[1]['load'];
    } else {
        $sysinfo_items['load'] = "";
    }

    if ($test3[1]['localtime'] != "null") {
        $sysinfo_items['localtime'] = $test3[1]['localtime'];
    } else {
        $sysinfo_items['localtime'] = "";
    }

    if ($test3[1]['memory']) {
        $sysinfo_items['memory'] = $test3[1]['memory'];
    } else {
        $sysinfo_items['memory'] = "";
    }

    if ($test3[1]['uptime'] != "null") {
        $sysinfo_items['uptime'] = $test3[1]['uptime'];
    } else {
        $sysinfo_items['uptime'] = "";
    }

    foreach ($test as $j => $val) {
        if ($val['interface'] == "lan") {
            $sysinfo_items['lan-interface'] = $test[$j];
        } else if ($val['interface'] == "wan") {
            $sysinfo_items['wan-interface'] = $test[$j];
        }
    }

    $sysinfo_items['lan'] = $test2['interface-lan'];
    $sysinfo_items['wan'] = $test2['interface-wan'];

    return $sysinfo_items;
}

function get_hostname($edge_id)
{
    $info = get_info_system($edge_id);
    $hostname = $info['hostname'];
    return $hostname;
}

function get_load_average($edge_id)
{
    $info = get_info_system($edge_id);
    $loadavg = $info['load'];
    return $loadavg;
}

// function get_load_average($edge_id) {
//     $info = get_info_system($edge_id);
//     $loadavg = $info['load'];
//     return $loadavg;
// }

function get_uptime($getup)
{
    $uptime = floatval($getup);

    if (intval($uptime) == 0) {
        return;
    }

    $updays = (int)($uptime / 86400);
    $uptime %= 86400;
    $uphours = (int)($uptime / 3600);
    $uptime %= 3600;
    $upmins = (int)($uptime / 60);
    $uptime %= 60;
    $upsecs = (int)($uptime);

    $uptimestr = "";
    if ($updays > 1) {
        $uptimestr .= "$updays Days ";
    } else if ($updays > 0) {
        $uptimestr .= "1 Day ";
    }

    if ($uphours > 1) {
        $hours = "s";
    }

    if ($upmins > 1) {
        $minutes = "s";
    }

    if ($upmins > 1) {
        $seconds = "s";
    }

    $uptimestr .= sprintf("%02d Hour$hours %02d Minute$minutes %02d Second$seconds", $uphours, $upmins, $upsecs);
    return $uptimestr;
}

function mem_usage($edge_id, $url)
{
    global $serverip, $token;
    $free = $url['memory'];
    $mem = array_filter($free, function ($value) {
        return ($value !== null && $value !== false && $value !== '');
    }); // removes nulls from array
    $mem = array_merge($mem); // puts arrays back to [0],[1],[2] after 
    $totalMem = round($mem['total'] / 1000000, 2);
    if ($totalMem > 0) {
        $uuu = $mem['total'] - $mem['available'];
        // $inactiveMem = get_single_sysctl("vm.stats.vm.v_inactive_count");
        // $cachedMem = get_single_sysctl("vm.stats.vm.v_cache_count");
        // $freeMem = get_single_sysctl("vm.stats.vm.v_free_count");
        // $usedMem = $totalMem - ($inactiveMem + $cachedMem + $freeMem);
        // $memUsage = round(($usedMem * 100) / $totalMem, 0);
        $usedMem = round($uuu / 1000000, 2);
        $memUsage = round(($usedMem / $totalMem) * 100);
    } else {
        $memUsage = "NA";
    }

    return $memUsage;
}

function get_schedule_policy($aaa)
{
    global $serverip, $token, $day;
    // $schedule = arrayCastRecursive(json_decode($aaa));
    $schedule = $aaa;
    $result = array();
    if ($schedule['weekdays'] != null || $schedule['time_start'] != "" || $schedule['time_stop'] != "") {
        $result['schedule'] = true;
        $dem = count($schedule['weekdays']);
        if ($dem == 7) {
            $wdays = "All Days of Week";
        } else {
            $wdays = $day[$schedule['weekdays'][0]];
            foreach ($schedule['weekdays'] as $d => $days) {
                if ($d != "0") {
                    $wdays .= ', ' . $day[$days];
                }
            }
        }
        $result['weekdays'] = $wdays;
        if ($schedule['time_start'] == "" && $schedule['time_stop'] != "") {
            $time = "00:00-" . $schedule['time_stop'];
        } else if ($schedule['time_start'] == "" && $schedule['time_stop'] == "") {
            $time = "All Times";
        } else {
            $time = $schedule['time_start'] . "-" . $schedule['time_stop'];
        }
        $result['time'] = $time;
    } else {
        $result['schedule'] = false;
    }
    return $result;
}

function notification_edit_email()
{
    ?>
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:block;">
            <div class="modal-dialog modal-success " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h4 class="modal-title" id="myModalLabel"><b></i>SUCCESS!</b></h4> -->
                        <h4><i class="fa fa-check"><b></i> SUCCESS<b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form">
                            <div class="form-toggle"></div>
                            <div class="form-panel one">
                                <div class="form-header">
                                </div>
                                <div class="form-content">
                                    <form action="../index.php" method="post">
                                        <div class="form-group">
                                            <label for="email">Your email has been changed successfully. Please login again with your new email to confirm your change. <br />Redirect to profile <span id="time">30</span>s ...</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn bg-gradient-danger pull-left" role="button" href="/api/logout.php">Sign In</a>
                        <a class="btn btn-primary" role="button" href="profile.php">Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function startTimer(duration, display) {
                var timer = duration,
                    minutes, seconds;
                setInterval(function() {
                    seconds = parseInt(timer % 60, 10);
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display.textContent = seconds;
                    if (--timer < 0) {
                        timer = duration;
                    }
                }, 1000);
            }

            window.onload = function() {
                var fiveMinutes = 10 * 3,
                    display = document.querySelector('#time');
                startTimer(fiveMinutes, display);
            };

            let tID = setTimeout(function() {

                // redirect page.
                window.location.href = 'profile.php';

                window.clearTimeout(tID); // clear time out.

            }, 32000);
        </script>

    <?php
}

function button_cancel($link)
{
    global $_POST, $_REQUEST;
    if ($_POST["action"] == "cancel") {
        unset($_POST);
        unset($_REQUEST);
        header("Location: $link");
    }
}
function buttonCancelV3($link, $nameId, $id)
{
    global $_POST, $_REQUEST;
    if ($_POST["action"] == "cancel") {
        unset($_POST);
        unset($_REQUEST);
        header("Location: $link?$nameId=$id");
    }
}


function get_logs_user($id, $data1, $device_id)
{
    global $serverip, $token;
    $url = "http://$serverip/api/user-flow/" . $_SESSION['edge_id'];
    $data = json_encode(array(
        "userid" => $id
    ));
    $api_result = call_api("GET", $token, $url, $data);
    $result = $api_result['data'];

    // return $result;
    // if ($data1) {
    //     $result = $api_result['data']['3'];
    //     $log = $total_logs = array();
    //     foreach ($result as $val) {
    //         if ($val['detected_application_name'] != "Unknown") {
    //             unset($log);
    //             $log['deviceName'] = $val['count'];
    //             $log['first_seen_at'] = date("H:i d-m-Y", $val["first_seen_at"] / 1000);
    //             $log['last_seen_at'] = date("H:i d-m-Y", $val["last_seen_at"] / 1000);
    //             $log['detected_application_name'] = $val['total_bytes'];
    //         }
    //         if ($total_logs[0] == "") {
    //             $total_logs[0] = $log;
    //         } else {
    //             $total_logs[count($total_logs)] = $log;
    //         }
    //     }


    //     asort($total_logs);
    // } else {
    // $testtt = $api_result['data'];
    if ($device_id) {
        if ($data1) {
            $appName = $api_result['data'][$device_id]; // 4 ở đây đnag là biến tĩnh đang thay đổi để chọn như details 
            $log = $total_logs = [];
            foreach ($appName as $i => $val) {
                if ($i != "Unknown") {
                    unset($log);
                    $log['detected'] = $i;
                    $log['up_time'] = $val['up_time'];
                    $log['first_seen_at'] = date("H:i d-m-Y", $val["start_time"] / 1000);
                    $log['total_bytes'] = round($val["total_bytes"] / 1024);
                    if ($total_logs[0] == "") {
                        $total_logs[0] = $log;
                    } else {
                        $total_logs[count($total_logs)] = $log;
                    }
                }
            }
        } else {
            $total_logs = $api_result['data'][$device_id];
        }
        return $total_logs;
    } else {
        return $result;
    }
}


function testlog($id)
{
    global $serverip, $token;
    $url = "http://$serverip/api/user-flow/" . $_SESSION['edge_id'];
    $data = json_encode(array(
        "userid" => $id
    ));
    $api_result = call_api("GET", $token, $url, $data);

    $result = $api_result['data'];

    return $result;
}


// function get_logs_user($id, $data1)
// {
//     global $serverip, $token;
//     $url = "http://$serverip/api/user-flow/" . $_SESSION['edge_id'];
//     $data = json_encode(array(
//         "userid" => $id
//     ));
//     $api_result = call_api("GET", $token, $url, $data);
//     if ($data1) {
//         $result = $api_result['data']['3'];
//         $log = $total_logs = array();
//         foreach ($result as $val) {
//             if ($val['detected_application_name'] != "Unknown") {
//                 unset($log);
//                 $log['deviceName'] = $val['deviceName'];
//                 $log['first_seen_at'] = date("H:i d-m-Y", $val["first_seen_at"] / 1000);
//                 $log['last_seen_at'] = date("H:i d-m-Y", $val["last_seen_at"] / 1000);
//                 $log['detected_application_name'] = $val['detected_application_name'];
//             }
//             if ($total_logs[0] == "") {
//                 $total_logs[0] = $log;
//             } else {
//                 $total_logs[count($total_logs)] = $log;
//             }
//         }
//         asort($total_logs);
//     } else {
//         $appName = $api_result['data']['4'];
//         $total_logs = $appName;
//     }
//     return $total_logs;
// }

#this function to filter hostname by macaddress
function get_host_name($mac)
{
    global $serverip, $token;
    $url = "http://$serverip/api/search-hostname";
    $data = json_encode(array(
        "mac" => "$mac"
    ));
    $api_result = call_api("GET", $token, $url, $data);
    if ($api_result['success'] == "false") {
        $api_result['data'] = [];
        $api_result['data']['hostname'] = "Unknown";
    }
    return $api_result['data'];
}
