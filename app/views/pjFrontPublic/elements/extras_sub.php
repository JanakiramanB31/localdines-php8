<tr class="child-extras extras-child-<?php echo $identifier;?>">
	<td class="col-lg-7 col-md-6 col-sm-5 col-xs-4 text-capitalize">
		<?php echo pjSanitize::clean($extra['name']); ?>
	</td>
	
	<td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-right">
		<span>
			<?php 
			if (intval($extra['price'])) {
				// echo $extra['price'];
				echo pjCurrency::formatPrice($extra['price']);
			} 
			?>
		</span>
	</td><!-- /.col-lg-2 col-md-2 col-sm-2 col-xs-2 -->

	<td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
		<?php 
		if (intval($extra['price'])) {
			echo 'x';
		} 
		?>
		
	</td>
	<?php if (intval($extra['price'])) { ?>
	<td class="col-lg-2 col-md-3 col-sm-4 col-xs-5">
		<div class="input-group pjFdCounter">
			<span class="input-group-btn">
				<button class="btn btn-default fdOperator" type="button" data-index="<?php echo $category_id?>-<?php echo $product['id']?>-<?php echo $extra['id'];?>" data-sign="-">-</button>
			</span>
			<input id="fdQty_<?php echo $category_id?>-<?php echo $product['id']?>-<?php echo $extra['id'];?>" name="extra_id[<?php echo $extra['id'];?>]" class="fdQtyInput form-control align-center" value="0"/>
			<span class="input-group-btn">
				<button class="btn btn-default fdOperator" type="button" data-index="<?php echo $category_id?>-<?php echo $product['id']?>-<?php echo $extra['id'];?>" data-sign="+">+</button>
			</span>
		</div><!-- /.input-group pjFdCounter -->
	</td>
<?php } else { ?>
	<td>
		<input type="hidden" id="fdQty_<?php echo $category_id?>-<?php echo $product['id']?>-<?php echo $extra['id'];?>" name="extra_id[<?php echo $extra['id'];?>]" class="fdQtyInput form-control align-center" value="0"/>
		<input type="checkbox" data-index="<?php echo $category_id?>-<?php echo $product['id']?>-<?php echo $extra['id'];?>" name="fdextra_id[<?php echo $extra['id'];?>]" class="big-checkbox form-check-input fdQtyCheck" >
	</td>
<?php } ?>
</tr>