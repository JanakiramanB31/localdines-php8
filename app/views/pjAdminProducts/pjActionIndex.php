<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-10">
        <h2 id="productIndexTitle"><?php __('infoProductsTitle', false, true);?></h2>
      </div>
    </div><!-- /.row -->
    <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoProductsDesc', false, true);?></p>
  </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <!-- <input type="hidden" id="updated_product_category" value="<?php //echo $_SESSION['updateProductCategory']; ?>">
    <input type="hidden" id="updated_product_page" value="<?php //echo $_SESSION['updateProductPage']; ?>"> -->
  	<?php
  	$error_code = $controller->_get->toString('err');
    $updated_category = $controller->_get->toString('category');
  	if (!empty($error_code)) {
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
  	  switch (true) {
        case in_array($error_code, array('AP01', 'AP03')):
    ?>
      		<div class="alert alert-success">
      			<i class="fa fa-check m-r-xs"></i>
      			<strong><?php echo @$titles[$error_code]; ?></strong>
      			<?php echo @$bodies[$error_code]?>
      		</div>
		  <?php
				break;
        case in_array($error_code, array('AP04', 'AP05', 'AP08', 'AP09')):
          $bodies_text = str_replace("{SIZE}", ini_get('post_max_size'), @$bodies[$error_code]);
		  ?>
  				<div class="alert alert-danger">
  					<i class="fa fa-exclamation-triangle m-r-xs"></i>
  					<strong><?php echo @$titles[$error_code]; ?></strong>
  					<?php echo $bodies_text;?>
  				</div>
			<?php
				break;
  		}
  	}
  	?>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row m-b-md">
          <div class="col-md-2">
          	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('btnAddProduct') ?></a>
          	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionUpload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</a>
          </div><!-- /.col-md-6 -->
          <div class="col-md-4 col-sm-3">
            <form action="" method="get" class="form-horizontal frm-filter">
              <div class="input-group">
                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div><!-- /.col-md-3 -->
          <?php
            $filter = __('filter', true);
          ?>
          <div class="col-md-6 text-right">
            <?php if (ORDER_TYPE) { ?>
            <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-primary btn-type active" data-column="order_type" data-value="eatin"><i class="fa fa-building m-r-xs"></i><?php echo "Eatin" ?></button>
              <button type="button" class="btn btn-default btn-type" data-column="order_type" data-value="takeaway"><i class="fa fa-shopping-bag m-r-xs"></i><?php echo "Takeaway" ?></button>
            </div>
          <?php } ?>
            <div class="btn-group" role="group" aria-label="...">
              <!-- <button type="submit" class="btn btn-default jsBtnStatus" id="all" value="all"><?php echo "All"; ?></button>
              <button type="submit" class="btn btn-default btn-filter jsBtnStatus" id="active" value="active" data-column="status" data-value="T"><i class="fa fa-check m-r-xs"></i><?php echo "Active"; ?></button>
              <button type="submit" class="btn btn-default btn-filter jsBtnStatus" id="inactive" value="inactive" data-column="status" data-value="F"><i class="fa fa-times m-r-xs"></i><?php echo "InActive"; ?></button> -->
              <button type="button" class="btn btn-primary btn-all active"><?php __('lblAll'); ?></button>
              <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="1"><i class="fa fa-check m-r-xs"></i><?php echo $filter['active']; ?></button>
              <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="0"><i class="fa fa-times m-r-xs"></i><?php echo $filter['inactive']; ?></button>
            </div>
            <select name="type" id="filter_category" class="form-control" style="width:auto; display: inline-block;">
              <option value="all">-- <?php echo "All"; ?> --</option>
              <?php 
              foreach($tpl['categories'] as $category) { ?>
                <option value="<?php echo $category['id'] ?>" <?php if($updated_category > 0 && $updated_category == $category['id']) { ?> selected="selected" <?php }  ?>><?php echo $category['name']; ?></option>
              <?php } ?>
            </select>
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
	      <div id="grid"></div>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
</div>

<script type="text/javascript">
  var pjGrid = pjGrid || {};
  pjGrid.queryString = "";
  <?php
  if ($controller->_get->toInt('category_id'))
  {
  ?>  pjGrid.queryString += "&category_id=<?php echo $controller->_get->toInt('category_id'); ?>";<?php
  }
  ?>
  var myLabel = myLabel || {};
  myLabel.image = <?php x__encode('lblImage'); ?>;
  myLabel.name = <?php x__encode('lblName'); ?>;
  myLabel.price = <?php x__encode('lblPrice'); ?>;
  myLabel.status = <?php x__encode('lblStatus'); ?>;
  myLabel.active = <?php x__encode('filter_ARRAY_active'); ?>;
  myLabel.inactive = <?php x__encode('filter_ARRAY_inactive'); ?>;
  myLabel.is_featured = "Hot Keys";
  //myLabel.is_featured = <?php echo "Hot Keys"; ?>;
  myLabel.yes = <?php x__encode('_yesno_ARRAY_T'); ?>;
  myLabel.no = <?php x__encode('_yesno_ARRAY_F'); ?>;
  myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
  myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
</script>