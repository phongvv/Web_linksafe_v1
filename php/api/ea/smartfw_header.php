<section class="content">
    <div class="container-fluid" id="myDiv">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header" style="text-align: center;">
                        <h3 class="card-title"><b>Smart Firewall Rule List</b></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?= gettext("Rule Name") ?></th>
                                    <th><?= gettext("Rule Action") ?></th>
                                    <th><?= htmlspecialchars("Source Type"); ?></th>
                                    <th><?= htmlspecialchars("Source Name"); ?></th>
                                    <th><?= htmlspecialchars("Destination Type"); ?></th>
                                    <th><?= htmlspecialchars("Destination Name"); ?></th>
                                    <!-- <th><?= gettext("Status") ?></th> -->
                                    <th style="text-align:center;"><?= gettext("Actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                            require_once("functions.inc");
                            global $serverip,$token;
                            $action = array(
                                "0" => "Permit",
                                "1" => "Block",
                                "2" => "QoS"
                            );
                            $stype = array(
                                "0" => "End Device",
                                "1" => "End User",
                                "2" => "Group End User",
                                "3" => "Ip&Port"
                            );
                            $dtype = array(
                                "0" => "Web Categories",
                                "1" => "Web",
                                "2" => "App Categories",
                                "3" => "App",
                                "4" => "Proto Categories",
                                "5" => "Proto",
                                "6" => "Ip,Port&Proto"
                            );
                            $url = "http://$serverip/api/web-app/rules/".$edge_id;
                            $api_result = call_api("GET",$token,$url,false);
                            $api = $api_result['data']['values'];
                            foreach($api as $i => $result) {
                                if(!empty($result)) {
                                    if ($result['type'] == "qos"){
                                        list($name) = explode("_bandwidth",$result['.name']);
                                    } else {
                                        list($name) = explode("_firewall",$result['.name']);
                                        https://www.getpostman.com/collections/f94285b06761d34bd343          }
                            ?>
                            <tr>
                                <td><?=htmlspecialchars($name);?></td>
                                <td><?=htmlspecialchars($result['type']);?></td>
                                    <?php
                                if (isset($result['device'])) {
                                    ?>
                                    <td><?=htmlspecialchars($stype[0]);?></td>
                                    <td><?=htmlspecialchars($result['device']);?></td>
                                    <?php
                                } else if (isset($result['user'])) {
                                    ?>
                                    <td><?=htmlspecialchars($stype[1]);?></td>
                                    <td><?=htmlspecialchars(get_one_user($result['user']));?></td>
                                    <?php
                                } else if (isset($result['group'])) {
                                    ?>
                                    <td><?=htmlspecialchars($stype[2]);?></td>
                                    <td><?=htmlspecialchars($tet = get_one_group($result['group']));?></td>
                                    <?php
                                } else if (isset($result['src'])) {
                                    ?>
                                    <td><?=htmlspecialchars($stype[3]);?></td>
                                    <td><?=htmlspecialchars($result['src']);?></td>
                                    <?php
                                } else if (isset($result['sport'])) {
                                    ?>
                                    <td><?=htmlspecialchars($stype[3]);?></td>
                                    <td><?=htmlspecialchars($result['sport']);?></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><?=htmlspecialchars("Any");?></td>
                                    <td><?=htmlspecialchars("Any");?></td>
                                <?php }
                                if (isset($result['web_cat'])) {
                                    $web_cat = show_web_cat();
                                    foreach($web_cat as $tt) {
                                        if ($result['web_cat'] == $tt['id']) {
                                            $webcat = $tt['name'];
                                            break;
                                        }
                                    }
                                    ?>
                                <td><?=htmlspecialchars($dtype[0]);?></td>
                                <td><?=htmlspecialchars($webcat);?></td>
                                    <?php
                                } else if (isset($result['url'])) {
                                    ?>
                                    <td><?=htmlspecialchars($dtype[1]);?></td>
                                    <td><?=htmlspecialchars($result['url']);?></td>
                                    <?php
                                } else if (isset($result['app_cat'])) {
                                    $app_cat = show_app_cat();
                                    foreach($app_cat as $tt) {
                                        if ($result['app_cat'] == $tt['id']) {
                                            $appcat = $tt['name'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <td><?=htmlspecialchars($dtype[2]);?></td>
                                    <td><?=htmlspecialchars($appcat);?></td>
                                    <?php
                                } else if (isset($result['app'])) {
                                    ?>
                                    <td><?=htmlspecialchars($dtype[3]);?></td>
                                    <td><?=htmlspecialchars($result['app']);?></td>
                                    <?php
                                } else if (isset($result['proto_cat'])) {
                                    $proto_cat = show_proto_cat();
                                    foreach($proto_cat as $tt) {
                                        if ($result['proto_cat'] == $tt['id']) {
                                            $protocat = $tt['description'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <td><?=htmlspecialchars($dtype[4]);?></td>
                                    <td><?=htmlspecialchars($protocat);?></td>
                                    <?php
                                } else if (isset($result['proto'])) {
                                    ?>
                                    <td><?=htmlspecialchars($dtype[5]);?></td>
                                    <td><?=htmlspecialchars($result['proto']);?></td>
                                    <?php
                                } else if (isset($result['dest'])) {
                                    ?>
                                    <td><?=htmlspecialchars($dtype[6]);?></td>
                                    <td><?=htmlspecialchars($result['dest']);?></td>
                                    <?php
                                } else if (isset($result['tcp_udp'])) {
                                    ?>
                                    <td><?=htmlspecialchars($dtype[6]);?></td>
                                    <td><?=htmlspecialchars($result['tcp_udp']);?></td>
                                    <?php
                                } else {
                                ?>
                                <td><?=htmlspecialchars("Any");?></td>
                                <td><?=htmlspecialchars("Any");?></td>
                                <?php }
                                ?>
                                <td>
                                    <a style="width: 25px" class="fa fa-pen"	title="<?=$gettext_array['edit']?>"	role="button" href="create_smartfirewall?id=<?=$i?>" ></a>
                                    <!-- <a class="fa fa-trash no-confirm"	title="<?=$gettext_array['del']?>"	role="button" href="network?id=smartfw&&idd=<?=$result['.name']?>&&di=<?=$result['.index']?>"></a> -->

                                    <a style="width: 25px" class="fa fa-trash no-confirm" role="button" title="DELETE" data-toggle="modal" data-target="#myModal<?= $result['.name'] ?>" ></a>
                                        <?php 
                                        $link='network?id=smartfw&&di='.$result['.name'].'&&di='.$result['.index'].'&&';
                                        $id=$i;
                                        $string='Are you sure to delete Smart Firewall Rule "'.$name.'"';
                                        confirm_delete($link,$id,$string);
                                        ?>
                                </td>
                            </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <nav class="action-buttons">
                        <div style="text-align:right;">
                            <a  href="create_smartfirewall" role="button" class="btn bg-gradient-success btn-sm">
                                <i class="fa fa-plus icon-embed-btn"></i>
                                Add</a>
                        </div>
                    </nav>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>