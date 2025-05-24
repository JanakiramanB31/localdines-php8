

<div class="wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
    {
    	$titles = __('plugin_base_error_titles', true);
    	$bodies = __('plugin_base_error_bodies', true);
    	switch (true)
    	{
    		case in_array($error_code, array('PSS01')):
    			?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]?>
    			</div>
    			<?php 
    			break;
    		case in_array($error_code, array('')):	
    			?>
    			<div class="alert alert-danger">
    				<i class="fa fa-exclamation-triangle m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]?>
    			</div>
    			<?php
    			break;
    	}
    }
    ?>
<div class="tabs-container tabs-reservations m-b-lg">
  <div class="tab-content">
		<div class="form-group">					
            <?php //if ($tpl['has_access_list']): ?>
                <div role="tabpanel" class="tab-pane<?php echo $tpl['has_access_settings']? null: ' active'; ?>" id="message-sent">
                    <div class="panel-body ibox-content">
                        <div class="row m-b-md">
                            <div class="col-md-4 col-md-offset-4">
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
                        </div><!-- /.row -->

                        <div id="grid"></div>

                    </div>
                </div>
            <?php //endif; ?>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};
var myLabel = myLabel || {};
myLabel.created = <?php x__encode('plugin_base_sms_date_time_sent'); ?>;
myLabel.number = <?php x__encode('plugin_base_sms_number'); ?>;
myLabel.text = <?php x__encode('plugin_base_sms_message'); ?>;
myLabel.status = <?php x__encode('plugin_base_sms_status'); ?>;
                          
myLabel.test_sms_title = <?php x__encode('plugin_base_sms_test_sms_title'); ?>;
myLabel.test_sms_text = <?php x__encode('plugin_base_sms_test_sms_text'); ?>;
myLabel.test_sms_number =  <?php x__encode('plugin_base_sms_number'); ?>;
myLabel.btn_send_sms = <?php x__encode('plugin_base_btn_send_sms'); ?>;
myLabel.btn_cancel = <?php x__encode('plugin_base_btn_cancel'); ?>;
</script>