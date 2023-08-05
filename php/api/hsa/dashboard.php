<?php
$active_menu = "dashboard_hsa";
require_once("functions.inc");
check_session("/api");
$checks = check_permission_hsa(6);
if ($checks == true) {
    global $token, $serverip;
    include_once "head.php";
    if ($_SESSION['active'] == null) {
        $_SESSION['active'] = true;
    }
?>
    <style>
        p {
            text-align: center;
        }
    </style>


    <style>
        .content-wrapper {
            height: auto;
        }
    </style>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <?php include_once "topmenu.php"; ?>
            <?php include_once "systems.php"; ?>
            <?php include_once "left-sidebar.php"; ?>
            <div class="content-wrapper">
                <?php include_once("dashboard_header.php") ?>

            </div>
        </div>

        <?php include_once "../copyright.php"; ?><?php include_once "../footer.php"; ?>

        </div>

        <?php include_once "../src.php"; ?>
        <div class="control-sidebar-bg"></div>
        </div>
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
<?php }
