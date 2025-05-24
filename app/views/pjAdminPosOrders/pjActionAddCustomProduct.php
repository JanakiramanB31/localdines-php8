<?php
  $index = "new_" . mt_rand(0, 999999); 
?>   
<tr class="fdLine new" data-index="<?php echo $index;?>" data-prepTime="0">
  <td class="tdProductName">
    <input type="hidden" id="fdProduct_<?php echo $index; ?>" name="product_id[<?php echo $index; ?>]" value=0>
    <input type="hidden" id="fdProduct_<?php echo $index; ?>" name="product_description[<?php echo $index; ?>]" value="<?php echo $tpl['product_arr']['description']; ?>">
    <input type="hidden" id="fdProduct_<?php echo $index; ?>" name="product_type[<?php echo $index; ?>]" value="custom">
    <?php echo $tpl['product_arr']['description']; ?>
  </td>
  <td>
    <div class="business-<?php echo $index;?>" data-parent-index="<?php echo $index;?>">
      <input type="hidden" id="fdProductQty_<?php echo $index;?>" name="cnt[<?php echo $index;?>]" class="form-control" value="<?php echo $tpl['product_arr']['qty']; ?>" />
      <span><?php echo $tpl['product_arr']['qty']; ?></span>
    </div>
  </td>
  <td>
    <div class="business-<?php echo $index;?>">
      <input id="extra-<?php echo $index;?>" type="hidden" name='extras[<?php echo $index; ?>]' value data-count>
        <div class="p-w-xs">
          <a class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
        </div>
    </div>
  </td>
  <td id="fdPriceTD_<?php echo $index;?>">
    <input type="hidden" id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-price="<?php echo $tpl['product_arr']['price'];?>" class="fdSize form-control" value="<?php echo $tpl['product_arr']['price']; ?>">
    <span class="fdPriceLabel" data-price="<?php echo pjCurrency::formatPrice($tpl['product_arr']['price']); ?>"><?php echo pjCurrency::formatPrice($tpl['product_arr']['price']); ?></span>
  </td>
  <td>
    <strong><span id="fdTotalPrice_<?php echo $index;?>"><?php echo pjCurrency::formatPrice($tpl['product_arr']['total']);?></span></strong>
  </td>
              
  <td>
    <a href="#" id="cus-si_<?php echo $index;?>" data-pdname="<?php echo $tpl['product_arr']['description']; ?>" aria-hidden="true" data-index = "<?php echo $index;?>" class="btn btn-spl-ins-add spcl_ins"><i class="fa fa-comment-o"></i></a>
    <input type="hidden" id="fdSpecialInstruction_<?php echo $index;?>" name="special_instruction[<?php echo $index;?>]" class="form-control special-instruction" value="" />
    <input type="hidden" id="fdCustomSpecialInstruction_<?php echo $index;?>" name="custom_special_instruction[<?php echo $index;?>]" class="form-control custom-special-instruction" value="" />  
  </td>
  <td>
    <input type="hidden" id="jsIndex" value="<?php echo $index;?>">
    <div class="d-inline" id="productDelete_rowOne<?php //echo $index;?>">
      <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
    </div>
  </td>
</tr>