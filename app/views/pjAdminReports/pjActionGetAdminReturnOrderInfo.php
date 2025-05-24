<?php 
//echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
  // echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
  /* echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
  echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";*/ 
  //echo "<pre>"; print_r($tpl['order_details']); echo "</pre>";
  $htmlSpaceCode = "&nbsp;";
  $htmlSpaceRepeat = 5;
  //echo $tpl['order_type'];
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
  <fieldset>
    <legend>Admin Return Item Info</legend>
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
        <tr>
          <td><?php echo $tpl['data']['product_name']; ?></td>
          <td>X</td>
          <td><?php echo $tpl['data']['qty']; ?></td>
          <td><?php echo $tpl['data']['qty']; ?></td>
          <td><?php echo $tpl['data']['reason']; ?></td>
          <td style="text-align: right; padding-right:20px"><?php echo pjCurrency::formatPrice($tpl['data']['amount']); ?></td>
        </tr>
        <tr>
          <th colspan="5" style="text-align: right;"><?php echo "Total: " ?></th>
          <td style="text-align: right; padding-right:20px"><strong><?php echo pjCurrency::formatPrice($tpl['data']['amount']); ?></strong></td>
        </tr>
      </tbody>
    </table>
    <br/>
  </fieldset>
</div>
