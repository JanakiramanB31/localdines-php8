<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <h2><?php __('infoPrintTemplateTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>    
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoPrintTemplateDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
	{
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
	    switch (true)
	    {
	        case in_array($error_code, array('AO07')):
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
	<div class="row">
		<div class="col-lg-9">
		   <div class="ibox-content">
		   		<?php
		   		if (isset($tpl['arr']) && is_array($tpl['arr']) && !empty($tpl['arr']))
		   		{
		   		    $locale = $controller->_get->toInt('locale') ?: NULL;
		   		    if (is_null($locale))
		   		    {
		   		        foreach ($tpl['lp_arr'] as $v)
		   		        {
		   		            if ($v['is_default'] == 1)
		   		            {
		   		                $locale = $v['id'];
		   		                break;
		   		            }
		   		        }
		   		    }
		   		    if (is_null($locale))
		   		    {
		   		        $locale = @$tpl['lp_arr'][0]['id'];
		   		    }
		   		    ?>
		   		    <form id="frmUpdateOptions" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" class="form-horizontal" method="post">
                        <input type="hidden" name="options_update" value="1" />
                        <input type="hidden" name="next_action" value="pjActionPrintOrder" />

                        <div class="row">
                            <div class="col-lg-11">
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-5 control-label"><?php __('opt_o_print_order') ?></label>

                                    <div class="col-lg-10 col-md-7 mce-md">
                                        <?php
                                        foreach ($tpl['lp_arr'] as $v)
                                        {
                                            ?>
                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : 'table'; ?>">
                                                <textarea name="i18n[<?php echo $v['id']; ?>][o_print_order]" class="form-control mceEditor" style="width: 400px; height: 260px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['o_print_order'])); ?></textarea>
                                                <?php if ($tpl['is_flag_ready']) : ?>
                                                <span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div><!-- /.col-lg-8 -->
                        </div><!-- /.row -->

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                        	<div class="col-lg-11">
                        		<div class="row">
                        			<div class="col-lg-10 col-lg-offset-2 col-md-7 col-md-offset-5">
			                            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader" data-style="zoom-in">
			                                <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
			                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
			                            </button>
									</div>
	                            </div>
                            </div>
                        </div><!-- /.clearfix -->
                    </form>
		   		    <?php
		   		}
		   		?>
		   </div>
		</div>
	
		<div class="col-lg-3">
			<div class="ibox float-e-margins settings-box">
				<div class="ibox-content ibox-heading">
					<h3><?php __('notifications_tokens'); ?></h3>
	
					<small><?php __('notifications_tokens_note'); ?></small>
				</div>
	
				<div class="ibox-content">
					<div class="row">
						<div class="col-xs-6">
							<div><small>{Name}</small></div>
							<div><small>{Email}</small></div>
        					<div><small>{Password}</small></div>
							<div><small>{Phone}</small></div>
							<div><small>{Company}</small></div>
							<div><small>{Notes}</small></div>
							<div><small>{Country}</small></div>
							<div><small>{City}</small></div>
							<div><small>{State}</small></div>
							<div><small>{Zip}</small></div>
							<div><small>{Address1}</small></div>
							<div><small>{Address2}</small></div>
							<div><small>[Delivery]..[/Delivery]</small></div>
							<div><small>{dCountry}</small></div>
							<div><small>{dCity}</small></div>
							<div><small>{dState}</small></div>
							<div><small>{dZip}</small></div>
						</div>
	
						<div class="col-xs-6">
							<div><small>{dAddress1}</small></div>
							<div><small>{dAddress2}</small></div>
							<div><small>{dNotes}</small></div>
							<div><small>{pNotes}</small></div>
							<div><small>{DateTime}</small></div>
							<div><small>{Location}</small></div>
							<div><small>{OrderID}</small></div>
							<div><small>{OrderDetails}</small></div>
							<div><small>{Subtotal}</small></div>
							<div><small>{PackingFee}</small></div>
							<div><small>{Delivery}</small></div>
							<div><small>{Discount}</small></div>
							<div><small>{Tax}</small></div>
							<div><small>{Total}</small></div>
							<div><small>{PaymentMethod}</small></div>
							<div><small>{CancelURL}</small></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>