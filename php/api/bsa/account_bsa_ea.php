<?php
$active_menu = "account_bsa_ea";
require_once('functions.inc');
check_session("/api");
$checks = check_permission_bsa(7);
// phpsession_begin();
if ($checks == true) {
    global $token,$serverip;
    
    switch($_SESSION['accounttype']) {
        case "7":
            $countries = edge_bsa_list(true);
            $countries1 = edge_bsa_available_list();
            if (isset($_REQUEST['idd'])) {
                $url = "http://$serverip/api/delete/branch-account";
                $data = array(
                    "id" => $_REQUEST["idd"],
                    "type" => "EA"
                );
                $api = call_api('DELETE', $token, $url, json_encode($data));
                $input_success[] = sprintf(gettext("Update successful!"));
                $_SESSION['input_success'] = $input_success;
                header("Location: account_bsa_ea");
                exit;
            } else {

                $url = "http://$serverip/api/get/EA-account";
                $api_result= call_api('GET', $token, $url, false);

            }
            break;
        case "6":
            $countries=edge_list();
            $countries1=bsa_list();
            $branch_name=branch_list()["true"][$_SESSION["branch_id"]];
            $token=$_SESSION["accesstoken"];
            if (isset($_REQUEST['idd'])){
                    $url="http://$serverip/api/delete/branch-account";
                    $data=array(
                        "id" => $_REQUEST["idd"],
                        "type" => "EA"
                    );
                    $api = call_api('DELETE',$token,$url,json_encode($data));
                    $input_success[] = sprintf(gettext("Update successful!"));
                    $_SESSION['input_success'] = $input_success;
                    header("Location: account_bsa_ea");
            } else {
                $url="http://$serverip/api/get/branch-account";
                $data=json_encode(array(
                    "type" => "LSA_BSA"
                ));
                $datahsa = call_api('GET',$token,$url,$data);
                foreach($datahsa["data"] as $j => $res) {
                    if($res["department"] == $branch_name){
                        $idbsa = $res["id"];
                    }
                }
                $data=json_encode(array(
                    "id" => [$idbsa],
                    "type" => "EA"
                ));
                $api_result = call_api('GET',$token,$url,$data);
            }
            break;
        }
    include_once "head.php";
?>



<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once "topmenu.php"; ?>
        <?php include_once "left-sidebar.php"; ?>
        <div class="content-wrapper" style="height: 780px;">
            <!-- content-page  -->
            <section class="content">
                <div class="container-fluid" id="myDiv">
                    <div class="row">
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
                        <div class="col-12">
                            <div class="card card-info">
                                <div class="card-header" style="text-align: center;">
                                    <h3 class="card-title">EA List</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>User name</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Organization</th>
                                                <th>Address</th>
                                                <th>Edge</th>
                                                <th>Phone</th>
                                                <th>Active</th>
                                                <th style="width: 50px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dem = 0;
                                            foreach ($api_result["data"] as $j => $res) {
                                                $a = string_to_aray($res['description']);
                                                // $edge = $countries["true"][$a[0]];

                                                if ($res['description'] == "[]"){
                                                    $edge = "";
                                                } else {
                                                    $a5=string_to_aray($res['description']);
                                                    foreach ($a5 as $i => $result) {
                                                        $edge_name=$countries["true"][$result];
                                                        if( $i == "0"){
                                                            $edge = $edge_name;
                                                        }else{
                                                            $edge = $edge.','.$edge_name;
                                                        }
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($dem++); ?></td>
                                                    <td><?= htmlspecialchars(($res['username'] != "") ? $res['username'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['fullname'] != "") ? $res['fullname'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['email'] != "") ? $res['email'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['organization'] != "") ? $res['organization'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['address'] != "") ? $res['address'] : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($edge != "") ? $edge : 'None'); ?></td>
                                                    <td><?= htmlspecialchars(($res['phonenumber'] != "") ? $res['phonenumber'] : 'None'); ?></td>
                                                    <?php
                                                    if ($res['description'] == "[]") {
                                                        ?><td><span class="badge badge-danger rounded-pill d-inline">InActive</span></td><?php
                                                    } else {
                                                        ?><td><span class="badge badge-success rounded-pill d-inline">Active</span></td><?php
                                                    }
                                                    ?>
                                                    <td style="text-align:center;">
                                                        <a style="width: 25px" class="fa fa-pencil" title="<?= $gettext_array['edit'] ?>" role="button" href="account_bsa_ea_edit?id=<?= $j ?>"></a>
                                                        <!-- <a style="width: 25px" class="fa fa-trash no-confirm" role="button" name="action" value="del" href="account_bsa_ea?idd=<?= $res['id'] ?>"></a> -->
                                                        <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $res['id'] ?>" ></a>
                                                        <?php 
                                                        $link="account_bsa_ea?";
                                                        $id=$res['id'];
                                                        $string='Are you sure to delete ea account "'.$res["username"].'"';
                                                        confirm_delete($link,$id,$string);
                                                        ?>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <nav class="action-buttons">
                                        <div style="text-align:right;">
                                            <a href="account_bsa_ea_edit" role="button" class="btn bg-gradient-success btn-sm">
                                                <i class="fa fa-plus icon-embed-btn"></i>
                                                <?= gettext("Add") ?>
                                            </a>
                                        </div>
                                    </nav>
                                </div>
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

</body>

<?php }
