<tr class="fdLine" data-index="{INDEX}">
    <td>
        <select id="fdProduct_{INDEX}" data-index="{INDEX}" name="product_id[{INDEX}]" class="fdProduct" data-live-search="true">
			<option value="">-- <?php __('lblChoose'); ?>--</option>
			<?php
			foreach ($tpl['product_arr'] as $p)
			{
				if ($p['status'] == 1) {
				
				?><option value="<?php echo $p['id']; ?>" data-extra="<?php echo $p['cnt_extras'];?>"><?php echo stripslashes($p['name']); ?></option><?php
			}
		}
			?>
		</select>
    </td>
    <td>
		<div class="business-{INDEX}" style="display: none;">
			<input type="text" id="fdProductQty_{INDEX}" name="cnt[{INDEX}]" class="form-control pj-field-count" value="1" style="width: 35px;" />
		</div>
	</td>
    <td>
		<div class="business-{INDEX}" style="display: none;">
			<table id="fdExtraTable_{INDEX}" class="table no-margins pj-extra-table">							
				<tbody>
				</tbody>
			</table>
			<div class="p-w-xs">
                <a href="#" class="btn btn-primary btn-xs btn-outline pj-add-extra fdExtraBusiness_{INDEX} fdExtraButton_{INDEX}" data-index="{INDEX}"><i class="fa fa-plus"></i> <?php __('btnAddExtra');?></a>
            </div><!-- /.p-w-xs -->
			<span class="fdExtraBusiness_{INDEX} fdExtraNA_{INDEX}"><?php __('lblNA');?></span>
		</div>
	</td>
    <!-- MEGAMIND -->

	<td><span id="fdCategory_{INDEX}"></span></td>		
     
    <!-- MEGAMIND -->
    <td id="fdPriceTD_{INDEX}">
		<div class="business-{INDEX}" style="display: none;">
			<select id="fdPrice_{INDEX}" name="price_id[{INDEX}]" data-type="select" class="fdSize form-control">
				<option value="">-- <?php __('lblChoose'); ?>--</option>
			</select>
		</div>
	</td>

	<!-- MEGAMIND -->

	<td><span id="fdPrepTime_{INDEX}"></span></td>		
     
    <!-- MEGAMIND -->

    <td>
		<strong><span id="fdTotalPrice_{INDEX}"><?php echo pjCurrency::formatPrice(0);?></span></strong>
	</td>
    <td>
    	<input type="hidden" id="specialInstruction_{INDEX}" name="special_instruction[{INDEX}]" class="form-control" value="" />
    </td>      
    <td>
       	<div class="text-right" id="productDelete_{INDEX}" style="display: none">
            <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
        </div>
    </td>
</tr>