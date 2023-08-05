<?php
$active_menu = "vpn";

require_once('functions.inc');
check_session("/api");
$checks = check_permission_ea(8);
button_cancel('network?id=vpn');
if ($checks == true) {
    $edge_id = $_SESSION['edge_id'];
    include_once "head.php";
    $statuss = check_status_edge($_SESSION["edge_id"]);
    if ($statuss == true) {
        $url = "http://$serverip/api/vpn/" . $_SESSION['edge_id'];


        $encryp = array(
            "aes128" => "aes128",
            "aes128ctr" => "aes128ctr",
            "aes128gmac" => "aes128gmac",
            "aes192" => "aes192",
            "aes192ctr" => "aes192ctr",
            "aes192gmac" => "aes192gmac",
            "aes256" => "aes256",
            "aes256gcm16" => "aes256gcm16"
        );
        $hash = array(
            "md5" => "md5",
            "md5_128" => "md5_128",
            "sha1" => "sha1",
            "aesxcbc" => "aesxcbc",
            "aescmac" => "aescmac",
            "aes128gmac" => "aes128gmac",
            "aes192gmac" => "aes192gmac",
            "aes256gmac" => "Ip&aes256gmac",
            "sha256" => "sha256",
            "sha384" => "sha384",
            "sha512" => "sha512",
            "sha256_96" => "sha256_96"
        );
        $dh = array(
            "modp768" => "modp768",
            "modp1024" => "modp1024",
            "modp1536" => "modp1536",
            "modp2048" => "modp2048",
            "modp3072" => "modp3072",
            "modp4096" => "modp4096",
            "modp6144" => "modp6144",
            "modp8192" => "modp8192"
        );
        if ($_REQUEST["id"] != "") {
            $idd = $_REQUEST["id"];
            $api_result = get_list_vpn();
            $data = $api_result[$idd];
        }
        if ($_POST['actions'] == 'save') {
            unset($input_errors);
            $pconfig = $_POST;

            $reqdfields = explode(" ", "name");
            $reqdfieldsn = explode(",", gettext("Name"));

            do_input_validation($_POST, $reqdfields, $reqdfieldsn, $input_errors);

            if ($pconfig['encryp'] == '') {
                $input_errors[] = sprintf(gettext("The"));
            }

            if (!$input_errors) {
                if ($pconfig['id'] == "") {
                    $method = "POST";
                    $data = array(
                        "name" => $pconfig['name'],
                        "encryption" => $pconfig['encryp'],
                        "hash" => $pconfig['hash'],
                        "dh" => $pconfig['dh'],
                        "encryption1" => $pconfig['encryp1'],
                        "hash1" => $pconfig['hash1'],
                        "dh1" => $pconfig['dh1'],
                        "key" => $pconfig['key'],
                        "local_address" => $pconfig['lip'],
                        "local_id" => $pconfig['lid'],
                        "local_subnet" => $pconfig['ls'],
                        "remote_address" => $pconfig['rip'],
                        "remote_id" => $pconfig['rid'],
                        "remote_subnet" => $pconfig['rs']
                    );
                } else {
                    $method = "PATCH";
                    $data = array(
                        "name" => $pconfig['name'],
                        "encryption" => $pconfig['encryp'],
                        "hash" => $pconfig['hash'],
                        "dh" => $pconfig['dh'],
                        "encryption1" => $pconfig['encryp1'],
                        "hash1" => $pconfig['hash1'],
                        "dh1" => $pconfig['dh1'],
                        "key" => $pconfig['key'],
                        "local_address" => $pconfig['lip'],
                        "local_id" => $pconfig['lid'],
                        "local_subnet" => $pconfig['ls'],
                        "remote_address" => $pconfig['rip'],
                        "remote_id" => $pconfig['rid'],
                        "remote_subnet" => $pconfig['rs'],
                    );
                }
                $api_result = call_api($method, $token, $url, json_encode($data));
                if ($api_result["message"] != "null") {
                    $input_errors[] = sprintf(gettext($api['message']));
                } else {
                    if ($method == "PATCH") {
                        $input_success[] = sprintf(gettext("Edit Tunnel VPN successful!"));
                    } else if ($method == "POST") {
                        $input_success[] = sprintf(gettext("Add Tunnel VPN successful!"));
                    }
                    $_SESSION['input_success'] = $input_success;
                    header("Location:network?id=vpn");
                    exit;
                }
            }
        }
?>


        
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- bootstrap time picker -->
        <script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../plugins/iCheck/icheck.min.js"></script>
        <link rel="stylesheet" href="../plugins/iCheck/all.css">
        <script src="../assets/js/lancsnet.js"></script>

        <body class="hold-transition sidebar-mini layout-fixed" onload="myFunction()">
            <div class="wrapper">
                <?php include_once "topmenu.php"; ?>
                <?php include_once "left-sidebar.php"; ?>
                <div class="content-wrapper" style="height: 780px;">
                    <!-- content-page  -->
                    <section class="content">
                        <!-- right column -->
                        <div id="loader"></div>
                        <div class="col-md-* animate-bottom" style="display:none;" id="myDiv">
                            <!-- Horizontal Form -->
                            <?php
                            if ($input_errors) {
                                print_error_box($input_errors);
                            }
                            ?>

                            <div class="card card-info">
                                <div class="card-header with-border" style="text-align: center;">
                                    <h3 class="card-title"><b>VPN Create</b></h3>
                                </div>

                                <form action="create_vpn" method="post" class="form-horizontal">
                                    <input id="id" type="hidden" name="id" value="<?= $idd ?>" />
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <label for="scenrioname" class="col-sm-2 control-label">VPN Name:</label>

                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="name" required="required" id="name" placeholder="...">
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">ESP:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text fix-text">
                                                        <label>Encryption</label>
                                                    </div>
                                                    <select name="encryp" id="encryp" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($encryp as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                    <div class="input-group-text fix-text">
                                                        <label>Hash</label>
                                                    </div>
                                                    <select name="hash" id="hash" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($hash as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                    <div class="input-group-text fix-text">
                                                        <label>Diffie-Hellman</label>
                                                    </div>
                                                    <select name="dh" id="dh" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($dh as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">IKE:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text fix-text">
                                                        <label>Encryption</label>
                                                    </div>
                                                    <select name="encryp1" id="encryp1" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($encryp as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                    <div class="input-group-text fix-text">
                                                        <label>Hash</label>
                                                    </div>
                                                    <select name="hash1" id="hash1" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($hash as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                    <div class="input-group-text fix-text">
                                                        <label>Diffie-Hellman</label>
                                                    </div>
                                                    <select name="dh1" id="dh1" class="form-control select2 fix-select2">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        foreach ($dh as $cc => $vl) {
                                                            echo '<option value="' . $cc . '">' . $vl . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Key:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-key"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="key" id="key" placeholder="..." required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Local Address:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="lip" name="lip" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Local ID:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="lid" name="lid" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Local Subnet:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="ls" name="ls" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Remote Address:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="rip" name="rip" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Remote ID:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="rid" name="rid" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Remote Subnet:</label>

                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-laptop"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="rs" name="rs" data-inputmask="'alias': 'ip'" data-mask required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <!-- <div class="col-sm-offset-2 col-sm-4">
                                        <button type="submit" class="btn bg-gradient-success" name="actions" value="save" style="width:40%">Save</button>
                                    </div> -->
                                            <div class="col-sm-offset-2 col-sm-4">
                                                <button type="submit" class="btn bg-gradient-success" name="actions" value="save">Save</button>
                                            </div>
                                            <div class="col-sm-offset-3 col-sm-3 pull-right">
                                                <button type="submit" class="btn bg-gradient-danger" name="action" formnovalidate value="cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--/.col (right) -->
                </div>
                <!-- /.row -->
                </section>
                <!-- content-page  -->

            </div>


            <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

            </div>
            <?php include_once "../src.php"; ?>
            <script src="../pages/tables/data_tables/script.js"></script>
            <script type="text/javascript">
                var myVar;

                function myFunction() {
                    myVar = setTimeout(showPage, 1000);
                }

                function showPage() {
                    document.getElementById("loader").style.display = "none";
                    document.getElementById("myDiv").style.display = "block";
                }
            </script>
            <script src="script.js"></script>
            <script type="text/javascript">
                <?php if ($_POST) { ?>
                    document.getElementById("name").value = "<?= $_POST["name"]; ?>";
                    document.getElementById("encryp").value = "<?= $_POST["encryp"]; ?>";
                    document.getElementById("hash").value = "<?= $_POST["hash"]; ?>";
                    document.getElementById("dh").value = "<?= $_POST["dh"]; ?>";
                    document.getElementById("encryp1").value = "<?= $_POST["encryp1"]; ?>";
                    document.getElementById("hash1").value = "<?= $_POST["hash1"]; ?>";
                    document.getElementById("dh1").value = "<?= $_POST["dh1"]; ?>";
                    document.getElementById("key").value = "<?= $_POST["key"]; ?>";
                    document.getElementById("lip").value = "<?= $_POST["lip"]; ?>";
                    document.getElementById("lid").value = "<?= $_POST["lid"]; ?>";
                    document.getElementById("ls").value = "<?= $_POST["ls"]; ?>";
                    document.getElementById("rip").value = "<?= $_POST["rip"]; ?>";
                    document.getElementById("rid").value = "<?= $_POST["rid"]; ?>";
                    document.getElementById("rs").value = "<?= $_POST["rs"]; ?>";
                <?php } else if (isset($idd)) {
                    list($hash, $dh) = explode("-", $data[$idd . "_esp"]['dh_group']);
                    list($hash1, $dh1) = explode("-", $data[$idd . "_ike"]['dh_group']);
                ?>
                    document.getElementById("name").value = "<?= $idd; ?>";
                    document.getElementById("encryp").value = "<?= $data[$idd . "_esp"]['encryption_algorithm']; ?>";
                    document.getElementById("hash").value = "<?= $hash; ?>";
                    document.getElementById("dh").value = "<?= $dh; ?>";
                    document.getElementById("encryp1").value = "<?= $data[$idd . "_ike"]['encryption_algorithm']; ?>";
                    document.getElementById("hash1").value = "<?= $hash1; ?>";
                    document.getElementById("dh1").value = "<?= $dh1; ?>";
                    document.getElementById("key").value = "<?= $data[$idd]["pre_shared_key"]; ?>";
                    document.getElementById("lip").value = "<?= $data[$idd . "_tunnel"]["local_leftip"]; ?>";
                    document.getElementById("lid").value = "<?= $data[$idd]["local_identifier"]; ?>";
                    document.getElementById("ls").value = "<?= $data[$idd . "_tunnel"]["local_subnet"]; ?>";
                    document.getElementById("rip").value = "<?= $data[$idd]["gateway"]; ?>";
                    document.getElementById("rid").value = "<?= $data[$idd]["remote_identifier"]; ?>";
                    document.getElementById("rs").value = "<?= $data[$idd . "_tunnel"]["remote_subnet"]; ?>";
                <?php
                }
                ?>
            </script>
            <link rel="stylesheet" href="../plugins/select2/select2.min.css">
            <script src="../plugins/select2/select2.full.min.js"></script>
<?php }
}
