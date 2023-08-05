<?php
  require_once('functions.inc');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $avatar ?>" class="img-rounded" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?=$_SESSION["fullname"]?></p>
                <i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
    </section>
    <!-- /.sidebar -->
</aside>
<script>
    var parent = $("ul.sidebar-menu li.active").closest("ul").closest("li");
    if (parent[0] != undefined)
        $(parent[0]).addClass("active");
</script>