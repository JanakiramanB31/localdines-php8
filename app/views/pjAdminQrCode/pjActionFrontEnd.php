<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2>Front End Settings</h2>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
      </div>
    </div><!-- /.row -->
    <p class="m-b-none"><i class="fa fa-info-circle"></i>Use the form below to control front end settings of your system.</p>
  </div><!-- /.col-md-12 -->
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <form id="frmUpdateQrOptions" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminQrCode&amp;action=pjActionUpdate" class="form-horizontal" method="post" enctype="multipart/form-data">
            <input type="hidden" name="qr_settings_update" value="1" />
            <div class="row">
              <div class="col-lg-11">
              	<div class="form-group">
              		<label class="col-lg-3 col-md-4 control-label">Frontend Menu Review</label>
              		<div class="col-lg-9 col-md-8">
              			<div class="clearfix">
                      <div class="switch onoffswitch-data pull-left">
                        <div class="onoffswitch onoffswitch-front_qrMenu">
                          <input type="checkbox" class="onoffswitch-checkbox" id="front_qr" name="front_qr" <?php echo $tpl['arr'][0]['value'] == 1 ? "checked" : NULL; ?>>
                          <label class="onoffswitch-label" for="front_qr">
                            <span class="onoffswitch-inner" data-on="enable" data-off="disable"></span>
                            <span class="onoffswitch-switch"></span>
                          </label>
                        </div>
                      </div>
				            </div>
              		</div>
              	</div>
                <div class="form-group">
                  <label class="col-lg-3 col-md-4 control-label">Qr Scan Image</label>
                  <div class="col-lg-9 col-md-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
                      <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="qr_image" type="file"></span>
                      <span class="fileinput-filename"></span>
                      <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                    </div>
                      <!-- <div>
                          <p><b>Size of Product Image:</b> 270 * 220</p>
                      </div> -->
                  </div>
                  <div class="col-lg-3 col-md-4"></div>
                  <div class="col-lg-9 col-md-8">
                    <p><b>Size of Qr Image:</b> 270 * 270</p>
                  </div>
                </div>
                <?php 
                if (!empty($tpl['arr'][1]['value']) && is_file(PJ_INSTALL_PATH . $tpl['arr'][1]['value'])) {
                ?>
                  <div class="form-group">
                    <div class="col-md-4 col-lg-3"></div>
                    <div class="col-lg-9 col-md-8">
                      <a download="<?php echo PJ_INSTALL_URL . $tpl['arr'][1]['value']; ?>" href="<?php echo PJ_INSTALL_URL . $tpl['arr'][1]['value']; ?>">
                      <img src="<?php echo PJ_INSTALL_URL . $tpl['arr'][1]['value']; ?>?r=<?php echo rand(1,9999); ?>" alt="" id="qr-image" class="align_middle" style="max-width: 180px; margin-right: 10px;"></a>
                    </div>
                  </div>
              <?php } ?>
                <div class="form-group">
                  <label class="col-lg-3 col-md-4 control-label">Qr Page Url</label>
                  <div class="col-lg-9 col-md-8">
                    <input type="text" name="qr_url" class="form-control" value="<?php echo $tpl['arr'][2]['value'] ?>" style="width: 50%;">
                        <!-- <div>
                            <p><b>Size of Product Image:</b> 270 * 220</p>
                        </div> -->
                  </div>
                </div>
              </div><!-- /.col-lg-8 -->
            </div><!-- /.row -->
            <div class="hr-line-dashed"></div>
              <div class="row">
              	<div class="col-lg-11">
              		<div class="row">
              			<div class="col-lg-9 col-lg-offset-3 col-md-8 col-md-offset-4">
                      <button type="submit" id="btn-save-settings" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader" data-style="zoom-in">
                        <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
                        <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                      </button>
    								</div>
                  </div>
                </div>
            </div><!-- /.clearfix -->
          </form>
        </div>
      </div>
    </div><!-- /.col-lg-12 -->
  </div>
</div>
