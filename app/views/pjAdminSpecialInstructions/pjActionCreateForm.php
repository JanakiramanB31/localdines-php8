<?php
$filter = __('filter', true);
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpecialInstructions&amp;action=pjActionCreate" method="post" id="frmCreateSpecialInstruction" autocomplete="off"  enctype="multipart/form-data">
	<input type="hidden" name="special_instruction_create" value="1" />
    <div class="panel-heading bg-completed">
        <p class="lead m-n"><?php echo "Add New Special Instruction";?></p>
    </div><!-- /.panel-heading -->

    <div class="panel-body">
    	<?php
    	foreach ($tpl['lp_arr'] as $v)
    	{
        	?>
            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                <label class="control-label"><?php echo "Instruction";?></label>
                                        
                <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
					<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][name]" data-msg-required="<?php __('fd_field_required', false, true);?>">	
					<?php if ($tpl['is_flag_ready']) : ?>
					<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
					<?php endif; ?>
				</div>
            </div>
            <?php
        }
        ?>
        <?php if(!empty($tpl['special_instruction_types']))
        {
            ?>
            <div class="form-group">
                <label class="control-label"><?php echo "Type"; ?></label>

                <select name="special_instruction_type" id="special_instruction_type" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
                    <?php
                    foreach ($tpl['special_instruction_types'] as $v)
                    {
                        ?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['type']); ?></option><?php
                    }
                    ?>
                </select>
            </div><!-- /.form-group -->
            <?php
        }else{
            ?>
            <div class="form-group">
                <label class="control-label"><?php echo "Type"; ?></label>

                <select name="special_instruction_type" id="special_instruction_type" class="form-control select2-hidden-accessible required" data-placeholder="-- <?php __('lblChoose'); ?> --" data-msg-required="<?php __('fd_field_required', false, true);?>">
                    
                </select>
            </div><!-- /.form-group -->
            <?php
        } ?>
        <div class="form-group">
            <label class="control-label"><?php __('lblImage', false, true); ?></label>

            <div>
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
                    <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="image" type="file"></span>
                    <span class="fileinput-filename"></span>

                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                </div>
                <div>
                    <p><b>Size of Product Image:</b> 270 * 220</p>
                </div>
            </div>
        </div><!-- /.form-group -->
        <div class="form-group">
            <label class="control-label"><?php __('lblStatus'); ?></label>
        
            <div class="clearfix">
                <div class="switch onoffswitch-data pull-left">
                    <div class="onoffswitch">
                        <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status" checked>
                        <label class="onoffswitch-label" for="status">
                            <span class="onoffswitch-inner" data-on="<?php echo $filter['active']; ?>" data-off="<?php echo $filter['inactive']; ?>"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div><!-- /.clearfix -->
        </div>

		<div class="m-t-lg">
            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                <span class="ladda-label"><?php __('btnSave'); ?></span>
                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
            </button>
            <button type="button" class="btn btn-white btn-lg pull-right pjFdBtnCancel"><?php __('btnCancel'); ?></button>
        </div><!-- /.clearfix -->
    </div><!-- /.panel-body -->
</form>