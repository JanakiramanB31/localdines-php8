<div class="fdCategoryContainer">
	<a href="#" class="fdCateItem fdPrev"></a>
	<div class="fdCategoryList">
		<span id="fdCateInner_<?php echo $index;?>">
		<?php
		foreach($tpl['main']['category_arr'] as $k => $v)
		{
			?><a href="#" class="fdCategoryNode fdCateItem<?php echo $tpl['main']['open_id'] == $v['id'] ? ' fdCateFocus' : null; ?>" data-id="<?php echo $v['id'];?>"><?php echo pjSanitize::clean($v['name']);?><label><abbr></abbr></label></a><?php
		} 
		?>
		</span>
	</div>
	<a href="#" class="fdCateItem fdNext"></a>
</div>

<div class="fdProductList">
	<?php

	if(!empty($tpl['main']['product_arr']))
	{
		foreach($tpl['main']['product_arr'] as $product)
		{
			
			?>
			<div id="fdProductBox_<?php echo $product['id'];?>" class="fdProductBox">
				<form style="overflow: hidden;" action="" method="post">
					<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
					<?php
					$image_path = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/no_image.png';
					if(!empty($product['image']))
					{
						$image_path = PJ_INSTALL_URL . $product['image'];
					} 
					?>
					<div class="fdImage" data-id="<?php echo $product['id'];?>" style="background-image: url('<?php echo $image_path;?>');"></div>
					<div class="fdBoxOnRight">
						<div class="fdBoxHeading">
							<div class="fdTitle">
								<a class="fdProductTitle" data-id="<?php echo $product['id'];?>" href="#"><?php echo pjSanitize::clean($product['name']);?></a>
								<div class="fdPrice">
									<?php
									if($product['set_different_sizes'] == 'T' && count($product['price_arr']) > 0)
									{
										?>
										<select id="fdSelectSize_<?php echo $product['id'];?>" name="price_id" class="fdSelect fdW100p fdSelectSize" data-id="<?php echo $product['id'];?>">
											<option value="">-----</option>
											<?php
											foreach($product['price_arr'] as $price)
											{
											    ?><option value="<?php echo $price['id']?>"><?php echo $price['price_name']?>: <?php echo pjCurrency::formatPrice($price['price']);?></option><?php
											} 
											?>
										</select>
										<?php
									} else {
									    ?><label><?php echo pjCurrency::formatPrice($product['price']);?></label><?php
									} 
									?>
								</div>
								
							</div>	
						</div>
						<div class="fdBoxDesc">
							<div class="fdDescription"><?php echo nl2br(pjSanitize::clean($product['description']));?></div>
							<div class="fdExtraList">
								<?php
								if(!empty($product['extra_arr']))
								{
									foreach($product['extra_arr'] as $extra)
									{
										?>
										<div class="fdExtraBox">
											<label><?php echo pjSanitize::clean($extra['name']); ?></label>
											<span class="fdExtraPrice"><?php echo pjCurrency::formatPrice($extra['price']);?></span>
											<a href="#" class="fdAddExtra" data-index="<?php echo $product['id']?>-<?php echo $extra['id'];?>"><?php __('front_add');?></a>
											<div class="fdExtraQty"><div class="fdSpinner fdLeft"><abbr class="fdOperator" data-index="<?php echo $product['id']?>-<?php echo $extra['id'];?>" data-sign="-">-</abbr></div><div class="fdMiddle"><input id="fdQty_<?php echo $product['id']?>-<?php echo $extra['id'];?>" name="extra_id[<?php echo $extra['id'];?>]" class="fdQtyInput" value="0"/></div><div class="fdSpinner fdRight"><abbr class="fdOperator" data-index="<?php echo $product['id']?>-<?php echo $extra['id'];?>" data-sign="+">+</abbr></div></div>
										</div>
										<?php
									}
									?>
									<a id="fdProductOrder_<?php echo $product['id']; ?>" class="fdButton fdAbsoluteButton fdProductOrder" data-id="<?php echo $product['id'];?>" href="#"><?php __('front_order_now');?></a>
									<?php
								}else{
									?>
									<a id="fdProductOrder_<?php echo $product['id']; ?>" class="fdButton fdProductOrder" data-id="<?php echo $product['id'];?>" href="#"><?php __('front_order_now');?></a>
									<?php
								} 
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php
		}
	} else {
		__('front_product_not_found', false, false);
	}
	?>
</div>