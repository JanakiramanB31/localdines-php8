<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <?php //print_r($tpl['category_arr']); ?>
        <div class="row">
            <div class="col-sm-10">
                <h2 id="createPage"><?php __('infoAddOrderTitle');?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoAddOrderDesc');?></p>
      
    </div><!-- /.col-md-12 -->
</div>
<?php
$time_format = 'HH:mm';
if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1))
{
    $time_format = 'hh:mm a';
}
if((strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
{
    $time_format = 'hh:mm A';
}
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
$statuses = __('order_statuses', true, false);
unset($statuses['cancelled']);
unset($statuses['delivered']);
?>
<div class="row wrapper wrapper-content animated fadeInRight" id="orderContainer">
    <div class="col-lg-9">
    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrders&amp;action=pjActionCreate" method="post" id="frmCreateOrder">
        <div class="row">
            <div class="col-md-6"></div>
           
            <div class="col-md-3">
                <div class="switch onoffswitch-data pull-right">
                    <div class="onoffswitch onoffswitch-order">
                        <input type="checkbox" class="onoffswitch-checkbox" id="type" name="type" checked>
                        <label class="onoffswitch-label" for="type">
                            <span class="onoffswitch-inner" data-on="<?php __('types_ARRAY_delivery', false, true);?>" data-off="<?php __('types_ARRAY_pickup', false, true);?>"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="form-group">
                    <select name="select_box_name" id="chef" style="float: right;" class="form-control fdRequired required input-small" data-msg-required="<?php __('fd_field_required', false, true);?>">
                        <option value="">Choose Chef</option>
                            <?php
                                foreach ($tpl['chef_arr'] as $chef => $ch)
                                {

                                    ?><option  value="<?php echo $ch['id']; ?>"  <?php echo ($_SESSION['chef'] == $ch['id']) ? 'selected' : ''; ?>><?php echo $ch['name']; ?></option><?php
                                }
                                ?>

                    </select>
                </div>
                
            </div>
        </div>
            
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
            <input type="hidden" id="vouchercode" name="vouchercode" value="" />
			<input type="hidden" id="call_start" name="call_start" value="<?php echo date('h:i:s A'); ?>" />
			
            <!-- MEGAMIND -->

            <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
            <div class="tabs-container tabs-reservations m-b-lg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#order-details" aria-controls="order-details" role="tab" data-toggle="tab"><?php __('menuOrders'); ?></a></li>
                </ul>
    
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="order-details">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-6">
                                    <div class="form-group col-sm-12 col-md-12">
                                            <?php
                                            foreach ($tpl['category_arr'] as $k)
    										{
                                                if ($k['status'] == 'T') {
                                                    
    											?><a  href="#" class="btn btn-primary btn-sm clickCategory" role="button" style="margin-right:5px;margin-bottom: 5px;" data-id='<?php echo $k['id']; ?>'><?php echo $k['name']; ?></a><?php
                                                }
    										}
    										?>
                                
                                    </div>
                                   
                                </div><!-- /.col-md-3 -->
                            </div><!-- /.row -->
    
                            <div class="hr-line-dashed"></div>
    
                            <div class="form-group ibox-content">
                            	<div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>
                                <div class="table-responsive table-responsive-secondary">

                                    <table id="fdOrderList" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php __('lblProduct');?></th>
                                                <th><?php __('lblQty');?></th>
                                                <th>
                                                    <div class="p-w-xs"><?php __('lblExtra');?></div>
                                                </th>
                                                <th><?php __('lblCategory');?></th>
                                                <th><?php __('lblSizeAndPrice');?></th>
                                                <th><?php echo 'Prep.Time';?></th>
                                                <th><?php __('lblTotal');?></th>
                                                <th><?php echo 'Special Instruction';?></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $index = "new_" . mt_rand(0, 999999); 
                                        ?>                    
                                        <tbody class="main-body">
                                            <tr class="fdLine" data-index="<?php echo $index;?>">
                                                <!-- <?php //print_r($tpl['product_arr']); ?>  -->
                                                <td>
                                                    <select id="fdProduct_<?php echo $index;?>" data-index="<?php echo $index;?>" name="product_id[<?php echo $index;?>]" class="selectpicker fdProduct" data-live-search="true">
                										<option value="">-- <?php __('lblChoose'); ?>--</option>
                										<?php
                										foreach ($tpl['product_arr'] as $p)
                                                        {
                                                            if ($p['status'] == 1) {
                                                                
                                                            
                									
                											?><option value="<?php echo $p['id']; ?>" data-extra="<?php echo $p['cnt_extras'];?>"><?php echo stripslashes($p['name']); ?></option><?php
                                                            //echo $p['cnt_extras'];
                                                        }
                										}
                										?>
                									</select>
                                                </td>
                                                <td>
                									<div class="business-<?php echo $index;?>" style="display: none;">
                										<input type="text" id="fdProductQty_<?php echo $index;?>" name="cnt[<?php echo $index;?>]" class="form-control pj-field-count" value="1" style="width: 35px;" />
                									</div>
                								</td>
                                                <td>
                									<div class="business-<?php echo $index;?>" style="display: none;">
                										<table id="fdExtraTable_<?php echo $index;?>" class="table no-margins pj-extra-table">							
                											<tbody>
                											</tbody>
                										</table>
                										<div class="p-w-xs">
                                                            <a href="#" class="btn btn-primary btn-xs btn-outline pj-add-extra fdExtraBusiness_<?php echo $index;?> fdExtraButton_<?php echo $index;?>" data-index="<?php echo $index;?>"><i class="fa fa-plus"></i> <?php __('btnAddExtra');?></a>
                                                        </div><!-- /.p-w-xs -->
                										<span class="fdExtraBusiness_<?php echo $index;?> fdExtraNA_<?php echo $index;?>"><?php __('lblNA');?></span>
                									</div>
                								</td>

                                                <!-- MEGAMIND -->

                                                <td>
                                                    <span id="fdCategory_<?php echo $index;?>"></span>

                                                </td>

                                                <!-- MEGAMIND -->

                                                <td id="fdPriceTD_<?php echo $index;?>">
                                                    <div class="business-<?php echo $index;?>" style="display: none;">
                                                        <select id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-type="select" class="fdSize form-control">
                                                            <option value="">-- <?php __('lblChoose'); ?>--</option>
                                                        </select>
                                                    </div>
                                                </td>
                            
                                                

                                                <!-- MEGAMIND --> 

                                                <td>
                                                    <span id="fdPrepTime_<?php echo $index;?>"></span>

                                                </td>

                                                <!-- MEGAMIND -->
                                                
                                                <td>
                									<strong><span id="fdTotalPrice_<?php echo $index;?>"><?php echo pjCurrency::formatPrice(0);?></span></strong>
                								</td>
                                                            
                                                <td>
                                                    <input type="text" id="special_instruction" name="special_instruction[<?php echo $index;?>]" class="form-control" value="" />
                                                </td>
                                                <td>
                                                    <input type="hidden" id="jsIndex" value="<?php echo $index;?>">
                                                    <div class="text-right" id="productDelete_rowOne<?php //echo $index;?>" style="display: none">
                                                        <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
    
                                        </tbody>
                                    </table>
                                    <div class="m-b-md">
                                        <a href="#" class="btn btn-primary btn-outline m-t-xs" id="btnAddProduct" style="display: none"><i class="fa fa-plus"></i> <?php __('btnAddProduct');?></a>
                                    </div>
                                    
                                </div>
                            </div>
                           
                            <h4 style="margin-top: 50px;">SMS Delivery Info</h4>
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo 'Mobile' //__('lblPhone'); ?></label>
                                        <input type="text" name="phone_no" id="phone_no" class="form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                    </div>
                                </div><!-- /.col-md-3 --> 
                                <div class="col-md-4 col-sm-6">
                                    <label class="control-label"><?php echo 'Delivery Info' //__('lblPhone'); ?></label>
                                    <div class="form-group">
                                        <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_yes" value="1" data-wt="valid" checked><label> Yes</label>
                                        <input type="radio" name="mobile_delivery_info" id="mobile_delivery_info_no" value="0" data-wt="valid"><label> No</label>
                                    </div>
                                </div><!-- /.col-md-3 -->   
                                <div class="col-md-4 col-sm-6">
                                <label class="control-label"><?php echo 'Discount Codes/Offers' //__('lblPhone'); ?></label>
                                    <div class="form-group">
                                        <input type="radio" name="mobile_offer" id="mobile_offer_yes" value="1"><label> Yes</label>
                                        <input type="radio" name="mobile_offer" id="mobile_offer_no" value="0"><label> No</label>
                                    </div>
                                </div><!-- /.col-md-3 -->   
                            </div>
                            <div class="row">
                            <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo 'Email' //__('lblPhone'); ?></label>
                                        <!-- <input type="text" name="sms_email" id="c_email" class="form-control email<?php //echo $tpl['option_arr']['o_bf_include_email'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php //__('fd_field_required', false, true);?>"/> -->
                                        <input type="text" name="sms_email" id="c_email" class="form-control email" />
                                    </div>
                                </div><!-- /.col-md-3 --> 
                                
                                <div class="col-md-4 col-sm-6">
                                <label class="control-label"><?php echo 'Delivery Info/Receipt' //__('lblPhone'); ?></label>
                                    <div class="form-group">
                                        <input type="radio" name="email_receipt" id="email_receipt_yes" value="1" class="fdRequired required" data-wt="valid" checked><label> Yes</label>
                                        <input type="radio" name="email_receipt" id="email_receipt_no" value="0" class=" fdRequired required" data-wt="valid"><label> No</label>
                                    </div>
                                </div><!-- /.col-md-3 -->   
                                <div class="col-md-4 col-sm-6" id="jsEmailOffer" style="display: none;">
                                <label class="control-label"><?php echo 'Discount Codes/Offers' //__('lblPhone'); ?></label>
                                    <div class="form-group">
                                        <input type="radio" name="email_offer" id="email_offer_yes" value="1" data-wt="valid"><label> Yes</label>
                                        <input type="radio" name="email_offer" id="email_offer_no" value="0" data-wt="valid"><label>No</label>
                                    </div>
                                </div><!-- /.col-md-3 -->   
                            </div>
                            <h4 style="margin-top: 30px;">Customer Details</h4>                           
                            <div class="hr-line-dashed"></div>
                            <!-- Client Details -->
                            <div class="new-client-area">
    							<?php
    							ob_start();
    							$field = 0;
    							if (in_array($tpl['option_arr']['o_bf_include_title'], array(2, 3)))
    							{
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
                                                unset($name_titles['rev']);
                                                foreach ($name_titles as $k => $v)
        										// foreach ($title_arr as $v)
        										{
        										// 	?><!-- <option value="<?php //echo $v; ?>"><?php //echo $name_titles[$v]; ?></option> --><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
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
    
                                            <input type="text" name="c_name" id="c_name" class="form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-md-5 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo 'Surname'//__('lblName'); ?></label>
    
                                            <input type="text" name="surname" id="c_surname" class="form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
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
                             <h4 style="margin-top: 30px;">Address Details</h4>
                            <div class="hr-line-dashed"></div>

    						<div class="order-delivery">
                                
                                <?php
                                ob_start();
                                $field = 0;
                                //echo $tpl['option_arr']['o_df_include_zip'];
                                if (in_array($tpl['option_arr']['o_df_include_zip'], array(1, 2)))
    							{
    							    ?>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo 'Postcode'; ?></label>
                                                <div class="input-group" id="post_code">
                                                    
                                                    <input type="text" class="form-control fdRequired required" placeholder="Type your postCode" name="post_code" id="inputPostCode">
                                            
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
    
                                            <input type="text" name="d_address_1" id="d_address_1" class="form-control<?php echo $tpl['option_arr']['o_df_include_address_1'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblAddress2'); ?></label>
    
                                            <input type="text" name="d_address_2" id="d_address_2" class="form-control<?php echo $tpl['option_arr']['o_df_include_address_2'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblCity'); ?></label>
    
                                            <input type="text" name="d_city" id="d_city" class="form-control<?php echo $tpl['option_arr']['o_df_include_city'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
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
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblSpecialInstructions'); ?></label>
    
                                            <textarea name="d_notes" id="d_notes" class="form-control<?php echo $tpl['option_arr']['o_df_include_notes'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"></textarea>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                        <div class="form-group" id="delivery_fee_frmgrp">
                                            <label class="control-label"><?php echo "Delivery fee"; ?></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                                                <input type="text" name="delivery_fee" id="delivery_fee" class="form-control">
                                            </div>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-md-3 col-sm-6" id="jsOverridePc">
                
                                    <div class="form-group">
                                        <label class="control-label">Override Postcode?</label>
    
                                        <div class="switch">
                                            <div class="onoffswitch onoffswitch-overridePc">
                                                <input type="checkbox" class="onoffswitch-checkbox" name="override_pc" id="override_pc">
                                                <label class="onoffswitch-label" for="override_pc">
                                                    <span class="onoffswitch-inner" data-on="Yes" data-off="No"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    
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

                            <!-- End of Client Details -->
                            <h4 style="display: inline-block;margin-top: 30px;">Order Details</h4>
                           
                            <div class="col-sm-6 col-md-6 pull-right">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12" style="padding-right: 5px;padding-left: 5px;">
                                        
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
                                        <div style="background-color: #fbc994;color: black;margin-bottom: 5px;margin-top: 5px;padding: 10px 2px 10px 5px;border-radius: 3px;float: right;">
                                            <label>Kitchen Orders:</label>
                                            <span id="korders"><?php echo count($printedOrders); ?></span>
                                            <span>(<?php echo $totPrepTime?> Mins)</span>
                                            <input type="hidden" id="totKorderPrepTimeInput" name="tot_Korder_preparation_time" value="<?php echo $totPrepTime; ?>">
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6 col-sm-12" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="" style="display: inline; float: right;background-color: #fbc994;color: black;margin-bottom: 5px;margin-top: 5px;padding: 10px 2px 10px 5px;border-radius: 3px;">
                                        <label class="control-label"><?php echo 'Total Prep.Time(Mins):'; ?></label>

                                        <span id="total_prep-time_format" data-msg-required="<?php __('fd_field_required', false, true);?>"></span>
                                        <input type="hidden" id="prep_time" name="preparation_time"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="hr-line-dashed"></div>
                               
                            <div class="row">
                                <!-- <div class="col-lg-2 col-md-2 col-sm-6"> -->
                                    <!-- <div class="form-group">
                                        <label class="control-label"><?php __('lblType'); ?></label>
    
                                        <div class="clearfix">
                                            <div class="switch onoffswitch-data pull-left">
                                                <div class="onoffswitch onoffswitch-order">
                                                    <input type="checkbox" class="onoffswitch-checkbox" id="type" name="type" checked>
                                                    <label class="onoffswitch-label" for="type">
                                                        <span class="onoffswitch-inner" data-on="<?php __('types_ARRAY_delivery', false, true);?>" data-off="<?php __('types_ARRAY_pickup', false, true);?>"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --><!-- /.clearfix -->
                                    <!-- </div>
                                </div> --><!-- /.col-md-3 -->
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="form-group order-delivery">
                                       
                                        <label><?php __('lblDate'); ?></label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
            
                                                <input type="text" name="d_date" id="d_date" data-wt="open" class="form-control fdRequired required" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php 
                                                  echo date("d.m.Y");
                                                ?>" readonly>
                                            </div>
                                        </div><!-- /.form-group -->
                                         <label>Time(Mins)</label>
                                         <input type="hidden" name="delivery_time" id="delivery_time">
                                        <div class="form-group" id="jsTimeDiv">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                
                                                <input name="d_time" id="d_time" class=" form-control fdRequired required" data-msg-required="<?php __('fd_field_required', false, true);?>"/>    
                                                
                                                
                                            </div>
                                            <div>
                                                <label>Approximate delivery time:</label><span id="aproxDt"></span>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group" id="jsChangeTimeDiv" style="display: none">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                
                                                <select id="jsChangeTimeDivSelect" class="form-control"><option value="">-- Choose Time --</option>
                                                <?php
                                                $times = ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00",
                                                  "06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30",
                                                  "15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
                                                  foreach ($times as $time) { ?>
                                                  <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                                <?php  }
                                                ?>
                                                </select>
                                                
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
                                        <label>Time(Mins)</label>
                                        <input type="hidden"  name="pickup_time" id="pickup_time">  
                                        <div class="form-group" id="jsPTimeDiv">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                    
                                                <input name="p_time" id="p_time" class="form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>"/> 
                                                

                                            </div>
                                             <label>Approximate pickup time:</label><span id="aproxPt"></span>

                                        </div>
                                        <div class="form-group" id="jsChangePTimeDiv" style="display: none">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                
                                                <select id="jsChangePTimeDivSelect" class="form-control"><option value="">-- Choose Time --</option>
                                                <?php
                                                $times = ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00",
                                                  "06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30",
                                                  "15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
                                                  foreach ($times as $time) { ?>
                                                  <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                                <?php  }
                                                ?>
                                                </select>
                                                
                                                
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                        
                                        <label class="control-label"><?php __('lblPaymentMethod');?></label>
                                        <?php
                                        //print_r($tpl['payment_titles']);
                                        $online_arr = array();
                                        $offline_arr = array();
                                        foreach (__('payment_methods', true, false) as $k => $v)
                                        {
                                            if($k == 'creditcard') continue;
                                            if(in_array($k, array('cash', 'bank')))
                                            {
                                                $offline_arr[$k] = $v;
                                            }else{
                                                $online_arr[$k] = $v;
                                            }
                                        }
                                        ?>
                                        <select name="payment_method" id="payment_method" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                                            <option value="">-- <?php __('lblChoose'); ?>--</option>
                                            <optgroup label="<?php __('script_online_payment_gateway', false, true); ?>">
                                            <?php
                                            foreach($online_arr as $k => $v)
                                            {
                                                ?><option value="<?php echo $k;?>"><?php echo $v;?></option><?php
                                            }
                                            ?>
                                            </optgroup>
                                            <optgroup label="<?php __('script_offline_payment', false, true); ?>">
                                            <?php
                                            foreach($offline_arr as $k => $v)
                                            {
                                                ?><option value="<?php echo $k;?>"><?php echo $v;?></option><?php
                                            }
                                            ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblOrderIsPaid'); ?></label>
    
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
                                <!-- /.col-md-3 -->
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblStatus'); ?></label>
    
                                        <select name="status" id="status" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                                            <?php
    										foreach ($statuses as $k => $v)
    										{
    											?><option value="<?php echo $k; ?>"<?php echo $k =='pending' ? ' selected="selected"' : NULL;?>><?php echo stripslashes($v); ?></option><?php
    										}
    										?>
                                        </select>
                                    </div>
                                </div><!-- /.col-md-3 -->
                                
                                
                            </div>
                            <div class="clearfix">
                                <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                                <button type="submit" id="submitJs" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-right" data-style="zoom-in" style="margin-right: 15px;"

                                >
                                    <span class="ladda-label"><?php __('btnSave'); ?></span>
                                    <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                                </button>
                                
                            </div><!-- /.clearfix -->
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
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- /.col-lg-8 -->

    <div class="col-lg-3">
        <div class="m-b-lg">
            <div  class="panel no-borders ibox-content">
                <div class="panel-heading bg-pending desc-head">
                    <p class="lead m-n"><i class="fa fa-check"></i> <?php echo 'Description'  ?>: <span class="pull-right status-text"></span></p>    
                </div><!-- /.panel-heading -->
                <div class='panel-body desc-body' id="curProdDesc">

                    <!-- MEGAMIND --> 

                    <p id="fdDescription"></p>

                    <!-- <div class="hr-line-dashed"></div>

                    <h3 class="lead m-b-md"><?php //echo "Total Prep.Time:";?> <strong id="total_prep-time_format" class="pull-rigth text-right"></strong></h3> -->
                    
                    <!-- MEGAMIND -->
                
                </div>
            </div>
        </div>
        <div class="m-b-lg">
            <div  class="panel no-borders ibox-content">
                <div class="panel-heading bg-pending desc-head">
                    <p class="lead m-n"><i class="fa fa-check"></i> <?php __('lblVoucher'); ?>: <span class="pull-right status-text"></span></p>    
                </div><!-- /.panel-heading -->
                <div class='panel-body desc-body' id="curProdDesc">

                    <!-- MEGAMIND --> 

                    <input type="text" name="voucher_code" id="voucher_code" class="form-control" disabled>

                    <!-- <div class="hr-line-dashed"></div>

                    <h3 class="lead m-b-md"><?php //echo "Total Prep.Time:";?> <strong id="total_prep-time_format" class="pull-rigth text-right"></strong></h3> -->
                    
                    <!-- MEGAMIND -->
                
                </div>
            </div>
        </div>
        <div class="m-b-lg">
            <div id="pjFdPriceWrapper" class="panel no-borders ibox-content">
            	<div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>
                <div class="panel-heading bg-pending">
                    <p class="lead m-n"><i class="fa fa-check"></i> <?php __('lblStatus'); ?>: <span class="pull-right status-text"><?php __('order_statuses_ARRAY_pending');?></span></p>    
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                    <p class="lead m-b-md"><?php __('lblPrice'); ?>: <span id="price_format" class="pull-right"><?php echo pjCurrency::formatPrice(0);?></span></p>
                    <!-- <p class="lead m-b-md"><?php//__('lblPacking'); ?>: <span id="packing_format" class="pull-right"><?php //echo pjCurrency::formatPrice(0);?></span></p> -->
                    <p class="lead m-b-md"><?php __('lblDelivery'); ?>: <span id="delivery_format" class="pull-right"><?php echo pjCurrency::formatPrice(0);?></span></p>
                    <p class="lead m-b-md"><?php __('lblDiscount'); ?>: <span id="discount_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice(0);?></span></p>
                    <p class="lead m-b-md"><?php __('lblSubTotal'); ?>: <span id="subtotal_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice(0);?></span></p>
                    <!-- <p class="lead m-b-md"><?php //__('lblTax'); ?>: <span id="tax_format" class="pull-right text-right"><?php //echo pjCurrency::formatPrice(0);?></span></p> -->

                    <div class="hr-line-dashed"></div>

                    <h3 class="lead m-b-md"><?php __('lblTotal'); ?>: <strong id="total_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice(0);?></strong></h3>

                </div><!-- /.panel-body -->
            </div>

        </div><!-- /.m-b-lg -->
        <!-- <div class="">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblVoucher'); ?></label>
    
                                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" disabled>


                                    </div>
                                </div>
    </div> -->
    <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->
</div><!-- /.wrapper wrapper-content -->

<table style="display: none" id="boxProductClone">
	<tbody>
	<?php
	include PJ_VIEWS_PATH . 'pjAdminOrders/elements/clone.php'; 
	?>
	</tbody>
</table>
<!-- Modal Popup for selected category -->
<!-- Button trigger modal -->
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


<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="catModalTitle"></h2>
      </div>
      <div class="modal-body"  id="catModalBody">
       
      </div>
      <div class="modal-footer">
        <div class="row">
            <div id='page_navigation' class="col-sm-8">
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div class="modal fade" id="catModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="catModalTitle"></h2>
      </div>
      <div class="modal-body" id="catModalBody">

      </div>
      <div class="modal-footer">
        <div class="row">
            <div id='page_navigation' class="col-sm-8">
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        
        
      </div>
    </div>
  </div>
</div> -->
<!-- End of Modal -->
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>

<script type="text/javascript">


//console.log(client_info);
var myLabel = myLabel || {};
myLabel.currency = "<?php echo $tpl['option_arr']['o_currency'];?>";
myLabel.restaurant_closed = <?php x__encode('lblRestaurantClosed');?>;
myLabel.email_exists = <?php x__encode('email_taken'); ?>;
myLabel.phoneNumber_err = '<?php echo 'Mobile Number is invalid'; ?>';
myLabel.email_err = '<?php echo 'Email address is invalid'; ?>';
myLabel.voucher_err = '<?php echo 'Voucher code is invalid'; ?>';
myLabel.delivery_fee_err = '<?php echo 'This field only accepts integer and float values'; ?>'; 
myLabel.mobileDelivery_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
myLabel.emailReceipt_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
var categoryList = '<?php echo json_encode($tpl['category_list']); ?>';  
categoryList =  JSON.parse(categoryList);  
var client_info = '<?php echo json_encode($tpl['client_info']); ?>';
client_info = JSON.parse(client_info);

</script>