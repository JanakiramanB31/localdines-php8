<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <?php //print_r($tpl['user_info']); ?>
        <div class="row">
            <div class="col-sm-10">
                <h2><?php echo "Update Review";?></h2>
            </div>
        </div><!-- /.row -->
    </div><!-- /.col-md-12 -->
</div>
<?php
$u_statarr = __('u_statarr', true)
?>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('AC01', 'AC03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('AC04', 'AC08')):
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
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReviews&amp;action=pjActionUpdate" method="post" id="frmUpdateReview">
                	<input type="hidden" name="review_update" value="1" />
                	<input type="hidden" name="id" value="<?php echo (int) $tpl['arr']['id']; ?>" />
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblStatus'); ?></label>

                                <div class="clearfix">
                                    <div class="switch onoffswitch-data pull-left">
                                        <div class="onoffswitch">
                                            <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status"<?php echo $tpl['arr']['status']=='T' ? ' checked' : NULL;?>>
                                            <label class="onoffswitch-label" for="status">
                                                <span class="onoffswitch-inner" data-on="<?php echo "Aprove";?>" data-off="<?php echo "Disaprove";?>"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- /.clearfix -->
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
					</div>
					
					<div class="row">
                        <?php if ($tpl['arr']['user_type'] == "guest"): ?>

                            <div class="col-lg-12 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo "User"; ?></label>
                                    <input type="text" name="user_type" value="Guest" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                                   
                                </div>
                            </div><!-- /.col-md-3 -->
                            
                        <?php elseif ($tpl['arr']['user_type'] == "client"): ?>

                            <div class="col-lg-6 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo "User"; ?></label>
                                    <input type="text" name="name" value="<?php echo $tpl['user_info']['name']; ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                                </div>
                            </div><!-- /.col-md-3 -->
                            <div class="col-lg-6 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Email ID"; ?></label>
                                    <input type="text" name="name" value="<?php echo $tpl['user_info']['email']; ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                                   
                                </div>
                            </div> <!-- /.col-md-3 -->

                        <?php endif ?>
                        
                        <div class="col-lg-6 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Product"; ?></label>
                                <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['product_name'])); ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                               
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-6 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Type"; ?></label>

                                <input type="text" id="product_type" name="type" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['type'])); ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                         <div class="col-lg-6 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Table No"; ?></label>

                                <input type="text" id="c_name" name="surname" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['table_number'])); ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                    
                        
						<div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Rating"; ?></label>

                                    <input type="text" name="user_type" value="<?php echo $tpl['arr']['rating']; ?>" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                                    
                                

                           </div>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-6 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Review"; ?></label>
                                
                               
                                <textarea rows="8"  name="c_email" id="email" 
                                class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>"><?php echo htmlspecialchars(stripslashes($tpl['arr']['review'])); ?></textarea>
                                
                            </div>
                        </div><!-- /.col-md-3 -->
                    </div>
                        

                   

                    <div class="clearfix">
                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                            <span class="ladda-label"><?php __('btnSave'); ?></span>
                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        </button>
                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminClients&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                    </div><!-- /.clearfix -->
                </form>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">

</script>