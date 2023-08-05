<?php
require_once('functions.inc');

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
                <li class="nav-item <?php isActive("tech", "partial") ?> <?php isActive("business", "partial") ?> <?php isActive("customer", "partial") ?>">
                    <a href="#" class="nav-link active">
                        <i class="fa fa-handshake" aria-hidden="true"></i>
                        <p>
                            Account List
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="account?id=ssa-tech" class="nav-link <?php isActive("tech") ?> ">
                                <i class="fa fa-circle-o"></i>
                                <p>SSA-Tech</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account?id=ssa-business" class="nav-link <?php isActive("business") ?>">
                                <i class="fa fa-circle-o" aria-hidden="false"></i>
                                <p>SSA-Business</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account?id=ssa-customer" class="nav-link <?php isActive("customer") ?> ">
                                <i class="fa fa-circle-o" aria-hidden="false"></i>
                                <p>SSA-Customer</p>
                            </a>
                        </li>

                    </ul>

                </li>
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
    var parent = $("ul.sidebar-menu li.active").closest("li");
    if (parent[0] != undefined)
        $(parent[0]).addClass("active");
</script>