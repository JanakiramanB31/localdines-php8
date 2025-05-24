<?php 
  $grouped_sel_ins_arr = array();
  foreach($tpl["selected_ins_arr"] as $selected_arr) {
    $grouped_sel_ins_arr[$selected_arr['qid']] = $selected_arr;
  }
?>
<?php //echo "<pre>"; print_r($tpl["selected_ins_arr"]); echo "</pre>"; ?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Special Instructions</th>
      <th scope="col">Custom Instructions</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id='spl_pagination_container'>
    <?php for($i=1, $sel_counter=0; $i <= $tpl['qty']; $i++, $sel_counter++) { 
    $current_qty_id = 'qty_'.$i;
    if ($tpl['selected_ins_arr'] && array_key_exists($current_qty_id, $grouped_sel_ins_arr)) {
      $arr= true;
      $selected_ins_arr = explode(',', $grouped_sel_ins_arr[$current_qty_id]['ids']);
      $selected_ins_imgs = explode(',', $grouped_sel_ins_arr[$current_qty_id]['imgs']);
    } else {
      $arr = false;
      $selected_ins_arr = array();
      $selected_ins_imgs = array();
    }
    ?>
      <tr class="page-link <?php echo $i == 1 ? "table-bg-primary" : "table-bg-default"; ?>" data-qty_container="#pos_si_qty" data-page_container="#spl_pagination_container" data-page="<?php echo "qty_".$i; ?>">
        <td scope="row" class="index"><?php echo $i; ?></td>
        <td id="imgs_qty_<?php echo $i; ?>">
          <?php foreach ($selected_ins_imgs as $img_src) { ?>
            <?php if ($img_src != "") { ?>
              <img src="<?php echo $img_src ?>">
            <?php } ?>
          <?php } ?>
        </td>
        <td>
          <textarea id="custom_special_qty_<?php echo $i; ?>" data-id="qty_<?php echo $i; ?>" placeholder="Type Here..."  class="jsVK-normal form-control custom_inst"  data-kioskboard-specialcharacters="true"><?php echo $arr ? $grouped_sel_ins_arr[$current_qty_id]['cus_ins'] : ""; ?></textarea>
        </td>
        <td class="spl_reset" data-id="qty_<?php echo $i; ?>" data-ins="custom_special_qty_<?php echo $i; ?>" data-clear="imgs_qty_<?php echo $i; ?>"><a ><i class="fa fa-repeat" aria-hidden="true"></i> reset</a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<div id="pos_si_qty">
  <?php for($i=1, $sel_counter=0; $i <= $tpl['qty']; $i++, $sel_counter++) { 
    $current_qty_id = 'qty_'.$i;
    if ($tpl['selected_ins_arr'] && array_key_exists($current_qty_id, $grouped_sel_ins_arr)) {
      $arr= true;
      $selected_ins_arr = explode(',', $grouped_sel_ins_arr[$current_qty_id]['ids']);
    } else {
      $arr = false;
      $selected_ins_arr = array();
    }
    ?>
    <div id="qty_<?php echo $i; ?>" <?php if ($i>1) echo "class='d-none'"?>>
      <table class="table table-striped">
        <thead>
          <tr>
            <?php foreach($tpl['special_instructions'] as $spcl_ins) { ?>
              <td><?php echo $spcl_ins['instruction_type'] ?></td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php foreach($tpl['special_instructions'] as $spcl_ins) { ?>
            <td class="col-special-instructions"><?php foreach($tpl['special_instructions_children'] as $spcl_ins_child) { 
                if($spcl_ins['id'] == $spcl_ins_child['parent_id']) {
                ?>
              <img src="<?php echo $spcl_ins_child['image'] ?>" 
                alt="" data-qty_id="qty_<?php echo $i; ?>" 
                class="img_class_qty_<?php echo $i; ?> img-fluid img_<?php echo $spcl_ins_child['id']; ?> 
                <?php echo in_array($spcl_ins_child['id'], $selected_ins_arr) ? 'spcl_ins_selected': '' ?>" 
                data-id="<?php echo $spcl_ins_child['id']; ?>" style="margin-left: 10px;">
              <?php }
          } ?></td>
          <?php } ?>
          </tr>
        </tbody>
      </table>
  </div>
    <input type="hidden" id="arr_qty_<?php echo $i; ?>" name="qty_<?php echo $i; ?>" value data-images>
  <?php } ?>
</div>