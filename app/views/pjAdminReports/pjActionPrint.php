<?php
if(isset($tpl['location_arr']))
{
    ?><p><?php __('lblLocation');?>: <strong><?php echo pjSanitize::html($tpl['location_arr']['name']);?></strong></p><?php
}
?>
<p><?php __('lblDate'); ?>: <strong><?php echo $controller->_get->toString('date_from');?></strong> <?php __('lblTo'); ?> <strong><?php echo $controller->_get->toString('date_to');?></strong> </p>

<table class="table">
	<tr>
		<th><?php __('report_total_orders'); ?></th>
		<th><?php __('report_confirmed'); ?></th>
		<th><?php __('report_pickup'); ?></th>
		<th><?php __('report_delivery'); ?></th>
	</tr>
	<tr>
		<td><?php echo $tpl['total_orders']?></td>
		<td><?php echo $tpl['confirmed_orders']?></td>
		<td><?php echo $tpl['pickup_orders']?></td>
		<td><?php echo $tpl['delivery_orders']?></td>
	</tr>
	<tr>
		<th colspan="2"><?php __('report_unique_clients'); ?></th>
		<th colspan="2"><?php __('report_first_time_clients'); ?></th>
	</tr>
	<tr>
		<td colspan="2"><?php echo $tpl['unique_clients']?></td>
		<td colspan="2"><?php echo $tpl['first_time_clients']?></td>
	</tr>
	<tr>
		<th><?php __('report_total_amount'); ?></th>
		<th><?php __('report_delivery_fees'); ?></th>
		<!-- <th><?php __('report_tax'); ?></th> -->
		<th><?php __('report_discounts'); ?></th>
	</tr>
	<tr>
		<td><?php echo pjCurrency::formatPrice($tpl['price_info']['total_amount']);?></td>
		<td><?php echo pjCurrency::formatPrice($tpl['price_info']['delivery_fee']);?></td>
		<!-- <td><?php echo pjCurrency::formatPrice($tpl['price_info']['tax']);?></td> -->
		<td><?php echo pjCurrency::formatPrice($tpl['price_info']['discount']);?></td>
	</tr>
	<tr>
		<th colspan="2"><?php __('report_total_products_ordered'); ?></th>
		<!-- <th colspan="2"><?php __('report_packing_fees'); ?></th> -->
	</tr>
	<tr>
		<td colspan="2"><?php echo $tpl['total_products']?></td>
		<!-- <td colspan="2"><?php echo pjCurrency::formatPrice($tpl['price_info']['packing_fee']);?></td> -->
	</tr>
</table>
<br/>
<table class="table">
    <thead>
        <tr>
            <th><?php __('report_category');?></th>
			<th><?php __('report_products_ordered');?></th>
			<th><?php __('report_total_amount');?></th>
        </tr>
    </thead>

    <tbody>
        <?php
		foreach($tpl['category_arr'] as $k => $v)
		{
			if((int) $v['total_products'] > 0) {
			?>
			<tr>
				<td><?php echo pjSanitize::html($v['name']);?></td>
				<td><?php echo (int) $v['total_products'];?></td>
				<td><?php echo pjCurrency::formatPrice($v['total_amount']);?></td>
			</tr>
			<?php
		    }
		} 
		?>
    </tbody>
</table>
<br/>
<table class="table">
    <thead>
		<tr>
			<th><?php __('report_product');?></th>
			<th><?php __('report_quantity');?></th>
			<th><?php __('report_total_amount');?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($tpl['product_arr'] as $k => $v)
		{
			if((int) $v['total_products'] > 0) {
			?>
			<tr>
				<td><?php echo pjSanitize::html($v['name']);?></td>
				<td><?php echo (int) $v['total_products'];?></td>
				<td><?php echo pjCurrency::formatPrice($v['total_amount']);?></td>
			</tr>
			<?php
			}
		} 
		?>
	</tbody>
</table>