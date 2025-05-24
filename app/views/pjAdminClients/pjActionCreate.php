<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('infoCreateClientTitle', false, true);?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoCreateClientDesc', false, true);?></p>
    </div><!-- /.col-md-12 -->
</div>
<?php
$u_statarr = __('u_statarr', true)
?>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionCreate" method="post" id="frmCreateClient">
                	<input type="hidden" name="client_create" value="1" />
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblStatus'); ?></label>

                                <div class="clearfix">
                                    <div class="switch onoffswitch-data pull-left">
                                        <div class="onoffswitch">
                                            <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status" checked>
                                            <label class="onoffswitch-label" for="status">
                                                <span class="onoffswitch-inner" data-on="<?php echo $u_statarr['T'];?>" data-off="<?php echo $u_statarr['F'];?>"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- /.clearfix -->
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblTitle'); ?></label>

                                <select name="c_title" id="c_title" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
                					<option value="">-- <?php __('lblChoose'); ?>--</option>
                					<?php
                					$name_titles = __('personal_titles', true, false);
                                    unset($name_titles['rev']);
                					foreach ($name_titles as $k => $v)
                					{
                						?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
                					}
                					?>
                				</select>
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Firstname"; ?></label>

                                <input type="text" id="c_name" name="c_name" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Surname"; ?></label>

                                <input type="text" id="surname" name="surname" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblEmail'); ?></label>

								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-at"></i></span>
                                	<input type="text" name="c_email" id="email" class="form-control email" placeholder="info@domain.com"  maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('pass'); ?></label>
								
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                	<input type="text" name="c_password" id="c_password" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>" maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblPhone'); ?></label>
								
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                	<input type="text" name="c_phone" id="phone" class="form-control" placeholder="(123) 456-7890" maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblCompany'); ?></label>

                                <input type="text" name="c_company" id="c_company" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="hr-line-dashed"></div>
                    
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblAddressLine1'); ?></label>

                                <input type="text" name="c_address_1" id="c_address_1" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblAddressLine2'); ?></label>

                                <input type="text" name="c_address_2" id="c_address_2" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblCity'); ?></label>

                                <input type="text" name="c_city" id="c_city" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "County"; ?></label>

                                <input type="text" name="c_county" id="c_county" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo "Postcode"; ?></label>

                                <input type="text" name="post_code" id="c_zip" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblNotes'); ?></label>

                                <textarea id="c_notes" name="c_notes" rows="4" class="form-control"></textarea>
                            </div>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                    <!-- <div class="hr-line-dashed"></div> -->

                    <div class="row" style="background-color: #f3f3f4; padding: 10px;">
                        <div class="col-md-12">
                            <p>I would like to receive exclusive offers and receipts from Localdines</p>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="email_offer" disabled="disabled">
                                
                                <label class="form-check-label">Email Discount Codes/Offers</label>
                                
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-check">
                                <input type="checkbox" name="mobile_offer" class="form-check-input">
                                <label class="form-check-label">Mobile Discount Codes/Offers</label>
                               
                                
                            </div>
                        </div>
                        
                    </div>

                    <div class="hr-line-dashed"></div>

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
var myLabel = myLabel || {};
myLabel.email_exists = <?php x__encode('email_taken'); ?>;
myLabel.phoneno_exists = "<?php echo 'Client with such phone number exists.'; ?>";
</script>