<!-- Start of Pause Modal -->
<div class="modal" tabindex="-1" id="pauseModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="modal-title" style="display: inline-block;">Pause Order</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="row">
          <div class="col-sm-12">
              Do you want to hold the order?<br/>
          </div>
        </div>
        <div class="row con-table-selection">
          <div class="col-sm-12">
            <div class="confirm_table_name" id="confirm_table_name">
              <?php foreach ($tpl['table_list'] as $table_id => $name) { ?>
                  <button id = "<?php echo $table_id; ?>" class="btn confirm-table-btn"><?php echo stripslashes($name); ?></button>
              <?php } ?>
              <button id = "Take Away" class="btn confirm-table-btn"><?php echo "Take Away"; ?></button>
            </div>
            <div id="confirm-table-error-msg" class="error-msg"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="paymentBtn" data-valid = "false" data-phone="" class="btn btn-primary btn-w-m-75 btn-h-m-40" style="margin-right: 10px">Ok</button>
        <button type="button" class="btn btn-secondary btn-h-m-40" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Pause Modal -->