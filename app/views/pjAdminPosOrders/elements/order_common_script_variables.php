<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>
<script type="text/javascript">
  var myLabel = myLabel || {};
  myLabel.currency = "<?php echo $tpl['option_arr']['o_currency'];?>";
  myLabel.restaurant_closed = <?php x__encode('lblRestaurantClosed');?>;
  myLabel.email_exists = <?php x__encode('email_taken'); ?>;
  myLabel.phoneNumber_err = '<?php echo 'Mobile Number is invalid'; ?>';
  myLabel.email_err = '<?php echo 'Email address is invalid'; ?>';
  myLabel.voucher_err = '<?php echo 'Voucher code is invalid'; ?>';
  myLabel.delivery_fee_err = '<?php echo 'This field only accepts integer and float values'; ?>'; 
  myLabel.mobileDelivery_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
  myLabel.emailReceipt_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
  var categoryList = '<?php echo json_encode($tpl['category_list']); ?>';  
  categoryList =  JSON.parse(categoryList);  
  var client_info = '<?php echo json_encode($tpl['client_info']); ?>';
  client_info = JSON.parse(client_info);
  var ideal_api_key = "<?php echo IDEAL_API_KEY;?>";
  var dojo_payment_active = "<?php echo DOJO_PAYMENT_ACTIVE;?>";
  var dojo_host = "<?php echo DOJO_PAYMENT_URL;?>"+"/"+"<?php echo DOJO_PAY_TYPE_PAC;?>"+"?token="+"<?php echo DOJO_TOKEN; ?>"+"&api-version=v1&software-house-id="+"<?php echo DOJO_SOFTWARE_HOUSE_ID;?>"+"&installer-id="+"<?php echo DOJO_INSTALLER_ID; ?>"+"&final-pos-receipt-request";
  var dojo_notification_messages = JSON.parse('<?php echo DOJO_NOTIFICATION_MESSAGES; ?>');
  var swagger_payment_active = "<?php echo SWAGGER_PAYMENT_ACTIVE;?>";
  var swagger_pay_url = "<?php echo SWAGGER_PAYMENT_URL;?>";
  var CONFIG_SUB_CATEGORY = <?php echo SUB_CATEGORY; ?>;
  // wss://sd711b330000.test.connect.paymentsense.cloud/PAT?token=0247c27a-3e9d-41a5-bb20-a0c05db55f38&api-version=<connect-version>&software-house-id=SD711B33&installer-id=SD711B33&[final-pos-receipt-request]
// URL - sd711b330000.test.connect.paymentsense.cloud
// API key - 0247c27a-3e9d-41a5-bb20-a0c05db55f38
// Software-House-Id and Installer-Id - SD711B33

// VCMINVLSIP0 - simulates a successful chip and pin payment
// VCMINVLDIP0 - simulates a declined chip and pin payment
// VCMINVLSCD0 - simulates a contactless payment with device verification
// VCMINVLSIS0 - simulates a signature payment
// VCMINVLUIP0 - simulates an unsuccessful payment result
// VCMINVLTIP0 - simulates a "TIMED_OUT" payment result
// wss://<account-name>.connect.paymentsense.cloud/<integration-type>?token=<api-key>&api-version=<connect-version>&software-house-id=<software-house-id>&installer-id=<installer-id>&[final-pos-receipt-request]
  //var host = 'wss://sd711b330000.test.connect.paymentsense.cloud/pac?token=0247c27a-3e9d-41a5-bb20-a0c05db55f38&api-version=v1&software-house-id=SD711B33&installer-id=SD711B33&final-pos-receipt-request';
</script>