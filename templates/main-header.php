<?php include "model/fetch_brgy_info.php"; ?>

<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" style="background-color:#0d1654;">

        <a href="dashboard.php" class="logo">
            <img src="assets/uploads/<?= $brgy_logo ?>" class="logo-img rounded-circle" width="50">
            
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
        <div class="container-fluid d-flex justify-content-end ">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-settings"></i>
                    </a>
                    <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                        <li>
                            <?php if (isset($_SESSION["role"])): ?>  
                                <?php if (role(["user"])): ?>
                                <a class="see-all" href="profile-edit.php">Edit Information<i class="icon-user"></i> </a>
                                <?php endif; ?>
                                <a class="see-all" href="model/logout.php">Sign Out<i class="icon-logout"></i> </a>
                            <?php else: ?>
                                <a class="see-all" href="login.php">Sign In<i class="icon-login"></i> </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>