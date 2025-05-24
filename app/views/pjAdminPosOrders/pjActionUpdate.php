<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_welcome_header.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-5" id="col-5">
    <div class="row">
      <div class="col-sm-12 bg-blue-secondary" style="min-height: 700px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/hot_keys_list.php'; ?>
        <input type='hidden' id='current_page' />
        <input type='hidden' id='show_per_page' />
        <input type='hidden' id='nop' />
        <div class="ibox-content" style="display: none;margin: 0px -20px 0px -20px;">
          <div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <div id="products-sec" class="row products" style="display: none;"></div>
        </div>
      </div>

      <div class="col-sm-12 bg-blue-secondary topnav-two">
        <div class="row" style="margin-top: 5px;margin-bottom: 5px;">
          <!-- <div class="col-sm-8" style="display: flex;">
            <div id="btnCategories" style="width: 50%; margin-right: 15px">
              Menu
            </div>
            <div id="slideCategories" style="display: none; border-radius: 50%; width:60px; color: black; padding:0px; height: 60px;">
              <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
            </div>
          </div> -->
          <div id="page_navigation" class="col-sm-12" style="text-align: right;">
          </div>
        </div> 
      </div>

    </div>
  </div>
  <div class="col-sm-7 bg-light" id="col-7">
    <?php 
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_header_buttons.php'; 
    ?> 
    <?php if (strtolower($tpl['arr']['origin']) == "telephone" || (strtolower($tpl['arr']['origin']) == "web" )) { ?>
      <div class="col-sm-12" style="min-height: 500px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_telephone_update.php'; ?>
      </div>
      <div class="col-sm-12">
        <?php 
        if ($tpl['arr']['status'] == PENDING_STATUS) { 
          include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php';
        } ?>
      </div>
    <?php } elseif ($tpl['arr']['origin'] == "Pos") { ?>
      <div class="col-sm-12" style="min-height: 500px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_pos_eatin_update.php'; ?>
      </div>
      <div class="col-sm-12">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php'; ?>
      </div> 
    <?php } ?>
  </div>
</div>
<div class="row cus-pb-2 cus-pt-2 bottom_row"> 
  <div class="col-sm-5">
    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_left_element.php'; ?>
  </div>
  <div class="col-sm-7">
    <?php
    if (strtolower($tpl['arr']['origin']) == "telephone" || (strtolower($tpl['arr']['origin']) == "web" )) {  
      if ($tpl['arr']['status'] == PENDING_STATUS) { 
        include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_element.php';
      }
    } elseif ($tpl['arr']['origin'] == "Pos") {
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_element.php';
    } ?>
  </div>
</div>

<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/category_list.php'; ?>

<div class="row wrapper wrapper-content animated fadeInRight">

</div>

<!-- End of Modal -->
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/table_select_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cart_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/postal_modal.php';  
  //include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_tel_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/price_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_view_model.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/extra_model.php';
  
  //include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/pause_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/custom_product_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cancel_return_product_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cancel_return_product_modal.php';
  //include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/no_print.php'; 
  //Common Script Variables.
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_common_script_variables.php';
?>



