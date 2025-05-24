<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionUpdateEpos"  method="post" id="frmUpdateOrder_pos">
  <input type="hidden" name="order_update" value="1" />
  <input type="hidden" id="min_amt" value="<?php echo $tpl['option_arr']['o_minimum_order']; ?>" />
  <input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
  <input type="hidden" name="order_despatched" value="<?php echo $tpl['arr']['order_despatched']?>" />
  <input type="hidden" name="delivered_customer" value="<?php echo $tpl['arr']['delivered_customer']?>" />
  <input type="hidden" name="delivered_time" value="<?php echo $tpl['arr']['delivered_time']?>" />
  <input type="hidden" name="sms_sent_time" value="<?php echo $tpl['arr']['sms_sent_time']?>" />
  <input type="hidden" id="price" name="price" value="<?php echo $tpl['arr']['price']; ?>" />
  <input type="hidden" id="price_packing" name="price_packing" value="<?php echo $tpl['arr']['price_packing']; ?>" />
  <input type="hidden" id="price_delivery" name="price_delivery" value="<?php echo $tpl['arr']['price_delivery']; ?>" />
  <input type="hidden" id="discount" name="discount" value="<?php echo $tpl['arr']['discount']; ?>" />
  <input type="hidden" id="subtotal" name="subtotal" value="<?php echo $tpl['arr']['subtotal']; ?>" />
  <input type="hidden" id="tax" name="tax" value="<?php echo $tpl['arr']['tax']; ?>" />
  <input type="hidden" id="total" name="total" value="<?php echo $tpl['arr']['total']; ?>" />
  <input type="hidden" id="origin" name="origin" value="<?php echo $tpl['arr']['origin']; ?>" />
  <input type="hidden" id="p_date" name="p_date" value="<?php echo date('d.m.Y'); ?>" />
  <input type="hidden" id="pickup_time" name="pickup_time" value="<?php echo date('H:i'); ?>" />
  <input type="hidden" id="vouchercode" name="vouchercode" value="<?php echo  stripslashes($tpl['arr']['voucher_code']);?>" />
  <input type="hidden" id="is_paused" name="is_paused" value = "<?php echo $tpl['arr']['status'] == PENDING_STATUS ? '1' : '0'; ?>">
  <input type="hidden" id="res_table_name" name="res_table_name" value="<?php echo $tpl['arr']['table_name'];?>" />
  <input type="hidden" id="payment_method" name="payment_method" value="Cash" />
  <input type="hidden" id="cash_amount" name="cash_amount" value="" />
  <input type="hidden" id="is_paid" name="is_paid" value = "0">
  <input type="hidden" id="customer_paid" name="customer_paid" value="0" />
  <input type="hidden" id="api_payment_response" name="response" value="" />
  <input type="hidden" id="total_persons" name="total_persons" value="<?php echo $tpl['arr']['total_persons'];?>" />
  <div class="row">
    <div class="col-sm-12">
      <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_table.php';  ?> 
      <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/voucher_code.php';  ?>
      <div class="clearfix" id="btns-epos">
        <!-- <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a> -->
        <?php if ($_SESSION[$controller->defaultUser]['role_id'] == WAITER_R0LE_ID) { ?>
        <a href="#" class="btn btn-primary btn-lg pull-right" data-cart="" id="btn-save">Save</a> 
        <?php } else if ($tpl['arr']['status'] == PENDING_STATUS) { ?>
         <!-- <a class="nav-link ladda-button btn btn-primary btn-lg pull-right" data-cart="<?php echo $tpl['arr']['total']; ?>" id="btn-payment">Payment</a>  -->
        <?php } ?>
      </div>
    </div>
  </div>
</form>