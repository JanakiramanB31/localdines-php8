<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <?php //echo "<pre>";print_r($tpl['data_arr']);?>
    <div class="row">
      <div class="col-sm-10">
        <h2>Reviews</h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
  	<?php
  	$error_code = $controller->_get->toString('err');
  	if (!empty($error_code)) {
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
	    switch (true) {
        case in_array($error_code, array('AC01', 'AC03')):
	            ?>
				<div class="alert alert-success">
					<i class="fa fa-check m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
            case in_array($error_code, array('AC04', 'AC08')):
				?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-triangle m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
		  }
  	}
  	?>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row m-b-md">
          <div class="col-md-4">
          </div><!-- /.col-md-6 -->
          <div class="col-md-4 col-sm-8">
            <form action="" method="get" class="form-horizontal frm-filter">
              <div class="input-group">
                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div><!-- /.col-md-3 -->
					<?php
  					$filter = __('filter', true);
  					$u_statarr = __('u_statarr', true);
					?>
          <div class="col-md-4 text-right">
            <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-primary btn-all active"><?php __('lblAll'); ?></button>
              <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="1"><i class="fa fa-check m-r-xs"></i><?php echo "Approve"; ?></button>
              <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="0"><i class="fa fa-times m-r-xs"></i><?php echo "Disapprove"; ?></button>
            </div>
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
        <div id="grid"></div>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="VoucherPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
        <h2 class="modal-title" id="modalTitle">Thank you message!</h2>
        <h3>Client Name: <span id="review_cname"></span></h3>
        <h3>Client ph.no: <span id="review_cphone"></span></h3>
      </div>
      <div class="modal-body" id="modalBody">
        <form role='form_edit' action="" method="post"> 
          <div class="form-group" style="margin-top: 20px;">
            <h3>Type your message...</h3>
            <textarea class="form-control" rows='5' id="tq_message" name="tq_msg" required></textarea>
          </div>
          <button type="submit" class="btn btn-default btn-primary" id="tq_msg_submit" >Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->

<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
pjGrid.currentUserId = <?php echo (int) $_SESSION[$controller->defaultUser]['id']; ?>;
var myLabel = myLabel || {};
myLabel.review_date = '<?php echo "Date & Time"; ?>';
myLabel.product_name = '<?php echo "Product name"; ?>';
myLabel.table_number = '<?php echo "Table no"; ?>';
myLabel.review_type = '<?php echo 'Type'; ?>';
myLabel.rating = "<?php echo 'Rating'; ?>";
myLabel.review = "<?php echo 'Review'; ?>";
myLabel.revert_status = <?php x__encode('revert_status'); ?>;
myLabel.user = "<?php echo 'User'; ?>";
myLabel.name = "<?php echo 'Name'; ?>";
myLabel.email = "<?php echo 'Email'; ?>";
myLabel.phone = "<?php echo 'Mobile No'; ?>";
myLabel.exported = <?php x__encode('lblExport'); ?>;
myLabel.active = "<?php echo 'Aprove'; ?>";
myLabel.inactive = "<?php echo 'Disaprove'; ?>";
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.status = <?php x__encode('lblStatus'); ?>;
</script>