<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
        	<div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoExtrasTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoExtrasDesc', false, true);?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-8">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                	<div class="col-md-4">
                    	<a href="#" class="btn btn-primary pjFdAddExtra"><i class="fa fa-plus"></i> <?php __('btnAddExtra') ?></a>
                    </div><!-- /.col-md-6 -->
                    <div class="col-md-4 col-sm-8">
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
    </div><!-- /.col-lg-8 -->

    <div class="col-lg-4">
        <div id="pjFdFormWrapper" class="panel no-borders">
        	
        </div><!-- /.panel panel-primary -->
    </div><!-- /.col-lg-3 -->
</div>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.extra_name = <?php x__encode('lblExtraName'); ?>;
myLabel.products_used = <?php x__encode('lblProductsUsed'); ?>;
myLabel.price = <?php x__encode('lblPrice'); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.trigger_create = <?php echo $controller->_get->toInt('create'); ?>;
</script>
<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var myLabel = myLabel || {};
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>