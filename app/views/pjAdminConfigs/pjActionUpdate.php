<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2> Edit Config</h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div> 
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 col-lg-offset-3 col-lg-6">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminConfigs&amp;action=pjActionUpdate" method="post" id="frmUpdateConfig" autocomplete="off">
          <input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>">
          <?php
          include PJ_VIEWS_PATH . 'pjAdminConfigs/elements/pjConfigFormElement.php';
          ?>
        </form>
      </div>
    </div>
  </div>
</div>
