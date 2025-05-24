<?php 
echo '<pre>'; 
print_r($tpl['arr']); echo '</pre>'; 
echo "<pre>"; print_r($tpl['curSign']); echo "</pre>";
echo "<pre>"; print_r($tpl['oi_extras']); echo "</pre>";
?>
<script>
  const itemsArray = <?php echo json_encode($tpl['oi_arr']); ?>;
  const itemsExtras = <?php echo json_encode($tpl['oi_extras']); ?>;
  const specialIns = <?php echo json_encode($tpl['special_instructions']); ?>;
  const discountAmt = parseFloat(<?php echo $tpl['arr']['discount']; ?>);
  const isPaid = parseFloat(<?php echo $tpl['arr']['is_paid']; ?>);
  const paymentMethod = "<?php echo $tpl['arr']['payment_method']; ?>".toLocaleLowerCase();
 const PRODUCT_TYPES = <?php echo json_encode(PRODUCT_TYPES); ?>;
  const RETURN_TYPES = <?php echo json_encode(RETURN_TYPES); ?>;
  const LEFT_PADDING = "    "; // 4 spaces for left margin
  const RIGHT_PADDING = "  ";  // 2 spaces for right margin
  console.log(itemsArray)
  var tpl = {
    arr: {
        price_delivery: 2.50,
        total: 23.00,
        payment_method: "cash",
        customer_paid: 30.00,
        cash_amount: 30.00
    }
};
var pjCurrency = {
    formatPrice: function(amount) {
      console.log(amount);
      const num = typeof amount === 'string' ? 
        parseFloat(amount) :  // Handle European decimal format
        Number(amount);
    
    // Check if the conversion resulted in a valid number
    if (isNaN(num)) {
        console.error('Invalid price value:', amount);
        return '£0.00'; // Return default formatted value with Euro
    }
    
    return '£' + num.toFixed(2); // European format with comma
    }
};

var printer = new EscPosPrinter(576); // 80mm paper (576 dots)
const PRINT_RECEIPT_QR_CODE = false;
function printReceipt() {
  var ip = '192.168.0.174';
  var sn = '';
  var copies = 1;

    if (!ip) {
        alert("Printer IP address is required");
        return;
    }

    printer.clear();
    printer.lineFeed(1);

    // Header Section
    printer.setLineSpacing(80);
    printer.setPrintModes(true, true, false); // Bold, double height
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("<?php echo $tpl['location_arr'][0]['name'];?>" + "\n");
    
    printer.restoreDefaultLineSpacing();
    printer.setPrintModes(false, false, false); // Normal text
    printer.appendText("<?php echo $tpl['location_arr'][0]['address'];?>" + "\n");
    printer.appendText("TEL: " + "<?php echo WEB_CONTACT_NO; ?>" + "\n");
    printer.appendText("OrderID: " + "<?php echo $tpl['arr']['order_id']; ?>" + "\n");
    printer.appendText("<?php echo date('d-m-Y', strtotime($tpl['arr']['created'])); ?>" + "\n");
    printer.appendText("Order No: " + "<?php echo $tpl['arr']['order_count_of_the_day'];?>" + "\n");
    printer.lineFeed(2)
    console.log(printer.dotsPerLine);
    // return;
    // Items Table Header
    // printer.setAlignment(ALIGN_LEFT);
    // if (printer.dotsPerLine == 576) {
    //     printer.setColumnWidths(
    //       columnWidthWithAlignment(432, ALIGN_LEFT),   // Name (75% width)
    //       columnWidthWithAlignment(0, ALIGN_RIGHT)     // Amount (auto-fill)
    //     );
    // } else {
    //     printer.setColumnWidths(
    //       columnWidthWithAlignment(432, ALIGN_LEFT),   // Name (75% width)
    //       columnWidthWithAlignment(0, ALIGN_RIGHT)     // Amount (auto-fill)
    //     );
    // }
    const LEFT_MARGIN = 24;  // Left margin width in dots
    const RIGHT_MARGIN = 24; // Right margin width in dots
    const TOTAL_WIDTH = printer.dotsPerLine;
    const CONTENT_WIDTH = TOTAL_WIDTH - LEFT_MARGIN - RIGHT_MARGIN;
   
    printer.setColumnWidths(
      columnWidthWithAlignment(CONTENT_WIDTH * 0.7 + LEFT_MARGIN, ALIGN_LEFT),
      columnWidthWithAlignment(CONTENT_WIDTH * 0.3 + RIGHT_MARGIN, ALIGN_RIGHT)
    );
    printer.appendText('------------------------------------------------\n');
    printer.setPrintModes(true, false, false); // Bold
    printer.printInColumns(LEFT_PADDING +"Name",  "Amount"+RIGHT_PADDING);
    // printer.lineFeed(1);
    printer.setPrintModes(false, false, false); // Bold
    printer.appendText('------------------------------------------------\n');
    // Items List
    printItems(itemsArray, itemsExtras, specialIns);
    // itemsArray.forEach(item => {
    //     printer.printInColumns(
    //         item.product_name.substring(0, 24),
    //         item.cnt.toString(),
    //         pjCurrency.formatPrice(item.price)
    //     );
    // });
    printer.appendText('------------------------------------------------\n');
    // Delivery Fee
    if (parseInt(<?php echo $tpl['arr']['price_delivery']; ?>) > 0) {
        printer.printInColumns(
            LEFT_PADDING+"Delivery Fee",
            "",
            "<?php echo $tpl['curSign'].pjCurrency::formatPriceOnly($tpl['arr']['price_delivery']); ?>"+RIGHT_PADDING
        );
    }

    
    if (discountAmt) {
      let totalPrice = parseFloat(<?php echo ($tpl['arr']['price']); ?>);
      printer.printInColumns(
        LEFT_PADDING+"Price",
        pjCurrency.formatPrice(totalPrice)+RIGHT_PADDING
      );

      printer.printInColumns(
        LEFT_PADDING+"Discount",
        pjCurrency.formatPrice(discountAmt)+RIGHT_PADDING
      );
      printer.appendText('------------------------------------------------\n');
    }
    
    
    // Total
    printer.setPrintModes(true, false, false); // Bold
    let totalText = pjCurrency.formatPrice("<?php echo ($tpl['arr']['total']); ?>");
    console.log(totalText);
    printer.printInColumns(
        LEFT_PADDING+"TOTAL",
        totalText+RIGHT_PADDING
    );
    
    
    // Payment Method
    if (isPaid) {
      printer.setAlignment(ALIGN_CENTER);
      printer.setPrintModes(true, false, false);
      switch(paymentMethod) {
          case 'cash':
          case 'card':
              // printer.appendText("PAID IN CASH\n");
              printer.setAlignment(ALIGN_LEFT);
              printer.printInColumns(
                  LEFT_PADDING+"Cash Tendered",
                  pjCurrency.formatPrice(<?php echo $tpl['arr']['customer_paid']; ?>)+RIGHT_PADDING
              );
              break;
          
          case 'split':
              // printer.appendText("SPLIT PAYMENT\n");
              printer.setAlignment(ALIGN_LEFT);
              printer.printInColumns(
                  LEFT_PADDING+"Cash",
                  pjCurrency.formatPrice("<?php echo $tpl['arr']['cash_amount']; ?>")+RIGHT_PADDING
              );
              printer.printInColumns(
                  LEFT_PADDING+"Card",
                  pjCurrency.formatPrice("<?php echo $tpl['arr']['total'] - $tpl['arr']['cash_amount']; ?>")+RIGHT_PADDING
              );
              break;
      }
    }
    printer.setPrintModes(false, false, false);
    
    // Footer
    printer.lineFeed(1);
    printer.setAlignment(ALIGN_CENTER);
    printer.appendText("Thank you for your order!\n");
    printer.appendText("<?php echo $tpl['location_arr'][0]['name'];?>" + "\n");
    
    // QR Code
    if (PRINT_RECEIPT_QR_CODE) {
        printer.appendQRcode(5, 1, "https://yourbusiness.com/order/" + tpl.arr.order_id);
        printer.appendText("Scan for digital receipt\n");
    }
    
    // Finalize
    printer.lineFeed(5);
    printer.cutPaper(false);
    printer.print(ip, sn, copies);
}

// Print items function
async function printItems(items, extras, specialInstructions) {
  try {
    
    // printer.align('LT');
    printer.setAlignment(ALIGN_LEFT);
    printer.setPrintModes(false, false, false);
    
    
    items.forEach(item => {
      printer.orderData += "1B7210";
      if (!PRODUCT_TYPES.includes(item.type)) 
        return;
      
      // Strike-through for returned items
      if (RETURN_TYPES.includes(item.status)) {
        // printer.style('REVERSE');
        printer.setPrintModes(false, false, false); 
        printer.orderData += "1B7211"; // Turn on reverse printing
      }
      
      // Main product line
      let productName = item.type === 'custom' 
        ? item.custom_name 
        : `${item.product_name} ${item.size || ''}`;
      productName = productName.substring(0, 28);
      // printer.appendText(`${item.cnt} x ${productName}\n`);
      column1Data = `${item.cnt} x ${productName}\n`;
      
      // Extras
      if (extras[item.hash]) {
        extras[item.hash].forEach(extra => {
          printer.appendText(`  + ${extra.extra_name} x ${extra.extra_count}\n`);
        });
      }
      
      // Special instructions
      if (item.special_instruction) {
        const instructions = JSON.parse(item.special_instruction);
        if (instructions[0]) {
          if (instructions[0].ids) {
            printer.appendText('  Special: ');
            instructions[0].ids.split(',').forEach(id => {
              const instruction = specialInstructions.find(i => i.id == id);
              if (instruction) printer.appendText(`${instruction.name}, `);
            });
            printer.text('\n');
          }
          
          if (instructions[0].cus_ins) {
            printer.appendText(`  Note: ${instructions[0].cus_ins}\n`);
          }
        }
      }
      
      // Price
      let column2Data = pjCurrency.formatPrice(item.cnt * item.price);
      
      // Extras prices
      if (extras[item.hash]) {
        extras[item.hash].forEach(extra => {
          column2Data += `\n  +${pjCurrency.formatPrice(extra.extra_count * extra.extra_price)}`;
        });
      }
      
      // Right-align the price column
      printer.setAlignment(ALIGN_RIGHT);
      // printer.appendText(`${priceLine}\n`);
      printer.printInColumns(LEFT_PADDING+column1Data, column2Data+RIGHT_PADDING);
      printer.setAlignment(ALIGN_LEFT);
      
      // Reset strike-through if applied
      if (RETURN_TYPES.includes(item.status)) {
        printer.setPrintModes(false, false, false); 
      }
      
      
    });
    
  } catch (error) {
    console.error('Printing failed:', error);
  }
}

// // Euro formatting function
// function formatPrice(amount) {
//   const num = parseFloat(amount) || 0;
//   return `€${num.toFixed(2).replace('.', ',')}`;
// }


</script>
<input type="button" id="btn_print" value="Print Sample" onclick="printReceipt()">