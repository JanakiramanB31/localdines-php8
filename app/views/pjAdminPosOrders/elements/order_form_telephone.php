<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionCreate" method="post" id="frmCreateOrder_pos" onsubmit="submitTel.disabled = true; return true;">
  <input type="hidden" name="order_create" value="1" />
  <input type="hidden" id="min_amt" value="<?php echo $tpl['option_arr']['o_minimum_order']; ?>" />
  <input type="hidden" id="currency_place" value="<?php echo $tpl['option_arr']['o_currency_place']; ?>" />
  <input type="hidden" id="price" name="price" value="" />
  <input type="hidden" id="price_packing" name="price_packing" value="" />
  <input type="hidden" id="price_delivery" name="price_delivery" value="" />
  <input type="hidden" id="discount" name="discount" value="" />
  <input type="hidden" id="subtotal" name="subtotal" value="" />
  <input type="hidden" id="tax" name="tax" value="" />
  <input type="hidden" id="total" name="total" value="" />
  <input type="hidden" id="customer_paid" name="customer_paid" value="0" />
  <input type="hidden" id="vouchercode" name="vouchercode" value="" />
  <input type="hidden" id="origin" name="origin" value="Telephone" />
  <input type="hidden" id="api_payment_response" name="response" value="" />
  <input type="hidden" id="payment_method" name="payment_method" value="Cash" />
  <input type="hidden" id="cash_amount" name="cash_amount" value="" />
  
  <input type="hidden" id="call_start" name="call_start" value="<?php echo date('h:i:s A'); ?>" />
  <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>">
  </div>
  <div class="row" id="rowSwitch">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
      <div class="form-group pull-right">
        <label style="margin-right: 5px;"><?php __('lblStatus'); ?></label>
        <select name="status" id="status" class="required" data-msg-required="<?php __('fd_field_required', false, true);?>">
            <?php
            foreach ($statuses as $k => $v) { ?>
              <option value="<?php echo $k; ?>"<?php echo $k =='pending' ? ' selected="selected"' : NULL;?>><?php echo stripslashes($v); ?></option>
            <?php } ?>
        </select>
        <div class="switch onoffswitch-data pull-right" style="margin-top:2px; margin-left: 10px;">
          <div class="onoffswitch onoffswitch-order">
            <input type="checkbox" class="onoffswitch-checkbox" id="type" name="type" checked>
            <label class="onoffswitch-label" for="type">
              <span class="onoffswitch-inner" data-off="<?php __('types_ARRAY_delivery', false, true);?>" data-on="<?php __('types_ARRAY_pickup', false, true);?>"></span>
              <span class="onoffswitch-switch"></span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <ul class="nav nav-tabs" id="orderTab" role="tablist">
        <li class="nav-item active">
          <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="true">ORDER</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">CLIENT DETAILS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="orderType-tab" data-toggle="tab" href="#orderType" role="tab" aria-controls="orderType" aria-selected="false">ORDER INFO</a>
        </li>
      </ul>
      <div class="tab-content" id="orderTabContent">
        <div class="tab-pane fade active in" id="order" role="tabpanel" aria-labelledby="order-tab">
          <div class="col-sm-12">
            <div id="fdPosTableContainer">
              <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_table.php';  ?>
            </div>
            <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/voucher_code.php';  ?>
            <div class="clearfix" id="btns-pos">
              <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
              <a class="nav-link next-tab ladda-button btn btn-primary btn-lg pull-right" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Next</a>  
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
          <fieldset>
            <legend style="margin-top: 50px;">SMS Delivery Info</legend>
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo 'Mobile' //__('lblPhone'); ?></label>
                  <input type="text" name="phone_no" id="phone_no" class="jsVK-numpad form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-kioskboard-type="numpad" data-kioskboard-placement="bottom" placeholder="Your Number" />
                </div>
              </div><!-- /.col-md-3 --> 
              <div class="col-md-4 col-sm-6">
                <label class="control-label"><?php echo 'Receipt' //__('lblPhone'); ?></label>
                  <div class="form-group">
                    <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_yes" value="1" data-wt="valid" checked><label> Yes</label>
                    <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_no" value="0" data-wt="valid"><label> No</label>
                  </div>
              </div><!-- /.col-md-3 -->   
              <div class="col-md-4 col-sm-6">
                <label class="control-label"><?php echo 'Deals/Offers' //__('lblPhone'); ?></label>
                <div class="form-group">
                  <input type="radio" name="mobile_offer" id="mobile_offer_yes" value="1"><label> Yes</label>
                  <input type="radio" name="mobile_offer" id="mobile_offer_no" value="0" checked><label> No</label>
                </div>
              </div><!-- /.col-md-3 -->   
            </div>
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo 'Email' //__('lblPhone'); ?></label>
                  <input type="text" name="sms_email" id="c_email" class="jsVK-email form-control email"  data-kioskboard-specialcharacters="true" />
                </div>
              </div><!-- /.col-md-3 -->
              <div class="col-md-4 col-sm-6">
                <label class="control-label"><?php echo 'Receipt' //__('lblPhone'); ?></label>
                <div class="form-group">
                  <input type="radio" name="email_receipt" id="email_receipt_yes" value="1" class="fdRequired required" data-wt="valid" checked><label> Yes</label>
                  <input type="radio" name="email_receipt" id="email_receipt_no" value="0" class=" fdRequired required" data-wt="valid"><label> No</label>
                </div>
              </div><!-- /.col-md-3 -->   
              <div class="col-md-4 col-sm-6" id="jsEmailOffer" style="display: none;">
                <label class="control-label"><?php echo 'Deals/Offers' //__('lblPhone'); ?></label>
                  <div class="form-group">
                    <input type="radio" name="email_offer" id="email_offer_yes" value="1" data-wt="valid"><label> Yes</label>
                    <input type="radio" name="email_offer" id="email_offer_no" value="0" data-wt="valid" checked><label>No</label>
                  </div>
              </div><!-- /.col-md-3 -->   
            </div>
          </fieldset>     
          <fieldset>
            <legend style="margin-top: 30px;">Customer Details</legend>        
            <div class="new-client-area">
              <?php
                ob_start();
                $field = 0;
                if (in_array($tpl['option_arr']['o_bf_include_title'], array(2, 3))) {
                  $title_arr = pjUtil::getTitles();
                  $name_titles = __('personal_titles', true, false);
              ?>
              <div class="col-md-2 col-sm-2">
                <div class="form-group">
                  <label class="control-label"><?php __('lblTitle'); ?></label>
                  <select id="c_title" name="c_title" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_title'] == 3) ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">
                    <option value="">----</option>
                    <?php
                    $title_arr = pjUtil::getTitles();
                    $name_titles = __('personal_titles', true, false);
                    unset($name_titles['rev'], $name_titles['dr'], $name_titles['rev'], $name_titles['other'], $name_titles['prof']);
                    $firstElement = array_splice( $name_titles, 0, 1 );
                    $name_titles += $firstElement;
                    foreach ($name_titles as $k => $v) {
                    ?>
                    <option value="<?php echo $k; ?>" <?php echo ($k=="mr")?'selected="selected"':''; ?>><?php echo $v; ?></option><?php
                    }
                    ?>
                  </select>
                </div>
              </div><!-- /.col-md-3 -->
              <?php
                  $field++;
                }
                if (in_array($tpl['option_arr']['o_bf_include_name'], array(2, 3))) {
              ?>
              <div class="col-md-5 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo 'Firstname'//__('lblName'); ?></label>
                  <input type="text" name="c_name" id="c_name" class="jsVK-normal form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="Firstname or Surname is required"/>
                </div>
              </div><!-- /.col-md-3 -->
              <div class="col-md-5 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo 'Surname'//__('lblName'); ?></label>
                  <input type="text" name="surname" id="c_surname" class="jsVK-normal form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="Firstname or Surname is required"/>
                </div>
              </div><!-- /.col-md-3 -->
              <?php
                  $field = $field + 2;
                }
                if ($field == 3) {
                  $ob_fields = ob_get_contents();
                  ob_end_clean();
              ?>
              <div class="row">
                <?php echo $ob_fields;?>
              </div><!-- /.row -->
              <?php 
                } 
              ?>
            </div><!-- /.new-client-area -->
          </fieldset>
          <div class="clearfix">
            <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
            <a class="nav-link next-tab ladda-button btn btn-primary btn-lg pull-right" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="type" aria-selected="false">Next</a>
          </div>
        </div>
        <div class="tab-pane fade" id="orderType" role="tabpanel" aria-labelledby="orderType-tab">
          <div class="row">
          </div>
          <fieldset class="order-delivery" style="display:none">
            <legend>Address Details 
              <span class="pull-right postcode-legend" id="jsOverridePc">
                <label class="legend-input">Override Postcode?</label>
                <div class="switch">
                  <div class="onoffswitch onoffswitch-overridePc" id="postcode-on-off">
                    <input type="checkbox" class="onoffswitch-checkbox" name="override_postcode" id="override_pc">
                    <label class="onoffswitch-label" for="override_pc">
                      <span class="onoffswitch-inner" data-on="Yes" data-off="No"></span>
                      <span class="onoffswitch-switch" id="onoffswitch-switch-legend"></span>
                    </label>
                  </div>
                </div>
              </span>
            </legend>
            <div class="order-delivery">
              <?php
                ob_start();
                $field = 0;
                if (in_array($tpl['option_arr']['o_df_include_zip'], array(1, 2))) {
              ?>
              <div class="form-group">
                <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                    <label class="control-label"><?php echo 'Postcode'; ?></label>
                    <div class="input-group" id="post_code">
                      <input type="text" class="jsVK-normal form-control" placeholder="Type your postCode" name="post_code" id="inputPostCode">
                      <span class="input-group-btn">
                          <button class="btn btn-default" type="button" id="btnFindPostCode"><i class="glyphicon glyphicon-ok"></i></button>
                      </span>
                    </div><!-- /input-group -->
                    <div class="text-danger" style="display: none" id="postCodeErr">Invalid Post Code</div>
                  </div>
                </div><!-- /.col-md-3 -->
                <div class="col-md-6 col-sm-6">
                  <label class="control-label"><?php echo 'Address';// __('lblZip'); ?></label>
                  <div class='form-group' id='addressList'> 
                  </div>
                </div>
                  <?php
                    $field = 2;
                    }
                    if($field == 2) {
                      $ob_fields = ob_get_contents();
                      ob_end_clean();
                    ?>
                    <div class="row">
                      <?php echo $ob_fields;?>
                    </div><!-- /.row -->
                  <?php
                    }
                  ?>          
              </div>
              <?php 
                ob_start();
                $field = 3;
              ?>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php __('lblAddress1'); ?></label>
                  <input type="text" name="d_address_1" id="d_address_1" class="jsVK-normal form-control" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php __('lblAddress2'); ?></label>
                  <input type="text" name="d_address_2" id="d_address_2" class="jsVK-normal form-control"/>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php __('lblCity'); ?></label>
                  <input type="text" name="d_city" id="d_city" class="jsVK-normal form-control" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                </div>
              </div>
              <?php
                $field = 3;
                if ($field == 3) {
                  $ob_fields = ob_get_contents();
                  ob_end_clean();
              ?>
                <div class="row">
                    <?php echo $ob_fields;?>
                </div><!-- /.row -->
              <?php        
                }
              ?> 
            </div>
            <div>
              <?php
                ob_start();
                $field = 3;
                if ($field == 3) {
                  $ob_fields = ob_get_contents();
                  ob_end_clean();
              ?>
                <div class="row">
                    <?php echo $ob_fields;?>
                </div><!-- /.row -->
              <?php
                  ob_start();
                  $field = 0;
                }
                if (in_array($tpl['option_arr']['o_df_include_notes'], array(2, 3))) {
              ?>
                <div class="col-lg-2 col-md-4 col-sm-6">
                  <div class="form-group" id="delivery_fee_frmgrp">
                    <label class="control-label"><?php echo "Delivery fee"; ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                      <input type="text" name="delivery_fee" id="delivery_fee" class="jsVK-price form-control">
                    </div>
                  </div>
                </div><!-- /.col-md-3 -->
              <?php
                  $field++;
                }
                if ($field > 0) {
                  $ob_fields = ob_get_contents();
                  ob_end_clean();
                ?>
                <div class="row">
                  <?php echo $ob_fields;?>
                </div><!-- /.row -->
              <?php
                }
              ?>
            </div><!-- /.delivery -->
          </fieldset>
          <!-- End of Client Details -->
          <?php
            $printedOrders = array();
            $totPrepTime   = 0;
            foreach ($tpl['client_info'] as $orders        => $order) {
              if ($order['kprint'] == 1) {
                $dateOnly      = explode(" ", $order['created']);
                if ($dateOnly[0] == date('Y-m-d')) {
                  $printedOrders[]               = $order['id'];
                }
              }
            }
            foreach ($printedOrders as $pOrder) {
              foreach ($tpl['client_info'] as $orders      => $order) {
                if ($pOrder == $order['id'] && $order['order_despatched'] == 1) {
                  unset($printedOrders[array_search($pOrder, $printedOrders) ]);
                } elseif ($order['id'] == $pOrder) {
                  $totPrepTime = $totPrepTime + $order['preparation_time'];
                }
              }
            }
          ?>
          <fieldset>
            <legend>Order Details <span class="pull-right"><span class="spacing">KOrders : <span id="korders"><?php echo count($printedOrders); ?></span> (<?php echo $totPrepTime?> Mins)</span><span class="spacing"><?php echo 'Prep. Time (Mins) : '; ?></span></span>
            </legend>
            <input type="hidden" id="totKorderPrepTimeInput" name="tot_Korder_preparation_time" value="<?php echo $totPrepTime; ?>">
            <input type="hidden" id="prep_time" name="preparation_time"/>
            <div class="row">
              <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                <label class="control-label">Choose Chef</label>
                <select name="select_box_name" id="chef" style="float: right;" class="form-control fdRequired input-small" data-msg-required="<?php __('fd_field_required', false, true);?>">
                  <option value="">Choose Chef</option>
                  <?php foreach ($tpl['chef_arr'] as $chef => $ch) { ?>
                    <option  value="<?php echo $ch['id']; ?>"  <?php echo ($_SESSION['chef'] == $ch['id']) ? 'selected' : ''; ?>><?php echo $ch['name']; ?></option><?php
                      }
                  ?>
                </select>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="form-group order-delivery">
                  <label><?php __('lblDate'); ?></label>
                  <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                        <input type="text" name="d_date" id="d_date" data-wt="open" class="form-control fdRequired required" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php echo date("d.m.Y");?>" readonly>
                      </div>
                  </div><!-- /.form-group -->
                </div>
                <div class="form-group order-pickup">
                    <label><?php __('lblDate'); ?></label>
                    <div class="form-group">
                      <div class="input-group"> 
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                        <input type="text" name="p_date" id="p_date" data-wt="open" class="form-control fdRequired required" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php 
                            echo date("d.m.Y");
                            ?>" readonly>
                      </div>
                    </div><!-- /.form-group --> 
                </div>
              </div><!-- /.col-md-4 -->
              <!-- <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo "Payment"; //__('lblPaymentMethod');?></label>
                  <?php
                    $online_arr = array();
                    $offline_arr = array();
                    $enabled_payments = $tpl['payment_titles'];
                    //resetting name from 'Bank Account' to 'card'
                    $enabled_payments['bank'] = 'Card';
                    // foreach (__('payment_methods', true, false) as $k => $v) {
                    //     //echo $k;
                    //   if($k == 'bank') continue;
                    //   if($k == 'creditcard') $v = 'card';
                    //   if(in_array($k, array('cash', 'bank'))) {
                    //     $offline_arr[$k] = $v;
                    //   } else {
                    //     $online_arr[$k] = $v;
                    //   }
                    // }
                  ?>
                  <select name="payment_method" id="payment_method" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                    <option value="">-- <?php __('lblChoose'); ?>--</option>
                    <?php foreach($enabled_payments as $k => $v) { ?>
                    <option value="<?php echo $k;?>" <?php echo $k =='cash' ? ' selected="selected"' : NULL;?>><?php echo $v;?></option><?php
                      }
                    ?>
                  </select>
                </div>
              </div> -->
              <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php echo "Paid"; //__('lblOrderIsPaid'); ?></label>
                  <div class="switch">
                    <div class="onoffswitch onoffswitch-data">
                      <input type="checkbox" class="onoffswitch-checkbox" name="is_paid" id="is_paid">
                      <label class="onoffswitch-label" for="is_paid">
                        <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true);?>" data-off="<?php __('_yesno_ARRAY_F', false, true);?>"></span>
                        <span class="onoffswitch-switch"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div><!-- /.col-md-3 -->
            </div>
            <div class="row">
              <div class="col-md-4 col-sm-6 order-pickup">
                <label>Time(Mins)</label>
                <input type="hidden"  name="pickup_time" id="pickup_time" value="<?php echo date("H:i"); ?>">
                <div class="form-group" id="jsChangePTimeDiv" style="display: block">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    <select id="jsChangePTimeDivSelect" class="form-control"><option value="40">40</option>
                      <?php
                        foreach ($times as $time) { ?>
                      <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                      <?php  }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6 order-delivery">
                <label>Time(Mins)</label>
                <input type="hidden" name="delivery_time" id="delivery_time" value="<?php echo date("H:i"); ?>">
                <div class="form-group" id="jsChangeTimeDiv">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    <select id="jsChangeTimeDivSelect" class="form-control"><option value="40">40</option>
                      <?php
                      foreach ($times as $time) { ?>
                      <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                      <?php  }
                      ?>
                    </select>
                  </div>
                </div><!-- /.form-group -->
              </div>
              <div class="col-md-8 col-sm-6">
                <div class="form-group">
                  <label class="control-label"><?php __('lblSpecialInstructions'); ?></label>
                  <textarea name="d_notes" id="d_notes" class="jsVK-normal form-control"></textarea>
                </div>
              </div><!-- /.col-md-3 -->
            </div>
          </fieldset>
          <div class="clearfix">
            <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
            <button type="submit" id="submitJs" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-right" data-style="zoom-in" name="submitTel">
              <span class="ladda-label"><?php //__('btnSave'); ?>Hold</span>
              <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
            </button>
          </div><!-- /.clearfix -->
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="margin-top: 20px;">
    <div class="col-md-8"></div>
  </div>
  <div>
    <?php  
    $message = __('front_minimum_order_amount', true);
    $message = str_replace("{AMOUNT}", pjCurrency::formatPrice($tpl['option_arr']['o_minimum_order']), $message);
    ?>
    <div class="row mt-25">
      <div class="col-sm-12 text-left">
        <div id="alertJs" class="alert alert-warning" role="alert" style="display: none;"><?php echo $message;?>
        </div>
      </div>
      <br/>
    </div><!-- /.row -->
  </div>
</form>