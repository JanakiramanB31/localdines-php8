<div class="row">
  <div class="col-sm-12 col-lg-12">

    <div class="row">
      <input type="hidden" name="size" id="hidden_size" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['size'] ? $tpl['arr']['size'] : '' ?>">
      <div class="col-sm-6">
        <div id="product_name" class="form-group <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_id'] == 0 ? '': 'd-none' ?>">
          <label class="control-label">Product Name</label>
          <input type="text" class="form-control" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_name'] ? $tpl['arr']['product_name']: '' ?>" name="product_name" data-msg-required="This field is required." />
        </div>
        <?php if(!empty($tpl['product_arr'])) { ?>
          <div id="product_select" class="form-group <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_id'] == 0 ? 'd-none': '' ?>">
            <label class="control-label">Product Name</label>
            <select name="product_id" id="product_id" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
              <option value="" selected>-- <?php __('lblChoose'); ?> --</option>
              <?php foreach ($tpl['product_arr'] as $v) { ?>
                <option <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_id'] == $v['id'] ? 'selected' : '' ?> value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option>
              <?php } ?>
              <option value="0" <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_id'] == 0 ? 'selected': '' ?>>Custom Product</option>
            </select>
          </div><!-- /.form-group -->
        <?php } ?>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label">Order no</label>
          <input type="text" name="order_id" id="order_id" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['order_id'] ? $tpl['arr']['order_id'] : '' ?>" class="form-control"/>
        </div><!-- /.form-group -->
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label"><?php echo "Custom Product"  ?></label>
          <div class="switch">
            <div class="onoffswitch onoffswitch-data">
              <input type="checkbox" class="onoffswitch-checkbox" name="custom" id="custom" <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['product_id'] == 0 ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="custom">
                  <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true)?>" data-off="<?php __('_yesno_ARRAY_F', false, true)?>"></span>
                  <span class="onoffswitch-switch"></span>
                </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-sm-3 col-lg-3">
        <div class="form-group">
          <label class="control-label">Quantity</label>
          <input type="number" name="quantity" id="quantity" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['qty'] ? $tpl['arr']['qty'] : '1' ?>" class="form-control pj-field-count required number" data-msg-required="This field is required." readonly/>
        </div><!-- /.form-group -->
      </div>

      <div id="js-select" class="col-sm-3">
        <?php if (array_key_exists('different_size_product', $tpl) && $tpl['different_size_product']) { ?>
        <label class="control-label">Size</label>
        <select id="cus-select" class="form-control change-size" selected="selected">
          <option value="">-- choose --</option>
          <?php foreach($tpl['different_size_product'] as $v) { ?>
            <option value="<?php echo $v['price'] ?>" <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['size'] == $v['id'] ? 'selected' : '1' ?>  data-id="<?php echo $v['id'] ?>"><?php echo $v['price_name'] ?></option>
          <?php } ?>
        </select>
        <?php } ?>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label"><?php __('lblPrice'); ?></label>
      
          <div class="input-group">
            <input type="text" id="price" name="price" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['price'] ? $tpl['arr']['price'] : '' ?>" class="form-control text-right number required" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>">

            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label">Total Amount</label>
      
          <div class="input-group">
            <input type="text" id="amount" name="amount" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['amount'] ? $tpl['arr']['amount'] : '' ?>" class="form-control text-right number required" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>" readonly>

            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <?php
        $months = __('months', true);
        if ($months) {
          ksort($months);
        }
        $short_days = __('short_days', true);
      ?>
      <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>

      <div class="col-sm-6 col-lg-3">
        <div class="form-group">
          <label class="control-label">Purchase Date</label>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
            <input type="text" name="purchase_date" id="purchase_date" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['purchase_date']));?>" class="form-control" readonly>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="form-group">
          <label class="control-label">Return Date</label>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
            <input type="text" name="return_date" id="return_date" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['return_date']));?>" class="form-control" readonly>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label">Reason</label>
        <textarea class="form-control form-control-lg required" id="reason" name="reason"><?php echo array_key_exists('arr', $tpl) && $tpl['arr']['reason'] ? $tpl['arr']['reason'] : '' ?></textarea>
    </div><!-- /.form-group -->

  </div>

  <div class="col-sm-12 col-lg-12">
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="clearfix">
      <button type="submit" id="main_form" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
        <span class="ladda-label"><?php __('btnSave'); ?></span>
          <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
      </button>
      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOrderReturns&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>