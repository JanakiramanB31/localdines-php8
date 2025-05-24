<div class="row wrapper border-bottom white-bg page-heading">
    <!-- <?php //print_r($tpl['arr']['i18n']); ?>  -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoUpdateProductTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoUpdateProductDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
      // echo "<pre>"; print_r($tpl['category_id_arr']); echo "</pre>";
      // echo "<pre>"; print_r($tpl['category_types']); echo "</pre>";
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code)) {
  	    $titles = __('error_titles', true);
  	    $bodies = __('error_bodies', true);
  	    switch (true) {
	        case in_array($error_code, array('AP01', 'AP03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
  				break;
          case in_array($error_code, array('AP04', 'AP05', 'AP08', 'AP09', 'AP10')):
                    $bodies_text = str_replace("{SIZE}", ini_get('upload_max_filesize'), @$bodies[$error_code]);
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo $bodies_text;?>
    				</div>
    				<?php
  				break;
    		}
    	}
    	?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionUpdate" method="post" id="frmUpdateProduct" autocomplete="off" enctype="multipart/form-data">
            		<input type="hidden" name="product_update" value="1" />
            		<input type="hidden" id="index_arr" name="index_arr" value="" />
            		<input type="hidden" id="remove_arr" name="remove_arr" value="" />
            		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
                    <input type="hidden" id="page" name="page" value="<?php echo $tpl['page']?>" />
                    <input type="hidden" id="category" name="category" value="<?php echo $tpl['category']?>" />
                    <div class="row">
                        <div class="col-sm-6">
                        	<?php
                        	foreach ($tpl['lp_arr'] as $v)
                        	{
                            	?>
                                <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                    <label class="control-label"><?php __('lblName');?></label>
                                                            
                                    <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['name'])); ?>" name="i18n[<?php echo $v['id']; ?>][name]" data-msg-required="<?php __('fd_field_required', false, true);?>">	
										<?php if ($tpl['is_flag_ready']) : ?>
										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
										<?php endif; ?>
									</div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="form-group">
                                <label class="control-label"><?php __('lblCategory'); ?></label>

                                <select name="category_id[]" id="category_id" multiple="multiple" size="5" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
            						<?php
            						foreach ($tpl['category_arr'] as $v)
            						{
            							?><option value="<?php echo $v['id']; ?>"<?php echo in_array($v['id'], $tpl['category_id_arr']) ? ' selected="selected"' : null;?>><?php echo stripslashes($v['name']); ?></option><?php
            						}
            						?>
            					</select>
                            </div><!-- /.form-group -->     

                            <div class="row">
                                <div class="col-lg-8 col-md-7">
                                	<?php
                                	if(!empty($tpl['extra_arr']))
                                	{
                                    	?>
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblExtras'); ?></label>
    
                                            <select name="extra_id[]" id="extra_id" multiple="multiple" size="5" class="form-control" data-placeholder="-- <?php __('lblChoose'); ?> --">
                        						<?php
                        						foreach ($tpl['extra_arr'] as $v)
                        						{
                        							?><option value="<?php echo $v['id']; ?>"<?php echo in_array($v['id'], $tpl['extra_id_arr']) ? ' selected="selected"' : null;?>><?php echo stripslashes($v['name']); ?> (<?php echo pjCurrency::formatPrice($v['price']); ?>)</option><?php
                        						}
                        						?>
                        					</select>
                                        </div><!-- /.form-group -->
                                        <?php
                                	}else{
                                	    ?>
                                	    <div class="form-group">
                                	    	<label class="control-label"><?php __('lblExtras'); ?></label>
                                            <p class="form-control-static"><?php echo __('lblNoExtrasFound', true, false);?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex&create=1"><?php __('lblHere');?></a></p>
                                        </div>
                                	    <?php
                                	}
                                    ?> 
                                </div><!-- /.col-sm-9 -->

                                <div class="col-lg-4 col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Hot Key<?php //__('lblFeaturedProduct'); ?></label>

                                        <div class="switch">
                                            <div class="onoffswitch onoffswitch-data">
                                                <input type="checkbox" class="onoffswitch-checkbox" name="is_featured" id="is_featured"<?php echo $tpl['arr']['is_featured']==1 ? ' checked': NULL; ?>>
                                                <label class="onoffswitch-label" for="is_featured">
                                                    <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- /.form-group -->
                                </div><!-- /.col-sm-9 -->
                            </div><!-- /.row -->

                            <div class="form-group">
                                <label class="control-label"><?php __('lblImage', false, true); ?></label>

                                <div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
                                        <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="image" type="file"></span>
                                        <span class="fileinput-filename"></span>

                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                    </div>
                                    <div>
                                        <p><b>Size of Product Image:</b> 270 * 220</p>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                            <?php 
							if (!empty($tpl['arr']['image']) && is_file(PJ_INSTALL_PATH . $tpl['arr']['image']))
							{
								?>
								<div id="boxExtraImage" class="form-group">
									<img src="<?php echo PJ_INSTALL_URL . $tpl['arr']['image']; ?>?r=<?php echo rand(1,9999); ?>" alt="" class="align_middle" style="max-width: 180px; margin-right: 10px;">
									<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionDeleteImage&amp;id=<?php echo pjSanitize::html($tpl['arr']['id']);?>" class="btn btn-xs btn-danger btn-outline btnDeleteImage" data-id="<?php echo pjSanitize::html($tpl['arr']['id']);?>"><i class="fa fa-trash"></i> <?php __('plugin_app_management_btn_delete'); ?></a>
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
                                    <label class="control-label"><?php __('lblDescription');?></label>
                                                            
                                    <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
                                    	<textarea class="form-control form-control-lg" name="i18n[<?php echo $v['id']; ?>][description]" data-msg-required="<?php __('fd_field_required', false, true);?>"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['description'])); ?></textarea>
										<?php if ($tpl['is_flag_ready']) : ?>
										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
										<?php endif; ?>
									</div>
                                </div>
                                <?php
                            }
                            ?>

                           <!-- MEGAMIND -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label class="control-label"><?php echo "Preparation time"; ?></label>
                                        
                                        <select class="form-control select2" name="preparation_time"> 
                                            <option value="">Preparation Time</option> 
                                            <?php for ($minutes = 5; $minutes <= 60; $minutes=$minutes+5) { ?>
                                                <option value="<?php echo $minutes; ?>" <?php echo $tpl['arr']['preparation_time'] == $minutes ? "selected":""; ?>><?php echo $minutes; ?></option>
                                            <?php } ?>

                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label class="control-label"><?php echo "Status"; ?></label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="radEnabled" value="1" <?php echo $tpl['arr']['status'] == 1 ? "checked":""; ?> >Enabled
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="radDisabled" value="0" <?php echo $tpl['arr']['status'] == 0 ? "checked":""; ?> > Disabled
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                           <!-- MEGAMIND -->

                        </div><!-- /.col-sm-6 -->
                         <div class="col-sm-3">
                            <div class="form-group" data-index="<?php echo $v['id']; ?>">
                                <label class="control-label"><?php echo "Product Number"; ?></label>
                                <div>
                                    <input type="number" class="form-control col-sm-6" name="counter_number" id="counter_number" value="<?php echo $tpl['arr']['counter_number']; ?>" />
                                </div>
                            </div>
                         </div>
                    </div><!-- /.row -->

                    <!-- Added by JR -->
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                      <div class="col-lg-2 col-md-2 col-xs-3">
                        <div class="form-group">
                          <label class="control-label"><?php echo "Kitchen Item"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_kitchen" id="is_kitchen" <?php echo $tpl['arr']['is_kitchen'] == 1 ? 'checked' : NULL;?>>
                                <label class="onoffswitch-label" for="is_kitchen">
                                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                  <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 col-md-2 col-xs-3">
                        <div class="form-group">
                          <label class="control-label"><?php echo "Web Orderable"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_web_orderable" id="is_web_orderable" <?php echo $tpl['arr']['is_web_orderable'] == 1 ? 'checked' : NULL;?>>
                                <label class="onoffswitch-label" for="is_web_orderable">
                                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                  <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                            <span>It can't be ordered via web </span>
                          </div>
                        </div>
                      </div>
                      <div id="is_veg_div" class="col-lg-2 col-md-2 col-xs-3 <?php echo $tpl['category_types']['status'] && $tpl['arr']['is_veg'] != 1 ? "d-none" : '' ?>">
                        <div class="form-group">
                          <label class="control-label"><?php echo "Veg Item"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_veg" id="is_veg" <?php echo $tpl['arr']['is_veg'] == 1 ? 'checked' : NULL;?>>
                                <label class="onoffswitch-label" for="is_veg">
                                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                  <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 col-md-2 col-xs-3">
                        <div class="form-group">
                          <label class="control-label"><?php echo "VAT"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_vat" id="is_vat" <?php echo $tpl['arr']['is_vat'] == 1 ? 'checked' : NULL;?>>
                                <label class="onoffswitch-label" for="is_vat">
                                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                  <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php if (ORDER_TYPE) { ?>
                      <div class="col-lg-2 col-md-2 col-xs-3">
                        <div class="form-group">
                          <label class="control-label"><?php echo "Type"  ?></label>
                          <select class="form-control select2" name="order_type"> 
                            <option value="eatin" <?php echo $tpl['arr']['order_type'] == 'eatin' ? "selected":""; ?>>Eatin</option> 
                            <option value="takeaway" <?php echo $tpl['arr']['order_type'] == 'takeaway' ? "selected":""; ?>>Takeaway</option>
                        </select>
                        </div>
                      </div>
                    <?php } ?>
                    </div>
                    <!-- Added by JR End -->

                    <div class="hr-line-dashed"></div>

                    <h2><?php __('lblPrice'); ?></h2>

                    <p class="alert alert-info alert-with-icon m-t-xs"><i class="fa fa-info-circle"></i> <?php __('lblProductPriceDesc');?></p>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-xs-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblPriceBasedOnSize'); ?></label>

                                <div class="switch">
                                    <div class="onoffswitch onoffswitch-data onoffswitch-size">
                                        <input type="checkbox" class="onoffswitch-checkbox" id="set_different_sizes" name="set_different_sizes" value="T"<?php echo $tpl['arr']['set_different_sizes']=='T'? 'checked' : NULL;?>>
                                        <label class="onoffswitch-label" for="set_different_sizes">
                                            <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
            
                    <div class="row order-size-field" style="display:<?php echo $tpl['arr']['set_different_sizes']=='T' ? 'none' : '';?>;">
                        <div class="col-lg-3 col-md-4 col-xs-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblPrice'); ?></label>

                                <div class="input-group pjFdProductPrice">
                                    <input type="text" id="price" name="price" value="<?php echo pjSanitize::html($tpl['arr']['price']);?>" class="form-control pj-field-price<?php echo $tpl['arr']['set_different_sizes']=='F' ? ' required number' : '';?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>

                                    <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
					
                    <div class="row order-size-table" style="display:<?php echo $tpl['arr']['set_different_sizes']=='F' ? 'none' : 'block';?>;">
                        <div class="col-sm-5">
                            <div class="">
                                <div class="table-responsive table-responsive-secondary">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th><?php __('lblSize');?></th>
                                                <th><?php __('lblPrice');?></th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody id="fd_size_list">
                                        	<?php
                                        	if(isset($tpl['size_arr']))
                                        	{
                                        	    foreach($tpl['size_arr'] as $k => $size)
                                        	    {
                                        	        $index = $size['id'];
                                                	?>
                                                    <tr class="fd-size-row" data-index="<?php echo $index;?>">
                                                        <td class="fd-title-<?php echo $index;?>"><?php __('lblSize'); ?> <?php echo $k+1;?></td>
                                                        
                                                        <td>
                                                            <?php
                                                        	foreach ($tpl['lp_arr'] as $v)
                                                        	{
                                                            	?>
                                                                <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                                                    <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
                                										<input type="text" name="i18n[<?php echo $v['id']; ?>][price_name][<?php echo $index;?>]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired' . ($tpl['arr']['set_different_sizes']=='T' ? ' required': '') ; ?>" lang="<?php echo $v['id']; ?>" value="<?php echo htmlspecialchars(stripslashes(@$size['i18n'][$v['id']]['price_name'])); ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>	
                                										<?php if ($tpl['is_flag_ready']) : ?>
                                										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
                                										<?php endif; ?>
                                									</div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
        
                                                        <td>
                                                        	<div class="form-group">
                                                                <div class="input-group pjFdProductPrice">
                                                                    <input type="text" name="product_price[<?php echo $index;?>]" value="<?php echo $size['price']; ?>" class="form-control pj-field-price<?php echo $tpl['arr']['set_different_sizes']=='T' ? ' required number': '';?>"/>
            
                                                                    <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
                                                                </div>
                                                            </div>
                                                        </td>
        
                                                        <td>
                                                        	<?php
                                                        	if($k > 0)
                                                        	{
                                                        	    ?>
                                                        	    <div class="m-t-xs text-right">
                                                                    <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-size"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                        	    <?php
                                                        	}else{
                                                        	    echo '&nbsp;';
                                                        	}
                                                        	?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                        	    }
                                        	}
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.col-sm-7 -->

                        <div class="col-sm-5">
                            <div class="m-t-lg">
                                <a href="#" class="btn btn-primary btn-outline m-t-xs pj-add-size"><i class="fa fa-plus"></i> <?php __('btnAddSize'); ?></a>
                            </div>
                        </div><!-- /.col-sm-5 -->
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="clearfix">
                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                            <span class="ladda-label"><?php __('btnSave'); ?></span>
                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        </button>
                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminProducts&action=pjActionIndex&category=<?php echo $tpl['category']?>&page=<?php echo $tpl['page']?>"><?php __('btnCancel'); ?></a>
                    </div><!-- /.clearfix -->
                </form>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>

<table style="display:none;">
    <thead>
        <tr>
            <th></th>
            <th><?php __('lblSize');?></th>
            <th><?php __('lblPrice');?></th>
            <th></th>
        </tr>
    </thead>

    <tbody id="fd_size_clone">
        <tr class="fd-size-row" data-index="{INDEX}">
            <td class="fd-title-{INDEX}"><?php __('lblSize'); ?> {ORDER}</td>
            
            <td>
                <?php
            	foreach ($tpl['lp_arr'] as $v)
            	{
                	?>
                    <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                        <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
							<input type="text" name="i18n[<?php echo $v['id']; ?>][price_name][{INDEX}]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired required'; ?>" lang="<?php echo $v['id']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>	
							<?php if ($tpl['is_flag_ready']) : ?>
							<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
							<?php endif; ?>
						</div>
                    </div>
                    <?php
                }
                ?>
            </td>

            <td>
            	<div class="form-group">
                    <div class="input-group pjFdProductPrice">
                        <input type="text" name="product_price[{INDEX}]" class="form-control number pj-field-price"/>
    
                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
                    </div>
                </div>
            </td>

            <td>
                <div class="m-t-xs text-right">
                    <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-size"><i class="fa fa-trash"></i></a>
                </div>
            </td>
        </tr>

    </tbody>
</table>
<style>
    table .form-group{
        margin-bottom: 0px !important;
    }
</style>
<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.size = <?php x__encode('lblSize'); ?>;
myLabel.alert_title = <?php x__encode('lblDeleteImage'); ?>;
myLabel.alert_text = <?php x__encode('lblDeleteImageConfirm'); ?>;
myLabel.btn_delete = <?php x__encode('btnDelete'); ?>;
myLabel.btn_cancel = <?php x__encode('btnCancel'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
</script>