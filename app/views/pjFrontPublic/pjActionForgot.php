<?php //include_once dirname(__FILE__) . '/elements/navbar.php';?>
<div class="fdLoader"></div>
<?php $index = $controller->_get->toString('index');?>
<br />
<div class="container">
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob">
		
		<div class="login-form">
			<div id="forgot-password" class="text-center" style="border: 1px solid #ddd;">
				<form id="fdForgotForm_<?php echo $index;?>" action="" method="post" data-toggle="validator" class="form-horizontal p-a30 rdx-form text-center">
					<h3 class="form-title m-t0">Forgot Password ?</h3>
					<div class="rdx-separator-outer">
						<div class="rdx-separator style-liner"></div>
					</div>
					<p>Enter your e-mail address below to reset your password. </p>
					<div class="alert alert-warning alert-dismissible pjFdAlert" role="alert" style="display:none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-info-circle"></i> <span id="fdForgotMessage_<?php echo $index;?>"></span>
					</div>
					<div class="form-group" style="margin-bottom: 0px;">
					    <div class="col-md-12 col-sm-12">
							<input type="email" class="form-control" name="email" placeholder="<?php __('front_email'); ?>" data-required="<?php __('front_email_address_required');?>" data-email="<?php __('front_email_not_valid');?>"/>

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div>
					<div class="form-group text-left" style="margin-bottom: 0px;"> 
					    <div class="col-sm-12">
							<a class="site-button outline gray fdButtonGetLogin" style="color: #000;"><?php __('front_client_login');?></a>
							<button class="site-button pull-right fdButtonSend"><?php __('front_send_password');?></button>
                        </div>
					</div>
				</form>
			</div>
		</div><!-- /.panel panel-default -->
	</div><!-- /.col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft -->
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->
</div>
<?php //include_once dirname(__FILE__) . '/elements/footer.php';?>
<script>
	$(function() {
		if($(".food-item-desc").length > 0) {
			$(".search-me").css("display","block");
		} else {
			$(".search-me").css("display","none");
			if ($("#searchInput-group").css("display") == "flex") {
				$("#searchInput-group").css("display", "none");
				$(".logo").css("display", "block");
			}
		}
	})

</script>