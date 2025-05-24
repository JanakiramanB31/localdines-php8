<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_welcome_header.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 bg-light" id="col-12">
    <?php 
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_return_inventory_header_buttons.php'; 
    ?> 
      <div class="col-sm-12">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_return.php'; ?>
      </div>
  </div>
</div>

<!-- End of Modal -->
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/return_product_modal.php';
  //Common Script Variables.
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_common_script_variables.php';
?>
<!-- End of Payment Modal -->



