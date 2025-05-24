<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2><?php __('infoBookingsTitle') ?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoBookingsBody') ?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
    {
    	$titles = __('error_titles', true);
    	$bodies = __('error_bodies', true);
    	switch (true)
    	{
    		case in_array($error_code, array('AO02')):
    			?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]?>
    			</div>
    			<?php
    			break;
    		case in_array($error_code, array('')):
    			?>
    			<div class="alert alert-danger">
    				<i class="fa fa-exclamation-triangle m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]?>
    			</div>
    			<?php
    			break;
    	}
    }
    ?>
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?php
    				if (isset($tpl['arr']) && is_array($tpl['arr']) && !empty($tpl['arr']))
    				{
                    ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form-horizontal" id="frmUpdateOptions">
                        <input type="hidden" name="options_update" value="1" />
                        <input type="hidden" name="next_action" value="pjActionBooking" />
                        <?php
                        foreach ($tpl['arr'] as $option)
                        {
                            if(in_array($option['key'], array('o_allow_bank', 'o_allow_cash', 'o_allow_creditcard', 'o_bank_account'))) continue; // These will be managed from Payments menu
                            ?>
                            <div class="form-group">

                                <label class="col-sm-3 control-label"><?php __('opt_' . $option['key']); ?></label>
                                <div class="col-lg-5 col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?php
                                            switch ($option['type'])
                                            {
                                                case 'string':
                                                    ?><input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control" value="<?php echo pjSanitize::html($option['value']); ?>"><?php
                                                    break;
                                                case 'text':
                                                    ?><textarea name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control"><?php echo pjSanitize::html($option['value']); ?></textarea><?php
                                                    break;
                                                case 'int':
                                                    ?>
                                                    <input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-int" value="<?php echo pjSanitize::html($option['value']); ?>">
                                                    <?php
                                                    break;
                                                case 'float':
                                                    if(in_array($option['key'], array('o_booking_price'))) {
                                                        ?>
                                                        <div class="input-group">
                                                            <input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control decimal number text-right" value="<?php echo number_format($option['value'], 2) ?>" data-msg-number="<?php __('pj_please_enter_valid_number', false, true);?>">

                                                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?><input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-float number" value="<?php echo number_format($option['value'], 2) ?>"><?php
                                                    }
                                                    break;
                                                case 'enum':
                                                    if($option['key'] == 'o_payment_disable') {
                                                        include dirname(__FILE__) . '/elements/switch.php';
                                                    } else {
                                                        include dirname(__FILE__) . '/elements/enum.php';
                                                    }
                                                    break;
                                            }
                                            ?>
                                        </div>

                                        <?php if (in_array($option['key'], array('o_booking_status', 'o_payment_status', 'o_thankyou_page', 'o_payment_disable', 'o_min_hour'))): ?>
                                            <div class="col-sm-12">
                                                <span class="form-control-static"><?php __("opt_{$option['key']}_text") ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (in_array($option['key'], array('o_booking_length', 'o_booking_earlier'))): ?>
                                            <p class="m-t-xs"><?php __("opt_{$option['key']}_text") ?></p>
                                        <?php endif; ?>
                                        <?php if (in_array($option['key'], array('o_min_hour'))): ?>
                                            <p class="m-t-xs"><?php __("lblMinutes") ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="hr-line-dashed"></div>

                        <div class="clearfix">
                            <button class="ladda-button btn btn-primary btn-lg pull-left btn-phpjabbers-loader" data-style="zoom-in">
                                <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                            </button>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>