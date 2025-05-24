<?php
  $index = "new_" . mt_rand(0, 999999);
?>  
<style>
  tbody::-webkit-scrollbar {
    width: 20px;
  }
</style>
<table id="fdOrderList_1" class="table table-striped table-hover">
  <thead>
    <tr>
      <th><?php __('lblProduct');?></th>
      <th><?php __('lblQty');?></th>
      <th>
        <div class="p-w-xs"><?php //__('lblExtra');?>Extras</div>
      </th>
      <th><?php __('lblSizeAndPrice');?></th>
      <th><?php __('lblTotal');?></th>
      <th><?php echo 'S.I';?></th>
      <th></th>
    </tr>
  </thead>
  <tbody class="main-body">
    <?php
    //echo '<pre>';print_r($tpl['oi_arr']); echo '</pre>';
    if (array_key_exists('oi_arr', $tpl) && count($tpl['oi_arr']) > 0) {
      //foreach ($tpl['product_arr'] as $product) {
      //echo 'test';
      //exit;
        foreach ($tpl['oi_arr'] as $k => $oi) {
          //echo '<pre>';print_r($oi); echo '</pre>';
          //if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'])  {
          if (in_array($oi['type'], PRODUCT_TYPES))  {
          $has_extra = false;
          $oi_extras = '';
          if (array_key_exists($oi['foreign_id'], $tpl['product_arr'])) {
            $product = $tpl['product_arr'][$oi['foreign_id']];
          } else {
            $product = array(
              'id'=>0,
              'preparation_time'=>0,
              'name'=>$oi['custom_name'],
              'cnt_extras'=>0,
              'price'=>$oi['price'],
              'status'=>1,
            );
          }
         //print_r($product);
          $rowClass = "fdLine jsKprintDone ";
          if (in_array($oi['status'], RETURN_TYPES))  { 
            $rowClass .= "strikethrough";
          }

    ?>

      <tr class="<?php echo $rowClass; ?>" data-index="<?php echo $oi['hash']; ?>" data-preptime = "<?php echo $product['preparation_time']; ?>" >
        <td class="tdProductName">
          <?php
            if ($product['cnt_extras'] > 0) {
              $has_extra = true;
            }
            if ($product['status'] == 1) {
          ?>
          <input type="hidden" data-index="<?php echo $oi['hash']; ?>" data-extra="<?php echo $product['cnt_extras']; ?>" id="fdProduct_<?php echo $oi['hash']; ?>" name="product_id[<?php echo $oi['hash']; ?>]" value="<?php echo $product['id']; ?>">
          <?php if ($oi['type'] == 'product') { ?>
            <a href="#" data-product-name="<?php echo stripslashes($product['name']); ?>" class="product_desc" data-index="<?php echo $oi['hash']; ?>">
              <?php echo stripslashes($product['name']); ?>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
            </a>
          <?php } else { 
              echo stripslashes($product['name']);
            }
          } ?>
          <?php if ($has_extra && array_key_exists($oi['hash'], $tpl['oi_extras'])) {
                $oi_extras = json_decode($tpl['oi_extras'][$oi['hash']]['special_instruction']);
            $allCatNames1 = array();
            $allCatNames2 = array();
            foreach($oi_extras as $extraItem) {
              // echo '<pre>'; print_r($extraItem); echo '</pre>';
              if (isset($extraItem->extra_cat_id) && $extraItem->extra_cat_id == 1) {
                $allCatNames1[] = $extraItem->extra_name;
              } else {
                $allCatNames2[] = $extraItem->extra_name;
              }
              
            }
          ?>
          <div id="extra-names-<?php echo $oi['hash']?>" class="extra-names">
            <?php echo implode(', ', $allCatNames1); ?>
          </div>
          <div id="other-extra-names-<?php echo $oi['hash']?>" class="extra-names other-extra-names">
            <?php echo implode(', ', $allCatNames2); ?>
          </div>
        <?php } ?>
        </td>                       
        <td>
          <div class="business-<?php echo $oi['hash']; ?>">
            <input type="hidden" id="fdProductQty_<?php echo $oi['hash']; ?>" name="cnt[<?php echo $oi['hash']; ?>]" class="form-control input-disabled" value="<?php echo $oi['cnt']; ?>" style="width: 45px;" /> 
            <span><?php echo $oi['cnt']; ?></span>
          </div>
        </td>                                 
        <td>
          <div class="business-<?php echo $oi['hash']; ?> p-w-xs">
            <?php
              if ($has_extra && array_key_exists($oi['hash'], $tpl['oi_extras'])) {
                $oi_extra = $tpl['oi_extras'][$oi['hash']]['special_instruction'];
            ?>
              <a href="#" class="btn btn-primary btn-xs btn-outline pj-veiw-extra btn-has-extra" data-index="<?php echo $oi['hash']?>"><i class="fa fa-plus"></i> <?php //__('btnAddExtra');?></a>
               <input type='hidden' id="extra-<?php echo $oi['hash']; ?>" name="extras[<?php echo $oi['hash']; ?>]" data-index="<?php echo $oi['hash']; ?>_<?php echo $oi['id']; ?>" class="fdExtra fdExtra_<?php echo $oi['hash']; ?> form-control" value='<?php echo $oi_extra; ?>'/>
            <?php } else { ?>
              <a class="btn btn-danger btn-xs" disabled><i class="fa fa-times"></i></a>
            <?php } ?>
          </div>
        </td>
        <td id="fdPriceTD_<?php echo $oi['hash']; ?>">
          <?php $productData = json_encode($product, JSON_HEX_APOS); ?>
          <input type="hidden" id="prdInfo_<?php echo $oi['hash']; ?>" data-type="input" name="prdInfo_[<?php echo $oi['hash']; ?>]" value='<?php echo $productData; ?>' />
          <div class="business-<?php echo $oi['hash']; ?>">
            <?php
              if (empty($oi['price_id'])) {
            ?>
            <span class="fdPriceLabel"><?php echo pjCurrency::formatPrice($product['price']); ?></span>
            <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" data-type="input" name="price_id[<?php echo $oi['hash']; ?>]" value="<?php echo $product['price']; ?>" />
            <?php
            } else {
              if (isset($oi['price_arr']) && $oi['price_arr']) {
                foreach ($oi['price_arr'] as $pr) {
                  if ($pr['id'] == $oi['price_id']) {
               ?>
                  <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" value="<?php echo $pr['id']; ?>" data-price="<?php echo $pr['price']; ?>"/>
                  <span class="fdPriceLabel" data-price="<?php echo $pr['price']; ?>">
                    <?php echo stripslashes($pr['price_name']) . ": " . pjCurrency::formatPrice($pr['price']); ?>
                  </span>
              <?php
                  }
                }
              } else { ?>
                <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" value="" />
            <?php 
                }
            }
            ?>
          </div>
        </td>
        <td>
          <strong><span id="fdTotalPrice_<?php echo $oi['hash']; ?>"></span></strong>
        </td>                                                
        <td>
          <?php 
            $commentDisabled = '<a href="#" class="btn btn-primary" disabled><i class="fa fa-comment-o"></i></a>';
            if ($oi['special_instruction']) { 
              $selected_ins = json_decode($oi['special_instruction']); 
              for ($i = 0; $i < count($selected_ins); $i ++) {
                if ($selected_ins[$i]->ids != "" || $selected_ins[$i]->cus_ins != "") {
                  $name = stripslashes($product['name']);
                  $hash = $oi['hash'];
                  $commentDisabled = "<a href='#' class='btn product_spcl_ins btn-has-si' data-name='$name' data-index = '$hash'><i class='fa fa-comment-o'></i></a>";
                  break;
                } 
              } 
           } 
           echo $commentDisabled; 
          ?>
          <input type="hidden" id="fdSpecialInstruction_<?php echo $oi['hash']; ?>" name="special_instruction[<?php echo $oi['hash']; ?>]" class="form-control special-instruction" value='<?php echo $oi['special_instruction']; ?>' />
          <input type="hidden" id="fdCustomSpecialInstruction_<?php echo $oi['hash']; ?>" name="custom_special_instruction[<?php echo $oi['hash']; ?>]" class="form-control custom-special-instruction" value='<?php echo $oi['custom_special_instruction']; ?>' />
        </td>
        <td>
           <?php if (!in_array($oi['status'], RETURN_TYPES))  { ?>
          <div>
            <!-- <span class="" id="productDelete_<?php //echo $oi['hash']; ?>">
              <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
            </span> -->

            <input type="hidden" data-index="<?php echo $oi['hash']; ?>"  id="fdProdRetOrCancel_<?php echo $oi['hash']; ?>" name="return_or_cancel[<?php echo $oi['hash']; ?>]" value="">
            <input type="hidden" data-index="<?php echo $oi['hash']; ?>"  id="fdProdRetOrCancelReason_<?php echo $oi['hash']; ?>" name="return_or_cancel_reason[<?php echo $oi['hash']; ?>]" value="">
            <span class="" id="productReturn_<?php echo $oi['hash']; ?>">
              <a href="#" class="btn btn-danger btn-outline btn-sm jsBtnCancelReturn pj-return-product" data-index="<?php echo $oi['hash']; ?>"> <i class="fa fa-strikethrough"></i></a>
            </span>
          </div>
        <?php } else { ?>
          <input type="hidden" data-index="<?php echo $oi['hash']; ?>"  id="fdProdRetOrCancel_<?php echo $oi['hash']; ?>" name="return_or_cancel[<?php echo $oi['hash']; ?>]" value="<?php echo $oi['status']; ?>">
        <?php } ?>
        </td>
      </tr>
        <?php
          }
        }
      //}
    }
    ?>
  </tbody>
</table>
