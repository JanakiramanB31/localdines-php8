<div class="col-sm-12 mt-5">
  <div class="row">
    <div class="col-lg-7 col-sm-6 text-right">
      <form class="form-inline" style="" onkeydown="return event.key != 'Enter';">
        <div id="product_input" class="input-group d-none">
          <input id="inputSearch" type="text" class="jsVK-normal form-control" type="search" placeholder="Search Products..." aria-label="Search">
          <div id="productSearch" class="input-group-addon btn btn-primary btn-outline-success my-2 my-sm-0"><i class="fa fa-search" aria-hidden="true"></i></div>
        </div>
        <button id="productSearchHide" class="btn btn-primary btn-outline-success my-2 my-sm-0" type="button" ><i class="fa fa-search" aria-hidden="true"></i></button>
         <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionIndex" class="btn btn-primary">
        <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
      </a>
       <?php 
        $orderTitle = strtolower($tpl['order_title']); 
        if (in_array($orderTitle, HAS_TABLE_SELECTION)) { ?>
          <span id="sel_table_name" class="<?php echo $tpl['selTableName']?'':'d-none'?>"> <a href="#tableModal" id="sel_table_name_modal" class="btn btn-primary"><?php echo $tpl['selTableName']; ?></a>
        <?php } ?>
      </form>
    </div>
    <div class="col-lg-5 col-sm-6 text-right">
      <a href="#" class="btn btn-primary" id="showPostalCodes">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
      </a>
      <a href="#" class="btn btn-primary" id="showCart">
        <?php if (array_key_exists('arr', $tpl) && array_key_exists('total', $tpl['arr'])) { 
            $total = $tpl['arr']['total']; 
          } else {
            $total = 0; 
          }
        ?>
        <span id="cartPriceBottom"><?php echo pjCurrency::formatPrice($total); ?></span>
        <i class="fa fa-arrow-down" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</div>