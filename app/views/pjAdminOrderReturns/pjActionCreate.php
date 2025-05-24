<div class="row wrapper border-bottom white-bg page-heading">
  
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2>  Add new Return Product<?php //__('infoAddProductTitle');?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 col-lg-offset-2 col-lg-8 ">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrderReturns&amp;action=pjActionCreate" method="post" id="frmReturnOrder" autocomplete="off">
          <?php
          include PJ_VIEWS_PATH . 'pjAdminOrderReturns/elements/return_order_form.php';
          ?>
        </form>
      </div>
    </div>
  </div>
</div>