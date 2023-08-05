<section class="content">
    <div class="container-fluid" id="myDiv">
        <?php
        if ($_POST) {
            if ($input_errors) {
                print_error_box($input_errors);
            } else {
                print_success_box($a[0] = "Change Ip Address Success!");
            }
        }
        ?>
        <div class="row">
            <div class="col-10">
                <div class="nav-tabs-custom">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">WAN</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">LAN</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <div class="card card-info">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title"><b>WAN Address:</b></h3>
                             
                                    </div>
                                    <?php
                                    require_once("functions.inc");
                                    global $serverip, $token;
                                    $url1 = "http://$serverip/api/edge-EA";
                                    $url = "http://$serverip/api/edge-interfaces/" . $_SESSION['edge_id'];
                                    $api_result = call_api("GET", $token, $url, false);
                                    $api_result1 = call_api("GET", $token, $url1, false);
                                    $api = $api_result['data'][2];
                                    $api1 = $api_result1['data'][0];
                                    ?>
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Interface:</label>
                                            <!-- <div class="col-sm-2"> -->
                                            <input type="text" disabled value="<?= $api['l3_device'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Protocol:</label>
                                            <!-- <div class="col-sm-2"> -->
                                            <input type="text" disabled value="<?= $api['proto'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Ip Address:</label>
                                            <!-- <div class="col-sm-2"> -->
                                            <input type="text" disabled value="<?= $api['ipv4-address'][0]['address'] . '/' . $api['ipv4-address'][0]['mask'] ?>"></input>
                                            <!-- </div> -->
                                        </div>

                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2 control-label">Mac Address:</label>
                                            <!-- <div class="col-sm-8"> -->
                                            <input type="text" disabled value="<?= $api1['mac'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <div class="card card-info">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title"><b>LAN Address:</b></h3>
                          
                                    </div>
                                    <?php
                                    require_once("functions.inc");
                                    global $serverip, $token;
                                    $url = "http://$serverip/api/network/config/" . $edge_id;
                                    $api_result = call_api("GET", $token, $url, false);
                                    $api = $api_result['data']['lan'];
                                    ?>
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2">Ip Address:</label>
                                            <!-- <div class="col-sm-8"> -->
                                            <input type="text" disabled value="<?= $api['ipaddr'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2">NetMask:</label>
                                            <!-- <div class="col-sm-3"> -->
                                            <input type="text" disabled value="<?= $api['netmask'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="text" class="col-sm-2">Proto:</label>
                                            <!-- <div class="col-sm-4"> -->
                                            <input type="text" disabled value="<?= $api['proto'] ?>"></input>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-info">
                                    <div class="card-header with-border" style="text-align: center;">
                                        <h3 class="card-title"><b>General Configuration</b></h3>
                                    </div>

                                    <form action="network" method="post" class="form-horizontal">
                                        <input id="id" type="hidden" name="id" value="" />
                                        <div class="card-body">
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Enable:</label>

                                                <div class="col-sm-8">
                                                    <div class=" d-inline">
                                                        <input type="checkbox" id="check" name="check">
                                                        <label for="check">
                                                            Enable Interface.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Description:</label>

                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="description" id="description" placeholder="LAN">
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">IPv4 Configuration Type:</label>

                                                <div class="col-sm-8">
                                                    <select name="ipv4" id="ipv4" class="form-control select2" style="width: 100%;">
                                                        <!-- <option selected="selected"></option> -->
                                                        <option value="none">None</option>
                                                        <option value="static">Static Ipv4</option>
                                                        <option value="dhcp">DHCP</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-2 control-label">IPv6 Configuration Type:</label>

                                                <div class="col-sm-8">
                                                    <select name="ipv6" id="ipv6" class="form-control select2" style="width: 100%;">
                                                        <!-- <option selected="selected"></option> -->
                                                        <option value="none">None</option>
                                                        <option value="static6">Static Ipv6</option>
                                                        <option value="dhcp6">DHCP6</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">IP Address:</label>

                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-laptop"></i>

                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control" id="ipaddress" name="ipaddress" data-inputmask="'alias': 'ip'" data-mask>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Netmask:</label>

                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-laptop"></i>

                                                            </span>
                                                        </div>
                                                        <input type="text" id="netmask" name="netmask" class="form-control" data-inputmask="'alias': 'ip'" data-mask>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="col-sm-offset-2 col-sm-4">
                                                    <button type="submit" class="btn bg-gradient-success" name="action_int" value="save" style="width:40%">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <!-- /.card-footer -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
<script type="text/javascript">
    document.getElementById("check").checked = true;
    document.getElementById("ipv4").value = "<?= $api['proto']; ?>";
    document.getElementById("ipaddress").value = "<?= $api["ipaddr"]; ?>";
    document.getElementById("netmask").value = "<?= $api["netmask"]; ?>";
</script>