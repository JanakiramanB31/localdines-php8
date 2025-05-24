<?php 
//echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
  // echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
  /* echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
  echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";*/ 
  //echo "<pre>"; print_r($tpl['order_details']); echo "</pre>";
  $order_details = $tpl['order_details'];
  $htmlSpaceCode = "&nbsp;";
  $htmlSpaceRepeat = 5;
?>
<style>
	fieldset  {
		background-color: #eeeeee;
    margin: 10px 0px;
    width: 90%;
    margin: auto;
	}
	legend {
		background-color: grey;
    color: white;
    padding-left: 10px
	}
	.swal-medium {
		width: 70%;
		left: 36%;
	}
</style>
<div>
	<?php if (strtolower($order_details["origin"]) != "pos") { ?>
	<h2>Customer Info</h2>
	<table style='margin: auto; width: 90%;'>
		<tbody>
			<tr>
				<th>Name:</th>
				<td><?php echo $order_details['surname']; ?></td>
				<td><?php echo str_repeat($htmlSpaceCode, $htmlSpaceRepeat); ?></td>
				<th>Customer Type:</th>
				<td><?php echo $order_details['c_type']; ?></td>
			</tr>
			<tr>
				<th>SMS/Email:</th>
				<td><?php echo $order_details['sms_email']; ?></td>
				<td><?php echo str_repeat($htmlSpaceCode, $htmlSpaceRepeat); ?></td>
				<th>Phone No:</th>
				<td><?php echo $order_details['phone_no']; ?></td>
			</tr>
			<tr>
				<th>SMS Sent Time:</th>
				<td><?php echo $order_details['sms_sent_time']; ?></td>
				<td><?php echo str_repeat($htmlSpaceCode, $htmlSpaceRepeat); ?></td>
				<th>Estimated Delivery:</th>
				<td><?php echo $order_details['delivered_time']; ?></td>
			</tr>
			<tr>
				<th><?php echo $order_details["type"] == 'delivery'?"Delivered ":"Pickup "?> Time:</th>
				<td><?php echo $order_details['delivered_time']; ?></td>
				<td><?php echo str_repeat($htmlSpaceCode, $htmlSpaceRepeat); ?></td>
				<th>Status</th>
				<td><?php echo $order_details['status']; ?></td>
			</tr>
			<?php if ($order_details["type"] == 'delivery') { ?>
			<tr>
				<th>Address:</th>
				<!-- <td><?php //echo str_repeat($htmlSpaceCode, $htmlSpaceRepeat); ?></td> -->
				<td colspan="3"><br/><?php echo $order_details['address']; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<br/><br/>
<?php } ?>
	<fieldset>
		<legend>Order Items</legend>
		<table style='width: 100%;'>
			<tbody>
				<tr>
					<th style="text-align: center;">Product</th>
					<th></th>
					<th style="text-align: center;">Quantity</th>
					<th style="text-align: center;">Amount</th>
				</tr>
				<?php foreach($tpl['oi_arr'] as $k => $oi) { 
					if (in_array($oi['status'], RETURN_TYPES))  { 
            $rowClass = "strikethrough";
          } else {
          	$rowClass = "";
          }
          $productName = $oi['type'] == 'custom' ? $oi['custom_name']:$oi['product_name'];
				?>
				<tr class="<?php echo $rowClass; ?>" >
					<td><?php echo $productName; ?></td>
					<td>X</td>
					<td><?php echo $oi['cnt'] ?></td>
					<td style="text-align: right; padding-right:20px"><?php echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th colspan="3" style="text-align: right;"><?php echo "Total: " ?></th>
					<td style="text-align: right; padding-right:20px"><strong><?php echo pjCurrency::formatPrice($order_details["total"]); ?></strong></td>
				</tr>
			</tbody>
		</table>
		<br/>
	</fieldset>
</div>
