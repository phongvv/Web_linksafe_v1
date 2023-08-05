<?php
$active_menu = "devices_hsa_branch";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_hsa(6);
button_cancel('devices_hsa_branch');
if ($checks == true) {
    global $serverip, $token;
    if (isset($_REQUEST["id"])) {
        $id = $_REQUEST["id"];
        $url = "http://$serverip/api/branch";
        $data1 = json_encode(array(
            "type" => "LSA_BSA"
        ));
        $api_result = call_api('GET', $token, $url, $data1);
        $data = $api_result["data"][$id];
    }
    if ($_POST['action'] == 'save') {
        unset($input_errors);
        $pconfig = $_POST;

        // if(!preg_match("/^[a-zA-ZvxyỳọáầảấờễàạằệếýộậốũứĩõúữịỗìềểẩớặòùồợãụủíỹắẫựỉỏừỷởóéửỵẳẹèẽổẵẻỡơôưăêâđVXYỲỌÁẦẢẤỜỄÀẠẰỆẾÝỘẬỐŨỨĨÕÚỮỊỖÌỀỂẨỚẶÒÙỒỢÃỤỦÍỸẮẪỰỈỎỪỶỞÓÉỬỴẲẸÈẼỔẴẺỠƠÔƯĂÊÂĐĐ\'\-\040]+$/", $pconfig["name"])){
        //     $input_errors[] = sprintf(gettext("Invalid Fullname Details"));
        // }

        if (!$input_errors) {
            if ($id != "") {
                $url = "http://$serverip/api/branch";
                $method = "PATCH";
                $data = array(
                    "name" => $data["name"],
                    "address" => $_POST["address"],
                    "description" => $_POST["description"],
                    // "id" => $data["id"]
                );
            } else {
                $url = "http://$serverip/api/branch";
                $method = "POST";
                $data = array(
                    "name" => $_POST["name"],
                    "address" => $_POST["address"],
                    "description" => $_POST["description"],
                );
            }
            $api = call_api($method, $token, $url, json_encode($data));
            if ($api["message"] == "LSA_BSA account already exits") {
                $input_errors[] = sprintf(gettext('Email "' . $_POST["email"] . '" already exists'));
            } else if ($api["message"] != "null") {
                $input_errors[] = sprintf(gettext($api["message"]));
            } else {
                if ($method == "PATCH") {
                    $input_success[] = sprintf(gettext("Edit Branch successful!"));
                } else if ($method == "POST") {
                    $input_success[] = sprintf(gettext("Add Branch successful!"));
                }
                $_SESSION['input_success'] = $input_success;
                header("Location: devices_hsa_branch");
                exit;
            }
        }
    }
    include_once "head.php";
?>



    <body class="hold-transition sidebar-mini layout-fixed">

        <div class="wrapper">
            <?php include_once "topmenu.php"; ?>
            <?php include_once "left-sidebar.php"; ?>

            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid" id="myDiv">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($input_errors) {
                                    print_error_box($input_errors);
                                    $pconfig = $data;
                                }
                                ?>
                                <!-- content-page  -->
                                <div class=" animate-bottom">
                                    <div class="alert alert-info">
                                        <strong>Note :</strong> - Please fill out all required fields (marked with an <span style="color:red;font-weight:bold;">*</span>). </br>- After that, click <strong>"Save"</strong> at the bottom of form to save data.
                                    </div>
                                </div>
                                <div class="card card-info">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title" style="font-size:25px; font-style:inherit; font-weight:600">Create new Branch</h3>
                                    </div>
                                    <form action="devices_hsa_branch_edit" method="post" class="form-horizontal">
                                        <input id="id" type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="card-body">

                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="name" id="name" required="required" <?php if ($id != "") {
                                                                                                                                            echo "disabled";
                                                                                                                                        } ?> placeholder="User name">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Address</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="address" id="address" required="required" placeholder="address">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="description" id="description" placeholder="description">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button type="submit" class="btn bg-gradient-success" name="action" value="save">Save</button>
                                                </div>
                                                <div class="col-sm-offset-2 col-sm-4 pull-right">
                                                    <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- content-page  -->

            </div>

            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>
        </div>



        <?php include_once "../src.php"; ?>
        <script src="../pages/tables/data_tables/script.js"></script>


        <script src="script.js"></script>
        <script type="text/javascript">
            <?php if ($input_errors) { ?>
                document.getElementById("name").value = "<?= $_POST["name"]; ?>";
                document.getElementById("address").value = "<?= $_POST["address"]; ?>";
                document.getElementById("description").value = "<?= $_POST["description"]; ?>";
            <?php } else if (isset($id)) { ?>
                document.getElementById("name").value = "<?= $data["name"]; ?>";
                document.getElementById("address").value = "<?= $data["address"]; ?>";
                document.getElementById("description").value = "<?= $data["description"]; ?>";
            <?php } ?>
        </script>
    <?php } ?>