<?php
$products1 = array_slice($tpl['hot_products_arr'], 0, 6);
$products2 = array_slice($tpl['hot_products_arr'], 6, 6);
?>
<div id="jsHotItems" class="row wrapper wrapper-content animated">
  <div class='row'>
    <div class='col center' style="padding: 4px 0px 4px 12px; display: list-item; text-align: center;">
    <?php foreach($products1 as $key=>$product)  { 
      $productName = $product['name'];
      $productName = strlen($productName) >13 ? substr($productName, 0, 10)."..." : $productName;
    ?>
        <button class='btn btn-warning btn-lg hot-item col-sm-1' data-id="<?php echo $product['id'];?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hassize="<?php echo $product['set_different_sizes'];?>"><?php echo $productName; ?></button>

    <?php } ?>
    </div>
  </div> 
  <div class='row'>
    <div class='col center' style="padding: 0px 0px 4px 12px; display: list-item; text-align: center;">
  <?php foreach($products2 as $key=>$product)  { 
    $productName = $product['name'];
    $productName = strlen($productName) >13 ? substr($productName, 0, 10)."..." : $productName;
  ?>
      <button class='btn btn-warning btn-lg hot-item col-sm-1' data-id="<?php echo $product['id'];?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hassize="<?php echo $product['set_different_sizes'];?>"><?php echo $productName; ?></button>

  <?php } ?>
  </div>
  </div> 
</div>