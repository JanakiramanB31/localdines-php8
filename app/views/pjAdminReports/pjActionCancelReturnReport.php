<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h2>Cancel/Return Orders<?php //echo $tpl['loadData'];//__('infoExtrasTitle');?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-4">
            <select name="type" id="filter_reports2" class="form-control">
              <option value="top-selling">Top Selling Products</option>
              <option value="non-selling">Non Selling Products</option>
              <option value="income-Report">Income Reports</option>
              <option  value="expense-Report">Expense Reports</option>
              <option <?php if($tpl['loadData'] == "CancelRetrun") { echo "selected"; } ?> value="cancel-return-Report">Cancel and Return Reports</option>
            </select>
          </div><!-- /.col-md-6 -->
          <div class="col-md-4">
            <form action="" method="get" class="form-horizontal frm-filter">
              <div class="input-group m-b-md">
                <input type="text" id="query" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div><!-- /.col-lg-6 -->
          <div class="col-md-4">
            <?php if ( $_SESSION['admin_user']['role_id'] == ADMIN_R0LE_ID) { ?>
            <form id="frmFromAndToDate">
              <?php
                $months = __('months', true);
                if ($months) {
                  ksort($months);
                }
                $short_days = __('short_days', true);
              ?>
              <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                  <input type="text" name="date_from" id="date_from" value="<?php echo date("d.m.Y");?>" class="form-control" readonly>
                  <span class="input-group-addon"><?php __('lblTo'); ?></span>
                  <input type="text" name="date_to" id="date_to" value="<?php echo date("d.m.Y");?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->
            </form>
            <?php } ?>
          </div>
        </div><!-- /.row -->

        <div class="row" id="pos-grid-header">
          <div class="col-md-6">
            <input id="gridType" type="hidden" name="type" value="RO">
            <button id="returnOrder" data-type="RO" class="btn btn-primary pos-list-button selected js-switchGrid">
              <strong class="list-pos-type" >Daily Order Returns - </strong><span><?php echo $tpl['dailyReturnOrderTotal']; ?></span>
            </button>
            <button id="adminOrder" data-type="AR" class="btn btn-primary pos-list-button js-switchGrid">
              <strong class="list-pos-type">Admin Order Returns - </strong><span><?php echo $tpl['adminReturnOrderTotal']; ?></span>
            </button>
          </div>
          <div id="totalOfReturnOrder" class="col-md-6 text-right">
            <h1>Total - <span><?php echo $tpl['overAllReturnOrderTotal']; ?></span></h1>
          </div>
        </div><!-- /.row -->

        <div id="grid"></div>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
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
  myLabel.cancel_amount = '<?php echo 'Cancel Amount'; ?>';
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
  var app_url = "<?php echo APP_URL;?>";
</script>
