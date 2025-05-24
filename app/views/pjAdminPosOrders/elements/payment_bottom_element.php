<div id="paymentModal">
  <div class="row">
    <div class="col-lg-6 col-sm-6 text-left" style="padding: 2px">
      <div>
        <?php if ($_SESSION[$controller->defaultUser]['role_id'] != WAITER_R0LE_ID) { ?>
          <a href="#" class="btn btn-primary" id="btn-openDrawer"><i class="fa fa-unlock" aria-hidden="true"> TOpen</i></a>
          <?php } ?>
        <span class="confirm_payment_method" id="confirm_payment_method">
          <button id = "paymentTypeCash" class="btn payment-method-btn selected"><?php echo "Cash"; ?></button>
          <button id = "paymentTypeCard" class="btn payment-method-btn"><?php echo "Card"; ?></button>
          <button id = "paymentTypeSplit" class="btn payment-method-btn"><?php echo "Split"; ?></button>
        </span>
      </div>
    </div>
    <div class="col-lg-6 col-sm-6 text-right" style="padding: 2px">
      <div>
        <span style="padding-left:10px">
          <a href="#" class="btn btn-primary" id="btn-pause">
            <i class="fa fa-pause" aria-hidden="true"></i>
          </a>
           <button type="button" id="paymentBtn" data-valid = "false" data-phone="" class="btn btn-primary">&nbsp;&nbsp;Pay&nbsp;&nbsp;</button>
          <button type="button" class="btn btn-secondary" id="btn-clear">Clear</button>
          <a class="btn btn-secondary" id="btn-cancel" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
        </span>
      </div>
    </div>
  </div>
</div>