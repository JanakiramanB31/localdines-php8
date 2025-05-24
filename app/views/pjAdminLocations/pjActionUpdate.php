<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoUpdateLocationTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoUpdateLocationDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
	{
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
	    switch (true)
	    {
	        case in_array($error_code, array('AL01', 'AL03', 'AL09')):
	            ?>
				<div class="alert alert-success">
					<i class="fa fa-check m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
            case in_array($error_code, array('AL04', 'AL08')):
				?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-triangle m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
		}
	}
	?>
	<div class="tabs-container tabs-reservations m-b-lg">
		<ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionUpdate&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDetails');?></a></li>
            <li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuWorkingTime'); ?></a></li>
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $controller->_get->toInt('id');?>&tab=custom"><?php __('lblCustomerWorkingTime'); ?></a></li>
            <li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDeliveryPrices'); ?></a></li>
        </ul>
       <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="details">
                <div class="panel-body">
                   <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionUpdate" method="post" id="frmUpdateLocation" class="frmLocation"  autocomplete="off">
                   		<input type="hidden" name="location_update" value="1" />
                   		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
                   		<?php
                   		foreach ($tpl['coord_arr'] as $c)
                   		{
                   		    ?>
                			<input type="hidden" name="data[<?php echo $c['type']; ?>][<?php echo $c['id']; ?>]" value="<?php echo $c['data']; ?>" class="coords" data-type="<?php echo $c['type']; ?>" data-id="<?php echo $c['id']; ?>" />
                			<?php
                		}
                   		?>
                        <div class="row">
                            <div class="col-sm-6">
                            	<?php
                            	foreach ($tpl['lp_arr'] as $v)
                            	{
                                	?>
                                    <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                        <label class="control-label"><?php __('lblLocationName');?></label>
                                                                
                                        <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
    										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][name]" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['name'])); ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">	
    										<?php if ($tpl['is_flag_ready']) : ?>
    										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
    										<?php endif; ?>
    									</div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div><!-- /.col-sm-6 -->
    
                            <div class="col-sm-6">
                                <?php
                            	foreach ($tpl['lp_arr'] as $v)
                            	{
                                	?>
                                    <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                        <label class="control-label"><?php __('lblAddress');?></label>
                                                                
                                        <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
    										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" id="i18n_address_<?php echo $v['id'];?>" name="i18n[<?php echo $v['id']; ?>][address]" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['address'])); ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">	
    										<?php if ($tpl['is_flag_ready']) : ?>
    										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
    										<?php endif; ?>
    									</div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div><!-- /.col-sm-6 -->
                        </div><!-- /.row -->
    
                        <div class="hr-line-dashed"></div>
    
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="m-b-sm">
                                        <button type="button" class="btn btn-primary btn-outline btnGetCoords"><?php __('btnFindCoord'); ?></button>
                                    </div><!-- /.m-b-xs -->
                                </div>
    
                                <div class="m-b-md">
                                    <p class="no-margins"><small> <?php __('lblGetCoordsNotes');?></small></p>
                                </div>
                                <div class="m-b-md" id="fd_get_coords_error" style="display:none">
                                    <p class="no-margins text-danger"><small> <?php __('lblAddressNotFound');?></small></p>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblLatitude');?></label>
                                                                    
                                            <input class="form-control" type="text" id="lat" name="lat" value="<?php echo pjSanitize::html($tpl['arr']['lat']);?>">
                                        </div>
                                    </div><!-- /.col-sm-6 -->
    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblLongitude');?></label>
                                                                    
                                            <input class="form-control" type="text" id="lng" name="lng" value="<?php echo pjSanitize::html($tpl['arr']['lng']);?>">
                                        </div>
                                    </div><!-- /.col-sm-6 -->
                                </div><!-- /.row -->
                            </div><!-- /.col-sm-6 -->
    
                            <div class="col-sm-6">
                                <div id="fd_map_canvas" style="height: 250px; width: 100%;">
                                </div>
                            </div><!-- /.col-sm-6 -->
                        </div><!-- /.row -->
    
                        <div class="hr-line-dashed"></div>
    					<div class="clearfix">
                            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                                <span class="ladda-label"><?php __('btnSave'); ?></span>
                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                            </button>
                            <button type="button" class="btn btn-white btn-lg pull-left btnDeleteShape" style="display:none"><?php __('btnDeleteShape'); ?></button>
                            <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminLocations&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                        </div><!-- /.clearfix -->
                    </form>
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
    </div>
</div>
<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var myLabel = myLabel || {};
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
</script>
<?php endif; ?>