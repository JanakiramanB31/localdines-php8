<?php $paperWidth = "290px";  
  // echo '<pre>'; print_r($tpl['oi_arr'] ); print_r($tpl['arr']); echo '</pre>'; 
?>
<div id="receiptContainer">
<div class="ticket" style="margin: 5px 7px;width: <?php echo $paperWidth;?>;">
  <div style="margin: auto; width: <?php echo $paperWidth;?>; text-align: center;">
    <?php if (defined(RECEIPT_SHOW_LOGO) && RECEIPT_SHOW_LOGO) { ?>
      <div style="text-align: center;">
        <img src="<?php echo UPLOAD_URL  . 'receipt_logo.png'; ?>" alt="Logo" width="75" height="75"/>
      </div>
    <?php } ?>
    <span><strong><?php echo $tpl['location_arr'][0]['name'];?></strong></span><br/>
    <span style="text-align: center; font-size:15px"><?php echo $tpl['location_arr'][0]['address'];?></span><br/>
    <span style="font-size:15px">TEL : <?php echo WEB_CONTACT_NO; ?></span><br/>
    <span style="font-size:15px">OrderID : <?php echo $tpl['arr']['order_id']; ?></span>
    <span style="margin-top: 0px;margin-bottom: 0px; font-size:14px">&nbsp; <?php echo date('d-m-Y H:i:s', time()); ?></span><br/>
    <span><strong>Order No: <?php echo $tpl['arr']['order_count_of_the_day'];?></strong></span>
  </div>
  <table class="table table-borderless" style="width: <?php echo $paperWidth;?>;">
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
      <tr>
       <td colspan="2"><hr/></td>
     </tr>
     <?php if ($tpl['arr']['discount'] > 0) { ?> 
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_discount.php'; ?>
      <?php } ?>
      <tr>
       <td><strong>Total</strong></td>
       <td class="itemTD"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['total']); ?></strong></td>
      </tr>
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
      <?php // } elseif ($tpl['arr']['payment_method'] != 'bank') { ?>
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
      <?php } ?>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:14px"></siv><img height="150" width="150" src='<?php echo APP_URL."/app/web/upload/qrcode/QrCodeScanImage.png" ?>'><br/>QR Menu<br/><br/>Click & Collect<br/><br/>Thank you for ordering from<br/> <?php echo $tpl['location_arr'][0]['name'];?>!</td>
      </tr>
    </tbody>
  </table>
</div>
</div>
<div id="noPrint">
  <div class="ticket">
    <div>
      <span>&nbsp;</span><br/><br/>
    </div>
  </div>
</div>
<br/><br/>
<div class="hidden-print" style="margin: 5px 10px; width: 500px;">
  <button class="btn btn-primary printbutton" onClick="printDivLocal('receiptContainer')">Receipt Print</button>
  <button class="btn btn-primary printbutton" id="btn-openDrawer">Topen</button>
  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
  function decodeCurrencySymbol(htmlString) {
    // Create a temporary DOM element
    var tempElement = document.createElement("textarea");
    tempElement.innerHTML = htmlString;
    return tempElement.value;
  }
  var autoRPrint = '<?php echo AUTO_RECEIPT_PRINT; ?>';
  var RETURN_TYPES = '<?php echo json_encode(RETURN_TYPES); ?>';
  RETURN_TYPES = JSON.parse(RETURN_TYPES);
  var PRODUCT_TYPES = '<?php echo json_encode(PRODUCT_TYPES); ?>';
  PRODUCT_TYPES = JSON.parse(PRODUCT_TYPES);
  var saleItems = '<?php echo json_encode($tpl['oi_arr']); ?>';
  var orderItems = JSON.parse(saleItems);
  var curSign = '<?php echo $tpl['curSign']; ?>';
  curSign = decodeCurrencySymbol(curSign);
  function printDivLocal(divName) {
    $("#btn-openDrawer").trigger("click");
    //  var printContents = document.getElementById(divName).innerHTML;
    //  var originalContents = document.body.innerHTML;
    //  document.body.innerHTML = printContents;
    //  console.log(printContents);
     window.print();
    //  document.body.innerHTML = originalContents;
  }

  $(document).ready(function() {
    $("#btn-openDrawer").click(function(){
       $.ajax({
        type: "POST",
        async: false,
        url: "index.php?controller=pjAdminPosOrders&action=pjActionOpenDrawer",
        success: function (msg) {
          console.log(msg);
        },
      });
    });
    if (autoRPrint == 1) {
      printDivLocal();
    }

    var printer = new EscPosPrinter(576);
    var printerSN = '<?php echo RECEIPT_SN; ?>';
    var printerIP =  '192.168.0.174';
    var numOfCopies = 1;
    var divName = 'receiptContainer';
    console.log(printerSN);
    printDiv('receiptContainer');
    // printer.printHtml('192.168.0.174', printerSN, numOfCopies, divName);
    // printSample(printerIP, printerSN, numOfCopies);
    // printSaleReceipt(printerIP, printerSN, numOfCopies);
    printSaleReceiptGPT(printerIP, printerSN, numOfCopies);

  function printSaleReceiptGPT(printerIP, printerSN, numOfCopies) {
    // Create a new printer instance
    var printer = new EscPosPrinter(384); // 58mm printer
    var separator = "--------------------------------\n";
    
    // Column width and alignment variables
    let column1Width = 200;
    let column1Alignment = ALIGN_LEFT;
    let column2Width = 100;
    let column2Alignment = ALIGN_RIGHT;

    // Helper function to set column widths dynamically
    function setColumnWidths() {
      printer.setColumnWidths(
        columnWidthWithAlignment(column1Width, column1Alignment),
        columnWidthWithAlignment(column2Width, column2Alignment)
      );
    }

    // Clear any previous data and restore default settings
    printer.clear();
    printer.restoreDefaultSettings();

    // Set text alignment to center and print location info
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("<?php echo $tpl['location_arr'][0]['name'];?>\n");
    printer.setTextSize(1, 1);
    printer.appendText("<?php echo $tpl['location_arr'][0]['address'];?>\n");
    printer.appendText("TEL: <?php echo WEB_CONTACT_NO;?>\n");
    printer.appendText("OrderID : <?php echo $tpl['arr']['order_id']?>\n");
    printer.appendText("<?php echo date('d-m-Y H:i:s', time());?>\n");
    printer.setTextStyle("bold");
    printer.appendText("Order No: <?php echo $tpl['arr']['order_count_of_the_day'];?>\n\n");
    printer.setTextStyle("normal");

    // Separator line before printing items
    printer.appendText(separator);
    // Set column widths for items and amounts
    setColumnWidths();
    printer.printInColumns("Name", "Amount");
    printer.appendText(separator);

    // Loop through order items and print in columns
    var productInfo = [];
    orderItems.forEach(function(item) {
      productInfo[0] = '';
      if (PRODUCT_TYPES.includes(item.type)) {
        productInfo[0] = item.cnt + ' X ';
        if (item.type == 'custom') {
          productInfo[0] += item.custom_name;
        } else {
          productInfo[0] += item.product_name;
        }
      }
      productInfo[1] = curSign + ' ' + (item.cnt * item.price);
      printer.printInColumns(productInfo[0], productInfo[1]);
    });
    
    printer.appendText(separator);
    // Print totals and payment method
    setColumnWidths();
    var total = curSign + ' ' + '<?php echo $tpl['arr']['total']; ?>';
    var payMethod = '<?php echo $tpl['arr']['payment_method']; ?>';
    printer.printInColumns("Total", total);

    if (payMethod == 'split') {
      printer.printInColumns("Cash Tendered", curSign + ' ' + '<?php echo $tpl['arr']['cash_amount']; ?>');
      printer.printInColumns("Card", curSign + ' ' + '<?php echo $tpl['arr']['total'] - $tpl['arr']['cash_amount']; ?>');
      printer.appendText(separator);
      setColumnWidths();
      printer.printInColumns("Balance", curSign + ' ' + '<?php echo $tpl['arr']['customer_paid'] - $tpl['arr']['total']; ?>');
    } else if (payMethod == 'bank' || payMethod == 'card') {
      printer.printInColumns("Card Payment", "___________________");
    } else {
      printer.printInColumns("Cash Tendered", curSign + ' ' + '<?php echo $tpl['arr']['customer_paid']; ?>');
      printer.appendText(separator);
      setColumnWidths();
      printer.printInColumns("Balance", curSign + ' ' + '<?php echo $tpl['arr']['customer_paid'] - $tpl['arr']['total']; ?>');
    }
    
    printer.appendText(separator);

    // Print QR Code
    printer.lineFeed(2);
    printer.appendQRcode(4, 2, "<?php echo APP_HOME_URL; ?>");

    // Cut paper and print
    printer.lineFeed(3);
    printer.cutPaper(true);

    printer.print(printerIP, printerSN, 1); // Print 1 copy
  }

  function printSample(ip, sn, copies) {
    
    if (ip.length == 0) {
        alert("IP Address is missing.");
        return;
    }
    if (copies.length == 0)
        copies = "1";

    printer.clear();
    printer.lineFeed(1);

    printer.setLineSpacing(80);
    printer.setPrintModes(true, true, false);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("*** Welcome ***\n");

    printer.restoreDefaultLineSpacing();
    printer.setPrintModes(false, false, false);
    printer.setAlignment(ALIGN_LEFT);

    if (printer.dotsPerLine == 576) {
        /* Setup 3 columns:
           1st: 288 dots with left alignment
           2nd: 144 dots with center alignment
           3rd: use the remaining dots with right alignment */
       printer.setColumnWidths(
            columnWidthWithAlignment(288, ALIGN_LEFT  ),
            columnWidthWithAlignment(144, ALIGN_CENTER),
            columnWidthWithAlignment(0,   ALIGN_RIGHT ));
        printer.printInColumns("|----------------------|", "|----------|", "|----------|");
    } else {
        /* Setup 3 columns:
           1st: 96 dots with left alignment
           2nd: 144 dots with center alignment
           3rd: use the remaining dots with right alignment */
        printer.setColumnWidths(
            columnWidthWithAlignment(96,  ALIGN_LEFT  ),
            columnWidthWithAlignment(144, ALIGN_CENTER),
            columnWidthWithAlignment(0,   ALIGN_RIGHT ));
        printer.printInColumns("|------|", "|----------|", "|----------|");
    }
    printer.lineFeed(1);
    printer.printInColumns("商品名称", "数量\n(单位：随意)", "小计\n(单位：元)");
    printer.lineFeed(1);
    printer.printInColumns("这是\"一个很长的品名\"", "x1000", "￥2020.99");
    printer.lineFeed(1);
    printer.printInColumns("橙子", "【备注：赠品购物满1000,000元送一只】", "￥0.00");
    printer.lineFeed(1);

    printer.setAlignment(ALIGN_CENTER);

    /* Print CODE128 barcode */
    printer.appendBarcode(HRI_POS_BELOW, 160, 3, 73, "Abc-000789");
    printer.lineFeed(1);

    /* Print QR code */
    printer.appendQRcode(5, 1, "https://www.sunmi.com/");
    printer.lineFeed(1);

    printer.setAlignment(ALIGN_LEFT);

    printer.lineFeed(1);

    /* Print in page mode */
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("---- 页模式多区域打印 ----\n");
    printer.setAlignment(ALIGN_LEFT);
    printer.enterPageMode();
    // Region 1
    printer.setPrintAreaInPageMode(0, 0, 144, 500);
    printer.setPrintDirectionInPageMode(0);
    printer.printAndExitPageMode();

    printer.lineFeed(4);
    printer.cutPaper(false);

    // printer.print(ip, sn, copies);
  }

  

  function printSaleReceipt(ip, sn, copies) {
    printer.clear();
    printer.lineFeed(1);
    printer.setLineSpacing(80);
    printer.setPrintModes(true, true, false);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("<?php echo $tpl['location_arr'][0]['name'];?>");
    printer.lineFeed(1);
    printer.appendText("<?php echo $tpl['location_arr'][0]['address'];?>");
    printer.lineFeed(1);
    printer.appendText("TEL : <?php echo WEB_CONTACT_NO;?>");
    printer.lineFeed(1);
    printer.appendText("OrderID : <?php echo $tpl['arr']['order_id']?>");
    printer.lineFeed(1);
    printer.appendText("<?php echo date('d-m-Y H:i:s', time()); ?>");
    printer.lineFeed(1);
    printer.appendText("Order No : <?php echo $tpl['arr']['order_count_of_the_day'];?>");
    printer.lineFeed(1);
    printer.appendText("_____________________________________________");
    printer.setColumnWidths(
            columnWidthWithAlignment(432, ALIGN_LEFT  ),
            columnWidthWithAlignment(0,   ALIGN_RIGHT ));
    
    printer.printInColumns("Name",  "Amount");
    printer.lineFeed(1);
    printer.printInColumns("__________________________", "___________________");
    // $this.kitchenPrintFormat(printer, $data, $special_instructions, true);
    console.log(orderItems);
    var productInfo = [];
    orderItems.forEach(function(item) {
      console.log(item);
      productInfo[0] = '';
      if (PRODUCT_TYPES.includes(item.type)) {
        productInfo[0] = item.cnt +' X ';
        if (item.type == 'custom') {
          productInfo[0] += item.custom_name;
        } else {
          productInfo[0] += item.product_name;
        }
      }
      productInfo[1] = curSign +' ' + (item.cnt * item.price);
      printer.printInColumns(productInfo[0],  productInfo[1]);
      console.log(productInfo);
    });
    // printer.printInColumns("__________________________", "___________________");
    var total = curSign +' ' + '<?php echo $tpl['arr']['total']; ?>';
    var payMethod = '<?php echo $tpl['arr']['payment_method']; ?>';

    printer.printInColumns("Total",  total);
    printer.lineFeed(1);
    if (payMethod == 'split') {
      printer.printInColumns("Cash Tendered", curSign +' ' + '<?php echo $tpl['arr']['cash_amount']; ?>');
      printer.printInColumns("Card", curSign +' ' + '<?php echo $tpl['arr']['total'] - $tpl['arr']['cash_amount']; ?>');
      // printer.printInColumns("__________________________", "___________________");
      printer.printInColumns("Balance", curSign +' ' + '<?php echo $tpl['arr']['customer_paid'] - $tpl['arr']['total']; ?>');
    } else if (payMethod == 'bank' || payMethod == 'card' ) {
      printer.printInColumns("Card Payment", "___________________");
    } else {
      printer.printInColumns("Cash Tendered", curSign +' ' + '<?php echo $tpl['arr']['customer_paid']; ?>');
      // printer.printInColumns("__________________________", "___________________");
      printer.printInColumns("Balance", curSign +' ' + '<?php echo $tpl['arr']['customer_paid'] - $tpl['arr']['total']; ?>');
    }
    // printer.printInColumns("__________________________", "___________________");
    console.log("Total ", total);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendQRcode(5, 1, "<?php echo APP_HOME_URL; ?>");
    printer.lineFeed(1);
    /* Print in page mode */
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("QR Menu");
    printer.setAlignment(ALIGN_LEFT);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("Click & Collect");
    printer.setAlignment(ALIGN_LEFT);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("Thank you for ordering from");
    printer.setAlignment(ALIGN_LEFT);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("<?php echo $tpl['location_arr'][0]['name'];?>!");
    printer.setAlignment(ALIGN_LEFT);
    printer.enterPageMode();
    // Region 1
    printer.setPrintAreaInPageMode(0, 0, 144, 500);
    printer.setPrintDirectionInPageMode(0);
    printer.printAndExitPageMode();

    printer.cutPaper(false);

    // printer.print(ip, sn, copies);
  }

  });
 
</script>
