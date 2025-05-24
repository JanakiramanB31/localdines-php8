<style>
  .navbar-static-side {
    display: none;
  }
  #page-wrapper {
    margin: 0px;
  }
  .navbar-minimalize {
    display: none;
  }
  .wrapper {
    padding: 0px;
  }
  .wrapper-content {
    padding: 0px;
  }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
  <!-- <?php //print_r($tpl['data']); ?> -->
  <div class="col-sm-12" style="margin-bottom: 20px;">
    <div class="row">
      <div class="col-sm-1 col-lg-1"style="margin-top: 20px;">
        <a style="margin-left: 16px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true"></i> HOME</a>
         
      </div>
      <div class="col-sm-5 col-lg-7">
        <h2 id="orderIndexTitle" style="margin-left: 48%; text-align:center; margin-bottom: 0px; font-size:30px"><strong><?php __('infoOrdersListTitle');?></strong></h2>
      </div>
      <div class="col-sm-6 col-lg-4" style="margin-top: 20px; text-align: right; padding-right: 30px">
        <?php if (!CAFE_MODEL) { ?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreateEatin" class="btn btn-primary"><i class="fa fa-plus"> </i> EAT IN </a>
      <?php } ?>
         <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreateTelephone" class="btn btn-primary pos-list-button"><i class="fa fa-plus"> </i> TEL </a>
         <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreate" class="btn btn-primary pos-list-button"><i class="fa fa-plus"> </i> TAKE AWAY</a></div>
    </div><!-- /.row -->
        <!-- <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php //__('infoOrdersListDesc');?> </p> -->
  </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12" id="posWrapper">
    <input type="hidden" id="grid-type" value="<?php echo $tpl['origin_type']; ?>">
    <input type="hidden" id="user-role" value="<?php echo $tpl['role_id']; ?> ">
  	<?php
  	$error_code = $controller->_get->toString('err');
  	if (!empty($error_code)) {
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
	    switch (true) {
        case in_array($error_code, array('AR01', 'AR03')):
    ?>
		<div class="alert alert-success">
			<i class="fa fa-check m-r-xs"></i>
			<strong><?php echo @$titles[$error_code]; ?></strong>
			<?php echo @$bodies[$error_code]?>
		</div>
		 <?php
			  break;
        case in_array($error_code, array('AR07')):
      ?>
    <div class="alert alert-success">
      <i class="fa fa-check m-r-xs"></i>
      <strong><?php echo @$titles[$error_code]; ?></strong>
      <?php echo "New POS order has been saved."?>
    </div>
      <?php
        break;
        case in_array($error_code, array('AR04', 'AR08')):
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
  	$statuses = __('order_statuses', true, false);
  	?>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row m-b-md">
          <div class="col-md-6" id="pos-grid-header">
            <!-- <a href="<?php //echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreate" class="btn btn-primary pos-list-button"><i class="fa fa-plus"> </i> Order<?php //__('btnAddOrder') ?></a> -->
            <a href="javascript:;" id="btn-listpos" class="btn btn-primary pos-list-button <?php if($tpl['origin_type'] == 'Pos') { ?> selected <?php }  ?>"><i class="fa fa-calculator"></i> <?php echo "POS" ?><span id="pending-pos" class="pending-sup"> - <?php echo $tpl['posCount'];?> </span></a>
            <a href="javascript:;" id="btn-listtelephone" class="btn btn-primary pos-list-button <?php if($tpl['origin_type'] == 'Telephone') { ?> selected <?php }  ?>"><i class="fa fa-phone"></i> <?php echo "TEL" ?> <span id="pending-telephone" class="pending-sup"> - <?php echo $tpl['telCount'];?> </span></a>
            <a href="javascript:;" id="btn-listweb" class="btn btn-primary pos-list-button <?php if($tpl['origin_type'] == 'Web') { ?> selected <?php }  ?>"><i class="fa fa-shopping-cart"></i> <?php echo "WEB" ?> <span id="pending-web" class="pending-sup"> - <?php echo $tpl['webCount'];?> </span></a>
            <span class="blink" id="blink" style="display: none; float: right; font-size: 30px;">WEB ORDER</span>
          </div><!-- /.col-md-6 -->
          <!-- <input type="text" name="order_info" value="<?php //echo $tpl['order_info']; ?>"> -->
          <!-- <div class="col-md-6">
            <span class="blink" id="blink" style="display: none;">WEB ORDER</span>
          </div> -->
	        <form action="" method="get" class="form-horizontal frm-filter">
            <div class="col-md-4">
              <div class="input-group">
                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control jsVK-normal">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </div><!-- /.col-md-3 -->
            <div class="col-md-2 text-right">
              <select name="type" id="filter_type" class="form-control">
    				    <option value="">-- <?php echo "Orders"; ?> --</option>
        				<?php foreach (__('types', true, false) as $k => $v) { ?>
                <option value="<?php echo $k; ?>"<?php echo $controller->_get->toString('type') == $k ? ' selected="selected"' : NULL;?>><?php echo stripslashes($v); ?></option>
                <?php } foreach ($statuses as $k => $v) { ?>
                <option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option>
                <?php } ?>
                <?php if($tpl['role_id'] == 1) { ?>
                <option value="deleted"><?php echo "Deleted"; ?></option>
                <option value="all">-- <?php echo "All"; ?> --</option>
              <?php } ?>
              </select>
            </div><!-- /.col-md-6 -->
          </form>
        </div><!-- /.row -->
        <div id="grid" <?php if($tpl['origin_type'] == 'Telephone' || $tpl['origin_type'] == 'Web') { ?> class="pj-grid" <?php } else { ?> class="d-none" <?php } ?>></div>
        <div id="grid-epos"  <?php if($tpl['origin_type'] == 'Pos') { ?> class="pj-grid" <?php } else { ?> class="d-none" <?php } ?>></div>
    </div>
  </div>
</div><!-- /.col-lg-12 -->
<style>
  .modal h2 {
    color: #575757;
    font-size: 30px;
    text-align: center;
    font-weight: 600;
  }
  .modal .modal-body {
    font-size: 20px;
    font-weight: 400;
  }
</style>
<?php 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/sms_modal.php';  
?>

<!-- End of Modal -->
</div>
<script type="text/javascript">
  var pjGrid = pjGrid || {};
  pjGrid.queryString = "";
  <?php if ($controller->_get->toInt('client_id')) { ?>
    pjGrid.queryString += "&client_id=<?php echo $controller->_get->toInt('client_id'); ?>";<?php
  }
  if ($controller->_get->toString('type')) { ?>
    pjGrid.queryString += "&type=<?php echo $controller->_get->toString('type'); ?>";<?php
  }
  ?>
  var myLabel = myLabel || {};
  //myLabel.name = <?php x__encode('lblName'); ?>;
  myLabel.id = '<?php echo 'ID'; ?>';
  myLabel.name = '<?php echo 'Surname'; ?>';
  myLabel.phone = <?php x__encode('lblPhone'); ?>;
  myLabel.address = '<?php echo 'Address'; ?>';
  myLabel.postcode = '<?php echo 'Postcode'; ?>';
  myLabel.c_type = '<?php echo 'C.Type'; ?>';
  myLabel.doc = '<?php echo 'DOC'; ?>';
  // myLabel.call_start = '<?php //echo 'Call Start'; ?>';
  // myLabel.call_end = '<?php //echo 'Call End'; ?>';
  myLabel.sms_email = '<?php echo 'SMS/Email'; ?>';
  myLabel.order_despatched = '<?php echo 'OD'; ?>';
  myLabel.yes = <?php x__encode('_yesno_ARRAY_T'); ?>;
  myLabel.no = <?php x__encode('_yesno_ARRAY_F'); ?>;
  myLabel.expected_delivery = '<?php echo 'ED'; ?>';
  myLabel.sms_sent_time = '<?php echo 'SST'; ?>';
  myLabel.delivered_customer = '<?php echo 'DC'; ?>';
  myLabel.delivered_time = '<?php echo 'Delivered Time'; ?>';
  myLabel.is_paid = '<?php echo 'Is Paid?'; ?>';
  myLabel.review = '<?php echo 'R'; ?>';
  // myLabel.date_time = <?php x__encode('lblDateTime'); ?>;
  myLabel.order_date = '<?php echo 'Date'; ?>';
  myLabel.total = <?php x__encode('lblTotal'); ?>;
  myLabel.type = <?php x__encode('lblType'); ?>;
  myLabel.pickup = <?php x__encode('lblPickup'); ?>;
  myLabel.pickupAndCall = '<?php echo 'Pickup & Call'; ?>';
  myLabel.delivery = <?php x__encode('lblDelivery'); ?>;
  myLabel.status = <?php x__encode('lblStatus'); ?>;
  myLabel.exported = <?php x__encode('lblExport'); ?>;
  myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
  myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
  myLabel.pending = <?php x__encode('order_statuses_ARRAY_pending'); ?>;
  myLabel.confirmed = <?php x__encode('order_statuses_ARRAY_confirmed'); ?>;
  myLabel.cancelled = <?php x__encode('order_statuses_ARRAY_cancelled'); ?>;
  myLabel.delivered = '<?php echo "Delivered"; ?>';
  myLabel.posType = '<?php echo "Pos Type"; ?>';
  myLabel.paymentType = '<?php echo "Payment"; ?>';
  var kPrint = {
    type: "print",
    text: " Kprint",
    url: "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&id={:id}&source=index",
  };

  var buttons = [
    {
      type: "edit",
      text: " Edit",
      url: "index.php?controller=pjAdminPosOrders&action=pjActionUpdate&id={:id}",
    },
    {
      type: "print",
      text: " Rprint",
      url: "index.php?controller=pjAdminPosOrders&action=pjActionSalePrint&id={:id}&source=index",
    },
    {
      type: "info",
      text: " info",
      url: "#",
    },
    {
      type: "return",
      text: " Refund",
      url: "index.php?controller=pjAdminPosOrders&action=pjActionReturnOrderItem&id={:id}",
    },
    {
      type: "cancel",
      url: "index.php?controller=pjAdminPosOrders&action=pjActionCancelOrder&id={:id}",
    },
    {
      type: "delete",
      url: "index.php?controller=pjAdminPosOrders&action=pjActionDeleteOrder&id={:id}",
      model: "Order"
    },
  ];
  var telButtons = [
    {
      type: "delay",
      //text: "Delay",
      url: "#",
    },
    {
      type: "info",
      text: " info",
      url: "#",
    },
  ]
  var infoIndex = 4;
  var isKitchen = '<?php echo KITCHEN_PRINT; ?>';
  if (isKitchen === '1') {
    buttons.splice(1, 0, kPrint);
    var infoIndex = 5;
  }
  telButtons = telButtons.concat(buttons);
  telButtons.splice(infoIndex, 1);
  // console.log(buttons);
  // console.log(telButtons);
</script>
