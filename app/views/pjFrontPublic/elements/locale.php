<?php
if(isset($_SESSION[$controller->defaultLangMenu]) && $_SESSION[$controller->defaultLangMenu] == 'show')
{
	?>
	<div class="fdLocale">
	<?php
	if (isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && !empty($tpl['locale_arr']))
	{
		?>
		<ul class="fdLocaleMenu"><?php
		$locale_id = $controller->pjActionGetLocale();
		foreach ($tpl['locale_arr'] as $locale)
		{
			?><li><a href="#" class="fdSelectorLocale<?php echo $locale_id == $locale['id'] ? ' fdLocaleFocus' : NULL; ?>" data-id="<?php echo $locale['id']; ?>" title="<?php echo pjSanitize::html($locale['name']); ?>"><img src="<?php echo PJ_INSTALL_URL . 'core/framework/libs/pj/img/flags/' . $locale['file'] ?>" alt="<?php echo htmlspecialchars($locale['name']); ?>" /></a></li><?php
		}
		?>
		</ul>
		<?php
	}
	?>
	</div>
	<?php
} 
?>