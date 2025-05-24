<div class="row wrapper border-bottom white-bg page-heading">
  
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2>  Add new Master<?php //__('infoAddProductTitle');?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div> 
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 col-lg-offset-3 col-lg-6 ">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMasters&amp;action=pjActionCreate" method="post" id="frmCreateMaster" autocomplete="off">
          <?php
          include PJ_VIEWS_PATH . 'pjAdminMasters/elements/pjMasterFormElement.php';
          ?>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  table .form-group{
    margin-bottom: 0px !important;
  }
</style>
<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.size = <?php x__encode('lblSize'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
</script>
