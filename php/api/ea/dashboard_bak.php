<?php
$active_menu = "dashboard";
require_once('functions.inc');
global $token, $serverip;
check_session("/api");

$checks = check_permission_ea(8);
if ($checks == true) {
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        // include_once "header";
        if ($_SESSION['active'] == true) {
?>
            <style>
                .content-wrapper1 {
                    margin-left: 0;
                    min-height: max-content;
                }

                #content_ea {
                    display: inline-block;
                    width: 100%;
                }
            </style>
            <link href="../assets/js/yeti/splish.css" rel="stylesheet" type="text/css">
            <link href="../assets/js/yeti/style.css" rel="stylesheet" type="text/css">
            <!-- <link rel="stylesheet" href="../plugins/morris/morris.css"> -->

            <!-- Morris.js charts -->
            <!-- <script src="../plugins/morris/morris.min.js"></script>

            <script src="../plugins/raphael/raphael-min.js"></script> -->

            <!-- <body class="hold-transition skin-yellow-light sidebar-mini"> -->

            <!-- Content Wrapper. Contains page content -->
            <!-- <div class="content-wrapper1"> -->
            <!-- /.example-modal -->
            <section class="content" id="content_ea">
                <!-- Horizontal Form -->
                <?php

                if ($_REQUEST['search']) {
                ?>
                    <!-- <div class="col-md-7"> -->
                    <!-- /.col -->
                    <div class="card card-info">
                        <div class="card-header with-border">
                            <h3 class="card-title">Flows Graph</h3>
                            <!--
                                                        nút để phóng to thu nhỏ hoặc đóng tạm thời 
                                                    -->
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i></i></button>

                            </div>
                        </div>
                        <div class="card-body chart-responsive">
                            <div id="piechart_3d" style="width: 100%;height: 500px;"></div>
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- </section> -->

                    <!-- </div>/.content-wrapper -->

                    <!-- /.control-sidebar -->
                    <!-- Add the sidebar's background. This div must be placed
                immediately after the control sidebar -->
                    <!-- <div class="control-sidebar-bg"></div> -->
                    <!-- </div> -->
                    <!-- ./wrapper -->
                    <script src="../assets/js/yeti/script.js"></script>
                    <script src="../assets/js/yeti/moment.min.js"></script>
                    <!-- <script type="text/javascript">
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
                </script> -->
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load("current", {
                            packages: ["corechart"]
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['App', 'Flows'],
                                <?php
                                $url_log = "http://$serverip/api/edge-logs/" . $_SESSION['edge_id'];
                                $api_aa = call_api("GET", $token, $url_log, false);
                                $log = $api_aa['data'];
                                foreach ($log as $app => $val) {
                                    if (!empty($val) && $app != "Unknown") {
                                ?>['<?= $app ?>', <?= $val ?>],
                                <?php
                                    }
                                }
                                ?>
                            ]);

                            var options = {
                                title: '',
                                is3D: true,
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                            chart.draw(data, options);
                        }
                    </script>
                    <!-- <script src="../assets/js/index.js"></script>
                <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
                <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>  -->

                    <!-- Chart code -->
                    <!-- <script>
                    am5.ready(function() {

                        // Create root element
                        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                        var root = am5.Root.new("piechart_3d");

                        // Set themes
                        // https://www.amcharts.com/docs/v5/concepts/themes/
                        root.setThemes([
                            am5themes_Animated.new(root)
                        ]);

                        // Create chart
                        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
                        var chart = root.container.children.push(
                            am5percent.PieChart.new(root, {
                                endAngle: 270
                            })
                        );

                        // Create series
                        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
                        var series = chart.series.push(
                            am5percent.PieSeries.new(root, {
                                valueField: "value",
                                categoryField: "category",
                                endAngle: 270
                            })
                        );

                        series.states.create("hidden", {
                            endAngle: -90
                        });

                        // Set data
                        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
                        series.data.setAll([
                            <?php
                            // $url_log = "http://$serverip/api/edge-logs/" . $_SESSION['edge_id'];
                            // $api_aa = call_api("GET", $token, $url_log, false);
                            // $log = $api_aa['data']['detected_app'];
                            // foreach ($log as $app => $val) {
                            //     if (!empty($val) && $app != "Unknown") {
                            // 
                            ?>{ category: "<?= $app ?>", value: <?= $val ?>},
                            // <?php
                                //     }
                                // }
                                ?>]);

                        series.appear(1000, 100);

                    }); // end am5.ready()
                </script> -->
                    <?php
                }
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
