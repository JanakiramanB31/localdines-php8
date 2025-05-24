<?php
$index = $controller->_get->toString('index');
if ($controller->_get->toInt('product_id'))
{
    //MEGAMING

	$productData = json_encode( $tpl["arr"], JSON_HEX_APOS);
	
?>	
   
	<input type="hidden" id="prdInfo_<?php echo $index;?>" data-type="input" name="prdInfo_[<?php echo $index;?>]" value='<?php echo $productData; ?>' />

    <!-- /MEGAMIND -->

<?php	
	if($tpl['arr']['set_different_sizes'] == 'F')
    {
        ?>
		<span class="fdPriceLabel"><?php echo pjCurrency::formatPrice($tpl['arr']['price']);?></span>
		<input type="hidden" id="fdPrice_<?php echo $index;?>" data-type="input" name="price_id[<?php echo $index;?>]" value="<?php echo $tpl['arr']['price'];?>" />
		<?php
	}else{
		?>
		<select id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-type="select" class="fdSize form-control">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
			<?php
			foreach($tpl['price_arr'] as $v)
			{
			    ?><option value="<?php echo $v['id']?>" data-price="<?php echo $v['price'];?>"><?php echo pjSanitize::clean($v['price_name'])?>: <?php echo pjCurrency::formatPrice($v['price']); ?></option><?php
			} 
			?>
		</select>
		<?php
	}

} else {
	?>
	<div class="business-<?php echo $index;?>" style="display: none;">
		<select id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-type="select" class="fdSize form-control">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
		</select>
	</div>
	<?php
}
?>