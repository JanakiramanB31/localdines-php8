<div class="modal fade" id="cancelReturnModal" tabindex="-1" role="dialog" aria-labelledby="custom_product" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">Refund for Return Product <i class="fa fa-info-circle" aria-hidden="true"></i></h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form name="ProductReturnForm" id="ProductReturnForm">
          <input type="hidden" class="form-control" name="return" value="return" id="CancelOrReturn" readonly required />
          <input type="hidden" class="form-control" name="row_id" value="" id="CancelReturnID" readonly  />
          <div class="form-group">
            <label for="product" class="control-label">Type:</label>
            <span class="btn_container">
              <button type="button" id="prodcutReturned" class="btn jsProductMethodBtn product-method-btn selected">Refund</button>
            </span>
          </div>
          <div class="form-group">
            <label for="return_qty" class="control-label">Quantity:</label>
            <div class="input-group">
              <input min=1 type="text" class="form-control input-lg" name="return_qty" value="" id="cancelReturnQty" readonly />
            </div>
          </div>
          <div class="form-group">
            <label for="reason" class="control-label">Reason:</label>
            <textarea class="form-control jsVK-normal" id="CancelOrReturnReason" name="reason" required></textarea>
          </div>
           <div class="form-group">
            <label for="reason" class="control-label">Transaction Type:</label>
            <h3><?php echo $tpl['arr']['payment_method']; ?></h3>
          </div>
          <div class="form-group">
            <label for="reason" class="control-label">Refund Amount:</label>
            <h3><span id='jsRefundCur'></span><span id='jsRefundAmt'></span></h3>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" id="jsBtnReturnProduct">Submit</button>
        <button type="button" class="btn btn-default btn-w-m-75 btn-h-m-40" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



 