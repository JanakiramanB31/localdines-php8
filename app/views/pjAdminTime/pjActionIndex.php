<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoWTimeTitle');?></h2>
            </div>
            
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoWTimeDesc');?></p>
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
	        case in_array($error_code, array('AL01', 'AL03', 'AL09')):
	            ?>
				<div class="alert alert-success">
					<i class="fa fa-check m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
            case in_array($error_code, array('AL04', 'AL08')):
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
	
    $show_period = 'false';
    if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
    {
        $show_period = 'true';
    }
    ?>
	<div class="tabs-container tabs-reservations m-b-lg">
		<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('AT01', 'AT03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('AT08')):
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
    	$tab = $controller->_get->check('tab') ? $controller->_get->toString('tab') : 'default';
    	?>
		<ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionUpdate&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDetails');?></a></li>
            <li role="presentation"<?php echo $tab=='default' ? ' class="active"' : NULL; ?>><a href="#default" aria-controls="default" role="tab" data-toggle="tab"><?php __('menuWorkingTime'); ?></a></li>
            <li role="presentation"<?php echo $tab=='custom' ? ' class="active"' : NULL; ?>><a href="#custom" aria-controls="custom" role="tab" data-toggle="tab"><?php __('lblCustomerWorkingTime'); ?></a></li>
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDeliveryPrices'); ?></a></li>
        </ul>
       <div class="tab-content">
            <div role="tabpanel" id="default" class="tab-pane<?php echo $tab=='default' ? ' active' : NULL;?>">
                <div class="panel-body">
            	  	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex" method="post" id="frmDefaultTime">
            	  		<input type="hidden" name="working_time" value="1" />
						<input type="hidden" name="location_id" value="<?php echo $tpl['arr']['id'];?>" />
						<div class="row">
							<div class="col-lg-6">
								<div class="m-b-md">
                                    <h3 class="no-margins"><?php __('lblWorkingTimePickup');?></h3>
                                </div>
                                <div class="table-responsive table-responsive-secondary">
                                	<table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php __('lblDayOfWeek', false, true); ?></th>
                                				<th class="text-center"><?php __('lblIsDayOff', false, true); ?></th>
                                				<th><?php __('lblStartTime', false, true); ?></th>
                                				<th><?php __('lblEndTime', false, true); ?></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $days = __('days', true);
                                        $w_days = array(
                                            'monday' => $days[1],
                                            'tuesday' => $days[2],
                                            'wednesday' => $days[3],
                                            'thursday' => $days[4],
                                            'friday' => $days[5],
                                            'saturday' => $days[6],
                                            'sunday' => $days[0]
                                        );
                                        ?>
                                        <tbody>
                                        	<?php
                                        	foreach ($w_days as $k => $day)
                                        	{
                                        	    if (isset($tpl['wt_arr']) && count($tpl['wt_arr']) > 0)
                                        	    {
                                        	        $tpl['wt_arr']['p_' . $k.'_from'] = empty($tpl['wt_arr']['p_' . $k.'_from']) ? '08:00:00' : $tpl['wt_arr']['p_' . $k.'_from'];
                                        	        $tpl['wt_arr']['p_' . $k.'_to'] = empty($tpl['wt_arr']['p_' . $k.'_to']) ? '22:00:00' : $tpl['wt_arr']['p_' . $k.'_to'];
                                        	        
                                        	        $from = date('H:i', strtotime($tpl['wt_arr']['p_' . $k . '_from']));
                                        	        $to = date('H:i', strtotime($tpl['wt_arr']['p_' . $k.'_to']));
                                        	        
                                        	        if($show_period == 'true')
                                        	        {
                                        	            $from = date('h:iA', strtotime($tpl['wt_arr']['p_' . $k.'_from']));
                                        	            $to = date('h:iA', strtotime($tpl['wt_arr']['p_' . $k.'_to']));
                                        	        }
                                        	        
                                        	        $checked = NULL;
                                        	        $dayoff_class = NULL;
                                        	        
                                        	        if ($tpl['wt_arr']['p_' . $k.'_dayoff'] == 'T')
                                        	        {
                                        	            $checked = ' checked="checked"';
                                        	            $dayoff_class = 'none';
                                        	        }
                                        	    }else{
                                        	        $from = NULL;
                                        	        $to = NULL;
                                        	        $checked = NULL;
                                        	    }
                                        	    ?>
                                				<tr data-day="<?php echo $k;?>" data-type="pickup">
                                					<td><?php echo $day; ?></td>
                                					<td class="text-center"><input type="checkbox" class="i-checks pickup-working-day" id="p_<?php echo $k; ?>_dayoff" name="p_<?php echo $k; ?>_dayoff" value="T"<?php echo $checked; ?> /></td>
                                					<td>
                                                        <div class="input-group pickupWorkingDay_<?php echo $k;?>" style="display:<?php echo $dayoff_class; ?>;">
                                                            <input name="p_<?php echo $k?>_from" value="<?php echo $from;?>" class="pj-timepicker form-control"/>

                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group pickupWorkingDay_<?php echo $k;?>" style="display:<?php echo $dayoff_class; ?>;">
                                                            <input name="p_<?php echo $k?>_to" value="<?php echo $to;?>" class="pj-timepicker form-control"/>

                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                        </div>
                                                    </td>
                                				</tr>
                                				<?php
                                        	}
                                        	?>	
                                        </tbody>
                                	</table>
                                </div><!-- /.table-responsive table-responsive-secondary -->
							</div><!-- /.col-lg-6 -->
							
							<div class="col-lg-6">
								<div class="m-b-md">
                                    <h3 class="no-margins"><?php __('lblWorkingTimeDelivery');?></h3>
                                </div>
                                <div class="table-responsive table-responsive-secondary">
                                	<table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php __('lblDayOfWeek', false, true); ?></th>
                                				<th class="text-center"><?php __('lblIsDayOff', false, true); ?></th>
                                				<th><?php __('lblStartTime', false, true); ?></th>
                                				<th><?php __('lblEndTime', false, true); ?></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $days = __('days', true);
                                        $w_days = array(
                                            'monday' => $days[1],
                                            'tuesday' => $days[2],
                                            'wednesday' => $days[3],
                                            'thursday' => $days[4],
                                            'friday' => $days[5],
                                            'saturday' => $days[6],
                                            'sunday' => $days[0]
                                        );
                                        ?>
                                        <tbody>
                                        	<?php
                                        	foreach ($w_days as $k => $day)
                                        	{
                                        	    if (isset($tpl['wt_arr']) && count($tpl['wt_arr']) > 0)
                                        	    {
                                        	        $tpl['wt_arr']['d_' . $k.'_from'] = empty($tpl['wt_arr']['d_' . $k.'_from']) ? '08:00:00' : $tpl['wt_arr']['d_' . $k.'_from'];
                                        	        $tpl['wt_arr']['d_' . $k.'_to'] = empty($tpl['wt_arr']['d_' . $k.'_to']) ? '22:00:00' : $tpl['wt_arr']['d_' . $k.'_to'];
                                        	        
                                        	        $from = date('H:i', strtotime($tpl['wt_arr']['d_' . $k . '_from']));
                                        	        $to = date('H:i', strtotime($tpl['wt_arr']['d_' . $k.'_to']));
                                        	        
                                        	        if($show_period == 'true')
                                        	        {
                                        	            $from = date('h:iA', strtotime($tpl['wt_arr']['d_' . $k.'_from']));
                                        	            $to = date('h:iA', strtotime($tpl['wt_arr']['d_' . $k.'_to']));
                                        	        }
                                        	        
                                        	        $checked = NULL;
                                        	        $dayoff_class = NULL;
                                        	        
                                        	        if ($tpl['wt_arr']['d_' . $k.'_dayoff'] == 'T')
                                        	        {
                                        	            $checked = ' checked="checked"';
                                        	            $dayoff_class = 'none';
                                        	        }
                                        	    }else{
                                        	        $from = NULL;
                                        	        $to = NULL;
                                        	        $checked = NULL;
                                        	    }
                                        	    ?>
                                				<tr data-day="<?php echo $k;?>" data-type="delivery">
                                					<td><?php echo $day; ?></td>
                                					<td class="text-center"><input type="checkbox" class="i-checks pickup-working-day" id="d_<?php echo $k; ?>_dayoff" name="d_<?php echo $k; ?>_dayoff" value="T"<?php echo $checked; ?> /></td>
                                					<td>
                                                        <div class="input-group deliveryWorkingDay_<?php echo $k;?>" style="display:<?php echo $dayoff_class; ?>;">
                                                            <input name="d_<?php echo $k?>_from" value="<?php echo $from;?>" class="pj-timepicker form-control"/>

                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group deliveryWorkingDay_<?php echo $k;?>" style="display:<?php echo $dayoff_class; ?>;">
                                                            <input name="d_<?php echo $k?>_to" value="<?php echo $to;?>" class="pj-timepicker form-control"/>

                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                        </div>
                                                    </td>
                                				</tr>
                                				<?php
                                        	}
                                        	?>	
                                        </tbody>
                                	</table>
                                </div><!-- /.table-responsive table-responsive-secondary -->
							</div><!-- /.col-lg-6 -->
						</div><!-- /.row -->
					
    					<div class="hr-line-dashed"></div>
                                                                
                        <div class="clearfix">
                            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                                <span class="ladda-label"><?php __('btnSave'); ?></span>
                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                            </button>
                        </div><!-- /.clearfix -->
                    </form>
                   
                </div><!-- /.panel-body -->
            </div>
            <div role="tabpanel" id="custom" class="tab-pane<?php echo $tab=='custom' ? ' active' : NULL;?>">
                <div class="panel-body">
                	<?php
                    $months = __('months', true);
                    ksort($months);
                    $short_days = __('short_days', true);
                    ?>
    				<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
                    
                    <div class="row">
                		<div class="col-lg-8 ibox-content">
                			<div id="grid"></div>
                		</div><!-- /.col-lg-7 -->
                		<div class="col-lg-4 panel no-borders">
                			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex" method="post" class="form pj-form" id="frmTimeCustom">
                            	<input type="hidden" name="custom_time" value="1" />
                            	<input type="hidden" name="location_id" value="<?php echo $tpl['arr']['id'];?>" />
                                <div class="panel-heading bg-completed">
                                    <p class="lead m-n"><?php __('lblSetWorkingTime');?></p>
                                </div><!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="control-label"><?php __('lblIsDayOff'); ?></label>

                                        <div class="switch">
                                            <div class="onoffswitch onoffswitch-data">
                                                <input type="checkbox" class="onoffswitch-checkbox" name="is_dayoff" id="is_dayoff">
                                                <label class="onoffswitch-label" for="is_dayoff">
                                                    <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true);?>" data-off="<?php __('_yesno_ARRAY_F', false, true);?>"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('lblDate'); ?></label>

                                                <div class="input-group date">
                                                    <input type="text" name="date" value="<?php echo date($tpl['option_arr']['o_date_format'], time());?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" autocomplete="off">
                
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div><!-- /.col-md-6 -->

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('lblType'); ?></label>

                                                <select name="type" id="type" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                                    				<?php
                                    				foreach (__('types', true, false) as $k => $v)
                                    				{
                                    					?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
                                    				}
                                    				?>
                                    			</select>
                                            </div>
                                        </div><!-- /.col-md-6 -->
                                    </div><!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group pjFdTimeGroup">
                                                <label class="control-label"><?php __('lblStartTime'); ?></label>

                                                <div class="input-group">
                                                    <input name="start" class="pj-timepicker form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" autocomplete="off">

                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                </div>
                                            </div>
                                        </div><!-- /.col-md-3 -->

                                        <div class="col-sm-6">
                                            <div class="form-group pjFdTimeGroup">
                                                <label class="control-label"><?php __('lblEndTime'); ?></label>

                                                <div class="input-group">
                                                    <input name="end" class="pj-timepicker form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" autocomplete="off">

                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                                                </div>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    </div><!-- /.row -->
									<div class="clearfix">
                                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                                            <span class="ladda-label"><?php __('btnSave'); ?></span>
                                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                                        </button>
                                    </div><!-- /.clearfix -->
                                </div><!-- /.panel-body -->
                            </form>
                        </div><!-- /.col-lg-5 -->
                	</div><!-- /.row -->
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
    </div>
</div>
<style>
.ibox-content{
    border: none;
}
</style>

<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
pjGrid.queryString = "";
<?php
if ($controller->_get->toInt('id'))
{
    ?>pjGrid.queryString += "&location_id=<?php echo $controller->_get->toInt('id'); ?>";<?php
}
?>
var myLabel = myLabel || {};
myLabel.showperiod = <?php echo $show_period; ?>;
myLabel.date = <?php x__encode('lblDate'); ?>;
myLabel.type = <?php x__encode('lblType'); ?>;
myLabel.validate_time = <?php x__encode('lblValidateTime'); ?>;
myLabel.start_time = <?php x__encode('lblStartTime'); ?>;
myLabel.end_time = <?php x__encode('lblEndTime'); ?>;
myLabel.is_day_off = <?php x__encode('lblIsDayOff'); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.alert_title = <?php x__encode('lblWarning'); ?>;
myLabel.alert_text = <?php x__encode('lblWTimeError'); ?>;
myLabel.btn_ok = <?php x__encode('btnOK'); ?>;
</script>