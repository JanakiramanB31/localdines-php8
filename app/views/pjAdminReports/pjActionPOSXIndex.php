<div class="row border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-10">
        <!-- <h2><?php //__('infoReportsTitle', false, true);?></h2> -->
        <h2><?php echo 'X Report'; ?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>
<style type = 'text/css'>
  .pos-report-row {
    margin-left: 0px;
  }
/*  .float-right {
    text-align: right;
    margin-left: 5%;
  }*/
</style>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
        	<div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
        	<form action="" method="post" id="frmReport" autocomplete="off">
        		<input type="hidden" name="generate_report" value="1" />
            <input type="hidden" name="report_type" value="xReport" />
        		<?php
              $months = __('months', true);
              if ($months) {
                ksort($months);
              }
              $short_days = __('short_days', true);
            ?>
    				<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
            <div class="row m-b-md">
              <div class="col-md-6">
                <label><?php __('lblDate'); ?></label>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    <input type="text" name="date_from" id="date_from" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['date_from']));?>" class="form-control" readonly>
                    <span class="input-group-addon"><?php __('lblTo'); ?></span>
                    <input type="text" name="date_to" id="date_to" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['date_to']));?>" class="form-control" readonly>
                  </div>
                </div><!-- /.form-group -->
              </div>
  						<div class="col-md-3">
  							<label>&nbsp;</label>
  							<div class="form-group m-b-md">
  								<a id="pjFdPrintReport" href="#" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionPOSXPrint" class="btn btn-primary btn-outline"><i class="fa fa-eye"></i> <?php echo 'View';//__('btnPrint');?></a>
  							</div>
  						</div>
            </div><!-- /.row -->
          </form>
          <div class="hr-line-dashed"></div>
          <?php include PJ_VIEWS_PATH . 'pjAdminReports/elements/report_template.php';  ?>
        </div>
      </div>
    </div>
  </div>
</div>