
<div class="fdLoader"></div>
<?php
$index = $controller->_get->toString('index');
$STORAGE = @$_SESSION[$controller->defaultStore];
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();

$isPickup = !isset($STORAGE['type']) || $STORAGE['type'] == 'pickup';
$isDelivery = isset($STORAGE['type']) && $STORAGE['type'] == 'delivery';

$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
//print_r($tpl['wt_arr']);
?>
<br />
<div class="container">
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob">
            <?php
				if($tpl['status'] == 'OK')
				{
					$price = 0;
					$discount = 0;
					$discount_print = NULL;
					$subtotal = 0.00;
					$tax = 0.00;
					$total = 0.00;
					$delivery = 0.00;
					$packing = 0.00;
					
					$cart_price = 0;
					foreach ($tpl['cart_box']['cart'] as $hash => $item)
					{
						foreach ($tpl['cart_box']['product_arr'] as $product)
						{
							if ($product['id'] == $item['product_id'])
							{
								$product_price = 0;
								$product_price = $item['price'] * $item['cnt'];
								$packing += $product['packing_fee'] * $item['cnt'];
								$cart_price += $product_price;
								foreach ($item['extra_arr'] as $extra_id => $extra)
								{
									$cart_price += $extra['price'] * $extra['qty'];
								}
							}
						}
					}
					$controller->_set('price', $cart_price);
					
					if($controller->_is('price'))
					{
						$price = $controller->_get('price');
						?>
						
						<?php
					}
					# Packing fee
					$controller->_set('packing', $packing);
					?>
					
					<?php
					if($controller->_get('type') == 'delivery')
					{
						$delivery = $controller->_get("delivery");
						?>
						
						<?php
					}
					?>
					

					
					<?php 
					$discount_print = NULL;
					if ($controller->_is('voucher_code'))
					{
						$voucher_discount = $controller->_get('voucher_discount');
						switch ($controller->_get('voucher_type'))
						{
							case 'percent':
								$discount_print = $voucher_discount . "%";
								$discount = (($price + $packing) * $voucher_discount) / 100;
								break;
							case 'amount':
							    $discount_print = pjCurrency::formatPrice($voucher_discount);
								$discount = $voucher_discount;
								break;
						}
					}
					if ($discount > $price + $packing)
					{
						$discount = $price + $packing;
					}
					$subtotal = $price + $delivery + $packing - $discount;
					
					$controller->_set('discount', $discount);
					$controller->_set('price', $price);
					$controller->_set('subtotal', $subtotal);
					if($controller->_get('voucher_type') == 'amount')
					{
					    $discount_print = pjCurrency::formatPrice($discount);
					}
					if(!is_null($discount_print))
					{
						?>
						
						<?php
					}
					if($controller->_is('subtotal'))
					{
						?>
						
						<?php
					}
					if(!empty($tpl['option_arr']['o_tax_payment']))
					{
						if ($tpl['option_arr']['o_add_tax'] == '1' && $controller->_get('type') == 'delivery')
						{
							$tax = (($subtotal - $delivery) * $tpl['option_arr']['o_tax_payment']) / 100;
						} else {
							$tax = ($subtotal * $tpl['option_arr']['o_tax_payment']) / 100;
						}
						$controller->_set('tax', $tax);
						?>
						
						<?php
					}
					$total = $subtotal + $tax;
					$controller->_set('total', $total);
					?>
					
					<!-- <div class="row"> -->
						<!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
							<a href="#" class="btn btn-default text-uppercase fdButtonBackTypes"><?php //__('front_button_back');?></a>
						</div> -->
						<!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
							<a href="#" class="btn btn-default text-uppercase<?php echo $button_class;?>"><?php __('front_button_back');?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
							<a href="#" class="btn btn-default text-uppercase fdButtonPayment<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>"><?php __('front_button_continue');?></a>
						</div> -->
						<!-- /.col-sm-8 col-sm-offset-4 -->
					<!-- </div> -->
					<!-- /.row -->
					<?php
				}else{
					?>
					<!-- <div> -->
						<?php
						// $front_messages = __('front_messages', true, false);
						// $system_msg = str_replace("[STAG]", "<a href='#' class='fdStartOver'>", $front_messages[13]);
						// $system_msg = str_replace("[ETAG]", "</a>", $system_msg); 
						// echo $system_msg; 
						?>
					<!-- </div> -->
					<?php
				}					 
				?>
			
           
		<div class="panel panel-default">
			<?php //include_once dirname(__FILE__) . '/elements/header.php';?>	
			
			<div class="panel-body pjFdPanelBody">
				<?php
				if($tpl['status'] == 'OK')
				{ 
					?>
					<form id="fdTypeForm_<?php echo $index; ?>" action="" method="post" class="form-horizontal">
						<input type="hidden" name="loadTypes" value="1" />
						
						<div role="tabpanel">
							<ul class="nav nav-pills" role="tablist">
								<?php if(FRONT_END_PICKUP == 1) { ?>
								<li role="presentation" class="fdTabOuter<?php echo $isPickup ? ' active' : NULL; ?>">
									<a href="#" class="text-uppercase fdTypeTab fdTabPickup" aria-controls="pickup" role="tab">
										<i class="fa fa-suitcase"></i>
	
										&nbsp;
										<?php __('front_pickup');?>
									</a>
								</li>
								<?php } else {} ?>
								<?php if(FRONT_END_DELIVERY == 1) { ?>
								<li role="presentation" class="fdTabOuter<?php echo $isDelivery ? ' active' : NULL; ?>">
									<a href="#" class="text-uppercase fdTypeTab fdTabDelivery" aria-controls="delivery" role="tab">
										<i class="fa fa-taxi"></i>
										&nbsp;
										<?php __('front_delivery');?>
									</a>
								</li>
								<?php } else {} ?>
							</ul>
							<span style="overflow: hidden; height: 0; display: none;">
								<input type="radio" name="type" id="fdTypePickup_<?php echo $index; ?>" value="pickup"<?php echo $isPickup ? ' checked="checked"' : NULL; ?> />
								<input type="radio" name="type" id="fdTypeDelivery_<?php echo $index; ?>" value="delivery"<?php echo $isDelivery ? ' checked="checked"' : NULL; ?> />
							</span>
						</div><!-- role="tabpanel" -->
						
						<div class="tab-content">
							<div role="tabpanel" class="fdPickup" style="display: <?php echo @$STORAGE['type'] == 'delivery' ? 'none' : NULL; ?>">
								<div class="form-group">
									<!-- <label for="" class="col-sm-4 control-label"><?php __('front_location'); ?></label> -->
									<div class="col-sm-8" style="display:<?php echo count($tpl['location_arr']) != 1 ? ' block' : ' none';?>">
										<select name="p_location_id" class="form-control" data-err="<?php __('front_location_required'); ?>">
											<?php 
											if(count($tpl['location_arr']) != 1)
											{
												?>
												<option value="">-- <?php __('front_choose'); ?> --</option>
												<?php
											}
											foreach ($tpl['location_arr'] as $location)
											{
												?><option value="<?php echo $location['id']; ?>"<?php echo @$STORAGE['p_location_id'] == $location['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($location['name']); ?></option><?php
											}
											?>
										</select>
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div>
									<!-- <div class="col-sm-8" style="display: <?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>">
										<label class="control-label"><?php echo pjSanitize::html($tpl['location_arr'][0]['name']);?></label>
									</div> -->
								</div><!-- /.form-group -->
								
								<!-- <div class="form-group" style="display:<?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>;">
									<label for="" class="col-sm-4 control-label">&nbsp;</label>
									<div class="col-sm-8">
										<strong><?php __('front_address'); ?></strong>: <span id="fdPickupAddressLabel_<?php echo $index;?>"><?php echo count($tpl['location_arr']) == 1 ? pjSanitize::html($tpl['location_arr'][0]['address']) : null; ?></span>
										<input type="hidden" id="fdPickupAddress_<?php echo $index;?>" name="address" value="<?php echo count($tpl['location_arr']) == 1 ? pjSanitize::html($tpl['location_arr'][0]['address']) : null; ?>" class="fdText fdW100p" readonly="readonly" />
									</div>
								</div> -->
								<!-- /.form-group -->
									
								<!-- <div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12"> -->
										<!-- <div id="fdTypeMap_<?php echo $index; ?>" sty<!-- /.col-md-12 col-sm-12 col-xs-12 -->
									<!-- <br />
								</div>
								<br/> -->
								<div id="fdPickupDateTime_<?php echo $index;?>" class="form-group" style="display:<?php echo (isset($STORAGE['p_date']) && !empty($STORAGE['p_date']) || count($tpl['location_arr']) == 1) ? ' block' : ' none'; ?>;">
									<label for="" class="col-sm-4 control-label"><?php __('front_pickup_datetime'); ?></label>

									<div class="col-sm-8">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="input-group date">
													<span class="input-group-addon pjFdCursorPointer">
														<i class="fa fa-calendar"></i>
													</span>
													<input type="text" name="p_date" id="fd_p_date_<?php echo $index; ?>" class="form-control" value="<?php echo isset($STORAGE['p_date']) ? htmlspecialchars(stripslashes(@$STORAGE['p_date'])) : date($tpl['option_arr']['o_date_format'], time()); ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-err="<?php __('front_pickup_date_required'); ?>" />
												</div><!-- /.input-group date -->
												<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="fdPickupTime">
													<?php
													$p_time = isset($STORAGE['p_time']) ? $STORAGE['p_time'] : NULL;
													
													if(count($tpl['location_arr']) != 1 || $p_time != NULL )
													{
														//$p_date = '';
														
														$date = date('Y-m-d');
														$start_time = strtotime(date('Y-m-d 00:00:00'));
														$end_time = strtotime(date('Y-m-d 23:45:00'));
														if(isset($STORAGE['p_date']))
														{
														    $date = pjDateTime::formatDate(@$STORAGE['p_date'], $tpl['option_arr']['o_date_format']);
															//$p_date = pjDateTime::formatDate(@$STORAGE['p_date'], $tpl['option_arr']['o_date_format']);
														}
														
														if (isset($tpl['wt_arr']))
														{
															$start_time = strtotime($date . ' ' . $tpl['wt_arr']['start_hour'] . ':' . $tpl['wt_arr']['start_minutes'] . ':00');
															$end_time = strtotime($date . ' ' . $tpl['wt_arr']['end_hour'] . ':' . $tpl['wt_arr']['end_minutes'] . ':00');
														}
														if($end_time < $start_time)
														{
															$end_time += 86400;
														}
														$midnight = strtotime($tpl['date'] . ' 23:59:59');
														$interval = 900;
														$next = pjUtil::getCurrentTimeSnap15Minutes();
														if($start_time < $next)
														{
															$start_time = $next;
														}
														?>
														<select name="p_time" class="form-control">
															<option value="asap"><?php __('front_asap');?></option>
															<?php
															$todate = date('Y-m-d');
															if(isset($STORAGE['p_date']) && $STORAGE['p_date'] != $todate) {
                                                                for($i = $start_time; $i <= $end_time; $i += $interval)
																{
																	$iso_time = date($tpl['option_arr']['o_time_format'], $i);
																	$time_text = $iso_time;
																	if($i > $midnight )
																	{
																		$time_text .= ' (' . __('front_next_day', true) . ')';
																	}
																	?><option value="<?php echo $iso_time;?>"<?php echo $iso_time == $p_time ? ' selected="selected"' : null; ?>>1212<?php echo $time_text;?></option><?php	
																} 
															}
															
															?>
														</select>
														<?php
													}
													?>
												</div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
										</div><!-- /.row -->
									</div>
								</div><!-- /.form-group -->
								<?php
								if (in_array($tpl['option_arr']['o_pf_include_notes'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_special_instructions'); ?></label>
										<div class="col-sm-8">
											<textarea name="p_notes" rows="2" class="form-control" data-err="<?php __('front_special_required');?>"><?php echo isset($STORAGE['p_notes']) ? htmlspecialchars(stripslashes(@$STORAGE['p_notes'])) : htmlspecialchars(stripslashes(@$CLIENT['p_notes'])); ?></textarea>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								} 
								?>
							</div><!-- /.tabpanel -->
							<?php
							$message = null;
							$front_messages = __('front_messages', true, false);
							?>
							<div role="tabpanel" class="fdDelivery" style="display: <?php echo @$STORAGE['type'] == 'delivery' ? NULL : 'none'; ?>">
								<div class="form-group">
									<!-- <label for="" class="col-sm-4 control-label"><?php __('front_location'); ?></label> -->
									<div class="col-sm-8" style="display:<?php echo count($tpl['location_arr']) != 1 ? ' block' : ' none';?>">
										<select name="d_location_id" class="form-control" data-err="<?php __('front_delivery_area_required'); ?>">
											<?php
											if(count($tpl['location_arr']) != 1)
											{
												?>
												<option value="">-- <?php __('front_choose'); ?> --</option>
												<?php
											}
											foreach ($tpl['location_arr'] as $location)
											{
												if(@$STORAGE['d_location_id'] == $location['id'])
												{
													$message = str_replace("{LOCATION}", $location['name'], $front_messages[11]);
												}
												?><option value="<?php echo $location['id']; ?>"<?php echo @$STORAGE['d_location_id'] == $location['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($location['name']); ?></option><?php
											}
											?>
										</select>
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div>
									<!-- <div class="col-sm-8" style="display: <?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>">
										<label class="control-label"><?php echo pjSanitize::html($tpl['location_arr'][0]['name']);?></label>
									</div> -->
								</div><!-- /.form-group -->
								<?php
								if(count($tpl['location_arr']) != 1)
								{ 
									?>
									<!-- <div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<?php __('front_delivery_info'); ?>
										</div>
									</div> -->
									<?php
								}else{
									$message = str_replace("{LOCATION}", $tpl['location_arr'][0]['name'], $front_messages[11]);
								}
								?>
								<!-- <div class="row" style="display:<?php echo $message == null ? ' none' : ' block';?>">
									<div class="col-md-12 col-sm-12 col-xs-12 fdDeliveryNote"><?php echo $message;?></div>
								</div>
								<br/>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12"> -->
										<!-- <div id="fdDeliveryMap_<?php echo $index; ?>" style="height: 300px;"></div> -->
									<!-- </div>
									<br />
								</div>
								<br/> -->
								<div id="fdDeliveryDateTime_<?php echo $index;?>" class="form-group" style="display:<?php echo (isset($STORAGE['d_date']) && !empty($STORAGE['d_date']) || $message != null) ? ' block' : ' none'; ?>;">
									<label for="" class="col-sm-4 control-label"><?php __('front_delivery_datetime'); ?></label>

									<div class="col-sm-8">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="input-group date">
													<span class="input-group-addon pjFdCursorPointer">
														<i class="fa fa-calendar"></i>
													</span>
													<input type="text" name="d_date" id="fd_d_date_<?php echo $index; ?>" class="form-control" value="<?php echo isset($STORAGE['d_date']) ? htmlspecialchars(stripslashes(@$STORAGE['d_date'])) : date($tpl['option_arr']['o_date_format'], time()); ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-err="<?php __('front_delivery_date_required'); ?>" />
												</div><!-- /.input-group date -->
												<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="fdDeliveryTime">
													<?php
													$d_time = isset($STORAGE['d_time']) ? $STORAGE['d_time'] : NULL;
													
													if(count($tpl['location_arr']) != 1 || ($d_time != NULL) )
													{
														
														$start_time = strtotime(date('Y-m-d 00:00:00'));
														$end_time = strtotime(date('Y-m-d 23:45:00'));
														
														if (isset($tpl['wt_arr']))
														{
															$start_time = strtotime($date . ' ' . $tpl['wt_arr']['start_hour'] . ':' . $tpl['wt_arr']['start_minutes'] . ':00');
															$end_time = strtotime($date . ' ' . $tpl['wt_arr']['end_hour'] . ':' . $tpl['wt_arr']['end_minutes'] . ':00');
														}
														if($end_time < $start_time)
														{
															$end_time += 86400;
														}
														$midnight = strtotime($tpl['date'] . ' 23:59:59');
														
														$interval = 900;
														$next = pjUtil::getCurrentTimeSnap15Minutes();
														if($start_time < $next)
														{
															$start_time = $next;
														}
														?>
														<select name="d_time" class="form-control hey ">
															<option value="asap"><?php __('front_asap');?></option>
															<?php
															for($i = $start_time; $i <= $end_time; $i += $interval)
															{
																$iso_time = date($tpl['option_arr']['o_time_format'], $i);
																$time_text = $iso_time;
																if($i > $midnight )
																{
																	$time_text .= ' (' . __('front_next_day', true) . ')';
																}
																?><option value="<?php echo $iso_time;?>"<?php echo $iso_time == $d_time ? ' selected="selected"' : null; ?>><?php echo $time_text;?></option><?php	
															} 
													
															?>
														</select>
														<?php
													} 
													?>
												</div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
										</div><!-- /.row -->
									</div>
								</div><!-- /.form-group -->
								<?php
								if(isset($tpl['order_arr']) && count($tpl['order_arr']) > 0)
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_select_previous'); ?></label>
										<div class="col-sm-8">
											<select id="fdPreviousAddr_<?php echo $index;?>" name="previous_address" class="form-control">
												<option value="" data-add1="" data-add2="" data-city="" data-state="" data-zip="" data-country="">-- <?php __('front_choose'); ?> --</option>
												<?php
												foreach($tpl['order_arr'] as $v)
												{
													$order_detail = __('front_order', true, false) . ' ' . (!empty($v['uuid']) ? $v['uuid'] : strtotime($v['created'])) . ' ' . __('front_from', true, false) . ' ' . date($tpl['option_arr']['o_date_format'] . ', ' . $tpl['option_arr']['o_time_format'], strtotime($v['created']));
													?><option value="<?php echo $v['id']; ?>" data-add1="<?php echo pjSanitize::clean(@$v['d_address_1']);?>" data-add2="<?php echo pjSanitize::clean(@$v['d_address_2']);?>" data-city="<?php echo pjSanitize::clean(@$v['d_city']);?>" data-state="<?php echo pjSanitize::clean(@$v['d_state']);?>" data-zip="<?php echo pjSanitize::clean(@$v['d_zip']);?>" data-country="<?php echo pjSanitize::clean(@$v['d_country_id']);?>"><?php echo stripslashes($order_detail); ?></option><?php
												}
												?>
											</select>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_1'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label">Post Code</label>
										<div class="col-sm-8">
											<div class="input-group" id="post_code">
															
												<input type="text" class="form-control fdRequired required" data-validated="<?php if(isset($STORAGE['post_code']) || htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) != '') { echo '1'; } else { echo '0'; } ?>" placeholder="Type your postCode" name="post_code" id="inputPostCode" value="<?php echo isset($STORAGE['post_code']) && $STORAGE['post_code'] != '' ? htmlspecialchars(stripslashes(@$STORAGE['post_code'])) : htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])); ?>" <?php if((isset($STORAGE['post_code']) && isset($STORAGE['post_code']) !='') || htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) != '') { ?> readonly="readonly" <?php } ?> data-err="Postcode is required">
										        
												<span class="<?php if((isset($STORAGE['post_code']) || htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) != '')) { echo 'input-group-btn'; } else { echo 'd-none'; }?>">
													<button class="btn btn-postcode-edit" type="button" id="btnEditPostCode"><i class="glyphicon glyphicon-pencil"></i></button>
												</span>
												<span class="<?php if((isset($STORAGE['post_code']) || htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) != '')) { echo 'd-none'; } else { echo 'input-group-btn'; }?>">
													<button class="btn btn-postcode" type="button" id="btnFindPostCode"><i class="glyphicon glyphicon-ok"></i></button>
												</span>
												<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
												<span class="invlaid-pc d-none">Invalid Post Code</span>

												
											</div>
											<div id="postCodeErr" style="display:none; color: #a94442; margin-top; 10px;"></div>
								        </div>
								    </div>
									<!-- <div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php //__('front_address_line_1'); ?></label>
										<div class="col-sm-8"> -->
											<!-- <input type="text" name="d_address_1" class="form-control<?php //echo (int) $tpl['option_arr']['o_df_include_address_1'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_address_1']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_1'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_1'])); ?>" data-err="<?php __('front_address1_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div> 
									</div> -->
									  <!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_2'], array(2, 3)))
								{
									?>
									<!-- <div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php //__('front_address_line_2'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_address_2" class="form-control<?php //echo (int) $tpl['option_arr']['o_df_include_address_2'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_address_2']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_2'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_2'])); ?>" data-err="<?php __('front_address2_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div> -->
									<!-- /.form-group -->
									<div class="form-group <?php echo htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) == '' ? 'd-block' : 'd-none' ; ?>">
									    <label for="" class="col-sm-4 control-label" style="margin-bottom:18px;">Address List</label>
										<div class="col-sm-8">
											<div id='addressList'> 
												
											</div>
								        </div>
								    </div>
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_1'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_address_line_1'); ?></label>
										<div class="col-sm-8"> 
											<input type="text" id="d_address_1" name="d_address_1" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_address_1'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_address_1']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_1'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_1'])); ?>" data-err="<?php __('front_address1_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div> 
									</div>
									  <!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_2'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_address_line_2'); ?></label>
										<div class="col-sm-8">
											<input type="text" id="d_address_2" name="d_address_2" class="form-control" value="<?php echo isset($STORAGE['d_address_2']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_2'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_2'])); ?>" data-err="<?php __('front_address2_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div> 
									<!-- /.form-group -->
								<?php
								} 
								if (in_array($tpl['option_arr']['o_df_include_city'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_city'); ?></label>
										<div class="col-sm-8">
											<input type="text" id="d_city" name="d_city" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_city'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_city']) ? htmlspecialchars(stripslashes(@$STORAGE['d_city'])) : htmlspecialchars(stripslashes(@$CLIENT['c_city'])); ?>" data-err="<?php __('front_city_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_state'], array(2, 3)))
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_state'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_state" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_state'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_state']) ? htmlspecialchars(stripslashes(@$STORAGE['d_state'])) : htmlspecialchars(stripslashes(@$CLIENT['c_state'])); ?>" data-err="<?php __('front_state_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_zip'], array(2, 3)))
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_zip'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_zip" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_zip'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_zip']) ? htmlspecialchars(stripslashes(@$STORAGE['d_zip'])) : htmlspecialchars(stripslashes(@$CLIENT['c_zip'])); ?>" data-err="<?php __('front_zip_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_country'], array(2, 3)))
								{
									?>
									<!-- <div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php //__('front_country'); ?></label>
										<div class="col-sm-8">
											<select name="d_country_id" class="form-control<?php //echo (int) $tpl['option_arr']['o_df_include_country'] === 3 ? ' fdRequired' : NULL; ?>" data-err="<?php __('front_country_required');?>">
												<option value="">-- <?php //__('front_choose'); ?> --</option>
												<?php
												//foreach ($tpl['country_arr'] as $country)
												//{
												    ?><option value="<?php //echo $country['id']; ?>"<?php //echo isset($STORAGE['d_country_id']) ? (@$STORAGE['d_country_id'] == $country['id'] ? ' selected="selected"' : NULL) : (@$CLIENT['c_country'] == $country['id'] ? ' selected="selected"' : NULL); ?>><?php echo stripslashes($country['country_title']); ?></option><?php
												//}
												?>
											</select>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div> -->
									<!-- /.form-group -->
									<!-- <div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php //echo "Postcode"; ?></label>
										<div class="col-sm-8">
											<input type="text" name="post_code" class="form-control required" value="<?php //echo isset($STORAGE['post_code']) ? htmlspecialchars(stripslashes(@$STORAGE['post_code'])) : htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])); ?>" data-err="Postcode is required"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div> -->
									<!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_notes'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_special_instructions'); ?></label>
										<div class="col-sm-8">
											<textarea name="d_notes" rows="2" class="form-control" data-err="<?php __('front_special_required');?>"><?php echo isset($STORAGE['d_notes']) ? htmlspecialchars(stripslashes(@$STORAGE['d_notes'])) : htmlspecialchars(stripslashes(@$CLIENT['d_notes'])); ?></textarea>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								} 
								?>
							</div><!-- /.tabpanel -->
						</div><!-- /.tab-content -->
						
						<?php					
						$button_class = ' fdButtonGetLogin';
						if($controller->isFrontLogged())
						{
							$button_class = ' fdButtonGetCategories'; 
						}
						?>
						<!-- <div class="row">
							<div class="form-group">	
							    <label for="" class="col-sm-4 control-label"><?php __('front_promo_code'); ?></label>																 -->
								<!-- <div class="col-md-6 col-sm-6 col-xs-12">
									<strong><?php __('front_promo_code'); ?></strong>
								</div> -->
																	
								<!-- <div class="col-sm-8">
									<div class="input-group pjFdGroupVaucher" style="padding-left: 11px;">
										<input type="text" id="fdVoucherCode_<?php echo $index; ?>" name="voucher_code" value="<?php echo $controller->_is('voucher_code') ? $controller->_get('voucher_code') : NULL;?>" class="form-control"/>
										<span class="input-group-btn">
											<button id="voucher-btn" class="btn text-uppercase fdButtonApply <?php echo $controller->_is('voucher_code') ? 'diisabled' : NULL;?>" type="button"><?php __('front_button_apply');?></button>
										</span>
									</div>
									<div class="alert alert-warning" role="alert" id="fdVoucherMessage_<?php echo $index;?>" style="display: none;padding: 7px; margin-bottom: 0px;margin-top: 10px;margin-left: 11px;"></div>
								</div>
							</div> -->
							
						<!-- </div> -->
						<input type="hidden" id="promoCodeCounter" value="<?php echo $controller->_is('voucher_code') ? "valid" : ''; ?>">
						<div class="row" id="typesBtnUp">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
								<a href="#" class="btn btn-default text-uppercase<?php echo $button_class;?>"><?php __('front_button_back');?></a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
							    <!-- <a href="#" class="btn btn-default text-uppercase fdButtonPayment<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display: none;"><?php __('front_button_continue');?></a> -->
								<a href="#" class="btn btn-default text-uppercase fdButtonRefPickup fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isPickup ? '' : 'none';?>"><?php __('front_button_continue');?></a>
								<a href="#" class="btn btn-default text-uppercase fdButtonRefDelivery fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isDelivery ? '' : 'none';?>"><?php __('front_button_continue');?></a>
							</div>
							<!-- /.col-sm-8 col-sm-offset-4 -->
							
						</div><!-- /.row -->
						<div id="typesMinPriceErr" style="display:none;text-align: center;">
							    <p style="color: red; ">Minimum order price is <strong style="color: #red;" id="fdMinPrice" data-price = "<?php echo $tpl['option_arr']['o_minimum_order']; ?>"><?php echo pjCurrency::formatPrice($tpl['option_arr']['o_minimum_order']); ?></strong></p>
								<a id="backToMenu" href="#">Back to Menu</a>
							</div>
					</form>
					<?php
				}else{
					?>
					<div>
						<?php
						$front_messages = __('front_messages', true, false);
						$system_msg = str_replace("[STAG]", "<a href='#' class='fdStartOver'>", $front_messages[13]);
						$system_msg = str_replace("[ETAG]", "</a>", $system_msg); 
						//echo $system_msg; 
						echo "Your Session has Expired!! ". "<a href='#' class='fdStartOver'>Start Over</a>";
						?>
					</div>
					<?php
				} 
				?>
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
        
        
    </div>
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
	<?php
	if($tpl['status'] == 'OK')
	{
		?>
	<div class="col-md-8 col-sm-8 col-xs-12" style="margin-bottom: 10px;">
		<div class="row" id="typesBtnDown">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
				<a href="#" class="btn btn-default text-uppercase<?php echo $button_class;?>"><?php __('front_button_back');?></a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
				<!-- <a href="#" class="btn btn-default text-uppercase fdButtonPayment<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display: none;"><?php __('front_button_continue');?></a> -->
				<a href="#" class="btn btn-default text-uppercase fdButtonRefPickup fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isPickup ? '' : 'none';?>"><?php __('front_button_continue');?></a>
				<a href="#" class="btn btn-default text-uppercase fdButtonRefDelivery fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isDelivery ? '' : 'none';?>"><?php __('front_button_continue');?></a>
			</div>
			<!-- /.col-sm-8 col-sm-offset-4 -->
			
		</div><!-- /.row -->
	</div>
    <?php } ?>
</div><!-- /.row -->
</div>
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>
<script>
	const IDEAL_API_KEY = "<?php echo IDEAL_API_KEY; ?>";
	$("#btnFindPostCode").on("click", function (e) {
        var postcode_input = $("#inputPostCode");
        getAddresses(postcode_input);
      });

	$("#btnEditPostCode").on("click", function() {
		$(this).parent().removeClass("input-group-btn");
		$(this).parent().addClass("d-none");
		$("#btnFindPostCode").parent().addClass("input-group-btn");
		$("#btnFindPostCode").parent().removeClass("d-none");
		$("#inputPostCode").attr("data-validated", 0);
		$("#inputPostCode").prop("readonly", false);
		$("#d_address_1").val('');
		$("#d_address_2").val('');
		$("#d_city").val('');
	});
	function getAddresses($this) { 
      var Client = IdealPostcodes.Client;
      var lookupPostcode = IdealPostcodes.lookupPostcode;
      //var client = new Client({ api_key: "iddqd" });
      var client = new Client({ api_key:  IDEAL_API_KEY});
      console.log('Api', IDEAL_API_KEY);
      postcode = $this.val();
      if (postcode) {
        var addressList = $(
          '<select id="selAddress" name="selectAddress" class="form-control"/>'
        );
        $("<option />", { value: 0, text: "--Choose--"}).appendTo(addressList);
       
        lookupPostcode({ postcode, client }).then(function (result) {
          //console.log(result[0].postcode_outward);
          postalResult = result;
          if (result.length > 0) {
            var $pc_outward = result[0].postcode_outward;
            var $pc_valid;
            var i = 1;
            if ($("#post_code").hasClass("has-error")) {
              $("#post_code").removeClass("has-error");
              $("#postCodeErr").css("display","none");
            }
            if ($pc_outward) {
			
              $.ajax({
                type: "POST",
                async: false,
                url: $controller_url+"index.php?controller=pjFrontEnd&action=pjActionCheckPostcode",
                data: {post_code: $pc_outward},
                success: function (data) {
                  if (data.code == 100) {
                    $pc_valid = false;
                  } else {
                    $pc_valid = true;
                  }
                }
              });
            }
            // console.log($pc_valid);
            if ($pc_valid) {
			    $("#inputPostCode").attr("data-validated", "1");
				$("#btnEditPostCode").parent().addClass("input-group-btn");
				$("#btnEditPostCode").parent().removeClass("d-none");
				$("#btnFindPostCode").parent().removeClass("input-group-btn");
				$("#btnFindPostCode").parent().addClass("d-none");
				$("#inputPostCode").attr("data-validated", 1);
				$("#inputPostCode").prop("readonly", true);
				$.each(result, function (index) {
				var address =
					result[index].line_1 +
					", " +
					result[index].line_2 +
					", " +
					result[index].line_3;
				
				$("<option />", { value: i, text: address }).appendTo(
					addressList
				);
				i = i + 1;
				});
				$("#addressList").parents(".form-group").removeClass("d-none");
				$("#addressList").html(addressList);
            } else {
				$("#inputPostCode").attr("data-validated", "0");
				$("#inputPostCode").parent().addClass("has-error");
				$("#postCodeErr").text("Post code is not available for delivery");
				$("#postCodeErr").css("display","block");
				$("#d_address_1").val('');
				$("#d_address_2").val('');
				$("#d_city").val('');
            }

            
           }  
          if (result.length == 0) {
            $("#post_code").addClass("has-error");
			$("#postCodeErr").text("Invalid Postcode");
            $("#postCodeErr").css("display","block");
            $("#d_address_1").val('');
            $("#d_address_2").val('');
            $("#d_city").val('');
          }
          if (result.length >= 1) {
            $("#selAddress").change(function(){
            var index = $(this).val();
            index = index - 1;
            //console.log('Index', index);
            if (index >= 0) {
				
              $("#d_address_1").val(result[index].line_1);
              $("#d_address_2").val(result[index].line_2);
              $("#d_city").val(result[index].post_town);
            } else {
              $("#d_address_1").val('');
              $("#d_address_2").val('');
              $("#d_city").val('');
            }
          })
         } 
        });
      }

    }
	// console.log("today");
	$(function() {
		var $today = today();
		if($("input[id^='fd_p_date_']").val() == $today) {
            var $option = $("select[name='p_time']").children("option");
			$option.each(function() {
				if($(this).val() != 'asap') {
					$(this).css('display', 'none');
				} 
			})
            $option.css('display','none');
		}
		if($(".food-item-desc").length > 0) {
			$(".search-me").css("display","block");
		} else {
			$(".search-me").css("display","none");
			if ($("#searchInput-group").css("display") == "flex") {
				$("#searchInput-group").css("display", "none");
				$(".logo").css("display", "block");
			}
		}
		
		function today() {
			var currentUtcTime = new Date($.now());
			var d = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
			var yr = d.getFullYear();
			var month = d.getMonth() + 1;
			var date = d.getDate();
			//console.log(date<10);
			if (month < 10) {
				month = "0" + month;
			} 
			if (date<10) {
				date = "0" + date;
			}
			return date+"."+month+"."+yr;
		}
	})
</script>