<div class="modal fade" id="smsModal" tabindex="-1" role="dialog" aria-labelledby="smsModal" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
        <h2 class="modal-title" id="modalTitle">Message</h2>
        <h3>Client Name: <span id="delay_cname"></span></h3>
        <h3>Client ph.no: <span id="delay_cphone"></span></h3>
      </div>
      <div class="modal-body" id="modalBody">
        <form role='form_edit' action="" method="post"> 
          <!-- <select class="form-control" id="delay_reason">
            <option selected>Choose the reason</option>
            <option value="1">Traffic Delay</option>
            <option value="2">Too busy with more orders</option>
            <option value="3">Short of staff</option>
            <option value="4">Custom</option>
          </select> -->
          <span id="delay_container">
            
          </span>
          <div class="form-group" style="margin-top: 20px;">
            <textarea class="form-control jsVK-normal" rows='5' id="message" name="delay_msg"></textarea>
          </div>
          <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" id="d_msg" disabled>Send</button>
          </form>
      </div>
    </div>
  </div>
</div>