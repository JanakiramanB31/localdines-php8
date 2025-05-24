<div>
<?php

foreach($tpl['price_arr'] as $price) { ?>

<div class="form-check" style="text-align: center;">
  <button class="form-check-input btn radio-buttons" name="product_size" value="<?php echo $price['id']; ?>" id="price_<?php echo $price['id']; ?>" style="width: 50%; margin-bottom:10px; text-align:center;">
    <?php echo $price['price_name']." - ".pjCurrency::formatPrice($price['price']); ?>
  </button>
</div>

<?php }

?>
</div>