<div class="container-fluid pos-report" id="pjFdReportContent">
  <label class="h4">Financial</label>
  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total No of Sales:</label>
      <span><?php echo $tpl['num_of_sales']; ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">No of Pos Sales:</label>
      <span><?php echo $tpl['sales_report']['num_of_pos_sales']; ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">No of Table Sales:</label>
      <span><?php echo $tpl['sales_report']['num_of_table_sales']; ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">No of Tele Sales:</label>
      <span><?php echo $tpl['sales_report']['num_of_telephone_sales']; ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-4 mb-3">
      <label class="control-label">No of Take Away Sales:</label>
      <span><?php echo $tpl['sales_report']['num_of_direct_sales']; ?></span>
    </div>
    <div class="col-12 col-md-4 mb-3">
      <label class="control-label">No of Web Sales:</label>
      <span><?php echo $tpl['sales_report']['num_of_web_sales']; ?></span>
    </div>
    <div class="col-12 col-md-4 mb-3">
      <label class="control-label">No of Incomes:</label>
      <span><?php echo $tpl['sales_report']['num_of_incomes']; ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Cash Sales:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_sales']); ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Number of Return Orders:</label>
      <span><?php echo $tpl['sales_report']['num_of_return_orders']; ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Card Sales:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['card_sales']); ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Return Orders:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_return_orders']); ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Table Sales:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_table_sales']); ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Number of Suppliers Exp:</label>
      <span><?php echo $tpl['sales_report']['num_of_expenses']; ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Take Away Sales:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_direct_sales']); ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Suppliers Expenses:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_supplier_exp']); ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Gross Sales:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_amount']); ?></span>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label class="control-label">Total Expenses:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_expenses']); ?></span>
    </div>
  </div>

  <div class="hr-line-dashed"></div>

  <div class="row pos-report-row">
    <div class="col-12 mb-3">
      <label class="control-label">Cash in Hand:</label>
      <span><?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_in_hand']); ?></span>
    </div>
  </div>
</div>
