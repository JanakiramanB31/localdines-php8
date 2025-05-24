<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoConfirmationTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoConfirmationDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight" id="boxNotificationsWrapper">
	<div class="ibox float-e-margins settings-box">
		<div class="ibox-content ibox-heading">
			<h3><?php __('notifications_main_title'); ?></h3>
			<small><?php __('notifications_main_subtitle'); ?></small>
		</div>

		<div class="ibox-content">
			<div class="row">
				<div class="col-lg-3 col-sm-5">
					<div class="m-b-sm">
						<div class="row">
							<div class="col-sm-12">
							<h3><?php __('notifications_recipient'); ?></h3>
							</div>
						</div>
					</div>
			
					<div class="form-group">
						<div class="radio radio-primary">
							<input type="radio" id="recipient_client" name="recipient" value="client"<?php echo !isset($tpl['query']['recipient']) || $tpl['query']['recipient'] == 'client' ? ' checked' : NULL; ?>>
							<label for="recipient_client"><?php __('recipients_ARRAY_client'); ?></label>
						</div>
					</div>

					<div class="form-group">
						<div class="radio radio-primary">
							<input type="radio" id="recipient_admin" name="recipient" value="admin"<?php echo isset($tpl['query']['recipient']) && $tpl['query']['recipient'] == 'admin' ? ' checked' : NULL; ?>>
							<label for="recipient_admin"><?php __('recipients_ARRAY_admin'); ?><br /></label>
						</div>
					</div>
				</div>

				<div class="col-lg-9 col-sm-7" id="boxNotificationsMetaData">
				
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-9" id="boxNotificationsContent">
		   
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