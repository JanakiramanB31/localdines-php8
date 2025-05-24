<div class="modal fade" id="customProductModal" tabindex="-1" role="dialog" aria-labelledby="custom_product" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">Add Custom Product <i class="fa fa-info-circle" aria-hidden="true"></i></h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form name="custom_product_form" id="CustomProductForm">
          <div class="form-group">
            <input type="hidden" class="form-control" name="product_id" value="" id="ProductID" readonly />
            <label for="name" class="control-label">Name:</label>
            <input type="text" class="jsVK-normal form-control" name="name" id="ProductName" required />
          </div>
          <!-- <div class="form-group">
            <label for="message-text" class="control-label">Description:</label>
            <textarea class="form-control" id="message-text" name="description" required></textarea>
          </div> -->
          <div class="form-group">
            <label for="quantity" class="control-label">Quantity:</label>
            <input type="text" class="jsVK-price form-control" name="quantity" value="" required/>
          </div>
          <div class="form-group">
            <label for="price" class="control-label">Price:</label>
            <div class="input-group">
              <input type="text" class="jsVK-price form-control" name="price" required value="">
              <span class="input-group-addon">£</span>
            </div>
          </div>
        </form>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" id="jsBtnAddCusProduct">Add</button>
        <button type="button" class="btn btn-default btn-w-m-75 btn-h-m-40" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>