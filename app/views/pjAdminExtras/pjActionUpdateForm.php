<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionUpdate" method="post" id="frmUpdateExtra">
	<input type="hidden" name="extra_update" value="1" />
	<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
    <div class="panel-heading bg-completed">
        <p class="lead m-n"><?php __('infoUpdateExtraTitle');?></p>
    </div><!-- /.panel-heading -->

    <div class="panel-body">
    	<?php
    	foreach ($tpl['lp_arr'] as $v)
    	{
        	?>
            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                <label class="control-label"><?php __('lblExtraName');?></label>
                                        
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
        <div class="form-group">
            <label class="control-label"><?php __('lblPrice'); ?></label>
        
            <div class="input-group">
                <input type="text" name="price" value="<?php echo pjSanitize::html($tpl['arr']['price']);?>" class="form-control text-right number required" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>">

                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
            </div>
        </div>
        <?php if (EXTRA_CATEGORY) { ?>
           <div class="form-group">
            <label class="control-label"><?php echo 'Category'; ?></label>
              <select name="category_id" id="category_id" class="form-control select-item" data-placeholder="-- <?php __('lblChoose'); ?> --" >
                <option value="">--Choose--</option>
                <?php foreach(EXTRA_CATEGORY_TYPES as $key=>$value) { ?>
                  <option value="<?php echo ++$key;?>" <?php echo ($tpl['arr']['category_id'] == $key )? "selected":""; ?>><?php echo $value; ?></option>
                <?php } ?>
              </select>
          </div>
         <?php } ?>
        <?php
        if(!empty($tpl['product_arr']))
        {
            ?>
            <div class="form-group">
                <label class="control-label"><?php __('lblProducts'); ?>:</label>
            
                <select name="product_id[]" id="product_id" class="form-control select-item" data-placeholder="-- <?php __('lblChoose'); ?> --" multiple>
    				<?php
    				foreach ($tpl['product_arr'] as $v)
    				{
    					?><option value="<?php echo $v['id']; ?>"<?php echo in_array($v['id'], $tpl['extra_product_id_arr']) ? ' selected="selected"' : NULL;?>><?php echo pjSanitize::html($v['name']); ?></option><?php
    				}
    				?>
    			</select>
            </div>
            <?php
        }else{
            $text = __('lblAddProductText', true);
            $text = str_replace("{STAG}", '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjAdminProducts&amp;action=pjActionCreate">', $text);
            $text = str_replace("{ETAG}", '</a>', $text);
            ?>
            <div class="form-group">
                <label class="control-static-label"><?php echo $text; ?></label>
            </div>
            <?php
        }
        ?>
		<div class="m-t-lg">
            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                <span class="ladda-label"><?php __('btnSave'); ?></span>
                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
            </button>
            <button type="button" class="btn btn-white btn-lg pull-right pjFdBtnCancel"><?php __('btnCancel'); ?></button>
        </div><!-- /.clearfix -->
    </div><!-- /.panel-body -->
</form>