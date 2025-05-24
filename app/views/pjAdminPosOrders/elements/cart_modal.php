<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="cartModalTitle"></h2>
      </div>
      <div class="modal-body" id="cartModalBody">
        <div id="pjFdPriceWrapper" class="panel no-borders ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>
          <div class="panel-heading bg-pending">
              <p class="lead m-n"><i class="fa fa-check"></i> <?php __('lblStatus'); ?>: <span class="pull-right status-text"><?php __('order_statuses_ARRAY_pending');?></span></p>    
          </div><!-- /.panel-heading -->
          <div class="panel-body">
            <p class="lead m-b-md"><?php __('lblPrice'); ?>: <span id="" class="pull-right price_format"><?php echo pjCurrency::formatPrice(0);?></span></p>
            <p class="lead m-b-md"><?php echo "Extras" ?>: <span id="" class="pull-right extras_format"><?php echo pjCurrency::formatPrice(0);?></span></p>
            <p class="lead m-b-md"><?php __('lblDelivery'); ?>: <span id="" class="pull-right delivery_format"><?php echo pjCurrency::formatPrice(0);?></span></p>
            <p class="lead m-b-md"><?php __('lblDiscount'); ?>: <span id="" class="pull-right text-right discount_format"><?php echo pjCurrency::formatPrice(0);?></span></p>
            <p class="lead m-b-md"><?php __('lblSubTotal'); ?>: <span id="" class="pull-right text-right subtotal_format"><?php echo pjCurrency::formatPrice(0);?></span></p>
            <!-- <p class="lead m-b-md"><?php //__('lblTax'); ?>: <span id="tax_format" class="pull-right text-right"><?php //echo pjCurrency::formatPrice(0);?></span></p> -->

            <div class="hr-line-dashed"></div>

            <h3 class="lead m-b-md"><?php __('lblTotal'); ?>: <strong id="" class="pull-right text-right total_format"><?php echo pjCurrency::formatPrice(0);?></strong></h3>
          </div><!-- /.panel-body -->
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div id='page_navigation' class="col-sm-8">
          </div>
          <div class="col-sm-4">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->