<?php
$si = pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false);
if(isset($tpl['arr']['total'])) {
  $total = pjCurrency::formatPrice($tpl['arr']['total']);
  $total_val = ltrim(pjCurrency::formatPrice($tpl['arr']['total']),pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false));
} else {
  $total_val = "";
}
?>

<div id="paymentTelModal">
  <div class="modal-body text-center">
    <div class="row">
      <div class="col-sm-6 text-left">
        <div class="keyboard-container d-none">
          <button type="button" class="close-keyboard" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="num-keyboard-pos"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 text-left">
        <div class="money-container">
          <a href="javascript:;" data-rs = "50" class="btn"><?php echo $si; ?>50</a>
          <a href="javascript:;" data-rs = "20" class="btn"><?php echo $si; ?>20</a>
          <a href="javascript:;" data-rs = "10" class="btn"><?php echo $si; ?>10</a>
          <a href="javascript:;" data-rs = "5" class="btn"><?php echo $si; ?>5</a>
        </div>
      </div>
      
      <div class="col-sm-6 text-right">
        <div class="input-group money-container" >
          <span id="payment_modal_curr" class="d-none"><?php echo $si; ?> </span>
          <span id="payment_modal_tot" class="d-none" style="padding-right:20px;"><?php echo $total_val; ?></span>
          <span id="payment_btn_val">
            <?php if(isset($tpl['arr']['total'])) { ?>
              <a href="javascript:;" 
                data-rs = "<?php echo $total_val; ?>" 
                class="btn">
                <?php echo $total;?>
              </a>
            <?php } ?>
          </span>
          <input type="text" id="payment_modal_pay" class="form-control pull-right input" style="width:150px" value="0.00" name="payment_modal_pay">
          <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
        </div>
      </div>
    </div>

    <div class="row text-right">
      <div class="col-sm-12">
        <p><b>Balance: </b> <span id="payment_modal_bal"></span></p>
      </div>
    </div>
   
    <div class="row">
      <div class="col-sm-4 text-left">
        <span class="confirm_payment_method" id="confirm_payment_method">
          <button id = "paymentTypeCash" class="btn payment-method-btn"><?php echo "Cash"; ?></button>
          <button id = "paymentTypeCard" class="btn payment-method-btn"><?php echo "Card"; ?></button>
        </span>
      </div>
      <div class="col-sm-4 text-center">
        <button type="button" id="paymentBtn" data-valid = "false" data-phone="" class="btn btn-primary btn-w-m">Pay</button>
      </div>
      <div class="col-sm-4 text-right">
        <div>
          <?php if ($_SESSION[$controller->defaultUser]['role_id'] != WAITER_R0LE_ID) { ?>
          <a href="#" class="btn btn-primary" id="btn-openDrawer"><i class="fa fa-unlock" aria-hidden="true"> TOpen</i></a>
          <?php } ?>
          <button type="button" class="btn btn-secondary" id="btn-clear">Clear</button>
          <a class="btn btn-secondary" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex&type=Telephone"><?php __('btnCancel'); ?></a>
        </div>
      </div>
      <div class="col-sm-12 text-center">
        <p id="error-msg" class="text-danger d-none">Please enter a valid amount</p>
      </div>
    </div>
  </div>
</div>