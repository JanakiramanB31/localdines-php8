<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h2>Income Orders<?php //echo $tpl['loadData'];//__('infoExtrasTitle');?></h2>
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
              <option <?php if($tpl['loadData'] == "Income") { echo "selected";} ?> value="income-Report">Income Reports</option>
              <option  value="expense-Report">Expense Reports</option>
              <option value="cancel-return-Report">Cancel and Return Reports</option>
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
          <input id="gridTypeIncome" type="hidden" name="type" value="IR">
          <div id="overAllIncomeTotal" class="col-md-6 text-right">
           <?php $overAllIncomeTotal = $response['overAllIncomeTotal'] ?? ''; ?>
            <h1>Total - <span><?php echo $tpl['overAllIncomeTotal']; ?></span></h1>
          </div>
        </div><!-- /.row -->

        <div id="gridIncome"></div>
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
  myLabel.id = '<?php echo 'S.No'; ?>';
  myLabel.income_date = '<?php echo 'Date'; ?>';
  myLabel.master_name = '<?php echo 'Name'; ?>';
  myLabel.description = '<?php echo 'Description'; ?>';
  myLabel.amount = '<?php echo 'Amount'; ?>';
  var app_url = "<?php echo APP_URL;?>";
</script>
