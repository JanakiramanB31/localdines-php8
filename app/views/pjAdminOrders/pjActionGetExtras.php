<?php
mt_srand();
$extra_index = 'x_' . mt_rand();
$index = $controller->_get->toString('index');
//print_r($tpl['extra_arr']);
?>
<tr>
    <td>
        <select name="extra_id[<?php echo $index; ?>][<?php echo $extra_index; ?>]" data-index="<?php echo $index; ?>_<?php echo $extra_index; ?>" class="fdExtra fdExtra_<?php echo $index; ?> form-control">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
			<?php
			if (isset($tpl['extra_arr']))
			{
				foreach ($tpl['extra_arr'] as $e)
				{
				    ?><option value="<?php echo $e['id']; ?>" data-price="<?php echo $e['price'];?>"><?php echo stripslashes($e['name']); ?>: <?php echo pjCurrency::formatPrice($e['price']);?></option><?php
				}
			}
			?>
		</select>
    </td>
    
    <td><input type="text" id="fdExtraQty_<?php echo $index; ?>_<?php echo $extra_index; ?>" name="extra_cnt[<?php echo $index; ?>][<?php echo $extra_index; ?>]" class="form-control pj-field-count" value="1" placeholder="Qty"/></td>

    <td>
        <a href="#" class="btn btn-xs btn-danger btn-outline pj-remove-extra"><i class="fa fa-times"></i></a>
    </td>
</tr>