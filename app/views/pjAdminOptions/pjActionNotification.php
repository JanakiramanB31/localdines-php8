<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <h2><?php __('script_infobox_notifications_title');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>    
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('script_infobox_notifications_desc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->input->getString('err');
	if (!empty($error_code))
	{
	    $titles = __('script_error_titles', true);
	    $bodies = __('script_error_bodies', true);
	    switch (true)
	    {
	        case in_array($error_code, array('AO01')):
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
	if (isset($tpl['arr']))
	{
	    if (is_array($tpl['arr']))
	    {
	        $count = count($tpl['arr']) - 1;
	        if ($count > 0)
	        {
	            $locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : NULL;
	            if (is_null($locale))
	            {
	                foreach ($tpl['lp_arr'] as $v)
	                {
	                    if ($v['is_default'] == 1)
	                    {
	                        $locale = $v['id'];
	                        break;
	                    }
	                }
	            }
	            if (is_null($locale))
	            {
	                $locale = @$tpl['lp_arr'][0]['id'];
	            }
	            $tab_id = isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'client';
	            ?>
	            <div class="col-lg-12">
	            	<form id="frmNotification" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" class="form-horizontal" method="post">
	            		<input type="hidden" name="options_update" value="1" />
						<input type="hidden" name="next_action" value="pjActionNotification" />
						<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'client'; ?>" />
						<div class="tabs-container tabs-reservations m-b-lg">
							<ul class="nav nav-tabs">
								<li role="presentation"<?php echo $tab_id == 'client' ? ' class="active"' : NULL;?>><a href="#client" aria-controls="client" role="tab" data-toggle="tab"><?php __('script_tab_client'); ?></a></li>
								<li role="presentation"<?php echo $tab_id == 'admin' ? ' class="active"' : NULL;?>><a href="#admin" aria-controls="admin" role="tab" data-toggle="tab"><?php __('script_tab_admin'); ?></a></li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane<?php echo $tab_id == 'client' ? ' active' : NULL;?>" id="client">
									<div class="panel-body">
										<div class="m-b-lg">
                                            <h2 class="no-margins"><?php __('script_lbl_emails');?></h2>
                                        </div>
										<div class="row">
											<div class="col-lg-8">
												<div class="form-group">
			                                        <label class="col-lg-3 col-md-4 control-label"><?php __('script_lbl_notifications');?></label>
			                                    
			                                    	<div class="col-lg-5 col-md-8">
    		                                            <select name="client_email_notify" id="client_email_notify" class="form-control">
    														<?php
    														foreach (__('script_client_email_arr', true) as $k => $v)
    														{
    															?><option value="<?php echo $k; ?>"<?php echo $controller->input->getString('client_email') == $k ? ' selected="selected"' : NULL?>><?php echo $v; ?></option><?php
    														}
    														?>
    													</select>
    												</div>
			                                    </div>
			                                    <?php
			                                    for ($i = 0; $i < $count; $i++)
			                                    {
			                                        if ($tpl['arr'][$i]['tab_id'] == 1 && (int) $tpl['arr'][$i]['is_visible'] === 1)
			                                        {
			                                            $rowClass = NULL;
			                                            $rowStyle = NULL;
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot', 'o_email_forgot_subject', 'o_email_forgot_message')))
			                                            {
			                                                $rowClass = " boxClient boxClient1";
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot_subject', 'o_email_forgot_message')))
			                                            {
			                                                $rowClass = " boxClient boxClient1 boxClient1-Sub boxClient-o_email_forgot";
			                                                $rowStyle = "display: none";
			                                                switch ($tpl['option_arr']['o_email_forgot'])
			                                                {
			                                                    case '1':
			                                                        $rowStyle = NULL;
			                                                        break;
			                                                }
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_temporary_password', 'o_email_temporary_password_subject', 'o_email_temporary_password_message')))
			                                            {
			                                                $rowClass = " boxClient boxClient2";
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_temporary_password_subject', 'o_email_temporary_password_message')))
			                                            {
			                                                $rowClass = " boxClient boxClient2 boxClient2-Sub boxClient-o_email_temporary_password";
			                                                $rowStyle = "display: none";
			                                                switch ($tpl['option_arr']['o_email_temporary_password'])
			                                                {
			                                                    case '1':
			                                                        $rowStyle = NULL;
			                                                        break;
			                                                }
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_after_failed_login_attempts', 'o_email_after_failed_login_attempts_subject', 'o_email_after_failed_login_attempts_message')))
			                                            {
			                                                $rowClass = " boxClient boxClient3";
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_after_failed_login_attempts_subject', 'o_email_temporary_password_subject_subject')))
			                                            {
			                                                $rowClass = " boxClient boxClient3 boxClient3-Sub boxClient-o_email_after_failed_login_attempts";
			                                                $rowStyle = "display: none";
			                                                switch ($tpl['option_arr']['o_email_after_failed_login_attempts'])
			                                                {
			                                                    case '1':
			                                                        $rowStyle = NULL;
			                                                        break;
			                                                }
			                                            }
			                                            $label = NULL;
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot', 'o_email_temporary_password', 'o_email_after_failed_login_attempts')))
			                                            {
			                                                $label = __('script_lbl_send_this_notification', true);
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot_subject', 'o_email_temporary_password_subject', 'o_email_after_failed_login_attempts_subject')))
			                                            {
			                                                $label = __('script_lbl_subject', true);
			                                            }
			                                            if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot_message', 'o_email_temporary_password_message', 'o_email_after_failed_login_attempts_message')))
			                                            {
			                                                $label = __('script_lbl_message', true);
			                                            }
			                                            ?>
		                                            	<div class="form-group<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
		                                            		<label class="col-lg-3 col-md-4 control-label"><?php echo $label; ?></label>
		                                            		<div class="col-lg-9 col-md-8">
			                                            		<?php
			                                            		switch ($tpl['arr'][$i]['type']) {
			                                            		    case 'string':
			                                            		        if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot_subject', 'o_email_temporary_password_subject', 'o_email_after_failed_login_attempts_subject')))
			                                            		        {
			                                            		            foreach ($tpl['lp_arr'] as $v)
			                                            		            {
			                                            		                ?>
																				<div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
																					<input type="text" name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="form-control" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?>" />	
																					<?php if ($tpl['is_flag_ready']) : ?>
																					<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
																					<?php endif; ?>
																				</div>
																				<?php
																			}
					                                            		}
			                                            		    break;
                                                                    case 'text':
                                                                        if (in_array($tpl['arr'][$i]['key'], array('o_email_forgot_message', 'o_email_temporary_password_message', 'o_email_after_failed_login_attempts_message')))
                                                                        {
                                                                            foreach ($tpl['lp_arr'] as $v)
                                                                            {
                                                                                ?>
																				<div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
																					<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="form-control mceEditor" style="width: 400px; height: 260px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
																					<?php if ($tpl['is_flag_ready']) : ?>
																					<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
																					<?php endif; ?>
																				</div>
																				<?php
																			}
																		}
																	break;
                                                                    case 'enum':
                                                                        ?>
					                                            		<select name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="form-control" style="display:none;">
																		<?php
																		$default = explode("::", $tpl['arr'][$i]['value']);
																		$enum = explode("|", $default[0]);
																		
																		$enumLabels = array();
																		if (!empty($tpl['arr'][$i]['label']) && strpos($tpl['arr'][$i]['label'], "|") !== false)
																		{
																			$enumLabels = explode("|", $tpl['arr'][$i]['label']);
																		}
																		
																		foreach ($enum as $k => $el)
																		{
																			if ($default[1] == $el)
																			{
																				?><option value="<?php echo $default[0].'::'.$el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
																			} else {
																				?><option value="<?php echo $default[0].'::'.$el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
																			}
																		}
																		?>
																		</select>
					                                            		<div class="switch m-t-xs">
							                                                <div class="onoffswitch onoffswitch-data">
							                                                    <input class="onoffswitch-checkbox" id="switch_<?php echo $tpl['arr'][$i]['key'];?>"<?php echo $tpl['arr'][$i]['value'] == '0|1::1' ? ' checked="checked"' : NULL;?> data-key="<?php echo $tpl['arr'][$i]['key']; ?>" data-group="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" type="checkbox">
							                                                    <label class="onoffswitch-label" for="switch_<?php echo $tpl['arr'][$i]['key'];?>">
							                                                        <span class="onoffswitch-inner" data-on="<?php __('script_yesno_ARRAY_T', false, true); ?>" data-off="<?php __('script_yesno_ARRAY_F', false, true); ?>"></span>
							                                                        <span class="onoffswitch-switch"></span>
							                                                    </label>
							                                                </div>
							                                            </div>
					                                            		<?php
						                                            	break;
			                                            		}
			                                            		?>
			                                            	</div><!-- /.col-lg-9 col-md-8 -->
		                                            	</div><!-- /.form-group -->
			                                            <?php
			                                        }
			                                    }
			                                    ?>
											</div><!-- /.col-lg-8 -->
											<div class="col-lg-4">
												<?php
												
												?>
											</div><!-- /.col-lg-4 -->
										</div><!-- /.row -->
										<div class="hr-line-dashed"></div>
										
										<div class="clearfix">
                                        	<button type="submit" class="ladda-button btn btn-primary btn-lg pull-left btn-phpjabbers-loader" data-style="zoom-in">
                                                <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
                                                <strong class="phpjabbers-loader ladda-spinner">
                                                    <span class="load-1"><img src="<?php echo $logo_path;?>phpjabbers-logo-1,3-4.png" alt=""></span>
                                                    <span class="load-2"><img src="<?php echo $logo_path;?>phpjabbers-logo-2-4.png" alt=""></span>
                                                    <span class="load-3"><img src="<?php echo $logo_path;?>phpjabbers-logo-1,3-4.png" alt=""></span>
                                                    <span class="load-4"><img src="<?php echo $logo_path;?>phpjabbers-logo-4-4.png" alt=""></span>
                                                </strong>
                                            </button>
                        				</div><!-- /.clearfix -->
										
									</div><!-- /.panel-body -->
								</div><!-- /.tab-pane -->
								
								<div role="tabpanel" class="tab-pane<?php echo $tab_id == 'admin' ? ' active' : NULL;?>" id="admin">
									<div class="panel-body">
									</div><!-- /.panel-body -->
								</div><!-- /.tab-pane -->
							</div><!-- /.tab-content -->
						</div><!-- /.tabs-container tabs-reservations m-b-lg -->
	            	</form>
	            </div><!-- /.col-lg-12 -->
	            <?php
	        }
	    }
	}
	?>
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};

var myLabel = myLabel || {};
<?php if ($tpl['is_flag_ready']) : ?>
	var pjCmsLocale = pjCmsLocale || {};
	pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
</script>