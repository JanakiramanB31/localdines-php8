<?php $paperWidth = "290px"; //echo "<pre>"; print_r($tpl['arr']); echo "</pre>"; ?>
<div id="receiptContainer">
<div class="ticket" style="margin: 5px 7px;width: <?php echo $paperWidth;?>;">
	<div style="margin: auto; width: <?php echo $paperWidth;?>; text-align: center;">
     <?php if (defined('RECEIPT_SHOW_LOGO') && RECEIPT_SHOW_LOGO) { ?>
      <div style="text-align: center;">
        <img src="<?php echo UPLOAD_URL  . 'receipt_logo.png'; ?>" alt="Logo" width="75" height="75"/>
      </div>
    <?php } ?>
    <span><strong><?php echo $tpl['location_arr'][0]['name'];?></strong></span><br/>
    <span style="text-align: center; font-size:15px"><?php echo $tpl['location_arr'][0]['address'];?></span><br/>
    <span style="font-size:15px">TEL : <?php echo WEB_CONTACT_NO; ?></span><br/>
    <span style="font-size:15px">OrderID : <?php echo $tpl['arr']['order_id']; ?></span>
    <span style="margin-top: 0px;margin-bottom: 0px; font-size:14px">&nbsp; <?php //echo date('d-m-Y H:i:s', time()); ?>
    <?php echo date('d-m-Y', strtotime($tpl['arr']['created'])); ?>
    </span><br/>
    <span><strong>Order No: <?php echo $tpl['arr']['order_count_of_the_day'];?></strong></span>
	</div>
	<table class="table table-borderless" style="width: <?php echo $paperWidth;?>; text-align: center;">
		<thead>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
  			<td class="kitchen" width="60%">Name</th>
  			<td class="kitchen headerTD" width="40%">Amount</th>
      </tr>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
		</thead>
		<tbody>		
      <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/print_receipt_items.php'; ?>
      <?php if (strtolower($tpl['arr']['price_delivery']) == 'delivery' && $tpl['arr']['price_delivery'] && $tpl['arr']['price_delivery'] > 0) { ?>
        <tr>
          <td colspan="2"><hr/></td>
        </tr>
        <tr>
          <td>Delivery Fee</td>
          <td style="padding: 2px 15px; float: right;"><?php echo pjCurrency::formatPrice($tpl['arr']['price_delivery']); ?></td>
        </tr>
    <?php } ?>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td><strong>Total</strong></td>
        <td class="itemTD"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['total']); ?></strong></td>
      </tr>
      <?php if ($tpl['arr']['is_paid'] == 1) { ?>
      <?php if ($tpl['arr']['payment_method'] == 'split') { ?>
      <tr>
        <td><strong>Cash Tendered</strong></td>
        <td class="itemTD"><strong>
          <?php echo pjCurrency::formatPrice($tpl['arr']['cash_amount']); ?>
          </strong>
        </td>
      </tr>
      <tr>
        <td><strong>Card </strong></td>
        <td class="itemTD"><strong>
          <?php echo pjCurrency::formatPrice(($tpl['arr']['total'] - $tpl['arr']['cash_amount'])); ?>
          </strong>
        </td>
      </tr>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td><strong>Balance</strong></td>
        <td class="itemTD"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['customer_paid'] - $tpl['arr']['total']); ?></strong></td>
      </tr> 
      <?php /* } elseif ($tpl['arr']['payment_method'] != 'bank') { */ ?>
      <?php } elseif (!(in_array(strtolower($tpl['arr']['payment_method']), array('bank', 'card')))) { ?>
      <tr>
        <td><strong>Cash Tendered</strong></td>
        <td class="itemTD"><strong>
          <?php echo pjCurrency::formatPrice($tpl['arr']['customer_paid']); ?>
          </strong>
        </td>
      </tr>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td><strong>Balance</strong></td>
        <td class="itemTD"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['customer_paid'] - $tpl['arr']['total']); ?></strong></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td colspan="2"><strong>
          <?php echo "Card Payment"; ?>
          </strong>
        </td>
      </tr>
      <?php } } ?>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:14px"><img height="150" width="150" src='<?php echo UPLOAD_URL."/qrcode/QrCodeScanImage.png" ?>'><div style="font-size:14px">QR Menu<br/>Click & Collect<br/><br/>Thank you for ordering from <br/><?php echo $tpl['location_arr'][0]['name'];?>!</div></td>
      </tr>
		</tbody>
	</table>
</div>
</div>
<br/><br/>
<div class="hidden-print" style="margin: 5px 10px; width: 500px;">
  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&action=pjActionInitialKPrint&source=index&id=<?php echo $tpl['arr']['id'] ?>&origin=<?php echo ucfirst($tpl['arr']['origin']); ?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "KPrint" ?></a>
  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>&amp;origin=<?php echo ucfirst($tpl['arr']['origin']);?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    printDiv('receiptContainer');
  });
</script>