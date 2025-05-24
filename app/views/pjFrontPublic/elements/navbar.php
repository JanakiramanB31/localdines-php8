 <?php
$brandPath = PJ_INSTALL_URL . "app/web/img/frontend/localdine.png";
$show_locale = false;
if($controller->session->getData($controller->defaultLangMenu) == 'show' && isset($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
{
	$show_locale = true;
} 
?>
 <header class="header">
 	<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="pjFdBtnBrand" id="brand">
          	<img src="<?php echo $brandPath; ?>" alt="Responsive image" class="" style="height: 70px;width: 100px;">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="text-nav nav-item"><a href="#" class="pjFdBtnHome fdBtnHome" title="<?php echo __('front_menu');?>">View Menu</a></li>
            <li class="text-nav"><a href="#" class="pjFdBtnReservation">Reservation</a></li>
            <li class="text-nav"><a class="pjFdBtnAcc fdBtnAccount" href="#" title="<?php __('front_login_to_account', false, false);?>"><?php echo $controller->isFrontLogged() ? 'Profile' : 'Login' ?></a></li>
      			<?php
      			if($controller->isFrontLogged())
      			{ 
      				?>
      				<li class="text-nav"><a class="pjFdBtnAcc fdBtnLogout" href="#" title="<?php __('front_logout', false, false);?>">Logout</a></li>
      				<?php
      			} 
      			?>
			      <?php
      			if($show_locale == true)
      			{ 
      				$locale_id = $controller->pjActionGetLocale();
      				$selected_lang = '';
      				foreach ($tpl['locale_arr'] as $locale)
      				{
      					if($locale_id == $locale['id'])
      					{
      						$selected_lang = pjSanitize::html($locale['name']);
      					}
      				}
      				?>
    				<div class="btn-group pull-right pjFdLanguage" role="group" aria-label="">
    					<button type="button" class="btn btn-default dropdown-toggle pjFdBtnNav" data-toggle="dropdown" aria-expanded="false">
    						<?php echo $selected_lang;?>
    						<span class="caret"></span>
    					</button>
    					
    					<ul class="dropdown-menu text-capitalize" role="menu">
    						<?php
    						foreach ($tpl['locale_arr'] as $locale)
    						{
    							?><li><a href="#" class="fdSelectorLocale<?php echo $locale_id == $locale['id'] ? ' pjFdBtnActive' : NULL; ?>" data-id="<?php echo $locale['id']; ?>" title="<?php echo pjSanitize::html($locale['name']); ?>"><?php echo pjSanitize::html($locale['name']); ?></a></li><?php
    						} 
    						?>
    					</ul>
    				</div>
    				<?php
    			    } 
    			  ?>
            <li class="text-nav"><a href="#" class="fdContinue pjFdBtnLink">Register</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
 </header>