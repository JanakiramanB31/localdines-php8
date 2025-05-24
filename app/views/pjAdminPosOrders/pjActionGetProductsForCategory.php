<?php //print_r($tpl['price_arr']); ?>
<!-- <div id="paginate"> -->
   <h4 id='categoryName'><?php echo $tpl['category']; ?> </h4>
<?php foreach($tpl['product_arr'] as $product) { 
    // print_r($product);
    if ($product['status'] == 1) {
    ?>

<div class="col-sm-3">
  <div class="img-container" data-id="<?php echo $product['id']; ?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hasSize ="<?php echo $product['set_different_sizes']; ?>">
    <?php if (POS_MENU_IMAGE_DISPLAY) { 
        $imgSrc = $product['image'] != '' ?  $product['image'] : "app/web/img/backend/no_image.png"; ?>
    <img src="<?php echo $imgSrc; ?>" alt="" class="img-responsive" width="100%" style="height: 100%;">
    <?php } ?>
    <?php 
      // $count = 0;
      // $productName = $product['name']; 
      // $counterExists = strpos($productName, ' - ');

      if ($product['counter_number']) {
        //echo $count = substr ($productName, ($counterExists + 3));
    ?>
         <div class="counter_number">
            <?php echo $product['counter_number']; ?>
        </div>
   <?php } ?>
        <div class="content-price">
            <?php if ($product['set_different_sizes'] == 'F') { ?>
                <h4>
                    <?php 
                        // echo pjCurrency::formatPrice($product['price']); 
                        echo '&pound; '. $product['price']; 
                    ?>
                </h4>
            <?php } else { 
                foreach ($tpl['price_arr'] as $price) {
                    if ($price['product_id'] == $product['id']) { ?>
                    <h4><?php 
                        // echo pjCurrency::formatPrice($price['price']); 
                         echo '&pound; '.$price['price']; 
                    ?></h4>
                    
            <?php       break; }
                }

            } ?>
        </div>
        <div class="content">
            <h4><?php echo $product['name']; ?>
                <!-- <button><span id="content-description" class="fa fa-info-circle" aria-hidden="true"></span></button> -->
            </h4>
        </div>
    </div>
</div>
<?php
}
 } ?>
 <!-- </div> -->
