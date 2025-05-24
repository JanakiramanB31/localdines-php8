<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
        	<div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php echo "Special Instructions";?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php //if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php //endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php echo "Below is a list of all special instructions added to the system. Use the tab above to add new special instruction or edit one by clicking on the pencil icon of each row. You can organize special instructions with drag & drop.";?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-8">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                	<div class="col-md-4">
                    	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpecialInstructions&action=pjActionIndexTypes" class="btn btn-primary"><i class="fa fa-eye"></i> <?php echo "View Types" ?></a>
                        <a href="#" class="btn btn-primary pjFdAddSpecialInstruction"><i class="fa fa-plus"></i> <?php echo "Add Spcl Ins" ?></a>
                    </div><!-- /.col-md-6 -->
                   
                    <div class="col-md-4 col-sm-4">
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
                	<?php
                	$filter = __('filter', true);
                	?>
                    <div class="col-md-4 text-right">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-primary btn-all active"><?php __('lblAll'); ?></button>
                            <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="T"><i class="fa fa-check m-r-xs"></i><?php echo $filter['active']; ?></button>
                            <button type="button" class="btn btn-default btn-filter" data-column="status" data-value="F"><i class="fa fa-times m-r-xs"></i><?php echo $filter['inactive']; ?></button>
                        </div>
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
                
                <div id="grid"></div>
                
            </div>
        </div>
    </div><!-- /.col-lg-8 -->

    <div class="col-lg-4">
        <div id="pjFdFormWrapper" class="panel no-borders">
        	
        </div><!-- /.panel panel-primary -->
    </div><!-- /.col-lg-3 -->
</div>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.instructions = "<?php echo "Instructions" ?>";
myLabel.image = "<?php echo "Image" ?>";
myLabel.products = <?php x__encode('lblProducts'); ?>;
myLabel.order = <?php x__encode('lblOrder'); ?>;
myLabel.status = <?php x__encode('lblStatus'); ?>;
myLabel.active = <?php x__encode('filter_ARRAY_active'); ?>;
myLabel.inactive = <?php x__encode('filter_ARRAY_inactive'); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.trigger_create = <?php echo $controller->_get->toInt('create'); ?>;
</script>
<?php //if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
// var myLabel = myLabel || {};
// var pjCmsLocale = pjCmsLocale || {};
// pjCmsLocale.langs = <?php //echo $tpl['locale_str']; ?>;
// pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php //endif; ?>