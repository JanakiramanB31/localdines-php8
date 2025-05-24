<?php
  // echo "<pre>"; print_r($tpl['oi_arr']); echo "</pre>";
  // echo "<pre>"; print_r($tpl['oi_extras']); echo "</pre>";
  $i = 0;
  foreach ($tpl['oi_arr'] as $k => $oi) {
    //if ($oi['type'] == 'product') {
    $strikeThroughStart = '';
    $strikeThroughEnd = '';
    if (in_array($oi['status'], RETURN_TYPES))  { 
      $strikeThroughStart = '<s>';
      $strikeThroughEnd = '</s>';
    }
    if (in_array($oi['type'], PRODUCT_TYPES))  {
      $has_extra = false;
      $i = $i + 1; 
      $counter = 0;
      $product_count = $oi['cnt'];
?>
  <tr>
    <td class="kitchen">
    <?php
      // echo "<pre>"; print_r($oi); echo "</pre>";
      echo $strikeThroughStart;
      if ($oi['type'] == 'custom') {
        echo '<strong>'.$oi['cnt']. ' x '; ?> <?php echo $oi['custom_name'].'</strong>'; 
      } else {
        echo '<strong>'.$oi['cnt']. ' x '; ?> <?php echo $oi['product_name'].'</strong>'; 
        echo $oi['size'];
      }
      if (array_key_exists($oi['hash'], $tpl['oi_extras']) ) { 
        $extras_counter = 0;
        $extras_count = count($tpl['oi_extras'][$oi['hash']]);
        while($extras_counter < $extras_count) {
          $extra = $tpl['oi_extras'][$oi['hash']][$extras_counter]; //echo 'came here';
          echo '<br/><span style="margin-left: 20px">'.$extra->extra_name ." x ".$extra->extra_count.'</span>';
          $extras_counter++;
        }
      }
      if (array_key_exists($oi['hash'], $tpl['oi_extras']) && isset($tpl['oi_extras'][$oi['hash']][$counter])) { 
        $extra = $tpl['oi_extras'][$oi['hash']][$counter]; //echo 'came here';
        echo '<br/><span style="margin-left: 20px">'.$extra->extra_name ." x ".$extra->extra_count.'</span>';
      }
      if ($oi['special_instruction']) {
        $obj = json_decode($oi['special_instruction'], true);
        // echo $counter;
        // echo "<pre>"; print_r($obj); echo "</pre>";
        if (isset($obj[$counter])) {
          if ($obj[$counter]['ids']) {
            echo "<br/><span style='margin-left: 10px'>";
            $selected_ins_arr = explode(',', $obj[$counter]['ids']);
            foreach ($selected_ins_arr as $ins) {
              foreach ($tpl['special_instructions'] as $instruction) {
                if ($ins == $instruction['id']) {
                  echo "<img src='".$instruction['image']."' style='margin-left: 5px;height: 30px; width:30px;'/>";
                }
              }
            }
            echo "</span>";
          }
          
          if ($obj[$counter]['cus_ins']) {
            echo "<br/><span style='margin-left: 20px'># " . $obj[$counter]['cus_ins']. '</span>';
          }
        }
        echo "<br/>";
      }
      echo $strikeThroughEnd;
    ?>

   </td>
   <td class="nani itemTD">
    <?php 
      echo $strikeThroughStart;
      echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); 
      if (array_key_exists($oi['hash'], $tpl['oi_extras']) ) { 
        $counter = 0;
        $extras_count = count($tpl['oi_extras'][$oi['hash']]);
        while($counter < $extras_count) {
          $extra = $tpl['oi_extras'][$oi['hash']][$counter]; //echo 'came here';
          echo '<br/><span>&nbsp;&nbsp;'.pjCurrency::formatPrice($extra->extra_count * $extra->extra_price).'</span>';
          $counter++;
        }
      }
      echo $strikeThroughEnd;
   ?>
   </td>
  </tr>
<?php
    }
  }
?>