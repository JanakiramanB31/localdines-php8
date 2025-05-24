<div id="pjFdCartWrapper_<?php echo $controller->_get->toString('index');?>" class="panel panel-default">
	<div class="panel-heading pjFdPanelHead">
		<h1 class="panel-title clearfix">
			<i class="fa fa-shopping-cart"></i>
			&nbsp;&nbsp;
			<span class="lead text-uppercase"><?php __('front_your_cart');?></span>
			<a class="btn btn-default pull-right pjFdBtnTotal fdBtnOrderTotal" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>" href="#" role="button" title="<?php __('front_order_total');?>"><?php echo $tpl['cart_box']['items_in_cart'];?></a>
		</h1><!-- /.panel-title -->
	</div><!-- /.panel-heading pjFdPanelHead -->
	<div class="panel-body pjFdPanelBody">
		<?php
		$action = $controller->_get->toString('action');
		$readonlyMode = in_array($action, array('pjActionCheckout', 'pjActionPreview'));
		echo $controller->_get->toString('type');
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
								$expra_price = 0;
								foreach ($item['extra_arr'] as $extra_id => $extra)
								{
									$expra_price += $extra['price'] * $extra['qty'];
								}
								?>
								<div class="row">
									<div class="alert alert-dismissible clearfix" role="alert">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-10 col-xs-4 text-capitalize">
												<strong><?php echo stripslashes($product['name'])?></strong><?php echo (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL);?>
											</div><!-- /.col-lg-5 col-md-4 col-sm-10 col-xs-5 -->
											
											<div class="col-lg-4 col-md-5 col-sm-7 col-xs-4">
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
											
											<div class="col-lg-4 col-md-3 col-sm-5 col-xs-4 fdCartPriceCol">
												<span><?php echo pjCurrency::formatPrice($product_price + $expra_price, "");?></span>
												<?php 
												if (!$readonlyMode)
												{
													?>
													<button type="button" class="close fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""><span aria-hidden="true">&times;</span></button>
													<?php 
												}
												?>
											</div><!-- /.col-lg-3 col-md-3 col-sm-5 col-xs-4 -->
										</div><!-- /.row -->
										<?php
										$i = 1;
										foreach ($item['extra_arr'] as $extra_id => $extra)
										{
											$price += $extra['price'] * $extra['qty'];
											?><small><?php echo stripslashes($extra['name']); ?> (<?php echo pjCurrency::formatPrice($extra['price']); ?> x <?php echo $extra['qty']; ?>)</small><?php echo $i != count($item['extra_arr']) ? '<br/>' : null;
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
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<span class="text-uppercase"><?php __('front_price'); ?>:</span>
							<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($price); ?></span>
						</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
					</div><!-- /.row -->
					<?php
					if($action != 'pjActionVouchers')
					{
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
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 text-right">
									<span class="text-uppercase"><?php __('front_packing_fee'); ?>:</span>
									<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($packing); ?></span>
								</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
							</div><!-- /.row -->
							<?php
							if ($controller->_get('type') == 'delivery')
							{
								?>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 text-right">
										<span class="text-uppercase"><?php __('front_delivery_fee'); ?>:</span>
										<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($delivery); ?></span>
									</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
								</div><!-- /.row -->
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
									<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($total); ?></span>
								</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
							</div><!-- /.row -->
							<?php
						}
					}
					break;
				default:
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
								$expra_price = 0;
								foreach ($item['extra_arr'] as $extra_id => $extra)
								{
									$expra_price += $extra['price'] * $extra['qty'];
								}
								?>
								<div class="row">
									<div class="alert alert-dismissible clearfix" role="alert">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-10 col-xs-4 text-capitalize">
												<strong><?php echo stripslashes($product['name'])?></strong><?php echo (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL);?>
											</div><!-- /.col-lg-5 col-md-4 col-sm-10 col-xs-5 -->
											
											<div class="col-lg-4 col-md-5 col-sm-7 col-xs-4">
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
											
											<div class="col-lg-4 col-md-3 col-sm-5 col-xs-4 fdCartPriceCol">
												<span><?php echo pjCurrency::formatPrice($product_price + $expra_price, "");?></span>
												<button type="button" class="close fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""><span aria-hidden="true">&times;</span></button>
											</div><!-- /.col-lg-3 col-md-1 col-sm-5 col-xs-2 -->
										</div><!-- /.row -->
										<?php
										$i = 1;
										foreach ($item['extra_arr'] as $extra_id => $extra)
										{
											$price += $extra['price'] * $extra['qty'];
											?><small><?php echo stripslashes($extra['name']); ?> (<?php echo pjCurrency::formatPrice($extra['price']); ?> x <?php echo $extra['qty']; ?>)</small><?php echo $i != count($item['extra_arr']) ? '<br/>' : null;
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
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<span class="text-uppercase"><?php __('front_price'); ?>:</span>
							<span class="pjFdPriceTotal"><?php echo pjCurrency::formatPrice($price); ?></span>
						</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
					</div><!-- /.row -->
					
					<br />
					
					<div class="text-center">
						<a href="#" class="btn btn-default btn-block text-uppercase fdButtonCheckout" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>"><?php __('front_button_checkout');?></a>
					</div><!-- /.text-center -->
					<?php
					break;
			}
		} else {
			?><p class="text-center fdEmptyCart"><?php __('front_empty_cart');?></p><!-- /.text-center --><?php
		}
		?>
	</div><!-- /.panel-body pjFdPanelBody -->
</div><!-- /.panel panel-default -->