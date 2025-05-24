<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12">
        <h2>List of Configs<?php //__('infoExtrasTitle');?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
      	  <div class="col-md-4">
          	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminConfigs&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> Add Config<?php //__('btnAddExtra') ?></a>
          </div><!-- /.col-md-6 -->
          <div class="col-md-4">
          	<form action="" method="get" class="form-horizontal frm-filter">
              <div class="input-group m-b-md">
                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
        <div id="grid"></div>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};
    pjGrid.queryString = "";
var myLabel = myLabel || {};
myLabel.date = "Date";
myLabel.name = "Key";
myLabel.value = "Value";
myLabel.status = "Status";
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.trigger_create = <?php echo $controller->_get->toInt('create'); ?>;
</script>