<?php 
//echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
  // echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
  /* echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
  echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";*/ 
  //echo "<pre>"; print_r($tpl['order_details']); echo "</pre>";
  $order_details = $tpl['order_details'];
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
  <?php if (strtolower($order_details["origin"]) != "pos") { ?>
  <h2>Customer Info</h2>
  <table style='width: 90%;'>
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
  <?php 
  } 
  if ($tpl['order_type']== "orderinfo") {
    include PJ_VIEWS_PATH . 'pjAdminReports/elements/OrderInfo.php';
  } else {
    include PJ_VIEWS_PATH . 'pjAdminReports/elements/CancelReturnOrder.php';
  }
  ?>
  
</div>
