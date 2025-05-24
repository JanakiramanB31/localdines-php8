<?php 
$action = $controller->_get->toString('action'); 
//echo $controller->_is('voucher_code'); 
//echo '<pre>';print_r($_SESSION); echo '</pre>';
?>

<div id="pjFdCartWrapper_<?php echo $controller->_get->toString('index');?>" class="panel panel-default">
	<div class="panel-heading pjFdPanelHead">
		<h1 class="panel-title clearfix cart-title">
			<i class="fa fa-shopping-cart"></i>
			&nbsp;&nbsp;
			<span class="lead text-uppercase"><?php __('front_your_cart');?></span>
			<a style="background-color: #f7f7f7 !important; color: black;" class="pull-right pjFdBtnTotal fdBtnOrderTotal" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>" href="#" role="button" title="<?php __('front_order_total');?>"><?php echo $tpl['cart_box']['items_in_cart'];?> Items</a>
		</h1><!-- /.panel-title -->

	</div><!-- /.panel-heading pjFdPanelHead -->
	<div class="panel-body pjFdPanelBody">
		<?php if ($action == 'pjActionPreview') { ?>
			<h2 class="text-muted text-center" id="previewCartHead">Your Cart</h2>
		
		
		<?php } ?>
		<?php
		
		// $action = $controller->_get->toString('action');
		$readonlyMode = in_array($action, array('pjActionCheckout', 'pjActionPreview'));
		if ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart']) > 0)
		{
	    switch ($controller->_get->toString('type'))
			{
				case 'plain':
				case 'total':
					$price = 0;
					foreach ($tpl['cart_box']['cart'] as $hash => $item)
					{
						foreach ($tpl['cart_box']['product_arr'] as $product)
						{
							if ($product['id'] == $item['product_id'])
							{
								$product_price = 0;
								$product_price = $item['price'] * $item['cnt'];
								$price += $product_price;
								$extra_price = 0;
								foreach ($item['extra_arr'] as $extra_id => $extra)
								{
									$extra_price += $extra['price'] * $extra['qty'];
								}
								?>
								<div class="row">
									<div class="alert alert-dismissible clearfix" role="alert">
										<div class="row">
											<div class="col-lg-5 col-md-4 col-sm-10 col-xs-5 text-capitalize cart-product">
												<strong><?php echo stripslashes($product['name'])?></strong><?php echo (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL);?>
												<?php 
												if (!$readonlyMode)
												{
													?>
													<button type="button" class="close fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""><i class="fa fa-trash" aria-hidden="true"></i></button>
													<?php 
												}
												?>
											</div><!-- /.col-lg-5 col-md-4 col-sm-10 col-xs-5 -->
											
											<div class="col-lg-5 col-md-5 col-sm-7 col-xs-5">
												<div class="input-group pjFdCounter">
													<span class="input-group-btn">
														<button class="btn btn-default fdCartQty" type="button" data-hash="<?php echo $hash; ?>" data-sign="-" data-action="<?php echo $action;?>"<?php echo $readonlyMode ? ' disabled' : NULL; ?>>-</button>
													</span>
													<input class="form-control text-center pjFdCartCnt" type="text" disabled="disabled" placeholder="<?php echo $item['cnt']; ?>">
													<span class="input-group-btn">
														<button class="btn btn-default fdCartQty" type="button" data-hash="<?php echo $hash; ?>" data-sign="+" data-action="<?php echo $action;?>"<?php echo $readonlyMode ? ' disabled' : NULL; ?>>+</button>
													</span>
												</div>
											</div><!-- /.col-lg-4 col-md-5 col-sm-7 col-xs-4 -->
											
											<div class="col-lg-2 col-md-3 col-sm-5 col-xs-2 fdCartPriceCol">
												<span><?php echo pjCurrency::formatPrice($product_price + $extra_price, "");?></span>
												
											</div><!-- /.col-lg-3 col-md-3 col-sm-5 col-xs-4 -->
										</div><!-- /.row -->
										<?php
										$i = 1;
										foreach ($item['extra_arr'] as $extra_id => $extra)
										{
											$price += $extra['price'] * $extra['qty'];
											?><small><?php 
											
											echo stripslashes($extra['name']); ?> (<?php 
												if (intval($extra['price'])) {
												echo pjCurrency::formatPrice($extra['price']); ?> x <?php } echo $extra['qty']; ?>)</small><?php echo $i != count($item['extra_arr']) ? '<br/>' : null;
											$i++;
										} 
										?>
									</div>
								</div><!-- /.row -->
								<?php
							}
						}
					}
					?>
					<?php if($action != 'pjActionVouchers' && $action != 'pjActionPreview'  && $action != 'pjActionCheckout')
					{
						//echo $action;
					    if ($controller->_get->toString('type') == 'total') { ?>
					<div class="row">
						<div class="form-group">	
							<label for="" class="col-sm-4 control-label"><?php __('front_promo_code'); ?></label>																
							<!-- <div class="col-md-6 col-sm-6 col-xs-12">
								<strong><?php __('front_promo_code'); ?></strong>
							</div> -->
																
							<div class="col-sm-8">
								<div class="input-group pjFdGroupVaucher" style="padding-left: 11px;">
									<input type="text" id="fdVoucherCode" name="voucher_code" value="<?php echo $controller->_is('voucher_code') ? $controller->_get('voucher_code') : NULL;?>" class="form-control"/>
									<span class="input-group-btn">
										<!-- <button id="voucher-btn" class="btn text-uppercase fdButtonApply <?php //echo $controller->_is('voucher_code') && $controller->_get('voucher_code') ? 'disabled' : NULL;?>" type="button"><?php//__('front_button_apply');?></button> -->
										<button id="voucher-btn" class="btn text-uppercase fdButtonApply" type="button"><?php __('front_button_apply');?></button>
									</span>
								</div>
								<div class="alert alert-warning" role="alert" id="fdVoucherMessage" style="display: none;padding: 7px; margin-bottom: 0px;margin-top: 10px;margin-left: 11px;"></div>
							</div>
						</div>
						
					</div>
					<?php } } ?>

					
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<span class="text-uppercase"><?php __('front_price'); ?>:</span>
							<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($price); ?></span>
						</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
					</div><!-- /.row -->
					
					<?php
					
					if($action != 'pjActionVouchers')
					{
						// echo "comes inside";
					    if ($controller->_get->toString('type') == 'total')
						{
							$packing = $controller->_get("packing");
							$delivery = $controller->_get("delivery");
							$discount = 0;
							$discount_print = NULL;
							$subtotal = 0.00;
							$tax = 0.00;
							$total = 0.00;
							
							if ($controller->_get('voucher_code') !== false)
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

								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 text-right">
										<span class="text-uppercase"><?php __('front_discount'); ?>:</span>
										<span class="pjFdPriceTotal"><?php echo $discount_print; ?></span>
									</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
								</div><!-- /.row -->
								<?php
							}
							?>
							<!-- <div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 text-right">
									<span class="text-uppercase"><?php //__('front_packing_fee'); ?>:</span>
									<span class="pjFdPriceTotal"><?php //echo pjCurrency::formatPrice($packing); ?></span>
								</div>
							</div> -->
							<?php
							if ($controller->_get('type') == 'delivery')
							{
								?>
								<!-- <div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 text-right">
										<span class="text-uppercase"><?php //__('front_delivery_fee'); ?>:</span>
										<span class="pjFdPriceTotal"><?php //echo pjCurrency::formatPrice($delivery); ?></span>
									</div>
								</div> -->
								<?php
							}
							?>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 text-right">
									<span class="text-uppercase"><?php __('front_subtotal'); ?>:</span>
									<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($subtotal); ?></span>
								</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
							</div><!-- /.row -->
							<?php
							if(!empty($tpl['option_arr']['o_tax_payment']))
							{
								if($tpl['option_arr']['o_add_tax'] == '1' && $controller->_get('type') == 'delivery')
								{
									$tax = (($subtotal - $delivery) * $tpl['option_arr']['o_tax_payment']) / 100;
								} else {
									$tax = ($subtotal * $tpl['option_arr']['o_tax_payment']) / 100;
								}
								
								$controller->_set('tax', $tax);
								?>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 text-right">
										<span class="text-uppercase"><?php __('front_tax'); ?>:</span>
										<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($tax); ?></span>
									</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
								</div><!-- /.row -->
								<?php
							}
							$total = $subtotal + $tax;
							$controller->_set('total', $total);
							
							?>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 text-right">
									<span class="text-uppercase"><?php __('front_total'); ?>:</span>
									<span  class="pjFdPriceTotal" id="cartTotal" data-totalAmt = "<?php echo $total; ?>"><?php echo pjCurrency::formatPrice($total); ?></span>
								</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
							</div><!-- /.row -->
							<?php
						}
					} else { ?>
						<div class="text-center">
						<a href="#" id="btn-checkout" class="btn btn-default btn-block text-uppercase fdButtonCheckout" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>"><?php __('front_button_checkout');?></a>
					</div><!-- /.text-center -->
					<?php } 
					break;
				default:
				// echo "comes default";
				?>
				    
					<?php
					$price = 0;
					foreach ($tpl['cart_box']['cart'] as $hash => $item)
					{
						foreach ($tpl['cart_box']['product_arr'] as $product)
						{
							if ($product['id'] == $item['product_id'])
							{
								$product_price = 0;
								$product_price = $item['price'] * $item['cnt'];
								$price += $product_price;
								$extra_price = 0;
								foreach ($item['extra_arr'] as $extra_id => $extra)
								{
									$extra_price += $extra['price'] * $extra['qty'];
								}
								?>
								
								<div class="row">
									<div class="alert alert-dismissible clearfix" role="alert">
										<div class="row">
											<div class="col-lg-5 col-md-4 col-sm-10 col-xs-5 text-capitalize cart-product">
												<strong><?php echo stripslashes($product['name'])?></strong><?php echo (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL);?>
												<button type="button" class="close fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""><i class="fa fa-trash" aria-hidden="true"></i></button>
											</div><!-- /.col-lg-5 col-md-4 col-sm-10 col-xs-5 -->
											
											<div class="col-lg-5 col-md-5 col-sm-7 col-xs-5">
												<div class="input-group pjFdCounter">
													<span class="input-group-btn">
														<button class="btn btn-default fdCartQty" type="button" data-hash="<?php echo $hash; ?>" data-sign="-" data-action="<?php echo $action;?>">-</button>
													</span>
													<input class="form-control text-center pjFdCartCnt" type="text" disabled="disabled" placeholder="<?php echo $item['cnt']; ?>">
													<span class="input-group-btn">
														<button class="btn btn-default fdCartQty" type="button" data-hash="<?php echo $hash; ?>" data-sign="+" data-action="<?php echo $action;?>">+</button>
													</span>
												</div>
											</div><!-- /.col-lg-4 col-md-5 col-sm-7 col-xs-4 -->
											
											<div class="col-lg-2 col-md-3 col-sm-5 col-xs-2 fdCartPriceCol">
												<span><?php echo pjCurrency::formatPrice($product_price + $extra_price, "");?></span>
												
											</div><!-- /.col-lg-3 col-md-1 col-sm-5 col-xs-2 -->
										</div><!-- /.row -->
										

										<?php
										$i = 1;
										foreach ($item['extra_arr'] as $extra_id => $extra)
										{
											$price += $extra['price'] * $extra['qty'];
											?><small><?php echo stripslashes($extra['name']); ?> (<?php 
												if (intval($extra['price'])) {
												echo pjCurrency::formatPrice($extra['price']); ?> x <?php } echo $extra['qty']; ?>)</small><?php echo $i != count($item['extra_arr']) ? '<br/>' : null;
											$i++;
										} 
										?>
									</div>
								</div><!-- /.row -->
								
								<?php
							}
						}
					}
					    $controller->_set('price', $price);
					?>
					<div class="row">
					    <div class="col-md-12 col-sm-12 col-xs-12" id="orderType">
							<div class="row">
								<?php if(FRONT_END_PICKUP == 1) { ?>
								<div class="col-xs-12 d-flex justify-content-between">
									<label><h5>PICKUP</h5></label>
									<div class="checkBox">
								    <input type="radio" id="radiopickup" class="radio d-none" value="pickup" name="type" />
										<label for="radiopickup"></label>
                  </div>
								</div>
								<?php } else {} ?>
								<?php if(FRONT_END_DELIVERY == 1) { ?>
								<div class="col-xs-12 d-flex justify-content-between">
									<label><h5>DELIVERY</h5></label>
									<div class="checkBox">
									    <input type="radio" id="radiodelivery" class="radio d-none" value="delivery" name="type"/>
										<label for="radiodelivery"></label>
									</div>
								</div>
								<?php } else {} ?>
							</div>
						</div>
						<div class="row" id="postCodeSec">
							<div class="form-group">																	
								<div class="col-md-6 col-sm-6 col-xs-12">
									<strong><?php //__('front_promo_code'); ?></strong>
								</div>
								<!-- /.col-md-6 col-sm-6 col-xs-6 -->
																	
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="input-group" id="post_code">
																		
										<input type="text" class="form-control fdRequired required" placeholder="Enter your postcode" id="cartInputPostCode" value="<?php echo isset($STORAGE['post_code']) ? htmlspecialchars(stripslashes(@$STORAGE['post_code'])) : htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])); ?>" data-err="Postcode is required">
										
										<span class="input-group-btn">
											<button class="btn btn-postcode" type="button" id="cartBtnFindPostCode"><i class="glyphicon glyphicon-ok"></i></button>
										</span>
										
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										<span class="invlaid-pc d-none">Invalid Post Code</span>

										
									</div>
									<div id="postCodeErr" style="display:none; color: #a94442; margin-top; 10px;text-align: center;"></div>
									<div id="postCodeSuccess" style="display:none; color: #0a5114; margin-top; 10px;text-align: center;">Postcode is valid</div>
								</div>
								
							</div>
							
						
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<span class="text-uppercase"><?php __('front_price'); ?>:</span>
							<span id="totalCartPrice" class="pjFdPriceTotal" data-price = "<?php echo $price; ?>"><?php echo pjCurrency::formatPrice($price); ?></span>
						</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
					</div><!-- /.row -->
					
					<br />
					
					<div class="text-center">
						<a href="#" id="btn-checkout" class="btn btn-default btn-block text-uppercase fdButtonCheckout" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>" data-haspostcode="<?php echo $controller->hasPostcode() ? 'yes' : 'no';?>"><?php __('front_button_checkout');?></a>
					</div><!-- /.text-center -->
					<?php
					break;
			}
		} else {
			?><p class="text-center fdEmptyCart" id="emptyCart"><?php __('front_empty_cart');?></p><!-- /.text-center --><?php
		}
		?>
	</div><!-- /.panel-body pjFdPanelBody -->
	<p id="minPriceErr" style="display:none; color: red; text-align: center;">Minimum order price is <strong style="color: #red;" id="fdMinPrice" data-price = "<?php echo $tpl['option_arr']['o_minimum_order']; ?>"><?php echo pjCurrency::formatPrice($tpl['option_arr']['o_minimum_order']); ?></strong></p>
</div><!-- /.panel panel-default -->
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>
<script>
	$postcodeInputValue = $("#postCodeInSession").val();
	$("#cartInputPostCode").val($postcodeInputValue);
	if($postcodeInputValue == '') {
		pjQ.$(".fdBtnOrderTotal").addClass('disabled');
	}
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
		var cartPrice =  $('#totalCartPrice').attr("data-price");
		var minPrice =  $('#fdMinPrice').attr("data-price");
		var promoState =  $("#promoCodeCounter").val();
		var min_price_with_sign =  $('#fdMinPrice').text();
		$("#fdMyOrderPrice").text(" "+($('#totalCartPrice').text()));
		if (promoState != '') {
			if (promoState == 'invalid') {
        $("#fdVoucherMessage").html("Promo code is invalid").show();
			} else if (promoState == 'minpriceErr') { 
        $('#fdVoucherMessage').html("Minimum Price is "+min_price_with_sign).show();
			} else {
				$('#fdVoucherMessage').html('').hide();
			}
		}
		if($("form[id^=fdTypeForm_]").length > 0) {
			if ($(".fdDelivery").css("display") == "block") {
				var voucherTotal = pjQ.$("#cartTotal").attr('data-totalAmt');
				if (parseFloat(voucherTotal) < parseFloat(minPrice)) {
					$(".fdButtonRefDelivery").addClass('disabled');
					$("#typesMinPriceErr").css('display', 'block');
				} else {
					$(".fdButtonRefDelivery").removeClass('disabled');
					$("#typesMinPriceErr").css('display', 'none');
				}
			}
		}
		var $type =$("#orderTypeCounter").val();
		if ($type == 'delivery') {
		//if (0) {
			if (parseFloat(cartPrice) < parseFloat(minPrice)) {
        $("#minPriceErr").css('display', 'block');
				$("#btn-checkout").removeClass("fdButtonCheckout");
				$("#btn-checkout").addClass("disabled");
				$(".fdBtnOrderTotal").addClass("disabled");
			}
			$("#orderType #radiodelivery").prop("checked", true );
			$("#orderType #radiopickup").prop("checked", false );
		} else {
			$("#orderType #radiopickup").prop("checked", true );
			$("#orderType #radiodelivery").prop("checked", false );
			$("#minPriceErr").css('display', 'none');
			$("#btn-checkout").addClass("fdButtonCheckout");
			$("#btn-checkout").removeClass("disabled");
			$(".fdBtnOrderTotal").removeClass("disabled");
		}
		if ($("#emptyCart").length > 0) {
			$("#btn-cart").css("display", "none");
		} else {
			$("#btn-cart").css("display", "block");
			$("#fdMyOrderPrice").text(" "+($('#totalCartPrice').text()));
		}
		if($("#orderTypeCounter").length > 0 && $type == 'delivery') {
			$("#postCodeSec").css('display', 'flex');
		} else {
			$("#postCodeSec").css('display', 'none');
		}
		
		function today() {
			var currentUtcTime = new Date($.now());
			var d = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
			var yr = d.getFullYear();
			var month = d.getMonth() + 1;
			var date = d.getDate();
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
