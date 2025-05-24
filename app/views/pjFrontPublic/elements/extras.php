<style type="text/css">
	.big-checkbox {width: 2rem; height: 2rem;top:0.5rem; float: right; margin-right: 20px !important;}
	/*.pjFdProductEtras tr:nth-child(n+3),.loadLess {
  		display: none;
	}*/
	.child-extras {
		display: none;
	}
	.loadMore {
		font-weight: bold;
	}

</style>
<div class="row" style="margin-bottom: 10px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table pjFdProductMeta pjFdProductEtras" id='<?php echo "productsExtras-".$product_id; ?>'>
		<?php
		// echo '<pre>'; print_r($product['extra_arr']); echo '</pre>';
		ksort($product['extra_arr']);
		foreach($product['extra_arr'] as $extra_title=>$extras) { 
			if (is_array($extras)) {
				// echo '<pre>'; print_r($extras); echo '</pre>';
				list($parent_title, $par_cat_id) = explode('_', $extra_title);
				//Added for Base category on 20-June-2024
				if ($par_cat_id == 3) {
					$config_extra_cat = EXTRA_CATEGORY_TYPES;
					$parent_title = $config_extra_cat[2];
				}
				//End of on 20-June-2024
				$identifier = $product_id.'_'.$par_cat_id;
		?>
		<tr id="extras-parent-<?php echo $identifier;  ?>"><td colspan="4">
				<a href="#" class="loadMore" id='<?php echo "extras-parent-".$identifier; ?>'><?php echo $parent_title; ?></a>
			</td></tr>
		<?php
				foreach($extras as $extra) {
					include PJ_VIEWS_PATH . 'pjFrontPublic/elements/extras_sub.php';
				}
			} else {
				$extra = $extras;
				include PJ_VIEWS_PATH . 'pjFrontPublic/elements/extras_sub.php';
			}
		} 
		?>
	</table><!-- /.table -->
</div><!-- /.row -->