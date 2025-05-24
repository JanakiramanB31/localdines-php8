  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo.jpeg" alt="">
      </a>
      <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="<?php echo APP_HOME_URL.'index.php#hero';?>">Home<br></a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#about';?>">About</a></li>
        <li><a class="nav-link <?php echo $tpl['menu_category']=='indian'? 'active': ''; ?>" href="menu.php?cat=indian">Indian</a></li>
        <li><a class="nav-link <?php echo $tpl['menu_category']=='noodle_bar'? 'active': ''; ?>" href="menu.php?cat=noodle_bar">Noodle bar</a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#events';?>">Events<br></a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#gallery';?>">Gallery</a></li>
        <li><a href="<?php echo APP_HOME_URL.'index.php#contact';?>">Contact<br></a></li>
        <?php
          if($controller->isFrontLogged())
          { 
            ?>
            <li><a class="pjFdBtnAcc fdBtnLogout  nav-link<?php echo $tpl['menu_category'];?>  <?php echo $tpl['menu_category']==''? 'active': ''; ?>" href="" title="<?php __('front_logout', false, false);?>">Logout</a></li>
            <?php
          } else { ?>
            <li><a class="pjFdBtnAcc fdBtnLogin nav-link <?php echo $tpl['menu_category']==''? 'active': ''; ?>" href="menu.php#!/loadLogin">Login</a></li>
          <?php }
          ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>