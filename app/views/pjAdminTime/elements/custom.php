<?php if ($tpl['has_access_create']): ?>
    <?php
    $jqDateFormat = pjUtil::momentJsDateFormat($tpl['option_arr']['o_date_format']);
    $yesno = __('plugin_base_yesno', true, false);

    $startHour = pjDateTime::factory()
	    ->attr('name', 'start_hour')
	    ->attr('id', 'start_hour')
	    ->attr('class', 'form-control')
	    ->prop('ampm', $tpl['time_ampm']);
    
    $startMinute = pjDateTime::factory()
	    ->attr('name', 'start_minute')
	    ->attr('id', 'start_minute')
	    ->attr('class', 'form-control')
	    ->prop('step', 5);
    
    $endHour = pjDateTime::factory()
	    ->attr('name', 'end_hour')
	    ->attr('id', 'end_hour')
	    ->attr('class', 'form-control required greaterThan')
	    ->prop('ampm', $tpl['time_ampm']);
    
    $endMinute = pjDateTime::factory()
	    ->attr('name', 'end_minute')
	    ->attr('id', 'end_minute')
	    ->attr('class', 'form-control')
	    ->prop('step', 5);
    
    if ($tpl['time_ampm'])
    {
    	$startAmPm = pjDateTime::factory()
	    	->attr('name', 'start_ampm')
	    	->attr('id', 'start_ampm')
	    	->attr('class', 'form-control')
	    	->prop('ampm', $tpl['time_ampm']);
    	
    	$endAmPm = pjDateTime::factory()
	    	->attr('name', 'end_ampm')
	    	->attr('id', 'end_ampm')
	    	->attr('class', 'form-control')
	    	->prop('ampm', $tpl['time_ampm']);
    }
    ?>
    <form id="frmTimeCustom" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom" class="form-horizontal" method="post">
        <input type="hidden" name="custom_time" value="1" />

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php __('lblDate'); ?></label>
            <div class="col-lg-5 col-sm-7">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group date"
                             data-provide="datepicker"
                             data-date-autoclose="true"
                             data-date-format="<?php echo $jqDateFormat ?>"
                             data-date-week-start="<?php echo (int) $tpl['option_arr']['o_week_start'] ?>">
                            <input type="text" name="date" id="date" class="form-control required" autocomplete="off" value="<?php echo pjDateTime::formatDate(date("Y-m-d"), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group customeTimeRow">
            <label class="col-sm-2 control-label"><?php __('lblStartTime'); ?></label>
            <div class="col-lg-3 col-sm-7">
                
				<div class="input-group">
					<span class="input-group-btn"><?php echo $startHour->hour(); ?></span>
					<span class="input-group-btn"><?php echo $startMinute->minute(); ?></span>
					<?php if ($tpl['time_ampm']) : ?>
					<span class="input-group-btn"><?php echo $startAmPm->ampm(); ?></span>
					<?php endif; ?>
				</div>
										
            </div>
        </div>

        <div class="form-group customeTimeRow">
            <label class="col-sm-2 control-label"><?php __('lblEndTime'); ?></label>
            <div class="col-lg-3 col-sm-7">
                
                <div class="input-group">
					<span class="input-group-btn"><?php echo $endHour->hour(); ?></span>
					<span class="input-group-btn"><?php echo $endMinute->minute(); ?></span>
					<?php if ($tpl['time_ampm']) : ?>
					<span class="input-group-btn"><?php echo $endAmPm->ampm(); ?></span>
					<?php endif; ?>
				</div>
										
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php __('lblIsDayOff'); ?></label>
            <div class="col-lg-5 col-sm-7">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="clearfix">
                            <div class="switch onoffswitch-data pull-left">
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="is_dayoff" name="is_dayoff" value="T">
                                    <label class="onoffswitch-label" for="is_dayoff">
                                        <span class="onoffswitch-inner" data-on="<?php echo $yesno['T'] ?>" data-off="<?php echo $yesno['F'] ?>"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">&nbsp;</label>

            <div class="col-sm-10">
                <button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader" data-style="zoom-in">
                    <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
                    <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                </button>
            </div>
        </div>
    </form>
<?php endif; ?>

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div id="grid"></div>
    </div>
</div>