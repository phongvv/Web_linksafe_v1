<?php
require_once "functions.inc";
?>
<style>
    /**** back item */
    .nav-treeview .nav-link {
        padding-left: 40px;
    }

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
                <li class="nav-item ">
                    <a href="edge" class="nav-link  <?php isActive("Edge")  ?>">
                        <i class="fas fa-chart-pie" aria-hidden="true"></i>
                        <p>
                            Edge Management
                        </p>
                    </a>
                </li>
                <!--  User Management    ---->

                <?php
                if ($_SESSION['accounttype'] == 9) {
                ?>
                    <li class="nav-item <?php isActive("group", "partial") ?> <?php isActive("user", "partial") ?> <?php isActive("device", "partial") ?>">
                        <a href="#" class="nav-link <?php isActive("group") ?> <?php isActive("user") ?> <?php isActive("device") ?>">
                            <i class="fas fa-chart-pie" aria-hidden="true"></i>
                            <p>
                                User Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="account?id=group" class="nav-link <?php isActive("group") ?>">
                                    <i class="fa fa-users"></i>
                                    <p>Group</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="account?id=user" class="nav-link <?php isActive("user") ?>">
                                    <i class="fa fa-user"></i>
                                    <p>User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="account?id=device" class="nav-link <?php isActive("device") ?>">
                                    <i class="fa fa-desktop"></i>
                                    <p>Device</p>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!--  Scenario & Policy    ---->
                    <li class="nav-item <?php isActive("scenario", "partial") ?> <?php isActive("policy", "partial") ?>">
                        <a href="#" class="nav-link <?php isActive("scenario") ?> <?php isActive("policy") ?>">
                            <i class="fa fa-handshake" aria-hidden="true"></i>
                            <p>
                                Scenario & Policy
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="scenario" class="nav-link <?php isActive("scenario") ?>">
                                    <i class="fa fa-book"></i>
                                    <p>Scenario</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="create_policy" class="nav-link <?php isActive("policy") ?>">
                                    <i class="fa fa-newspaper" aria-hidden="false"></i>
                                    <p>Create Policy</p>
                                </a>
                            </li>

                        </ul>

                    </li>
                <?php
                }
                ?>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
    <div class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">
        <div class="nav-item dropdown">
            <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Close</a>
            <div class="dropdown-menu mt-0">
                <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">Close All</a>
                <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">Close All Other</a>
            </div>
        </div>
        <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft"><i class="fas fa-angle-double-left"></i></a>
        <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
        <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright"><i class="fas fa-angle-double-right"></i></a>
        <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen"><i class="fas fa-expand"></i></a>
    </div>
    <div class="tab-content">
        <div class="tab-empty">
            <h2 class="display-4">No tab selected!</h2>
        </div>
        <div class="tab-loading">
            <div>
                <h2 class="display-4">Tab is loading <i class="fa fa-sync fa-spin"></i></h2>
            </div>
        </div>
    </div>
</div> -->

<script>
    var parent = $("ul.sidebar-menu li.active").closest("ul").closest("li");
    if (parent[0] != undefined)
        $(parent[0]).addClass("active");
</script>