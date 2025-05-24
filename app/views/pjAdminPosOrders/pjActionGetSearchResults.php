<?php //print_r($tpl['price_arr']); ?>

<!-- <div id="paginate"> -->
<?php 
if(!empty($tpl['product_arr'])) {
foreach($tpl['product_arr'] as $product) { 
    if ($product['status'] == 1) {
    $imgSrc = $product['image'] != '' ?  $product['image'] : "app/web/img/backend/no_image.png"; ?>
<div class="col-sm-3">
    <div class="img-container" data-id="<?php echo $product['id']; ?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hasSize ="<?php echo $product['set_different_sizes']; ?>">
        <img src="<?php echo $imgSrc; ?>" alt="" class="img-responsive" width="100%" style="height: 100%;">
        <div class="content-price">
            <?php if ($product['set_different_sizes'] == 'F') { ?>
                <h4><?php echo pjCurrency::formatPrice($product['price']); ?></h4>
            <?php } else { 
                foreach ($tpl['price_arr'] as $price) {
                    if ($price['product_id'] == $product['id']) { ?>
                    <h4><?php echo pjCurrency::formatPrice($price['price']); ?></h4>
                    
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
 } 
}else { ?>
<div class="col-sm-5"></div>
<div class="col-sm-3">
<h3 class="text-center">No Search Results Found!</h3>
</div>
<?php } ?>
 <!-- </div> -->
