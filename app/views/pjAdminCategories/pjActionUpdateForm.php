<?php
$filter = __('filter', true);
// echo "<pre>"; print_r($tpl['arr']); echo "</pre>";
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCategories&amp;action=pjActionUpdate" method="post" id="frmUpdateCategory">
	<input type="hidden" name="category_update" value="1" />
	<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
    <div class="panel-heading bg-completed">
        <p class="lead m-n"><?php __('infoUpdateCategoryTitle');?></p>
    </div><!-- /.panel-heading -->

    <div class="panel-body">
    	<?php
    	foreach ($tpl['lp_arr'] as $v)
    	{
        	?>
            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                <label class="control-label"><?php __('lblCategoryName');?></label>
                                        
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
        <?php if (SUB_CATEGORY) { ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label class="control-label"><?php echo "Main Category" //__('lblCategoryPackingFee'); ?></label>
              <div class="input-group col-xs-12">
                <select name="category_id" id="category_id" class="form-control select2-hidden-accessible">
                  <option value="0"><?php __('lblChoose'); ?></option>
                  <?php foreach ($tpl['arr']['categories'] as $category_id=>$name) { ?>
                    <option value="<?php echo $category_id; ?>"<?php echo $category_id == $tpl['arr']['category_id'] ? ' selected="selected"' : null;?>><?php echo stripslashes($name); ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label"><?php echo "Order" //__('lblCategoryPackingFee'); ?></label>
              <div class="input-group col-xs-12">
                <input type="text" name="order_no" id="order_no" class="form-control required number" maxlength="3" value="<?php echo pjSanitize::html($tpl['arr']['order']); ?>">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label"><?php __('lblCategoryPackingFee'); ?></label>
              <div class="input-group col-xs-6">
                <input type="text" name="packing_fee" id="packing_fee" class="form-control required number" maxlength="17" value="<?php echo pjSanitize::html($tpl['arr']['packing_fee']); ?>">
                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label"><?php echo "Type" //__('lblStatus'); ?></label>
              <?php $types = ['veg'=> 'Veg', 'non-veg'=> 'Non-Veg', 'both' => 'Both', 'none'=> "None"]; ?>
              <select name="product_type" id="product_type" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
                <?php foreach($types as $k => $type) { ?>
                  <option value="<?php echo $k; ?>" <?php echo $tpl['arr']['product_type'] == $k? "selected" : "" ?>><?php echo $type; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label"><?php __('lblStatus'); ?></label>
          
              <div class="clearfix">
                <div class="switch onoffswitch-data pull-left">
                  <div class="onoffswitch">
                    <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status"<?php echo $tpl['arr']['status']=='T' ? 'checked' : NULL;?>>
                    <label class="onoffswitch-label" for="status">
                      <span class="onoffswitch-inner" data-on="<?php echo $filter['active']; ?>" data-off="<?php echo $filter['inactive']; ?>"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </div>
              </div><!-- /.clearfix -->
            </div>
          </div>
        </div>

		<div class="m-t-lg">
            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                <span class="ladda-label"><?php __('btnSave'); ?></span>
                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
            </button>
            <button type="button" class="btn btn-white btn-lg pull-right pjFdBtnCancel"><?php __('btnCancel'); ?></button>
        </div><!-- /.clearfix -->
    </div><!-- /.panel-body -->
</form>