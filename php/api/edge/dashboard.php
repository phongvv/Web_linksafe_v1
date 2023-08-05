<?php
$active_menu = "dashboard";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");

if (isset($_REQUEST["edge_id"])) {
    $_SESSION["edge_id"] = $_REQUEST["edge_id"];
    header("Location: dashboard");
    exit;
}

$checks = check_permission_ea(8);
if ($checks == true) {
    include_once "head.php";

    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        if ($_SESSION['active'] == true) {
?>
            <link href="../assets/js/yeti/splish.css" rel="stylesheet" type="text/css">
            <link href="../assets/js/yeti/style.css" rel="stylesheet" type="text/css">

            <style>
                .hold-transition {
                    min-height: 1000px !important;
                }

                #content_ea {
                    display: inline-block;
                    width: 100%;
                }

                td,
                th {
                    text-align: left !important;
                }
            </style>

            <body class="hold-transition sidebar-mini layout-fixed">
                <div class="wrapper">
                    <?php include_once "topmenu.php"; ?>
                    <?php include_once "left-sidebar.php"; ?>
                    <div class="content-wrapper">
                        <section class="content">
                            <?php

                            // if ($_REQUEST['search']) {
                            $test3 = get_info_system($_SESSION['edge_id']);
                            $memUsage = mem_usage($_SESSION['edge_id'], $test3);
                            // }
                            ?>
                            <div class="container-fluid" id="content_ea">
                                <div class="row">

                                    <div class="col-md-7">
                                        <div class="col">
                                            <div class="card card-info">
                                                <div class="card-header">
                                                    <h3 class="card-title text-black"><b>System Information</b></h3>

                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-hover table-striped table-condensed">
                                                        <tbody>
                                                            <tr>
                                                                <th><?= htmlspecialchars("System") ?></th>
                                                                <td><?= htmlspecialchars($test3['hostname']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= htmlspecialchars("Uptime") ?></th>
                                                                <td><?= htmlspecialchars(get_uptime($test3['uptime'])) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= htmlspecialchars("Current date/time") ?></th>
                                                                <td><?= htmlspecialchars(date("D M j G:i:s T Y")) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= htmlspecialchars("Load average") ?></th>
                                                                <td><?= htmlspecialchars(join(",", $test3['load'])) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= gettext("Memory Usage"); ?></th>
                                                                <td>
                                                                    <div class="progress">
                                                                        <div id="memUsagePB" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $memUsage ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $memUsage ?>%">
                                                                        </div>
                                                                    </div>
                                                                    <span id="memusagemeter"><?= $memUsage ?></span><span>% of <?= sprintf("%.0f", $test3['memory']['total'] / (1024 * 1000)) ?> Mib</span>
                                                                </td>
                                                            </tr>
                                                            <!-- <tr>
                                                            <th><?= htmlspecialchars("CPU Usage") ?></th>
                                                            <td><?= htmlspecialchars() ?></td>
                                                        </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card card-info">
                                                    <!-- display -->
                                                    <div class="card-header with-border">
                                                        <h3 class="card-title text-black"><b>WAN Statistics</b></h3>

                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-hover table-striped table-condensed">
                                                            <tbody>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("MacAddress") ?></th>
                                                                    <td><?= htmlspecialchars($test3["wan"]["macaddr"]) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Traffics Inbound") ?></th>
                                                                    <td><?= ($test3["wan"]["statistics"]["rx_bytes"] <= 1000000000) ? htmlspecialchars(round($test3["wan"]["statistics"]["rx_bytes"] / (1024 * 1024), 1) . " MiB") : htmlspecialchars(round($test3["wan"]["statistics"]["rx_bytes"] / (1024 * 1024 * 1024), 1) . " GiB") ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Traffics Outbound") ?></th>
                                                                    <td><?= ($test3["wan"]["statistics"]["tx_bytes"] <= 1000000000) ? htmlspecialchars(round($test3["wan"]["statistics"]["tx_bytes"] / (1024 * 1024), 1) . " MiB") : htmlspecialchars(round($test3["wan"]["statistics"]["tx_bytes"] / (1024 * 1024 * 1024), 1) . " GiB") ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Packets Inbound") ?></th>
                                                                    <td><?= htmlspecialchars($test3["wan"]["statistics"]["rx_packets"]) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Packets Outbound") ?></th>
                                                                    <td><?= htmlspecialchars($test3["wan"]["statistics"]["tx_packets"]) ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card card-info">
                                                    <!-- display -->
                                                    <div class="card-header with-border">
                                                        <h3 class="card-title text-black"><b>LAN Statistics</b></h3>

                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-hover table-striped table-condensed">
                                                            <tbody>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("MacAddress") ?></th>
                                                                    <td><?= htmlspecialchars($test3["lan"]["macaddr"]) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Traffics Inbound") ?></th>
                                                                    <td><?= ($test3["lan"]["statistics"]["rx_bytes"] <= 1000000000) ? htmlspecialchars(round($test3["lan"]["statistics"]["rx_bytes"] / (1024 * 1024), 1) . " MiB") : htmlspecialchars(round($test3["lan"]["statistics"]["rx_bytes"] / (1024 * 1024 * 1024), 1) . " GiB") ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Traffics Outbound") ?></th>
                                                                    <td><?= ($test3["lan"]["statistics"]["tx_bytes"] <= 1000000000) ? htmlspecialchars(round($test3["lan"]["statistics"]["tx_bytes"] / (1024 * 1024), 1) . " MiB") : htmlspecialchars(round($test3["lan"]["statistics"]["tx_bytes"] / (1024 * 1024 * 1024), 1) . " GiB") ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Packets Inbound") ?></th>
                                                                    <td><?= htmlspecialchars($test3["lan"]["statistics"]["rx_packets"]) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?= htmlspecialchars("Total Packets Outbound") ?></th>
                                                                    <td><?= htmlspecialchars($test3["lan"]["statistics"]["tx_packets"]) ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="memusagemeter1"></div>

                                    </div>
                                    <div class="col-md-5">
                                        <div class="col">
                                            <!-- Custom Tabs -->
                                            <div class="card card-info">

                                                <!-- display -->
                                                <div class="card-header with-border">
                                                    <h3 class="card-title text-black"><b>Interface</b></h3>

                                                </div>
                                                <?php
                                                $aaa = arsort($test);
                                                foreach ($test as $key => $value) {
                                                    if ($value['interface'] == 'loopback') {
                                                        unset($test[$key]);
                                                    }
                                                }
                                                ?>
                                                <div class="card-body">
                                                    <table class="table table-striped table-hover table-condensed">
                                                        <tbody>
                                                            <tr style="text-align: center;">
                                                                <td title="<?= $test3['wan-interface']['l3_device'] ?>(<?= ($test3['wan']['macaddr'] == "") ? htmlspecialchars("Null") : htmlspecialchars($test3['wan']['macaddr']) ?>)">
                                                                    <i class="fa fa-sitemap"></i>
                                                                    <a href="network?id=interface"><?= htmlspecialchars("WAN") ?></a>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($test3['wan-interface']['up'] == true) {
                                                                        echo ('<i class="fa fa-arrow-up text-success" title="up"></i>');
                                                                    } else {
                                                                        echo ('<i class="fa fa-arrow-down text-danger" title="down"></i>');
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td></td>
                                                                <td style="width: 50%;">
                                                                    <?= ($test3['wan-interface']['ipv4-address'][0]['address'] != "") ? htmlspecialchars($test3['wan-interface']['ipv4-address'][0]['address'] . '/' . $test3['wan-interface']['ipv4-address'][0]['mask']) : htmlspecialchars("N/a") ?>
                                                                </td>
                                                            </tr>
                                                            <tr style="text-align: center;">
                                                                <td title="<?= $test3['lan-interface']['l3_device'] ?>(<?= ($test3['lan']['macaddr'] == "") ? htmlspecialchars("Null") : htmlspecialchars($test3['lan']['macaddr']) ?>)">
                                                                    <i class="fa fa-sitemap"></i>
                                                                    <a href="network?id=interface"><?= htmlspecialchars("LAN") ?></a>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($test3['lan-interface']['up'] == true) {
                                                                        echo ('<i class="fa fa-arrow-up text-success" title="up"></i>');
                                                                    } else {
                                                                        echo ('<i class="fa fa-arrow-down text-danger" title="down"></i>');
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td></td>
                                                                <td style="width: 50%;">
                                                                    <?= ($test3['lan-interface']['ipv4-address'][0]['address'] != "") ? htmlspecialchars($test3['lan-interface']['ipv4-address'][0]['address'] . '/' . $test3['lan-interface']['ipv4-address'][0]['mask']) : htmlspecialchars("N/a") ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <!-- <div class="col-md-12"> -->
                                        <div class="col">
                                            <div class="card card-info">
                                                <div class="card-body">
                                                    <!-- <div class="container-calendar"> -->
                                                    <h3 id="monthAndYear"></h3>

                                                    <div class="button-container-calendar">
                                                        <div class="footer-container-calendar"> <button id="previous" onclick="previous()">&#8249;</button>

                                                            <label for="month">Jump To: </label>
                                                            <select id="month" onchange="jump()">
                                                                <option value=0>Jan</option>
                                                                <option value=1>Feb</option>
                                                                <option value=2>Mar</option>
                                                                <option value=3>Apr</option>
                                                                <option value=4>May</option>
                                                                <option value=5>Jun</option>
                                                                <option value=6>Jul</option>
                                                                <option value=7>Aug</option>
                                                                <option value=8>Sep</option>
                                                                <option value=9>Oct</option>
                                                                <option value=10>Nov</option>
                                                                <option value=11>Dec</option>
                                                            </select>
                                                            <select id="year" onchange="jump()"></select>
                                                            <button id="next" onclick="next()">&#8250;</button>
                                                        </div>
                                                    </div>
                                                    <table class="table-calendar" id="calendar" data-lang="en">
                                                        <thead id="thead-month"></thead>
                                                        <tbody id="calendar-body"></tbody>
                                                    </table>
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
        
                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>

                    <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

                </div><!-- </wrapper>  -->

                <?php include_once "../src.php"; ?>
                <script>
                    $(function() {
                        $("#example1").DataTable({
                            "lengthChange": true,
                            "responsive": true,
                            "lengthChange": false,
                            "autoWidth": false,
                            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                            "lengthChange": true
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                        $('#example2').DataTable({
                            "paging": true,
                            "lengthChange": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": false,
                            "responsive": true,
                        });
                    });
                </script>
                <script type="text/javascript">
                    function generate_year_range(start, end) {
                        var years = "";
                        for (var year = start; year <= end; year++) {
                            years += "<option value='" + year + "'>" + year + "</option>";
                        }
                        return years;
                    }

                    today = new Date();
                    currentMonth = today.getMonth();
                    currentYear = today.getFullYear();
                    selectYear = document.getElementById("year");
                    selectMonth = document.getElementById("month");


                    createYear = generate_year_range(1970, 2050);
                    /** or
                     * createYear = generate_year_range( 1970, currentYear );
                     */

                    document.getElementById("year").innerHTML = createYear;

                    var calendar = document.getElementById("calendar");
                    var lang = calendar.getAttribute('data-lang');

                    var months = "";
                    var days = "";

                    var monthDefault = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                    var dayDefault = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

                    months = monthDefault;
                    days = dayDefault;

                    var $dataHead = "<tr>";
                    for (dhead in days) {
                        $dataHead += "<th data-days='" + days[dhead] + "'>" + days[dhead] + "</th>";
                    }
                    $dataHead += "</tr>";

                    //alert($dataHead);
                    document.getElementById("thead-month").innerHTML = $dataHead;
                    monthAndYear = document.getElementById("monthAndYear");
                    showCalendar(currentMonth, currentYear);

                    function next() {
                        currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
                        currentMonth = (currentMonth + 1) % 12;
                        showCalendar(currentMonth, currentYear);
                    }

                    function previous() {
                        currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
                        currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
                        showCalendar(currentMonth, currentYear);
                    }

                    function jump() {
                        currentYear = parseInt(selectYear.value);
                        currentMonth = parseInt(selectMonth.value);
                        showCalendar(currentMonth, currentYear);
                    }

                    function showCalendar(month, year) {

                        var firstDay = (new Date(year, month)).getDay() - 1;

                        tbl = document.getElementById("calendar-body");

                        tbl.innerHTML = "";

                        monthAndYear.innerHTML = months[month] + " " + year;
                        selectYear.value = year;
                        selectMonth.value = month;

                        // creating all cells
                        var date = 1;
                        for (var i = 0; i < 6; i++) {

                            var row = document.createElement("tr");

                            for (var j = 0; j < 7; j++) {
                                if (i === 0 && j < firstDay) {
                                    cell = document.createElement("td");
                                    cellText = document.createTextNode("");
                                    cell.appendChild(cellText);
                                    row.appendChild(cell);
                                } else if (date > daysInMonth(month, year)) {
                                    break;
                                } else {
                                    cell = document.createElement("td");
                                    cell.setAttribute("data-date", date);
                                    cell.setAttribute("data-month", month + 1);
                                    cell.setAttribute("data-year", year);
                                    cell.setAttribute("data-month_name", months[month]);
                                    cell.className = "date-picker";
                                    cell.innerHTML = "<span>" + date + "</span>";

                                    if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                                        cell.className = "date-picker selected";
                                    }
                                    row.appendChild(cell);
                                    date++;
                                }


                            }

                            tbl.appendChild(row);
                        }

                    }

                    function daysInMonth(iMonth, iYear) {
                        return 32 - new Date(iYear, iMonth, 32).getDate();
                    }
                </script>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                <script type="text/javascript">
                    function getupdate() {
                        $(document).ready(function() {
                            $.ajax({
                                url: "dashboard_bak.php",
                                type: "post",
                                data: {
                                    search: $(this).val = true
                                },
                                success: function(result) {
                                    $("#memusagemeter1").html(result);
                                }
                            })
                        })
                    };
                    getupdate();
                    setInterval("getupdate()", 20000);
                </script>
                <script src="../assets/js/yeti/script.js"></script>
                <script src="../assets/js/yeti/moment.min.js"></script>
                <?php
            } else {
                if ($_POST['action'] == "change") {
                    unset($input_errors);
                    $pconfig = $_POST;
                    $type = list_id_account($_SESSION['accounttype']);
                    if ($pconfig['npassword'] != $pconfig['cnpassword']) {
                        $input_errors[] = sprintf(gettext("New Password and Confirm New Password must be match!"));
                    }
                    if (!$input_errors) {
                        if ($type == "SSA-Tech" || $type == "SSA-Business" || $type == "SSA-Customer" || $type == "RSA") {
                            $url = "http://$serverip/api/change-password/SSA-account";
                        } else if ($type == "Call-Center" || $type == "Tech-Support") {
                            $url = "http://$serverip/api/change-password/service-account";
                        } else {
                            $url = "http://$serverip/api/change-password/branch-account";
                        }
                        $data = array(
                            "type" => $type,
                            "new_password" => $_POST['npassword'],
                            "current_password" => $_SESSION["password"]
                        );
                        $api_result = call_api('PATCH', $_SESSION['accesstoken'], $url, json_encode($data));
                        if ($api_result['message'] == "Not has account") {
                            $input_errors[] = sprintf(gettext($api_result['message']));
                        } else if ($api_result['message'] != "null") {
                            $input_errors[] = sprintf(gettext($api_result['message']));
                        } else {
                            $_SESSION["password"] = $_POST['npassword'];
                            // header("Location:/api/information");
                ?>
                            <link rel="stylesheet" href="/api/assets/css/reset.min.css">
                            <link rel="stylesheet" href="/api/assets/css/style.css">
                            <form action="../information" method="post">
                                <!-- Form-->
                                <div class="form1">
                                    <div class="form-toggle1"></div>
                                    <?php
                                    if (isset($unotexits) && isset($_POST)) {
                                        print_box(false, 'User not exist. Sign Up and try again');
                                    }
                                    ?>
                                    <div class="form-panel1 one">
                                        <div class="form-header1">
                                            <h1>User Profile</h1>
                                            <h3> Check your information and replace them if it is incorrect. If not, click the skip button. </h3>
                                        </div>
                                        <div class="form-content">
                                            <form>
                                                <div class="form-group1">
                                                    <label for="username">Full name</label>
                                                    <input type="text" id="fullname" name="fullname" required="required"></input>
                                                </div>
                                                <div class="form-group1">
                                                    <label for="password">Phone Number</label>
                                                    <input type="text" id="phone" name="phone" required="required" />
                                                </div>
                                                <!-- <div class="form-group1">
                                            <label for="password">Email</label>
                                            <input type="email" id="email" name="email" required="required" />
                                        </div> -->
                                                <div class="form-group1">
                                                    <label for="password">Organization</label>
                                                    <input type="text" id="organization" name="organization" required="required" />
                                                </div>
                                                <div class="form-group1">
                                                    <label for="password">Department</label>
                                                    <input type="text" id="department" name="department" required="required" />
                                                </div>
                                                <div class="form-group1">
                                                    <div class="col-sm-5">
                                                        <button type="submit" name="action" value="update">Update</button>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <button type="submit" name="action" value="skip" style="background-color:chocolate">Skip</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <script>
                                document.getElementById("fullname").value = "<?= $_SESSION["fullname"]; ?>";
                                document.getElementById("phone").value = "<?= "0" . $_SESSION["phone"]; ?>";
                                // document.getElementById("email").value = "<?= $_SESSION["email"]; ?>";
                                document.getElementById("organization").value = "<?= $_SESSION["organization"]; ?>";
                                document.getElementById("department").value = "<?= $_SESSION["department"]; ?>";
                            </script>
                <?php
                            exit;
                        }
                    }
                }
                ?>
                <link rel="stylesheet" href="/api/assets/css/reset.min.css">
                <link rel="stylesheet" href="/api/assets/css/style.css">

                <body>

                    <form action="dashboard" method="post">
                        <!-- Form-->
                        <div class="form1">
                            <div class="form-toggle1"></div>
                            <?php
                            if (isset($input_errors)) {
                                print_error_box($input_errors);
                            }
                            ?>
                            <div class="form-panel1 one">
                                <div class="form-header1">
                                    <h1>Linksafe</h1>
                                    <h3>You must change password of username <h2 style="display:inline;color:tomato"><?= $_SESSION['email'] ?></h2> for first login!</h3>
                                </div>
                                <div class="form-content">
                                    <form>
                                        <div class="form-group1">
                                            <label for="password">New Password</label>
                                            <input type="password" id="npassword" name="npassword" required="required" />
                                        </div>
                                        <div class="form-group1">
                                            <label for="password">Confirm New Password</label>
                                            <input type="password" id="cnpassword" name="cnpassword" required="required" />
                                        </div>
                                        <div class="form-group1">
                                            <button type="submit" name="action" value="change">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script src="/api/assets/js/jquery.min.js"></script>
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
    <?php
            }
        }
    }
