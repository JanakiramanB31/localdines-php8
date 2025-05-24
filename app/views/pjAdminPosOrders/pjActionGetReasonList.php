<select class="form-control" id="delay_reason">
	<option value="" selected>Choose the reason</option>
<?php foreach ($tpl['titles'] as $title_id=>$title) { ?>
	<option value="<?php echo $title_id; ?>"><?php echo $title; ?></option>
<?php }
?>
</select>