<?php
require_once('functions.inc');
?>
<style>
    /**** back item */

    /**** */
    /**** */
    /**** */
    /**** */
    /**** */
    li.menu-open .over {
        overflow-y: scroll !important;
        width: 280px !important;
        height: 200px;
    }

    /* .sidebar-collapse .treeview-over:hover .over {
        overflow-y: scroll !important;
        height: 500px !important;
        display: block;
        width: 230px !important;
    } */

    /* .over li.nav-item{
        height: 35px;
        width: 200px;
    } */
</style>


<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../assets/images/lancsnet2.png" alt="LancsnetLogo" height="200" width="400">
</div>

<aside class="main-sidebar sidebar-light-info elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
        <img src="../assets/images/lancs.ico" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LinkSafe</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class=" image">
                <img src="../assets/images/male-user-manager-svgrepo-com.svg" class="img-rounded" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $_SESSION['fullname'] ?></a>
                <!-- <a href=""><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
        </div>

        <!-- SidebarSearch Form -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard---->
                <li class="nav-item">
                    <a href="dashboard" class="nav-link <?php isActive("dashboard") ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <!--  User Management    ---->
                <li class="nav-item <?php isActive("account_bsa_ea", "partial") ?> <?php isActive("devices_bsa_edge", "partial") ?>">
                    <a href="#" class="nav-link <?php isActive("account_bsa_ea") ?> <?php isActive("devices_bsa_edge") ?>">
                        <i class="fa fa-server" aria-hidden="true"></i>
                        <p>
                            Account Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item  <?php isActive("account_bsa_ea", "partial") ?>">
                            <a href="#" class="nav-link ">
                                <i class="fa fa-user"></i>
                                <p>Account</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="account_bsa_ea" class="nav-link item-f2 <?php isActive("account_bsa_ea") ?>">
                                        <i class="fa fa-user"></i>
                                        <p>EA</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item  <?php isActive("devices_bsa_edge", "partial") ?>">
                            <a href="#" class="nav-link ">
                                <i class="fa fa-laptop"></i>
                                <p>Device</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="devices_bsa_edge" class="nav-link item-f2 <?php isActive("devices_bsa_edge") ?>">
                                        <i class="fa fa-tablet"></i>
                                        <p>Edge</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>

                </li>
                <!--   Return   ---->
                <li class="nav-item ">
                    <?php if ($_SESSION['accounttype'] == "6") {
                    ?>
                        <a href="../redirect" class="nav-link ">
                            <i class="fa fa-rotate-left"></i>
                            <p>
                                Return HSA Dashboard
                                <!-- <i class="right fas fa-angle-left"></i> -->
                            </p>
                        </a>


                    <?php
                    }  else if ($_SESSION['accounttype'] == "9" || $_SESSION['accounttype'] == "8") { ?>

                        <a href="../redirect" class="nav-link ">
                            <i class="fa fa-rotate-left"></i>
                            <p>
                                Return EA Dashboard
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                    <?php } ?>

                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    var parent = $("ul.sidebar-menu li.active").closest("ul").closest("li");
    if (parent[0] != undefined)
        $(parent[0]).addClass("active");
</script>