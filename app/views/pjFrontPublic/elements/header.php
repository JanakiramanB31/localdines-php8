<?php
$show_locale = false;
if($controller->session->getData($controller->defaultLangMenu) == 'show' && isset($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
{
	$show_locale = true;
} 
?>
<div class="panel-heading pjFdPanelHead">
	<div class="row">
		<div class="<?php echo $show_locale == true ? 'col-lg-6 col-md-6 col-sm-6 col-xs-12' : 'col-lg-8 col-md-8 col-sm-8 col-xs-8'?>">
			<a href="#" class="btn btn-default pjFdBtnHome fdBtnHome" title="<?php echo __('front_menu');?>" style="display: none;">
				<i class="fa fa-cutlery fa-3"></i>
			</a>
			
			&nbsp;&nbsp;
			<?php
			$action = $controller->_get->toString('action');
			$menu_label = __('front_menu', true);
			switch ($action) {
				case 'pjActionMain':
					$menu_label = __('front_menu', true);
					break;
				case 'pjActionLogin':
					$menu_label = __('front_login_to_account', true, false);
					break;
				case 'pjActionTypes':
					$menu_label = __('front_order_details', true, false);
					break;
				case 'pjActionVouchers':
					$menu_label = __('front_order_total', true, false);
					break;
				case 'pjActionCheckout':
					$menu_label = __('front_payment', true, false);
					break;
				case 'pjActionPreview':
					$menu_label = __('front_confirm_order', true, false);
					break;
			}
			$menu_items = __('front_menu_titles', true);
			?>	
			<div class="btn-group pjFdNav">
				<button type="button" class="btn btn-default dropdown-toggle text-capitalize pjFdBtnNav" data-toggle="dropdown" aria-expanded="false"><?php echo $menu_label;?> <span class="caret"></span></button>
				<ul class="dropdown-menu text-uppercase" role="menu">
					<li><a href="#" data-load="loadMain" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionMain')) ? ' pjFdBtnActive' : (in_array($action, array('pjActionLogin', 'pjActionForgot', 'pjActionTypes', 'pjActionVouchers', 'pjActionCheckout', 'pjActionPreview')) ? ' pjFdBtnPassed' : ' disabled') ; ?>"><?php echo $menu_items[1];?></a></li>
					<li><a href="#" data-load="loadLogin" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionLogin')) ? ' pjFdBtnActive' : (in_array($action, array('pjActionForgot', 'pjActionTypes', 'pjActionVouchers', 'pjActionCheckout', 'pjActionPreview')) ? ' pjFdBtnPassed' : ' disabled'); ?>"><?php echo $menu_items[2];?></a></li>
					<li><a href="#" data-load="loadTypes" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionTypes')) ? ' pjFdBtnActive' : (in_array($action, array('pjActionVouchers', 'pjActionCheckout', 'pjActionPreview')) ? ' pjFdBtnPassed' : ' disabled'); ?>"><?php echo $menu_items[3];?></a></li>
					<li><a href="#" data-load="loadVouchers" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionVouchers')) ? ' pjFdBtnActive' : (in_array($action, array('pjActionCheckout', 'pjActionPreview')) ? ' pjFdBtnPassed' : ' disabled'); ?>"><?php echo $menu_items[4];?></a></li>
					<li><a href="#" data-load="loadCheckout" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionCheckout')) ? ' pjFdBtnActive' : (in_array($action, array('pjActionPreview')) ? ' pjFdBtnPassed' : ' disabled'); ?>"><?php echo $menu_items[5];?></a></li>
					<li><a href="#" data-load="loadPreview" class="btn btn-link pjFdBtnMenu pjFdBtn<?php echo in_array($action, array('pjActionPreview')) ? ' pjFdBtnActive' : ' disabled'; ?>"><?php echo $menu_items[6];?></a></li>
				</ul>
			</div><!-- /.btn-group pjFdNav -->
		</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
		<div class="pjFdHeaderRight<?php echo $show_locale == true ? ' col-lg-6 col-md-6 col-sm-6 col-xs-12' : ' col-lg-4 col-md-4 col-sm-4 col-xs-4'?>" style="display: none;">
			<?php
			if($controller->isFrontLogged())
			{ 
				?>
				<a class="btn btn-default pull-right pjFdBtnAcc fdBtnLogout" href="#" role="button" title="<?php __('front_logout', false, false);?>"><i class="fa fa-sign-out"></i></a>
				<?php
			} 
			?>
			<a class="btn btn-default pull-right pjFdBtnAcc fdBtnAccount" href="#" role="button" title="<?php __('front_login_to_account', false, false);?>"><i class="fa fa-<?php echo $controller->isFrontLogged() ? 'user' : 'lock';?>"></i></a>
			
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
		</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
	</div><!-- /.row -->
</div><!-- /.panel-heading pjFdPanelHead -->