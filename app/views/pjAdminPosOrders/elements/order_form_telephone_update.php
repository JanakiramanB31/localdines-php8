<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionUpdate" method="post" id="frmUpdateOrder_pos" onsubmit="submitTel.disabled = true; return true;">
    <input type="hidden" name="order_update" value="1" />
    <input type="hidden" id="min_amt" value="<?php echo $tpl['option_arr']['o_minimum_order']; ?>" />
    <input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
    <input id="order_despatched_tel" type="hidden" name="order_despatched" value="<?php echo $tpl['arr']['order_despatched']?>" />
    <input id="delivered_customer_tel" type="hidden" name="delivered_customer" value="<?php echo $tpl['arr']['delivered_customer']?>" />
    <input type="hidden" name="delivered_time" value="<?php echo $tpl['arr']['delivered_time']?>" />
    <input type="hidden" name="sms_sent_time" value="<?php echo $tpl['arr']['sms_sent_time']?>" />
    <!-- <input type="hidden" name="is_paid" value="<?php //echo $tpl['arr']['is_paid']?>" /> -->
    <input type="hidden" id="price" name="price" value="<?php echo $tpl['arr']['price']; ?>" />
    <input type="hidden" id="price_packing" name="price_packing" value="<?php echo $tpl['arr']['price_packing']; ?>" />
    <input type="hidden" id="price_delivery" name="price_delivery" value="<?php echo $tpl['arr']['price_delivery']; ?>" />
    <input type="hidden" id="discount" name="discount" value="<?php echo $tpl['arr']['discount']; ?>" />
    <input type="hidden" id="subtotal" name="subtotal" value="<?php echo $tpl['arr']['subtotal']; ?>" />
    <input type="hidden" id="tax" name="tax" value="<?php echo $tpl['arr']['tax']; ?>" />
    <input type="hidden" id="total" name="total" value="<?php echo $tpl['arr']['total']; ?>" />
    <input type="hidden" id="origin" name="origin" value="<?php echo $tpl['arr']['origin']; ?>" />
    <input type="hidden" id="customer_paid" name="customer_paid" value="0" />
    <input type="hidden" id="api_payment_response" name="response" value="" />
    <input type="hidden" id="cash_amount" name="cash_amount" value="" />
    <input type="hidden" id="payment_method" name="payment_method" value="Cash" />
    <input type="hidden" id="vouchercode" name="vouchercode" value="<?php echo  stripslashes($tpl['arr']['voucher_code']);?>" />
    <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
          <div class="form-group pull-right">
            <label style="margin-right: 5px;"><?php __('lblStatus'); ?></label>
            <select name="status" id="status" class="required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                <?php
                $client_sts = ucfirst($tpl['arr']['status']);
                $statuses = __('order_statuses', true, false);
                unset($statuses['confirmed']);
                unset($statuses['delivered']);
                foreach ($statuses as $k => $v) {
                  ?><option value="<?php echo $k; ?>"<?php if ($client_sts==$v) { ?>
                      selected = "selected"
                  <?php }?>><?php echo stripslashes($v); ?></option><?php
                }
                ?>
            </select>
            <div class="switch onoffswitch-data pull-right" style="margin-top:2px; margin-left: 10px;"> 
              <?php //echo $tpl['arr']['type']; ?>
              <div class="onoffswitch onoffswitch-order">
                  <input type="checkbox" class="onoffswitch-checkbox" id="type" name="type"<?php echo $tpl['arr']['type'] == 'delivery' ?  null : ' checked';?>>
                  <label class="onoffswitch-label" for="type">
                  <span class="onoffswitch-inner" data-off="<?php __('types_ARRAY_delivery', false, true);?>" data-on="<?php __('types_ARRAY_pickup', false, true);?>"></span>
                  <span class="onoffswitch-switch"></span>
                  </label>
              </div>
            </div>
          </div>
        </div>
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
                    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_table.php';  ?> 
                    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/voucher_code.php';  ?>
                    <div class="clearfix">
                        
                        <a class="nav-link next-tab ladda-button btn btn-primary btn-lg pull-right" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Next</a>
                        <?php if ($tpl['arr']['status'] == PENDING_STATUS) { ?>
                        <!-- <a class="nav-link ladda-button btn btn-primary btn-lg pull-right" data-cart="<?php echo $tpl['arr']['total']; ?>" id="btn-payment-tel" style="margin-right: 10px;">Payment</a> -->
                      <?php } ?>
                    </div>
                    
                    </div>
                </div>
                <div class="tab-pane fade" id="orderType" role="tabpanel" aria-labelledby="orderType-tab">
                        
                      <fieldset class="order-delivery" style="display: <?php echo $tpl['arr']['type'] == 'delivery' ? 'block' : 'none'; ?>;">
                          <legend>
                            Address Details 
                            <span class="pull-right" id="jsOverridePc">
                              <label class="legend-input postcode-legend">Override Postcode?</label>
                              <div class="switch">
                                <div class="onoffswitch onoffswitch-overridePc" id="postcode-on-off">
                                  <input type="checkbox" class="onoffswitch-checkbox" name="override_postcode" id="override_pc" <?php echo (int) $tpl['arr']['override_postcode'] == 1 ? ' checked' : NULL;?>>
                                 
                                  <label class="onoffswitch-label" for="override_pc">
                                    <span class="onoffswitch-inner" data-on="Yes" data-off="No"></span>
                                    <span class="onoffswitch-switch" id="onoffswitch-switch-legend"></span>
                                  </label>
                                </div>
                              </div>
                            </span>
                          </legend>

                        <div class="order-delivery" >
                            <?php
                            ob_start();
                            $field = 0;
                            //echo $tpl['option_arr']['o_df_include_zip'];
                            if (in_array($tpl['option_arr']['o_df_include_zip'], array(1, 2)))
                            {
                                if ($tpl['arr']['post_code']) {
                                    $postcode = $tpl['arr']['post_code'];
                                    //$postcode = str_replace(' ','',$postcode); 
                                } else {
                                    $postcode = '';
                                }
                                ?>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo 'Postcode'; ?></label>
                                            <div class="input-group" id="post_code">
                                                
                                                <input type="text" class="jsVK-normal form-control fdRequired<?php echo $tpl['arr']['type'] == 'delivery' ? ' required' : NULL; ?>" placeholder="Type your postCode" name="post_code" id="inputPostCode" value="<?php  
                                                echo pjSanitize::html($postcode); ?>" data-wt="valid">
                                        
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnFindPostCode"><i class="glyphicon glyphicon-ok"></i></button>
                                                </span>

                                            <!--  <span class="help-block">Invalid Post Code</span> -->
                                            </div><!-- /input-group -->
                                            <div class="text-danger" style="display: none" id="postCodeErr">Invalid Post Code</div>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-md-6 col-sm-6">
                                        <label class="control-label"><?php echo 'Address';// __('lblZip'); ?></label>
                                        <div class='form-group' id='addressList'> 
                                            <!-- <input type="hidden" name="address"> -->
                                        </div>
                                        
                                    </div>
                                    <?php
                                $field = 2;
                            }
                            if($field == 2)
                            {
                                $ob_fields = ob_get_contents();
                                ob_end_clean();
                                ?>
                                <div class="row">
                                    <?php echo $ob_fields;?>
                                </div><!-- /.row -->
                                <?php
                                // ob_start();
                                // $field = 3;
                            }
                            
                            ?>
                                    
                            </div>

                        <!--  </div> -->
                        <?php 
                        ob_start();
                            $field = 3;
                                ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblAddress1'); ?></label>

                                        <input type="text" name="d_address_1" id="d_address_1" class="form-control<?php echo $tpl['option_arr']['o_df_include_address_1'] == 3 ? ' fdRequired' : NULL; ?><?php echo $tpl['arr']['type'] == 'delivery' ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"
                                        value = "<?php echo pjSanitize::html($tpl['arr']['d_address_1']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblAddress2'); ?></label>

                                        <input type="text" name="d_address_2" id="d_address_2" class="form-control"
                                        value = "<?php echo pjSanitize::html($tpl['arr']['d_address_2']); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblCity'); ?></label>

                                        <input type="text" name="d_city" id="d_city" class="form-control<?php echo $tpl['option_arr']['o_df_include_city'] == 3 ? ' fdRequired' : NULL; ?><?php echo $tpl['arr']['type'] == 'delivery' ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"
                                        value = "<?php echo pjSanitize::html($tpl['arr']['d_city']); ?>" />
                                    </div>

                                </div>
                                <?php
                                $field = 3;
                            
                            if($field == 3)
                            {
                                $ob_fields = ob_get_contents();
                                ob_end_clean();
                                ?>
                                <div class="row">
                                    <?php echo $ob_fields;?>
                                </div><!-- /.row -->
                                <?php
                                // ob_start();
                                // $field = 3;
                            }
                            ?> 
                        </div>
                        <div>
                            <?php
                                ob_start();
                                $field = 3;
                            
                            ?> 
            
                            
                            <?php
                            if($field == 3)
                            {
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
                            if (in_array($tpl['option_arr']['o_df_include_notes'], array(2, 3)))
                            {
                                ?>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="form-group" id="delivery_fee_frmgrp" style="display: <?php echo $tpl['arr']['type'] != 'delivery' ? 'none' : 'block'; ?>;">
                                        <label class="control-label"><?php echo "Delivery fee"; ?></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                                            <input type="text" name="delivery_fee" id="delivery_fee" class="form-control" value="<?php echo $tpl['arr']['price_delivery'];?>" data-wt="valid">
                                        </div>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                
                                
                                <?php
                                $field++;
                            }
                            if($field > 0)
                            {
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
                        <!-- <h4 style="display: inline-block;margin-top: 30px;">Order Details</h4> -->
                         <?php
                                $printedOrders = array();
                                $totPrepTime = 0;

                                foreach ($tpl['client_info'] as $orders => $order) {
                                    if($order['kprint'] == 1) {

                                        $dateOnly = explode(" ",$order['created']);
                                        // print_r("<pre>");print_r($dateOnly);print_r("</pre>");
                                        // print_r(date( 'Y-m-d' ));
                                        if ($dateOnly[0] == date( 'Y-m-d' )) {
                                            $printedOrders[] = $order['id'];
                                        }
                                        
                                        
                                        
                                        }

                                        }


                                foreach ($printedOrders as $pOrder) {
                                    // $i = 0;
                                    // $i = $i+1;

                                    foreach ($tpl['client_info'] as $orders => $order) {
                                        if ($pOrder == $order['id'] && $order['order_despatched'] == 1) {
                                            unset($printedOrders[array_search($pOrder,$printedOrders)]);
                                        } elseif ($order['id'] == $pOrder) {
                                            $totPrepTime = $totPrepTime + $order['preparation_time'];
                                        }
                                    }
                                }
                                
                                        ?>
                        <fieldset>
                          <legend>Order Details<span class="pull-right"><span class="spacing">KOrders : <span id="korders"><?php echo count($printedOrders); ?></span> (<?php echo $totPrepTime?> Mins)</span><span class="spacing"><?php echo 'Prep. Time (Mins) : '; ?></span></span></legend>
                          <div class="row">
                        <!-- /.col-md-3 -->
                        <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                            <label class="control-label">Choose Chef</label>
                            <select name="select_box_name" id="chef" style="float: right;" class="form-control fdRequired input-small" data-msg-required="<?php __('fd_field_required', false, true);?>">
                              <option value="">Choose Chef</option>
                              <?php foreach ($tpl['chef_arr'] as $chef => $ch) { ?>
                                <option  value="<?php echo $ch['id']; ?>"  <?php echo ($tpl['arr']['chef_id'] == $ch['id']) ? 'selected' : ''; ?>><?php echo $ch['name']; ?></option><?php
                                  }
                              ?>
                            </select>
                          </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                              <?php
                                  $dDate = !empty($tpl['arr']['d_dt']) ? date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['d_dt'])) : date("d.m.Y"); 
                                  $dTime = !empty($tpl['arr']['d_dt']) ? date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['d_dt'])) : ''; 
                                  $pDate = !empty($tpl['arr']['p_dt']) ? date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['p_dt'])) : date("d.m.Y"); 
                                  $pTime = !empty($tpl['arr']['p_dt']) ? date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['p_dt'])) : ''; 
                                ?>
                                <?php 
                                    $created = explode(" ",$tpl['arr']['created']);
                                    $delivery = explode(" ",$tpl['arr']['delivery_dt']);
                                    $future = 0;
                                    // if ($created[0] == $delivery[0] && $tpl['arr']['d_time'] > 0 ) { 
                                    if ($created[0] == $delivery[0]) { 
                                        $future = 0;
                                    } else {
                                        $future = 1;
                                    } 
                                    ?>
                             
                                <div class="form-group order-delivery" style="display: <?php echo $tpl['arr']['type']== 'delivery' ? 'block' : 'none';?>;">
                                  <label><?php __('lblDate'); ?></label>
                                  <div class="form-group order-delivery">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                                      <input type="text" name="d_date" id="d_date" data-wt="open" class="form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php 
                                            echo $dDate; ?>"  readonly>
                                            <!--  <input type="hidden" name="delivery_date" id="delivery_date"> -->
                                    </div>
                                  </div><!-- /.form-group -->
                                  <!-- <label>Time(Mins)</label> -->
                                  <input type="hidden" name="delivery_time" id="delivery_time"
                                                value="<?php 
                                                echo $dTime; ?>">
                                    
                                  <input type="hidden" value="<?php echo $future; ?>">
                                  <!-- <div class="form-group" id="jsTimeDiv" style="display: <?php //echo $future == 1 ? 'block' : 'none'; ?>">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                        <input name="d_time1" id="d_time1" class=" form-control fdRequired" data-msg-required="<?php //__('fd_field_required', false, true);?>"/ value = "<?php /*if($tpl['arr']['d_time']==0){ echo 0;}else{echo $tpl['arr']['d_time'];}*/ ?>">    
                                                
                                    </div>
                                 
                                            
                                </div> -->
                                <!-- /.form-group -->
                               
                            </div>
                            <div class="form-group order-pickup" style="display:<?php echo $tpl['arr']['type']== 'pickup' || $tpl['arr']['type']== 'pickup & call' ? 'block' : 'none';?>;">
                              <label><?php __('lblDate'); ?></label>
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                                  <input type="text" name="p_date" id="p_date" data-wt="open" class="form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php 
                                            echo $pDate;
                                            ?>" readonly>
                                </div>
                              </div><!-- /.form-group -->
                            </div>
                          </div><!-- /.col-md-3 -->
                            <!-- <div class="col-lg-3 col-md-3 col-sm-6">
                            </div> -->
                            <div class="col-lg-2 col-md-2 col-sm-6">
        
                                <div class="form-group">
                                    <label class="control-label"><?php __('lblOrderIsPaid'); ?></label>

                                    <div class="switch">
                                        <div class="onoffswitch onoffswitch-data">
                                            <input type="checkbox" class="onoffswitch-checkbox" name="is_paid" id="is_paid"<?php echo (int) $tpl['arr']['is_paid'] == 1 ? ' checked' : NULL;?>>
                                            <label class="onoffswitch-label" for="is_paid">
                                                <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true);?>" data-off="<?php __('_yesno_ARRAY_F', false, true);?>"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            
                            </div><!-- /.col-md-3 -->
                            <!-- /.col-md-3 -->
                            <!-- <div class="col-lg-3 col-md-3 col-sm-6"> -->
                                <!-- <div class="form-group">
                                    <label class="control-label"><?php //__('lblStatus'); ?></label>

                                    <select name="status" id="status" class="form-control required" data-msg-required="<?php //__('fd_field_required', false, true);?>">
                                        <?php
                                        /*$client_sts = ucfirst($tpl['arr']['status']);
                                        $statuses = __('order_statuses', true, false);
                                        unset($statuses['confirmed']);
                                        foreach ($statuses as $k => $v)
                                        { */
                                            ?><option value="<?php //echo $k; ?>"<?php //if ($client_sts==$v) { ?>
                                                selected = "selected"
                                            <?php //}?>><?php //echo stripslashes($v); ?></option><?php
                                        //}
                                        ?>
                                    </select>
                                </div> -->
                            <!-- </div> --><!-- /.col-md-3 -->
                            <!-- <div class="col-lg-3 col-md-3 col-sm-6"> -->
                                <!-- <div class="form-group">
                                    <label class="control-label"><?php //__('lblVoucher'); ?></label>

                                    <input type="text" name="voucher_code" id="voucher_code" class="form-control" value="<?php //echo  stripslashes($tpl['arr']['voucher_code']);?>" data-wt="valid">
                                </div> -->
                            <!-- </div> -->
                        </div> <!-- jaslin -->
                         <div class="row">
                              <div class="col-md-4 col-sm-6 order-pickup" style="display:<?php echo $tpl['arr']['type']== 'pickup' || $tpl['arr']['type']== 'pickup & call' ? 'block' : 'none';?>;">
                                <label>Time(Mins)</label>
                                      <input type="hidden"  name="pickup_time" id="pickup_time" value="<?php echo date("H:i"); ?>">  
                                      <!-- <div class="form-group" id="jsPTimeDiv" style="display: <?php //echo $future == 0 ? 'block' : 'none'; ?>">
                                        <div class="input-group">
                                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input name="p_time" id="p_time" class="form-control fdRequired" data-msg-required="<?php //__('fd_field_required', false, true);?>"  value = "<?php //if($tpl['arr']['p_time']==0){ echo 40;}else{echo $tpl['arr']['p_time'];} ?>"/> 
                                        </div>
                                        <label style="font-size: 10px;">Approx. pickup time:</label><span id="aproxPt"></span>
                                      </div> -->
                                        <div class="form-group" id="jsChangePTimeDiv" >
                                          <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            <select id="jsChangePTimeDivSelect" class="form-control"><option value="40">40</option>
                                                <?php
                                                $p_time = $tpl['arr']['p_time'];
                                                // $del_time = explode(":",$delivery[1]);
                                                // $d_time = $del_time[0].":".$del_time[1];
                                                foreach ($times as $time) { ?>
                                                <option value="<?php echo $time; ?>" <?php if($time == $p_time) { ?> selected = "selected" <?php }?>><?php echo $time; ?></option>
                                                <?php  }
                                                ?>
                                            </select>
                                          </div>
                                        </div>
                              </div>
                              
                              <div class="col-md-4 col-sm-6 order-delivery" style="display:<?php echo $tpl['arr']['type']== 'delivery'? 'block' : 'none';?>;">
                                <label>Time(Mins)</label>
                                <!-- <input type="hidden" name="delivery_time" id="delivery_time" value="<?php //echo date("H:i"); ?>"> -->
                                <!-- <div class="form-group" id="jsTimeDiv" style="display: <?php //echo $future == 0 ? 'block' : 'none'; ?>">
                                  <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    <input name="d_time" id="d_time" class=" form-control fdRequired required" value = "<?php //if($tpl['arr']['d_time']==0){ echo 0;}else{echo $tpl['arr']['d_time'];} ?>" data-msg-required="<?php //__('fd_field_required', false, true);?>"/>    
                                  </div>
                                </div> -->
                                <!-- <div class="form-group" id="jsChangeTimeDiv"
                                  style="display: <?php //echo $future == 1 ? 'block' : 'none'; ?>"> -->
                                  <div class="form-group" id="jsChangeTimeDiv">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>     
                                      <select id="jsChangeTimeDivSelect" class="form-control"><option value="40">40</option>
                                        <?php
                                        //echo 'ttt'.$d_time;
                                        foreach ($times as $time) { ?>
                                        <option value="<?php echo $time; ?>" <?php if($time == $tpl['arr']['d_time']) { ?> selected = "selected" <?php }?>><?php echo $time; ?></option>
                                        <?php  }
                                        ?>
                                      </select>
                                    </div>
                                  </div>
                                <div>
                                  <label style="font-size: 10px;">Approx. del. time:</label>
                                  <span id="aproxDt"><?php echo $dTime; ?></span>
                                </div>
                              </div>
                              <div class="col-md-8 col-sm-6">
                                <div class="form-group">
                                  <label class="control-label"><?php __('lblSpecialInstructions'); ?></label>
                                  <textarea name="d_notes" id="d_notes" class="jsVK-normal form-control"><?php echo $tpl['arr']['d_notes']; ?></textarea>
                                </div>
                              </div><!-- /.col-md-3 -->
                            </div>   
                        <!-- </div> -->
                        
                      </fieldset>



                        <div class="clearfix">
                            <button type="submit" id="submitJs" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-right" data-style="zoom-in" style="margin-right: 15px;" name="submitTel">
                                <span class="ladda-label"><?php __('btnSave'); ?></span>
                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                            </button>

                            
                        </div><!-- /.clearfix -->
                </div>
                <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                <!-- <h4 style="margin-top: 50px;">SMS Delivery Info</h4> -->
                <fieldset>
                <legend style="margin-top: 50px;">SMS Delivery Info</legend>
                
                        <!-- <div class="hr-line-dashed"></div> -->
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo 'Mobile' //__('lblPhone'); ?></label>
                                    <input type="text" name="phone_no" id="phone_no" class="jsVK-numpad form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" value = "<?php echo $tpl['arr']['phone_no']; ?>" data-wt = "<?php if($tpl['arr']['phone_no'] == ''){ echo 'invalid'; }else{ echo 'valid';} ?>" data-kioskboard-type="numpad" data-kioskboard-placement="bottom" placeholder="Your Number"/>

                                </div>
                            </div><!-- /.col-md-3 --> 
                            <div class="col-md-4 col-sm-6">
                                <label class="control-label"><?php echo 'Delivery Info' //__('lblPhone'); ?></label>
                                <div class="form-group">
                                    <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_yes" value="1" <?php if ($tpl['arr']['c_mobileDeliveryInfo'] == 1 ) { ?>checked = "checked" <?php }?> data-wt="valid"><label> Yes</label>
                                    <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_no" value="0"<?php if ($tpl['arr']['c_mobileDeliveryInfo'] == 0 ) { ?>checked = "checked" <?php }?> data-wt="valid"><label> No</label>
                                </div>
                            </div><!-- /.col-md-3 -->   
                            <div class="col-md-4 col-sm-6">
                                <label class="control-label"><?php echo 'Deals/Offers' //__('lblPhone'); ?></label>
                                <div class="form-group">
                                    <input type="radio" name="mobile_offer" id="mobile_offer_yes" value="1"<?php if ($tpl['arr']['c_mobileOffer'] == 1 ) { ?>checked = "checked" <?php }?>><label> Yes</label>
                                    <input type="radio" name="mobile_offer" id="mobile_offer_no" value="0"<?php if ($tpl['arr']['c_mobileOffer'] == 0 ) { ?>checked = "checked" <?php }?>><label> No</label>
                                </div>
                            </div><!-- /.col-md-3 -->      
                        </div>
                        <div class="row">
                        <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo 'Email' //__('lblPhone'); ?></label>
                                    <input type="text" name="sms_email" id="c_email" class="jsVK-email form-control email"
                                    value = "<?php echo $tpl['arr']['sms_email']; ?>" data-wt = "<?php if($tpl['arr']['sms_email'] == ''){ echo 'invalid'; }else{ echo 'valid';} ?>" />
                                </div>
                            </div><!-- /.col-md-3 --> 
                            
                            
                            <div class="col-md-4 col-sm-6">
                                <label class="control-label"><?php echo 'Delivery/Receipt' //__('lblPhone'); ?></label>
                                    <div class="form-group">
                                        <input type="radio" name="email_receipt" id="email_receipt_yes" value="1"<?php if ($tpl['arr']['c_emailReceipt'] == 1 ) { ?>checked = "checked" <?php }?> data-wt="valid"><label> Yes</label>
                                    <input type="radio" name="email_receipt" id="email_receipt_no" value="0"<?php if ($tpl['arr']['c_emailReceipt'] == 0 ) { ?>checked = "checked" <?php }?> data-wt="valid"><label> No</label>
                                    </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6" id="jsEmailOffer" style="display: <?php echo $tpl['arr']['sms_email'] != '' ? 'block' : 'none'; ?>">
                                <label class="control-label"><?php echo 'Discount Codes/Offers' //__('lblPhone'); ?></label>
                                <div class="form-group">
                                    <input type="radio" name="email_offer" id="email_offer_yes" value="1"<?php if ($tpl['arr']['c_emailOffer'] == 1 ) { ?>checked = "checked" <?php }?> ><label> Yes</label>
                                    <input type="radio" name="email_offer" id="email_offer_no" value="0"<?php if ($tpl['arr']['c_emailOffer'] == 0 ) { ?>checked = "checked" <?php }?>><label> No</label>
                                </div>
                            </div><!-- /.col-md-3 -->      
                        </div>
                        </fieldset>
                        <fieldset>
                            <!-- <h4 style="margin-top: 30px;">Customer Details</h4>                            -->
                            <legend style="margin-top: 30px;">Customer Details</legend>                           
                            <!-- <div class="hr-line-dashed"></div> -->
                            <!-- Client Details -->
                            <div class="new-client-area">
                            <?php
                            ob_start();
                            $field = 0;
                            if (in_array($tpl['option_arr']['o_bf_include_title'], array(2, 3)))
                            {
                                $title_arr = pjUtil::getTitles();
                                $name_titles = __('personal_titles', true, false);
                                // $tpl['arr']['c_title'] == 'other' ? $client_title = ucfirst($tpl['arr']['c_title']) :
                                // $client_title = ucfirst($tpl['arr']['c_title']).'.';
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
                                            if($tpl['arr']['guest_title'] != NULL) {
                                                foreach ($name_titles as $k => $v)
                                                //foreach ($title_arr as $v)
                                                {
                                                    ?><option value="<?php echo $k; ?>"<?php if ($tpl['arr']['guest_title']==$k) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option><?php
                                                }
                                            } else {
                                                foreach ($name_titles as $k => $v)
                                                //foreach ($title_arr as $v)
                                                {
                                                    ?><option value="<?php echo $k; ?>"<?php if ($tpl['arr']['c_title']==$k) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option><?php
                                                }
                                            }
                                            
                                            ?>
                                        </select>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                <?php
                                $field++;
                            }
                            if (in_array($tpl['option_arr']['o_bf_include_name'], array(2, 3)))
                            {
                                ?>

                                <div class="col-md-5 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo 'Firstname'//__('lblName'); ?></label>

                                        <input type="text" name="c_name" id="c_name" class="jsVK-normal form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 && $tpl['arr']['surname'] == '' ? ' fdRequired required' : NULL; ?>" data-msg-required="Firstname or Surname is required" value="<?php echo $tpl['arr']['first_name']? $tpl['arr']['first_name']:'' ?>"/>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                <div class="col-md-5 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo 'Surname'//__('lblName'); ?></label>

                                        <input type="text" name="surname" id="c_surname" class="jsVK-normal form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3  && $tpl['arr']['first_name'] == '' ? '' : NULL; ?>" data-msg-required="Firstname or Surname is required"  value="<?php echo $tpl['arr']['surname']?$tpl['arr']['surname']:'' ?>"/>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                <?php
                                $field = $field + 2;
                            }
                            
                            if($field == 3)
                            {
                                $ob_fields = ob_get_contents();
                                ob_end_clean();
                                ?>
                                <div class="row">
                                    <?php echo $ob_fields;?>
                                </div><!-- /.row -->
                                <?php
                                // ob_start();
                                // $field = 0;
                            }
                            ?>
                        </div><!-- /.new-client-area -->
                      </fieldset>
                        <div class="clearfix">
                            <a class="nav-link next-tab ladda-button btn btn-primary btn-lg pull-right" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="type" aria-selected="false">Next</a>
                            
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row" style="margin-top: 20px;">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-right">Total cart amount: <b id="cartPriceBottom"><?php //echo pjCurrency::formatPrice($tpl['arr']['total']);?></div>
    </div> -->
    <div>
        <?php  
        $message = __('front_minimum_order_amount', true);
        $message = str_replace("{AMOUNT}", pjCurrency::formatPrice($tpl['option_arr']['o_minimum_order']), $message);
        ?>
        <div class="row mt-25">
            <div class="col-sm-12 text-left">
                <div id="alertJs" class="alert alert-warning" role="alert" style="display: none;"><?php echo $message;?></div>
            </div>
            <br/>
        </div><!-- /.row -->
    </div>
</form>