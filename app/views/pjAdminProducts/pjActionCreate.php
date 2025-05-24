<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2><?php __('infoAddProductTitle');?></h2>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
        <?php if ($tpl['is_flag_ready']) : ?>
      <div class="multilang"></div>
        <?php endif; ?>
      </div>
    </div><!-- /.row -->
    <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoAddProductDesc');?></p>
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionCreate" method="post" id="frmCreateProduct" autocomplete="off" enctype="multipart/form-data">
          <input type="hidden" name="product_create" value="1" />
          <input type="hidden" id="index_arr" name="index_arr" value="" />
          <input type="hidden" id="remove_arr" name="remove_arr" value="" />
          <div class="row">
            <div class="col-sm-6">
            <?php foreach ($tpl['lp_arr'] as $v) { ?>
              <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                <label class="control-label"><?php __('lblName');?></label>                      
                <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
                  <input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][name]" data-msg-required="<?php __('fd_field_required', false, true);?>"> 
                  <?php if ($tpl['is_flag_ready']) : ?>
                  <span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php } if(!empty($tpl['category_arr'])) { ?>
              <div class="form-group">
                <label class="control-label"><?php __('lblCategory'); ?></label>
                <select name="category_id[]" id="category_id" multiple="multiple" size="5" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
                  <?php foreach ($tpl['category_arr'] as $v) { ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option>
                  <?php } ?>
                </select>
              </div><!-- /.form-group -->
              <?php } else { ?>
              <div class="form-group">
                <label class="control-label"><?php __('lblCategory'); ?></label>
                  <p class="form-control-static"><?php echo __('lblNoCategoriesFound', true, false);?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCategories&amp;action=pjActionIndex&create=1"><?php __('lblHere');?></a></p>
                  <input type="hidden" name="hidden_category_id" id="hidden_category_id" class="required" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
              </div><!-- /.form-group -->
              <?php } ?>     
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
                                      ?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?> (<?php echo pjCurrency::formatPrice($v['price']); ?>)</option><?php
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
                                                <input type="checkbox" class="onoffswitch-checkbox" name="is_featured" id="is_featured">
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
                        </div><!-- /.col-sm-6 -->
                        
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php
                                    foreach ($tpl['lp_arr'] as $v)
                                    {
                                        ?>
                                        <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                            <label class="control-label"><?php __('lblDescription');?></label>
                                                                    
                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
                                                <textarea class="form-control form-control-lg" name="i18n[<?php echo $v['id']; ?>][description]" data-msg-required="<?php __('fd_field_required', false, true);?>"></textarea>
                                                <?php if ($tpl['is_flag_ready']) : ?>
                                                <span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label class="control-label"><?php echo "Preparation time"; ?></label>
                                        
                                        <select class="form-control select2" name="preparation_time"> 
                                            <option value="">Preparation Time</option>
                                            <?php for ($minutes = 5; $minutes <= 60; $minutes=$minutes+5) { ?>
                                                <option value="<?php echo $minutes; ?>"><?php echo $minutes; ?></option>
                                            <?php } ?>

                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label class="control-label"><?php echo "Status"; ?></label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="radEnabled" value="1" checked> Enabled
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="radDisabled" value="0"> Disabled
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.col-sm-6 -->

                        <div class="col-sm-3">
                          <div class="form-group">
                            <label class="control-label"><?php echo "Product Number"; ?></label>
                              <div>
                                <input type="number" class="form-control col-sm-6" name="counter_number" id="counter_number" />
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
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_kitchen" id="is_kitchen" checked>
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
                          <label class="control-label"><?php echo "Web Order"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_web_orderable" id="is_web_orderable" checked>
                                <label class="onoffswitch-label" for="is_web_orderable">
                                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                  <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                            
                            <span>It can't ordered via web </span>
                          </div>
                        </div>
                      </div>
                      <div id="is_veg_div" class="col-lg-2 col-md-2 col-xs-3">
                        <div class="form-group">
                          <label class="control-label"><?php echo "Veg Item"  ?></label>
                          <div class="switch">
                            <div class="onoffswitch onoffswitch-data">
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_veg" id="is_veg" checked>
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
                              <input type="checkbox" class="onoffswitch-checkbox" name="is_vat" id="is_vat" checked>
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
                            <option value="eatin">Eatin</option> 
                            <option value="takeaway">Takeaway</option>
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
                                        <input type="checkbox" class="onoffswitch-checkbox" id="set_different_sizes" name="set_different_sizes" value="T">
                                        <label class="onoffswitch-label" for="set_different_sizes">
                                            <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
            
                    <div class="row order-size-field">
                        <div class="col-lg-3 col-md-4 col-xs-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblPrice'); ?></label>

                                <div class="input-group pjFdProductPrice">
                                    <input type="text" id="price" name="price" class="form-control pj-field-price required number" data-msg-required="<?php __('fd_field_required', false, true);?>"/>

                                    <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="row order-size-table">
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
                                          $index = 'fd_' . rand(1, 999999); 
                                          ?>
                                            <tr class="fd-size-row" data-index="<?php echo $index;?>">
                                                <td class="fd-title-<?php echo $index;?>"><?php __('lblSize'); ?> 1</td>
                                                
                                                <td>
                                                    <?php
                                                  foreach ($tpl['lp_arr'] as $v)
                                                  {
                                                      ?>
                                                        <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
                                            <input type="text" name="i18n[<?php echo $v['id']; ?>][price_name][<?php echo $index;?>]" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' fdRequired'; ?>" lang="<?php echo $v['id']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/> 
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
                                                            <input type="text" name="product_price[<?php echo $index;?>]" class="form-control number pj-field-price"/>
    
                                                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    &nbsp;
                                                </td>
                                            </tr>

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
                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminProducts&action=pjActionIndex"><?php __('btnCancel'); ?></a>
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
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
</script>