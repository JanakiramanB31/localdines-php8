<div class="row wrapper border-bottom white-bg page-heading">
      <?php //echo "<pre>";print_r($tpl['product_arr']); ?>
     <?php //echo "<pre>";print_r($tpl['arr']); ?>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <!-- <h2><?php //__('infoUpdateOrderTitle');?></h2> -->
                <h2 id="updatePage">Update Order</h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoUpdateOrderDesc');?></p>
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
?>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-9">
    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrders&amp;action=pjActionUpdate" method="post" id="frmUpdateOrder">
            <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-3">
                <div class="switch onoffswitch-data pull-right">
                    <div class="onoffswitch onoffswitch-order">
                        <input type="checkbox" class="onoffswitch-checkbox" id="type" name="type"<?php echo $tpl['arr']['type'] == 'delivery' ? ' checked' : NULL;?>>
                        <label class="onoffswitch-label" for="type">
                            <span class="onoffswitch-inner" data-on="<?php __('types_ARRAY_delivery', false, true);?>" data-off="<?php __('types_ARRAY_pickup', false, true);?>"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select name="chef_id" id="chef" style="float: right;" class="form-control fdRequired required input-small" data-msg-required="<?php __('fd_field_required', false, true);?>">
                        <option value="">Choose Chef</option>
                            <?php
                                foreach ($tpl['chef_arr'] as $chef => $ch)
                                {
                                    ?><option  value="<?php echo $ch['id']; ?>"  <?php echo ($tpl['arr']['chef_id'] == $ch['id']) ? 'selected' : ''; ?>><?php echo $ch['name']; ?></option><?php
                                }
                                ?>

                           <!--  <option value="1">one</option>
                            <option value="2">two</option> -->
                    </select>
                </div>
                
            </div>
        </div>
			<input type="hidden" name="order_update" value="1" />
            <input type="hidden" id="min_amt" value="<?php echo $tpl['option_arr']['o_minimum_order']; ?>" />
			<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
            <input type="hidden" name="order_despatched" value="<?php echo $tpl['arr']['order_despatched']?>" />
            <input type="hidden" name="delivered_customer" value="<?php echo $tpl['arr']['delivered_customer']?>" />
            <input type="hidden" name="delivered_time" value="<?php echo $tpl['arr']['delivered_time']?>" />
            <input type="hidden" name="sms_sent_time" value="<?php echo $tpl['arr']['sms_sent_time']?>" />
            <!-- <input type="hidden" name="is_paid" value="<?php //echo $tpl['arr']['is_paid']?>" /> -->
			<input type="hidden" id="price" name="price" value="<?php echo $tpl['arr']['price']; ?>" />
			<input type="hidden" id="price_packing" name="price_packing" value="<?php echo $tpl['arr']['price_packing']; ?>" />
			<input type="hidden" id="price_delivery" name="price_delivery" value="<?php echo $tpl['arr']['price_delivery']; ?>" />
			<input type="hidden" id="discount" name="discount" value="<?php echo $tpl['arr']['discount']; ?>" />
			<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $tpl['arr']['subtotal']; ?>" />
			<input type="hidden" id="tax" name="tax" value="<?php echo $tpl['arr']['tax']; ?>" />
            <input type="hidden" id="vouchercode" name="vouchercode" value="<?php echo $tpl['arr']['voucher_code']; ?>" />
			<input type="hidden" id="total" name="total" value="<?php echo $tpl['arr']['total']; ?>" />
			
			<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
            <div class="tabs-container tabs-reservations m-b-lg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#order-details" aria-controls="order-details" role="tab" data-toggle="tab"><?php __('menuOrders'); ?></a></li>
                </ul>
    
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="order-details">

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group col-sm-6 col-md-8">
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
                                                <div class="m-b-md" style="display: none;">
                                <a href="#" class="btn btn-primary btn-outline m-t-xs" id="btnAddProduct"><i class="fa fa-plus"></i> <?php __('btnAddProduct');?></a>
                            </div>
    
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
                                            <?php
                                            foreach ($tpl['product_arr'] as $product)
                                            {
                                                foreach ($tpl['oi_arr'] as $k => $oi)
                                                {
                                                    if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'])
                                                    {
                                                        $has_extra = false;
                                                        ?>
                                            <tr class="fdLine" data-index="<?php echo $oi['hash']; ?>">
                                                <!-- <?php //print_r($tpl['product_arr']); ?>  -->
                                                <td>
                                                    <select id="fdProduct_<?php echo $oi['hash']; ?>" data-index="<?php echo $oi['hash']; ?>" name="product_id[<?php echo $oi['hash']; ?>]" class="selectpicker fdProduct" data-live-search="true">
                                                        <option value="">-- <?php __('lblChoose'); ?>--</option>
                                                        <?php
                                                        foreach ($tpl['product_arr'] as $p)
                                                        {
                                                            if($p['id'] == $product['id'] && $p['cnt_extras'] > 0)
                                                            {
                                                                $has_extra = true;
                                                            }
                                                            if ($p['status'] == 1) {
                                                                
                                                            
                                                            ?><option value="<?php echo $p['id']; ?>"<?php echo $p['id'] == $product['id'] ? ' selected="selected"' : NULL; ?> data-extra="<?php echo $p['cnt_extras'];?>"><?php echo stripslashes($p['name']); ?></option><?php
                                                        }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                    
                                                <td>
                                                    <div class="business-<?php echo $oi['hash']; ?>">
                                                        <input type="text" id="fdProductQty_<?php echo $oi['hash']; ?>" name="cnt[<?php echo $oi['hash']; ?>]" class="form-control pj-field-count" value="<?php echo $oi['cnt']; ?>" style="width: 35px;" />
                                                    </div>
                                                </td>
                                                
                                                <td>
                                <div class="business-<?php echo $oi['hash']; ?>">
                                    <table id="fdExtraTable_<?php echo $oi['hash'];?>" class="table no-margins pj-extra-table">
                                        <tbody>
                                            <?php
                                            foreach ($tpl['extra_arr'] as $extra)
                                            {
                                                foreach ($tpl['oi_arr'] as $oi_sub)
                                                {
                                                    if ($oi_sub['type'] == 'extra' && $oi_sub['hash'] == $oi['hash'] && $oi_sub['foreign_id'] == $extra['id'])
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <select name="extra_id[<?php echo $oi['hash']; ?>][<?php echo $oi_sub['id']; ?>]" data-index="<?php echo $oi['hash']; ?>_<?php echo $oi_sub['id']; ?>" class="fdExtra fdExtra_<?php echo $oi['hash']; ?> form-control">
                                                                    <option value="">-- <?php __('lblChoose'); ?>--</option>
                                                                    <?php
                                                                    foreach ($tpl['extra_arr'] as $e)
                                                                    {
                                                                        if (in_array($e['id'], $product['allowed_extras']))
                                                                        {
                                                                            ?><option value="<?php echo $e['id']; ?>"<?php echo $e['id'] == $extra['id'] ? ' selected="selected"' : NULL; ?> data-price="<?php echo $e['price'];?>"><?php echo stripslashes($e['name']); ?>: <?php echo pjCurrency::formatPrice($e['price']);?></option><?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" id="fdExtraQty_<?php echo $oi['hash']; ?>_<?php echo $oi_sub['id']; ?>" name="extra_cnt[<?php echo $oi['hash']; ?>][<?php echo $oi_sub['id']; ?>]" class="form-control pj-field-count" value="<?php echo $oi_sub['cnt']; ?>" /></td>
                                                                                            <td><a href="#" class="btn btn-xs btn-danger btn-outline pj-remove-extra"><i class="fa fa-times"></i></a></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            } 
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="p-w-xs" style="display:<?php echo $has_extra == true ? 'block' : 'none'; ?>;">
                                                                        <a href="#" class="btn btn-primary btn-xs btn-outline pj-add-extra fdExtraBusiness_<?php echo $oi['hash'];?> fdExtraButton_<?php echo $oi['hash'];?>" data-index="<?php echo $oi['hash'];?>"><i class="fa fa-plus"></i> <?php __('btnAddExtra');?></a>
                                                                    </div><!-- /.p-w-xs -->
                                                                    <span class="fdExtraBusiness_<?php echo $oi['hash'];?> fdExtraNA_<?php echo $oi['hash'];?>" style="display:<?php echo $has_extra == false ? 'block' : 'none'; ?>;"><?php __('lblNA');?></span>
                                                                </div>
                                                            </td>

                                                <!-- MEGAMIND -->

                                                <td>
                                                    <span id="fdCategory_<?php echo $oi['hash'];?>">
                                                        <?php 
                                                        
                                                        foreach ($tpl['category_arr'] as $c) {
                                                            if ($oi['category'] == $c['id']) {
                                                                echo $tpl['category_list'][$c['id']]; 
                                                            }
                                                        }
                                                        // for ($i=1; $i <= count($tpl['category_list']); $i++) { 
                                                        //     if ($oi['category'] == $i) {
                                                        //         echo $tpl['category_list'][$i]; 
                                                        //     }
                                                        // } 
                                                        ?>
                                                    </span>
                                                    <!-- <input type="hidden" id="fdCategory_<?php //echo $oi['hash']; ?>" data-type="input" name="category_[<?php //echo $oi['hash']; ?>]"> -->

                                                </td>

                                                <!-- MEGAMIND -->

                                                <td id="fdPriceTD_<?php echo $oi['hash']; ?>">
                                                <?php $productData = json_encode( $product, JSON_HEX_APOS); ?>
                                                <input type="hidden" id="prdInfo_<?php echo $oi['hash'];?>" data-type="input" name="prdInfo_[<?php echo $oi['hash'];?>]" value='<?php echo $productData; ?>' />
                                                    <div class="business-<?php echo $oi['hash']; ?>">
                                                        <?php
                                                        if(empty($oi['price_id']))
                                                        {
                                                            ?>
                                                                <span class="fdPriceLabel"><?php echo pjCurrency::formatPrice($product['price']);?></span>
                                                                <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" data-type="input" name="price_id[<?php echo $oi['hash']; ?>]" value="<?php echo $product['price'];?>" />
                                                            <?php
                                                        } else {
                                                            if(isset($oi['price_arr']) && $oi['price_arr'])
                                                            {
                                                                ?>
                                                                <select id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" data-type="select" class="fdSize form-control">
                                                                    <option value="">-- <?php __('lblChoose'); ?>--</option>
                                                                    <?php
                                                                    foreach ($oi['price_arr'] as $pr)
                                                                    {
                                                                        ?><option value="<?php echo $pr['id']; ?>"<?php echo $pr['id'] == $oi['price_id'] ? ' selected="selected"' : NULL; ?> data-price="<?php echo $pr['price'];?>"><?php echo stripslashes($pr['price_name']).": ".pjCurrency::formatPrice($pr['price']); ?></option><?php
                                                                    } 
                                                                    ?>
                                                                </select>
                                                                <?php
                                                            } else {
                                                                ?><input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" value="" /><?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                

                                                <!-- MEGAMIND --> 

                                                <td>
                                                    <span id="fdPrepTime_<?php echo $oi['hash'];?>">
                                                        <?php foreach ($tpl['product_arr'] as $k => $v) {
                                                            if ($v['id'] == $oi['foreign_id']) {
                                                                if ($v['preparation_time'] == "") {
                                                                    echo 0;
                                                                }
                                                                else {echo $v['preparation_time'];}
                                                            }
                                                        }  ?>
                                                    </span>

                                                </td>

                                                <!-- MEGAMIND -->
                                                
                                                <td>
                                                    <strong><span id="fdTotalPrice_<?php echo $oi['hash']; ?>"></span></strong>
                                                </td>
                                                            
                                                <td>
                                                    <input type="text" id="special_instruction_<?php echo $oi['hash']; ?>" name="special_instruction[<?php echo $oi['hash']; ?>]" class="form-control" value="<?php echo $oi['special_instruction']; ?>" />
                                                </td>
                                                <td>
                                                    <div class="text-right" id="productDelete_<?php echo $oi['hash']; ?>">
                                                        <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                    }}
                                                }
                                            
                                            ?>
                                        <!-- <tr id="extraRow"></tr> -->
    
                                        </tbody>
                                        
                                    </table>
                                                                    
                                </div>
                            </div>
    
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo 'Mobile' //__('lblPhone'); ?></label>
                                        <input type="text" name="phone_no" id="phone_no" class="form-control<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" value = "<?php echo $tpl['arr']['phone_no']; ?>" data-wt = "<?php if($tpl['arr']['phone_no'] == ''){ echo 'invalid'; }else{ echo 'valid';} ?>"/>
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
                                <label class="control-label"><?php echo 'Discount Codes/Offers' //__('lblPhone'); ?></label>
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
                                        <input type="text" name="sms_email" id="c_email" class="form-control email"
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
                                                unset($name_titles['rev']);
                                                foreach ($name_titles as $k => $v)
                                                //foreach ($title_arr as $v)
                                                {
                                                    ?><option value="<?php echo $k; ?>"<?php if ($tpl['arr']['c_title']==$k) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option><?php
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
    
                                            <input type="text" name="c_name" id="c_name" class="form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php echo $tpl['arr']['first_name'] ?>"/>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-md-5 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo 'Surname'//__('lblName'); ?></label>
    
                                            <input type="text" name="surname" id="c_surname" class="form-control<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"  value="<?php echo $tpl['arr']['surname'] ?>"/>
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

                            <div class="order-delivery" style="display: <?php echo $tpl['arr']['type'] != 'delivery' ? 'none' : 'block'; ?>;">
                                
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
                                                    
                                                    <input type="text" class="form-control fdRequired<?php echo $tpl['arr']['type'] == 'delivery' ? ' required' : NULL; ?>" placeholder="Type your postCode" name="post_code" id="inputPostCode" value="<?php  
                                                    echo pjSanitize::html($postcode); ?>">
                                            
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
    
                                            <input type="text" name="d_address_2" id="d_address_2" class="form-control<?php echo $tpl['option_arr']['o_df_include_address_2'] == 3 ? ' fdRequired' : NULL; ?><?php echo $tpl['arr']['type'] == 'delivery' ? ' required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"
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
                                   <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('lblSpecialInstructions'); ?></label>
    
                                            <textarea name="d_notes" id="d_notes" class="form-control<?php echo $tpl['option_arr']['o_df_include_notes'] == 3 ? ' fdRequired required' : NULL; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>"
                                                ><?php echo $tpl['arr']['d_notes'] != '' ? $tpl['arr']['d_notes'] : $tpl['arr']['p_notes']; ?></textarea>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                        <div class="form-group" id="delivery_fee_frmgrp" style="display: <?php echo $tpl['arr']['type'] != 'delivery' ? 'none' : 'block'; ?>;">
                                            <label class="control-label"><?php echo "Delivery fee"; ?></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                                                <input type="text" name="delivery_fee" id="delivery_fee" class="form-control" value="<?php echo $tpl['arr']['price_delivery'];?>" data-wt="valid">
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

                                        <span id="total_prep-time_format" data-msg-required="<?php __('fd_field_required', false, true);?>"><?php echo $tpl['arr']['preparation_time'];?></span>
                                        <input type="hidden" name="preparation_time" id="prep_time" value="<?php echo $tpl['arr']['preparation_time']?>">
                                    </div>
                                </div>
                              
                                </div>
                                
                                        
                            </div>
                            
                            <div class="hr-line-dashed"></div>
                               
                            <div class="row">
                            <!-- /.col-md-3 -->
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                     <?php
                                            $dDate = !empty($tpl['arr']['d_dt']) ? date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['d_dt'])) : date("d.m.Y"); 
                                            $dTime = !empty($tpl['arr']['d_dt']) ? date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['d_dt'])) : ''; 
                                            $pDate = !empty($tpl['arr']['p_dt']) ? date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['p_dt'])) : date("d.m.Y"); 
                                            $pTime = !empty($tpl['arr']['p_dt']) ? date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['p_dt'])) : ''; 
                                        ?>
                                    <!-- <div class="form-group order-delivery">
                                        <label class="control-label"><?php //__('lblDeliveryDateTime'); ?></label> -->
    
                                        <!-- <div class="input-group"> -->
                                            <!-- <input type="hidden" id="d_dt" name="d_dt" class="form-control fdRequired required" data-wt="open" data-msg-required="<?php //__('fd_field_required', false, true);?>" readonly> -->
    
                                            <!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                                        </div> -->
                                        
                                    
                                        
                                    <!-- </div> -->
                                     
                                    <!--  <input type="hidden" id="d_dt" name="d_dt" class="form-control fdRequired required" data-wt="open" data-msg-required="<?php //__('fd_field_required', false, true);?>" readonly> -->
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
                                         <label>Time(Mins)</label>
                                         <input type="hidden" name="delivery_time" id="delivery_time"
                                                    value="<?php 
                                                      echo $dTime; ?>">
                                        <?php 
                                        $created = explode(" ",$tpl['arr']['created']);
                                        $delivery = explode(" ",$tpl['arr']['d_dt']);
                                        $future = 0;
                                        // if ($created[0] == $delivery[0] && $tpl['arr']['d_time'] > 0 ) { 
                                        if ($created[0] == $delivery[0]) { 
                                            $future = 0;
                                        } else {
                                            $future = 1;
                                        } 
                                        ?>
                                            <input type="hidden" value="<?php echo $future; ?>">
                                            <div class="form-group" id="jsTimeDiv" style="display: <?php echo $future == 1 ? 'none' : 'block'; ?>">

                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                    
                                                   <!--  <input name="d_time" class="pj-timepicker form-control required fdRequired" data-msg-required="<?php //__('fd_field_required', false, true);?>" readonly/>   -->
                                                    <input name="d_time" id="d_time" class=" form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>"/ value = "<?php if($tpl['arr']['d_time']==0){ echo 0;}else{echo $tpl['arr']['d_time'];} ?>">    
                                                    
                                                </div>
                                                <div>
                                                    <label>Approximate Delivery time:</label><span id="aproxDt"><?php 
                                                      echo $dTime; ?></span>
                                                </div>
                                                
                                            </div><!-- /.form-group -->
                                       
                                            <div class="form-group" id="jsChangeTimeDiv"
                                            style="display: <?php echo $future == 1 ? 'block' : 'none'; ?>">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                    
                                                    <select id="jsChangeTimeDivSelect" class="form-control"><option value="">-- Choose Time --</option>
                                                <?php
                                                $times = ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00",
                                                  "06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30",
                                                  "15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
                                                  $del_time = explode(":",$delivery[1]);
                                                  $d_time = $del_time[0].":".$del_time[1];
                                                  //echo $del_time;
                                                  foreach ($times as $time) { ?>
                                                  <option value="<?php echo $time; ?>" <?php if($time == $d_time) { ?> selected = "selected" <?php }?>><?php echo $time; ?></option>
                                                <?php  }
                                                ?>
                                                </select>
                                                    
                                                    
                                                </div>
                                                
                                                
                                           
                                        
                                        
                                    </div>

                                    

                                    
                                </div>
                                <div class="form-group order-pickup" style="display:<?php echo $tpl['arr']['type']== 'pickup' ? 'block' : 'none';?>;">
                                        <label><?php __('lblDate'); ?></label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
            
                                                <input type="text" name="p_date" id="p_date" data-wt="open" class="form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>" value="<?php 
                                                  echo $pDate;
                                                ?>" readonly>
                                            </div>
                                        </div><!-- /.form-group -->
                                         <label>Time(Mins)</label>
                                         <input type="hidden"  name="pickup_time" id="pickup_time"
                                                 value="<?php 
                                                  echo $pTime;
                                                ?>">  
                                        <?php 
                                        $created = explode(" ",$tpl['arr']['created']);
                                        $pickup = explode(" ",$tpl['arr']['p_dt']);
                                        $future = 0;
                                        //if ($created[0] == $pickup[0] && $tpl['arr']['p_time'] > 0 ) {
                                            if ($created[0] == $pickup[0]) { 
                                            $future = 0;
                                        } else {
                                            $future = 1;
                                        } 
                                        ?>
                                        <input type="hidden" value="<?php echo $tpl['arr']['p_time']; ?>">
                                        <div class="form-group" id="jsPTimeDiv" style="display: <?php echo $future == 1 ? 'none' : 'block'; ?>">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                
                                                <!-- <input name="p_time" class="pj-timepicker form-control fdRequired" data-msg-required="<?php //__('fd_field_required', false, true);?>" readonly/>    --> 
                                                <input name="p_time" id="p_time" class="form-control fdRequired" data-msg-required="<?php __('fd_field_required', false, true);?>" value = "<?php if($tpl['arr']['p_time']==0){echo 0;}else{echo $tpl['arr']['p_time'];} ?>"/> 
                                                 
                                            </div>
                                            <div>
                                                <label>Approximate pickup time:</label><span id="aproxPt"><?php echo $pTime; ?></span>
                                            </div>
                                        <?php //} else { ?>
                                            
                                        <?php //} ?>
                                            
                                        </div>
                                        <div class="form-group" id="jsChangePTimeDiv" style="display: <?php echo $future == 1 ? 'block' : 'none'; ?>">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                    
                                                    <select id="jsChangePTimeDivSelect" class="form-control"><option value="">-- Choose Time --</option>
                                                <?php
                                                $times = ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00",
                                                  "06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30",
                                                  "15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
                                                  $pick_time = explode(":",$pickup[1]);
                                                  $p_time = $pick_time[0].":".$pick_time[1];
                                                  //echo $del_time;
                                                  foreach ($times as $time) { ?>
                                                  <option value="<?php echo $time; ?>" <?php if($time == $p_time) { ?> selected = "selected" <?php }?>><?php echo $time; ?></option>
                                                <?php  }
                                                ?>
                                                </select>
                                                    
                                                    
                                                </div>
                                                
                                                
                                            </div><!-- /.form-group -->
                                        <!-- <label class="control-label"><?php //__('lblPickerDateTime'); ?></label>
    
                                        <div class="input-group">
                                            <input type="text" id="p_dt" name="p_dt" class="form-control fdRequired" data-wt="open" data-msg-required="<?php //__('fd_field_required', false, true);?>" readonly>
    
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                                        </div> -->
                                    </div>
                            </div><!-- /.col-md-3 -->
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblPaymentMethod');?></label>
                                        <?php
                                        if($tpl['arr']['payment_method'] == 'paypal_express') { $client_paymethod = 'PayPal Express Checkout'; } elseif ($tpl['arr']['payment_method'] == 'authorize') {
                                           $client_paymethod = 'Authorize.NET';
                                        }elseif ($tpl['arr']['payment_method'] == '2checkout') {
                                           $client_paymethod = '2checkout';
                                        }elseif ($tpl['arr']['payment_method'] == 'paypal') {
                                           $client_paymethod = 'PayPal';
                                        }elseif ($tpl['arr']['payment_method'] == 'world_pay') {
                                           $client_paymethod = 'WorldPay';
                                        }elseif ($tpl['arr']['payment_method'] == 'bank') {
                                           $client_paymethod = 'Bank account';
                                        } else {
                                        $client_paymethod = ucfirst($tpl['arr']['payment_method']);
                                         }
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
                                                ?><option value="<?php echo $k;?>" <?php if ($client_paymethod==$v) { ?>
                                                    selected = 'selected' <?php
                                                } ?>><?php echo $v;?></option><?php
                                            }
                                            ?>
                                            </optgroup>
                                            <optgroup label="<?php __('script_offline_payment', false, true); ?>">
                                            <?php
                                            foreach($offline_arr as $k => $v)
                                            {
                                                ?><option value="<?php echo $k;?>"<?php if ($client_paymethod==$v) { ?>
                                                    selected = 'selected' <?php
                                                } ?>><?php echo $v;?></option><?php
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
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblStatus'); ?></label>
    
                                        <select name="status" id="status" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                                            <?php
                                            $client_sts = ucfirst($tpl['arr']['status']);
                                            foreach (__('order_statuses', true, false) as $k => $v)
                                            {
                                                ?><option value="<?php echo $k; ?>"<?php if ($client_sts==$v) { ?>
                                                    selected = "selected"
                                                <?php }?>><?php echo stripslashes($v); ?></option><?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div><!-- /.col-md-3 -->
                               
                                
                            </div> <!-- jaslin -->
                            
    
        
    
                            <div class="clearfix">
                                <a class="btn btn-white btn-lg pull-left" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                                <button id="submitJs" type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-right" data-style="zoom-in" style="margin-right: 15px;">
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

                    <input type="text" name="voucher_code" id="voucher_code" class="form-control" value="<?php echo  stripslashes($tpl['arr']['voucher_code']);?>" data-wt="valid">

                    <!-- <div class="hr-line-dashed"></div>

                    <h3 class="lead m-b-md"><?php //echo "Total Prep.Time:";?> <strong id="total_prep-time_format" class="pull-rigth text-right"></strong></h3> -->
                    
                    <!-- MEGAMIND -->
                
                </div>
            </div>
        </div>
        <div class="m-b-lg">
            <div id="pjFdPriceWrapper" class="panel no-borders ibox-content">
            	<div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>
                <div class="panel-heading bg-<?php echo $tpl['arr']['status'];?>">
                    <p class="lead m-n"><i class="fa fa-check"></i> <?php __('lblStatus'); ?>: <span class="pull-right status-text"><?php __('order_statuses_ARRAY_' . $tpl['arr']['status']);?></span></p>    
                </div><!-- /.panel-heading -->

                <div class="panel-body">
                	<!-- <p class="lead m-b-xs"><span><a href="#" id="btnEmail" data-id="<?php //echo $tpl['arr']['id'];?>" class="btn btn-primary btn-md btn-block btn-outline"><i class="fa fa-bell-o"></i> <?php //__('btnResendOrder'); ?></a></span></p>
                    <p class="lead m-b-xs text-right"><span><a href="<?php //echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrders&amp;action=pjActionPrintOrder&amp;id=<?php //echo $tpl['arr']['id']; ?>&hash=<?php //echo sha1($tpl['arr']['id'].$tpl['arr']['created'].PJ_SALT)?>" class="btn btn-primary btn-block btn-md btn-outline" target="_blank"><i class="fa fa-print"></i> <?php //__('btnPrintOrderDetails'); ?></a></span></p> -->
                   <!--  <div class="hr-line-dashed"></div> -->
                    
                    <p class="lead m-b-md"><?php __('lblPrice'); ?>: <span id="price_format" class="pull-right"><?php echo pjCurrency::formatPrice($tpl['arr']['price']);?></span></p>
                    <!-- <p class="lead m-b-md"><?php __('lblPacking'); ?>: <span id="packing_format" class="pull-right"><?php //echo pjCurrency::formatPrice($tpl['arr']['price_packing']);?></span></p> -->
                    <p class="lead m-b-md"><?php __('lblDelivery'); ?>: <span id="delivery_format" class="pull-right"><?php echo pjCurrency::formatPrice($tpl['arr']['price_delivery']);?></span></p>
                    <p class="lead m-b-md"><?php __('lblDiscount'); ?>: <span id="discount_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice($tpl['arr']['discount']);?></span></p>
                    <p class="lead m-b-md"><?php __('lblSubTotal'); ?>: <span id="subtotal_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice($tpl['arr']['subtotal']);?></span></p>
                    <!-- <p class="lead m-b-md"><?php __('lblTax'); ?>: <span id="tax_format" class="pull-right text-right"><?php //echo pjCurrency::formatPrice($tpl['arr']['tax']);?></span></p> -->

                    <div class="hr-line-dashed"></div>

                    <h3 class="lead m-b-md"><?php __('lblTotal'); ?>: <strong id="total_format" class="pull-right text-right"><?php echo pjCurrency::formatPrice($tpl['arr']['total']);?></strong></h3>
                </div><!-- /.panel-body -->
            </div>

        </div><!-- /.m-b-lg -->
        <!-- <div class="">
                                    <div class="form-group">
                                        <label class="control-label"><?php //__('lblVoucher'); ?></label>
    
                                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" value="<?php echo  stripslashes($tpl['arr']['voucher_code']);?>" data-wt="valid">
                                    </div> 
                                </div> -->
                                <!-- /.col-md-3 -->
    <!-- </div> -->
    <!-- /.col-lg-4 -->
    
</div><!-- /.wrapper wrapper-content -->

<table style="display: none" id="boxProductClone">
	<tbody>
	<?php
	include PJ_VIEWS_PATH . 'pjAdminOrders/elements/clone.php'; 
	?>
	</tbody>
</table>
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
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
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
</div>
<div class="modal fade" id="ispaidPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
        <h2 class="modal-title" id="modalTitle">Order Paid?</h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form role='' action="" method="post"> 

            <h4 style="font-size: 25px;">Is payment made for the products?</h4>
            <hr>
            <button type="submit" id="paidBtn" class="modalBtn btn-primary" style="border-radius: 5px;border: none;padding: 10px 20px;">Yes</button>
            <button type="submit" id="notpaidBtn" class="modalBtn btn-danger" style="border-radius: 5px;border: none;padding: 10px 20px;float: right;">No</button>

        </form>

   

      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>

<!-- <div class="modal fade" id="reminderEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php //__('notifications_ARRAY_client_email_confirmation');?></h4>
		      </div>
		      <div id="emailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php //__('btnCancel');?></button>
		        	<button id="btnSendEmailConfirm" type="button" class="btn btn-primary"><?php //__('btnSend');?></button>
		      </div>
	    </div> --><!-- /.modal-content -->
  	<!-- </div> --><!-- /.modal-dialog -->
<!-- </div> --><!-- /.modal -->

<script type="text/javascript">
var categoryList = '<?php echo json_encode($tpl['category_list']); ?>';  
categoryList =  JSON.parse(categoryList);  
// var client_info = '<?php //echo json_encode($tpl['client_info']); ?>';
// client_info = JSON.parse(client_info);
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
</script>