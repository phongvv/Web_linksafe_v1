<?php
$active_menu = "customer";

require_once('functions.inc');
global $serverip, $token;
check_session("/api");
$checks = check_permission_ssa_customer(3);

$color = array(
    "0" => "danger",
    "1" => "primary",
    "2" => "success",
    "3" => "info",
    "4" => "none"
);

if ($checks == true) {
    if ($_REQUEST["id"] != '') {
        $id = $_REQUEST["id"];
        $url = "http://$serverip/api/customer-index/$id";
        $api_result1 = call_api('GET', $token, $url, false);
        $data = $api_result1["data"];
        $edge_info = $data['listEdge'];
    }
    if ($_POST['action'] == 'update') {
        unset($input_errors);
        unset($input_success);
        $pconfig = $_POST;

        if (!$input_errors) {
            if ($pconfig['id'] == "") {
                $method = "POST";
                $url = "http://$serverip/api/create/service-account";
                $data = array(
                    "email" => $_POST["email"],
                    "password" => $_POST["password"],
                    "username" => $_POST["username"],
                    "fullname" => $_POST["full_name"],
                    "address" => $_POST["address"],
                    "phonenumber" => $_POST["number_phone"],
                    "organization" => $_POST["organization"],
                    "department" => $_POST["department"],
                    "type" => "Call-Center"
                );
            } else {
                foreach ($edge_info as $ii => $vl) {
                    $ss = $pconfig['id' . $vl['id']];
                    if ($vl['serviceId'] == null) {
                        $vl['serviceId'] = 0;
                    }
                    if ($vl['serviceId'] != $pconfig["service_" . $ss]) {
                        $url = "http://$serverip/api/edge-service-change";
                        $data1 = array(
                            "edgeId" => $vl["id"],
                            "serviceId" => $pconfig["service_" . $ss]
                        );
                        $api_result = call_api("POST", $token, $url, json_encode($data1));
                    }
                }
                if ($api_result) {
                    if ($api_result["message"] != "Success") {
                        $input_errors[] = sprintf(gettext($api_result["message"]));
                        $_SESSION['input_errors'] = $input_errors;
                    } else {
                        $input_success[] = sprintf(gettext("Update successful!"));
                        $_SESSION['input_success'] = $input_success;
                    }
                    unset($_POST);
                    header("Location:add_customer?id=$id");
                    exit;
                } else {
                    $_SESSION['input_errors'][] = sprintf(gettext("Nothing to change!"));
                }
            }
        }
    }

    include_once "head.php";
?>
    <style>
        .box-header {
            text-align: center;
        }

        .content-wrapper {
            min-height: 100%;
        }

        .btn i {
            color: <?= rand_color() ?>;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
            box-shadow: none;
        }

        .float-right {
            float: right !important;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -7.5px;
            margin-left: -7.5px;
        }
    </style>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <section class="content">
                    <?php
                    if ($_SESSION['input_errors']) {
                        print_error_box($_SESSION['input_errors']);
                        unset($_SESSION['input_errors']);
                    }
                    if ($_SESSION['input_success']) {
                        print_success_box($_SESSION['input_success']);
                        unset($_SESSION['input_success']);
                    }
                    ?>
                    <!-- Horizontal Form -->
                    <form action="add_customer" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-danger">
                                    <div class="card-header with-border " style="background-color: #20c997">
                                        <h3 class="card-title">Customer Information</h3>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 inputcall">
                                <div class="col">
                                    <div class="card card-info ">
                                        <div class="card-header with-border">
                                            <h3 class="card-title">Customer Profile</h3>

                                        </div>

                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="control-label">Username:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="username" id="username" placeholder="User name">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="text" class=" control-label">Full Name:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="full_name" id="full_name" placeholder="....">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="control-label">Email:</label>
                                                <!-- <div class="checkcard">
                                                    <label>
                                                        <input type="checkcard" id="check" name="check">Check to config Except entries.
                                                    </label>
                                                </div> -->
                                                <!-- <div class="col-sm-8"> -->
                                                <input type="email" class="form-control" required="true" name="email" id="email" placeholder="Email" pattern="^([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="text" class=" control-label">Phone Number:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="number_phone" id="number_phone" placeholder="....">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class=" control-label">Address:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="address" id="address" placeholder="....................">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class=" control-label">Organization:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="organization" id="organization" placeholder="....................">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class=" control-label">Department:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="department" id="department" placeholder="....................">
                                                <!-- </div> -->
                                            </div>

                                            <!-- <div class="input-group mb-3">
                                            <div class="col-sm-offset-2 col-sm-4">
                                                <button type="submit" class="btn bg-gradient-success" name="action" value="save" style="width:40%">Save</button>
                                            </div>
                                        </div> -->

                                        </div>
                                    </div>
                                    <div class="">
                                        <a href="account?id=2" class="btn bg-gradient-danger">Cancel</a>
                                        <button type="submit" class="btn bg-gradient-info float-right" name="action" value="update">Update</button>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 inputcall">
                                <div class="col">
                                    <div class="card card-info ">
                                        <div class="card-header with-border">
                                            <h3 class="card-title">Contract Information</h3>

                                        </div>

                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class=" control-label">Contract Name:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="contractname" id="contractname" placeholder=".......">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="text" class="control-label">Contract Type:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="contracttype" id="contracttype" placeholder="........">
                                                <!-- </div> -->
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class=" control-label">Sale Contract Code:</label>

                                                <!-- <div class="col-sm-8"> -->
                                                <input type="text" class="form-control" name="sale" id="sale" placeholder="........">
                                                <!-- </div> -->
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card card-info ">
                                        <div class="card-header with-border">
                                            <h3 class="card-title">Edge Information</h3>

                                        </div>

                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <?php
                                        if (isset($edge_info) && $edge_info != "") {
                                            foreach ($edge_info as $edge_id => $edge_data) {
                                                if (!empty($edge_data)) {
                                                    // switch (count($edge_info)) {
                                                    //     case "1":
                                                    //         echo '<div class="col-md-12">';
                                                    //         break;
                                                    //     case "2":
                                                    //         echo '<div class="col-md-6">';
                                                    //         break;
                                                    //     default:
                                                    //         echo '<div class="col-md-4">';
                                                    //         break;
                                                    // }
                                        ?>
                                                    <div class="col-md-12">
                                                        <div class="col-12" id="accordion">
                                                            <div class="card card-primary card-outline card-<?= $color[rand(0, 4)] ?>">
                                                                <a class="d-block w-100" data-toggle="collapse" href="#collapse<?= $edge_data['id'] ?>">
                                                                    <div class="card-header">
                                                                        <h3 class="card-title w-100 "><?= $edge_data['name'] ?> </h3>
                                                                    </div>
                                                                    <input id="id<?= $edge_data['id'] ?>" type="hidden" name="id<?= $edge_data['id'] ?>" value="<?= $edge_data['id'] ?>" />

                                                                </a>

                                                                <div id="collapse<?= $edge_data['id'] ?>" class="collapse " data-parent="#accordion">
                                                                    <div class="card-body ">
                                                                        <div class="input-group mb-3">
                                                                            <label for="inputEmail3" class="control-label">Edge Name:</label>

                                                                            <!-- <div class="col-sm-8"> -->
                                                                            <input type="text" class="form-control" name="username" id="username" value="<?= $edge_data['name'] ?>" disabled>
                                                                            <!-- </div> -->
                                                                        </div>
                                                                        <div class="input-group mb-3">
                                                                            <label for="text" class=" control-label">Serial Number:</label>

                                                                            <!-- <div class="col-sm-8"> -->
                                                                            <input type="text" class="form-control" name="serial" id="serial" value="<?= $edge_data['serial'] ?>" disabled>
                                                                            <!-- </div> -->
                                                                        </div>
                                                                        <div class="input-group mb-3">
                                                                            <label for="inputPassword3" class="control-label">Service Name:</label>
                                                                            <select name="service_<?= $edge_data['id'] ?>" id="service_<?= $edge_data['id'] ?>" class="form-control" style="width: 100%;">
                                                                                <?php
                                                                                $list = get_services();
                                                                                if ($edge_data['serviceId'] == "null") {
                                                                                    $edge_data['serviceId'] = 0;
                                                                                }
                                                                                foreach ($list as $cc => $vl) {
                                                                                    if ($edge_data['serviceId'] == $cc) {
                                                                                        echo '<option selected="selected" value="' . $cc . '">' . $vl . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                                                    }
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>






                        </div>
                    </form>

                </section>



            </div>
            <div class="control-sidebar-bg"></div>
        </div>

        <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        <!-- </div> -->
        <?php include_once "../src.php"; ?>
        <script src="script.js"></script>
        <script src="../assets/js/lancsnet.js"></script>
        <script type="text/javascript">
            <?php if ($_POST) { ?>
                document.getElementById("username").value = "<?= $_POST["username"]; ?>";
                document.getElementById("full_name").value = "<?= $_POST["full_name"]; ?>";
                document.getElementById("email").value = "<?= $_POST["email"]; ?>";
                document.getElementById("number_phone").value = "<?= $_POST["number_phone"]; ?>";
                document.getElementById("organization").value = "<?= $_POST["organization"]; ?>";
                document.getElementById("department").value = "<?= $_POST["department"]; ?>";
                document.getElementById("address").value = "<?= $_POST["address"]; ?>";
            <?php } else if (isset($id)) { ?>
                document.getElementById("username").value = "<?= $data["username"]; ?>";
                document.getElementById("full_name").value = "<?= $data["fullname"]; ?>";
                document.getElementById("email").value = "<?= $data["email"]; ?>";
                document.getElementById("address").value = "<?= $data["address"]; ?>";
                document.getElementById("number_phone").value = "<?= $data["phone"]; ?>";
                document.getElementById("organization").value = "<?= $data["organization"]; ?>";
                document.getElementById("department").value = "<?= $data["department"]; ?>";
            <?php } ?>
        </script>
        <script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#number_phone").inputmask("9999999999", {
                    "onincomplete": function() {
                        alert('invalid phone number');
                    }
                });
            });
        </script>
    <?php }
