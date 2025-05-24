<input type="hidden" name="custom_time" value="1" />
<input type="hidden" name="location_id" value="<?php echo $controller->_post->toInt('location_id');?>" />
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
                    <input type="text" name="date" value="<?php echo date($tpl['option_arr']['o_date_format'], time());?>" class="form-control required" data-msg-required="<?php __('fd_required_field', false, true);?>">

                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div><!-- /.col-md-6 -->

        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><?php __('lblType'); ?></label>

                <select name="type" id="type" class="form-control required" data-msg-required="<?php __('fd_required_field', false, true);?>">
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
                    <input name="start" class="pj-timepicker form-control required" data-msg-required="<?php __('fd_required_field', false, true);?>"/>

                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
                </div>
            </div>
        </div><!-- /.col-md-3 -->

        <div class="col-sm-6">
            <div class="form-group pjFdTimeGroup">
                <label class="control-label"><?php __('lblEndTime'); ?></label>

                <div class="input-group">
                    <input name="end" class="pj-timepicker form-control required" data-msg-required="<?php __('fd_required_field', false, true);?>"/>

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
</div><!-- /.m-t-sm -->