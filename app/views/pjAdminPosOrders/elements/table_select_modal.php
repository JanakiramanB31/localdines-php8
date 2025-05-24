<!-- Start of Table selection Modal -->
<div class="modal" tabindex="-1" id="tableModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="modal-title" style="display: inline-block;">Table Selection</h2>
      </div>
      <div class="modal-body text-center">
        <div class="row con-table-selection">
          <div class="col-sm-12">
            <div class="confirm_table_name" id="confirm_table_name">
              <?php foreach ($tpl['table_list'] as $table_id => $name) { ?>
                <a id = "<?php echo $table_id; ?>" class="btn confirm-table-btn" alt="<?php echo stripslashes($name); ?>">
                  <img src="<?php echo IMAGE_BACKEND_URL.'icon-add-table.png' ?>"/> 
                  <?php echo stripslashes($name); ?>
                </a>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">No.of Persons</label>
          <input type="text" class="jsVK-numpad-table form-control"  data-kioskboard-type="numpad" id="no_of_persons" aria-describedby="" placeholder="Enter total no of persons" name="no_of_persons" >
        </div>
        <div id="confirm-table-error-msg" class="error-msg"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="selectTableBtn" data-valid = "false" data-phone="" class="btn btn-primary btn-w-m-75 btn-h-m-40" style="margin-right: 10px">Ok</button>
        <a id="jsCloseModal" href="#" class="btn btn-default btn-w-m-75 btn-h-m-40 d-none" data-dismiss="modal" style="padding-top:8px">Cancel</a>
        <a id="jsRedirectList" href="index.php?controller=pjAdminPosOrders&action=pjActionIndex" class="btn btn-default btn-w-m-75 btn-h-m-40" style="padding-top:8px">Cancel</a>
      </div>
    </div>
  </div>
</div>
<!-- End of Pause Modal -->