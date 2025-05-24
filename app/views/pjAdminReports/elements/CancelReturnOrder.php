<fieldset>
    <legend>Cancel/Return Items</legend>
    <table style='margin: auto; width: 100%;'>
      <tbody>
        <tr>
          <th style="text-align: center;">Product</th>
          <th></th>
          <th style="text-align: center;">Quantity</th>
          <th style="text-align: center;">Status</th>
          <th style="text-align: center;">Reason</th>
          <th style="text-align: center;">Amount</th>
        </tr>
        <?php $total = 0; foreach($tpl['oi_arr'] as $k => $oi) { 
        if (!in_array($oi['status'], RETURN_TYPES))  { 
          //$rowClass = "strikethrough";
          continue;
        } 
        $productName = $oi['type'] == 'custom' ? $oi['custom_name']:$oi['product_name'];
        $total += $oi['cnt'] * $oi['price'];
        ?>
        <tr>
          <td><?php echo $productName; ?></td>
          <td>X</td>
          <td><?php echo $oi['cnt'] ?></td>
          <td><?php echo $oi['status'] ?></td>
          <td><?php echo $oi['cancel_or_return_reason'] ?></td>
          <td style="text-align: right; padding-right:20px"><?php echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); ?></td>
        </tr>
        <?php } ?>
        <tr>
          <th colspan="5" style="text-align: right;"><?php echo "Total: " ?></th>
          <td style="text-align: right; padding-right:20px"><strong><?php echo pjCurrency::formatPrice($total); ?></strong></td>
        </tr>
      </tbody>
    </table>
    <br/>
  </fieldset>