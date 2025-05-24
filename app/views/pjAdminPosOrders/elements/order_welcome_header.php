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
  .col-container {
    display: table;
    width: 100%;
  }
  .col {
    display: table-cell;
    padding: 16px;
  }
  fieldset {
    background-color: #eeeeee;
    margin: 10px 0px;
  }
  legend {
    background-color: gray;
    color: white;
    padding-left: 10px;
  }
  fieldset .row {
    margin: 0px -5px;
  }
  .spacing {
    margin:  20px;
    font-size: 12px;
  }
  .legend-input {
    font-weight: 100;
    font-size: 12px;
  }
</style>
<?php
  $time_format = 'HH:mm';
  if ((strpos($tpl['option_arr']['o_time_format'], 'a') > -1)) {
    $time_format = 'hh:mm a';
  }
  if ((strpos($tpl['option_arr']['o_time_format'], 'A') > -1)) {
    $time_format = 'hh:mm A';
  }
  $months = __('months', true);
  if ($months) {
    ksort($months);
  }
  $short_days = __('short_days', true);
  $statuses = __('order_statuses', true, false);
  unset($statuses['cancelled'], $statuses['delivered'], $statuses['confirmed']);
  //unset($statuses['delivered']);
  $times = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
?>
<div class="row" id="order_welcome_header">
  <div class="col-sm-5">
    <div class="row">
      <div class="col-sm-8"><?php echo "Date: ". date($tpl['option_arr']['o_date_format']);  ?>
      <span style="margin-left: 5px; margin-right: 5px;">-</span><?php echo  date("D"); ?>
      <span style="float: right;" class="hidden-sm"><strong>Hot Keys</strong></span>
    </div>
      <div id="currentTimeUpdate" class="text-right"></div>
    </div>
  </div>
  <div class="col-sm-7">
    <div class="row">
      <div class="col-sm-7 text-right">
        <?php 
        $orderTitle = strtolower($tpl['order_title']); ?>
        <strong><?php echo $tpl['order_title']; ?></strong>
      </div>
      <div class="col-sm-5 text-right"><strong><?php echo $_SESSION[$controller->defaultUser]['name']; ?></strong></div>
    </div>
  </div>
</div>