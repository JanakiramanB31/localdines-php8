<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2>Upload CSV</h2>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionUpload" method="post" id="frmCreateUpload" autocomplete="off" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Products CSV File</label>
                <div>
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> select csv file</span>
                    <span class="fileinput-exists"></span><input name="csv_file" type="file"></span>
                    <span class="fileinput-filename"></span>
                  </div>
                </div>
              </div><!-- /.form-group -->
            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="clearfix">
              <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                  <span class="ladda-label"><?php __('btnSave'); ?></span>
                  <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
              </button>
              <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminProducts&action=pjActionIndex"><?php __('btnCancel'); ?></a>
          </div><!-- /.clearfix -->
        </form>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
</div>