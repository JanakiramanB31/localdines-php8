<div class="row">
    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_total_orders'); ?>:</label>

            <div><?php echo $tpl['total_orders']?></div>
        </div><!-- /.form-group -->
    </div>

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_confirmed'); ?>:</label>

            <div><?php echo $tpl['confirmed_orders']?></div>
        </div><!-- /.form-group -->
    </div>

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_pickup'); ?>:</label>

            <div><?php echo $tpl['pickup_orders']?></div>
        </div><!-- /.form-group -->
    </div>

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_delivery'); ?>:</label>

            <div><?php echo $tpl['delivery_orders']?></div>
        </div><!-- /.form-group -->
    </div>
</div><!-- /.row -->

<div class="hr-line-dashed"></div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_unique_clients'); ?>:</label>

            <div><?php echo $tpl['unique_clients']?></div>
        </div><!-- /.form-group -->
    </div>

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_first_time_clients'); ?>:</label>

            <div><?php echo $tpl['first_time_clients']?></div>
        </div><!-- /.form-group -->
    </div>
</div><!-- /.row -->

<div class="hr-line-dashed"></div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_total_amount'); ?>:</label>

            <div><?php echo pjCurrency::formatPrice($tpl['price_info']['total_amount']);?></div>
        </div><!-- /.form-group -->
    </div>

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_delivery_fees'); ?>:</label>

            <div><?php echo pjCurrency::formatPrice($tpl['price_info']['delivery_fee']);?></div>
        </div><!-- /.form-group -->
    </div>

    <!-- <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_tax'); ?>:</label>

            <div><?php echo pjCurrency::formatPrice($tpl['price_info']['tax']);?></div>
        </div>
    </div> -->

    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_discounts'); ?>:</label>

            <div><?php echo pjCurrency::formatPrice($tpl['price_info']['discount']);?></div>
        </div><!-- /.form-group -->
    </div>
</div><!-- /.row -->

<div class="hr-line-dashed"></div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_total_products_ordered'); ?>:</label>

            <div><?php echo $tpl['total_products']?></div>
        </div><!-- /.form-group -->
    </div>
    <!-- <div class="col-lg-3 col-md-4 col-xs-6">
        <div class="form-group">
            <label class="control-label"><?php __('report_packing_fees'); ?>:</label>

            <div><?php echo pjCurrency::formatPrice($tpl['price_info']['packing_fee']);?></div>
        </div>
    </div> -->
</div><!-- /.row -->

<div class="hr-line-dashed"></div>

<div class="table-responsive table-responsive-secondary">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?php __('report_category');?></th>
				<th><?php __('report_products_ordered');?></th>
				<th><?php __('report_total_amount');?></th>
            </tr>
        </thead>

        <tbody>
            <?php
			foreach($tpl['category_arr'] as $k => $v)
			{
                if($v['total_products'] > 0) {
				?>
				<tr>
					<td><?php echo pjSanitize::html($v['name']);?></td>
					<td><?php echo (int) $v['total_products'];?></td>
					<td><?php echo pjCurrency::formatPrice($v['total_amount']);?></td>
				</tr>
				<?php
                }
			} 
			?>
        </tbody>
    </table>
</div>

<div class="table-responsive table-responsive-secondary">
    <table class="table table-striped table-hover">
        <thead>
			<tr>
				<th><?php __('report_product');?></th>
				<th><?php __('report_quantity');?></th>
				<th><?php __('report_total_amount');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($tpl['product_arr'] as $k => $v)
			{
                if($v['total_products'] > 0) {
				?>
				<tr>
					<td><?php echo pjSanitize::html($v['name']);?></td>
					<td><?php echo (int) $v['total_products'];?></td>
					<td><?php echo pjCurrency::formatPrice($v['total_amount']);?></td>
				</tr>
				<?php
                }
			} 
			?>
		</tbody>
    </table>
</div>