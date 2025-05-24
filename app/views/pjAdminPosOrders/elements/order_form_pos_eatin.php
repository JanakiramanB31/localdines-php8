<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreateEpos"  method="post" id="frmCreateOrder_epos">
  <input type="hidden" name="order_create" value="1" />
  <input type="hidden" id="currency_place" value="<?php echo $tpl['option_arr']['o_currency_place']; ?>" />
  <input type="hidden" id="price" name="price" value="" />
  <input type="hidden" id="price_packing" name="price_packing" value="" />
  <input type="hidden" id="price_delivery" name="price_delivery" value="" />
  <input type="hidden" id="discount" name="discount" value="" />
  <input type="hidden" id="subtotal" name="subtotal" value="" />
  <input type="hidden" id="tax" name="tax" value="" />
  <input type="hidden" id="total" name="total" value="" />
  <input type="hidden" id="vouchercode" name="vouchercode" value="" />
  <input type="hidden" id="p_date" name="p_date" value="<?php echo date('d.m.Y'); ?>" />
  <input type="hidden" id="pickup_time" name="pickup_time" value="<?php echo date('H:i'); ?>" />
  <input type="hidden" id="call_start" name="call_start" value="<?php echo date('h:i:s A'); ?>" />
  <input type="hidden" id="is_paused" name="is_paused" value = "0">
  <input type="hidden" id="res_table_name" name="res_table_name" value="<?php echo $tpl['arr']['table_name']; ?>" />
  <input type="hidden" id="payment_method" name="payment_method" value="cash" />
  <input type="hidden" id="cash_amount" name="cash_amount" value="" />
  <input type="hidden" id="origin" name="origin" value="Pos" />
  <input type="hidden" id="customer_paid" name="customer_paid" value="0" />
  <input type="hidden" id="total_persons" name="total_persons" value="0" />
  <input type="hidden" id="api_payment_response" name="response" value="" />
  <div class="row">
    <div class="col-sm-12">
      <div id="fdEposTableContainer" style="margin-bottom: 40px;">
       <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_table.php';  ?>
      </div>
      <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/voucher_code.php';  ?>
      <div class="clearfix" id="btns-epos">
          <?php if ($_SESSION[$controller->defaultUser]['role_id'] == WAITER_R0LE_ID) { ?>
        <a href="#" class="btn btn-primary btn-lg pull-right" data-cart="" id="btn-save">Save</a> 
          <?php } else { ?>
        <!-- <a class="nav-link ladda-button btn btn-primary btn-lg pull-right" data-cart="" id="btn-payment">Payment</a>  -->
          <?php } ?>
      </div>
    </div>
  </div>
</form>