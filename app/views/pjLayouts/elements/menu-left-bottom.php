<?php
$controller_name = $controller->_get->toString('controller');
$action_name = $controller->_get->toString('action');

$isScriptPreview = $controller_name == 'pjAdminOptions' && $action_name == 'pjActionPreview';
$isScriptInstall = $controller_name == 'pjAdminOptions' && $action_name == 'pjActionInstall';

//$hasAccessScriptPreview = pjAuth::factory('pjAdminOptions', 'pjActionPreview')->hasAccess();
$hasAccessScriptPreview = false;
//$hasAccessScriptInstall = pjAuth::factory('pjAdminOptions', 'pjActionInstall')->hasAccess();
$hasAccessScriptInstall = false;
?>
<?php if ($hasAccessScriptPreview): ?>
    <li<?php echo $isScriptPreview ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPreview"><i class="fa fa-eye"></i> <span class="nav-label"><?php __('menuPreview');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptInstall): ?>
    <li<?php echo $isScriptInstall ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall"><i class="fa fa-code"></i> <span class="nav-label"><?php __('menuIntegrationCode');?></span></a>
    </li>
<?php endif; ?>
<li id="nav-item-sms"><a id="popover" href="#" data-toggle="popover" title="API not set" data-content="Please set api in > System Options > SMS settings <a href='#'></a>" data-html="true" data-placement="top" data-trigger="hover">
    <i class="fa fa-comments-o" aria-hidden="true"></i>
    <span class="nav-label"><?php echo "Sms.bizcall";?></span>
    <span id="credits_count" data-api-set="false" class="nav-label" style="display: inline-block;padding: 3px 8px;border-radius: 50%;background-color: #fc801c;color: #fff;">0</span>
</a></li>