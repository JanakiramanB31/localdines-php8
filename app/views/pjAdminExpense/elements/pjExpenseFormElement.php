<div class="row">
  <div class="col-sm-6">
    
    <div class="form-group">
      <label class="control-label">Supplier</label>
      <select name="master" id="master" class="form-control select-voucher required touchBigSelect" data-msg-required="This field is required.">
        <option value="">-- Choose --</option>
        <?php
          if(!empty($tpl['masters'])) {
            foreach($tpl['masters'] as $master) {
              ?><option value="<?php echo $master['id'] ?>"
               <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['master_id']== $master['id'] ? "selected" : '' ?>><?php echo $master['name'] ?></option><?php 
            }
          }
        ?>                                
      </select>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Category</label>
      <select name="category" id="category" class="form-control select-voucher required touchBigSelect" data-msg-required="This field is required.">
        <option value="">-- Choose --</option>
        <?php
          if(!empty($tpl['category_arr'])) {
            foreach($tpl['category_arr'] as $cat) {
              ?><option value="<?php echo $cat['id'] ?>"
                <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['category_id']== $cat['id'] ? "selected" : '' ?>
                ><?php echo $cat['name'] ?></option><?php 
            }
          }
        ?>                                
      </select>
    </div><!-- /.form-group -->

    <?php $expense_date =  array_key_exists('arr',  $tpl) &&  $tpl['arr']['expense_date'] ? $tpl['arr']['expense_date'] : '' ?>
     <?php 
      $expense_date = array_key_exists('arr', $tpl) && $tpl['arr']['expense_date'] ? $tpl['arr']['expense_date'] : '';
      if ($expense_date) {
        $expense_date = date('d-m-Y',strtotime($expense_date));
      } else {
        $expense_date = date('d-m-Y',strtotime('today'));
      }
    ?>
    <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
        <input type="text" name="expense_date" id="expense_date" data-date-format="dd-mm-yyyy" value="<?php echo $expense_date; ?>" class="form-control">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Amount</label>
      <div class="input-group pjFdProductAmount">
        <input type="text" id="amount" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['amount'] ? $tpl['arr']['amount'] : '' ?>" name="amount" class="jsVK-price form-control pj-field-amount required number" data-msg-required="This field is required." data-kioskboard-type="numpad" data-kioskboard-placement="bottom" />
        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
      </div>
    </div><!-- /.form-group -->
  </div>

  <div class="col-sm-6">
    <div class="form-group">
      <label class="control-label">Expense Name</label>
      <input type="text" name="expense_name" id="exp_name" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['expense_name'] ? $tpl['arr']['expense_name'] : '' ?>" class="jsVK-normal form-control " data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Sub category</label>
      <input type="text" name="sub_category" id="sub_cat" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['sub_category'] ? $tpl['arr']['sub_category'] : '' ?>" class="jsVK-normal form-control " data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Description</label>
      <textarea class="jsVK-normal form-control form-control-lg" name="description"><?php echo array_key_exists('arr', $tpl) && $tpl['arr']['description'] ? $tpl['arr']['description'] : '' ?></textarea>
    </div><!-- /.form-group -->

  </div>
  
</div>
<div class="hr-line-dashed"></div>
<div class="row">
  <div class="col-sm-12">

    <div class="clearfix">
      <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
        <span class="ladda-label"><?php __('btnSave'); ?></span>
          <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
      </button>

      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminExpense&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->

  </div>
</div>
<div class="hr-line-dashed"></div>