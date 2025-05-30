  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.jpeg" alt=""> -->
        <h1 class="site-name">Namaste India</h1>
      </a>
      <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="<?php echo APP_HOME_URL.'index.php#hero';?>">Home<br></a></li>
        <li><a class="nav-link active" href="menu.php">Menu</a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#events';?>">Events<br></a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#gallery';?>">Gallery</a></li>
        <?php
          if($controller->isFrontLogged())
          { 
            ?>
            <li><a class="nav-link exclude" href="#!/loadProfile">Profile</a></li>
            <li><a class="nav-link exclude" href="#!/loadMyOrders">My Orders</a></li>
            <li><a class="pjFdBtnAcc fdBtnLogoutt nav-link exclude" href="#" title="<?php __('front_logout', false, false);?>">Logout</a></li>
            <!-- <div class="dropdown">
              <a role="button" class="bi bi-person nav-profile dropdown-toggle" data-bs-toggle="dropdown" id="dropdownMenuLink">
                
              </a>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item" href="#!/loadProfile">Profile</a></li>
                <li><a class="dropdown-item" href="#!/loadMyOrders">My Orders</a></li>
                <li><a class="pjFdBtnAcc fdBtnLogoutss  nav-link" href="" title="<?php __('front_logout', false, false);?>">Logout</a></li>
              </ul>
            </div> -->
            <?php
          } else { ?>
            <li><a class="pjFdBtnAcc fdBtnLogin nav-link exclude" href="menu.php#!/loadLogin">Login</a></li>
          <?php }
          ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>
<!-- End Header -->