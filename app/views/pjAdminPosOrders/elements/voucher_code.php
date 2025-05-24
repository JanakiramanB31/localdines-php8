<div class="">
  <div class="panel no-borders ibox-content text-right" id="voucher-container" style="padding: 5px 5px; ">
    <div class="form-group row" style="margin-bottom: 2px;">
      <div class="control-label col-sm-9"></div>
      <label class="control-label col-sm-1" style="padding-left:0px; padding-right: 0px; margin-top:5px "><?php __('lblVoucher'); ?></label>
      <div class="col-sm-2">
        <?php
          $voucher_code = isset($tpl['arr']['voucher_code'])?stripslashes($tpl['arr']['voucher_code']):''; ?>
        <input type="text" name="voucher_code" class="jsVK-normal form-control voucher" value="<?php echo $voucher_code;?>">
        <span id="voucher_code-error" class="help-block d-none">voucher code is invalid</span>
        <span id="voucher_code-success" class="help-block d-none">Valid Voucher</span>
      </div>
    </div>
  </div><!-- /.col-md-3 -->
</div><!-- /.m-b-lg -->