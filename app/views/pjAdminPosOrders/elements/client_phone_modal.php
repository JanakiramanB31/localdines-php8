<!-- Start of Client Phone Number Modal -->
<div class="modal" tabindex="-1" id="clientPhoneNumberModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="modal-title" style="display: inline-block;">Pause Order</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p>Please enter your phone number to pause the order!</p>
        <div class="form-group">
            <input id="pause_phone" class="form-control" />    
            <span id="pause_phone-error" class="text-danger d-none">This field is required.</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="clientPhoneNumberBtn" data-phone="" class="btn btn-primary">OK</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Client Phone Number Modal -->