<div id="section-special-instructions">
    <?php //echo "<pre>"; print_r($tpl['selected_ins_arr']); ?>
    <div class="row">
        <?php foreach($tpl['special_instructions'] as $spcl_ins) { ?>
        <div class="col-sm-4">
            <img src="<?php echo $spcl_ins['image'] ?>" alt="" class="img-fluid img_<?php echo $spcl_ins['id']; ?> <?php echo in_array($spcl_ins['id'], $tpl['selected_ins_arr']) ? 'spcl_ins_selected': '' ?>" data-id = "<?php echo $spcl_ins['id']; ?>">
        </div>
        <?php } ?>
    </div>
</div>