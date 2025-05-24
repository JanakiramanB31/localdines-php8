<div class="fdLoader"></div>
<?php $index = $controller->_get->toString('index');?>
<br />
<div class="container">
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob">
		<div  class="login-form">
			<?php //include_once dirname(__FILE__) . '/elements/header.php';?>
			<div id="login" class="text-center" style="border: 1px solid #ddd;">
				<form id="fdLoginForm_<?php echo $index;?>" action="" method="post" class="form-horizontal p-a30 rdx-form" data-toggle="validator">
					<h3 class="form-title m-t0">Members Login</h3>
					<div class="rdx-separator-outer">
						<div class="rdx-separator style-liner"></div>
					</div>
					<p>Enter your e-mail address and your password. </p>
					<input type="hidden" name="login_client" value="1"/>
					<div class="alert alert-warning alert-dismissible pjFdAlert" role="alert" style="display:none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-info-circle"></i> <span id="fdLoginMessage_<?php echo $index;?>"></span>
					</div>
					<div class="form-group" style="margin-bottom: 0px;">
					    <div class="col-md-12 col-sm-12">
							<input type="email" class="form-control" name="login_email" id="login_email" placeholder="<?php __('front_email'); ?>" value="<?php if(isset($_COOKIE['login_email'])) { echo $_COOKIE['login_email']; } ?>" data-required="<?php __('front_email_address_required');?>" data-email="<?php __('front_email_not_valid');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
						<!-- <input name="dzName" required="" class="form-control" placeholder="User Name" type="text"/> -->
					</div>
					<div class="form-group" style="margin-bottom: 0px;">
	 					<div class="col-md-12 col-sm-12">
							<input type="password" name="login_password" placeholder="<?php __('front_password'); ?>" value="<?php if(isset($_COOKIE['login_password'])) { echo $_COOKIE['login_password']; } ?>" class="form-control" data-required="<?php __('front_password_required');?>">
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div>
					<div class="form-group text-left">
						<div class="col-md-12 col-sm-12">
							<div class="text-center"><button class="site-button m-r5 fdButtonLogin"><?php __('front_button_login');?></button></div>
							<div class="d-flex justify-content-between" style="margin-top: 10px;">
								<div id="rememberMe" class="d-flex justify-content-around">
								    <div class="checkBox">
									    <input name="login_remember" class="d-none"  id="checkRemember" type="checkbox" style= "" <?php if(isset($_COOKIE['login_email'])) { ?>checked<?php } ?> />
										<label for="checkRemember"></label>
									</div>
									
									<label> Remember me</label>
									<!-- <label><h5> Remember me</h5></label> -->
								</div>
								
								<a href="#" class="m-l5 btn-link fdForogtPassword pjFdBtnLink" style="dfloat: right;"><i class="fa fa-unlock-alt"></i> Forgot Password?</a> 
                            </div>
						</div>
					</div>
					<!-- <div class="clearfix" style="margin-bottom: 10px;">
						<h5 class="form-title m-t5 pull-left" style="margin-right: 10px;">Sign In With</h5>
						<div id="my-signin2"></div>
					</div> -->
					<p id="errMsg" class="text-danger d-none"></p>
					<div class="bottom-links" style="">  
						<a class="btn btn-link fdContinue pjFdBtnLink"><?php //__('front_new_client');?>Signup</a>
				    <?php if (FRONTEND_GUEST_LOGIN_ALLOWED) { ?>
				    <span style="margin-top:10px">
				    	<a href="#" class="btn btn-link fdContinueGuest pjFdBtnLink"><strong><?php echo "Continue as a guest";?></strong></a> 
				    </span>
				    <?php  } ?>
					</div>
				</form>
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
	</div><!-- /.col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft -->
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->
	<div class="modal fade" id="loginError" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
					
				</div>
				<div class="modal-body">
					<p id="errMsg"></p>
				</div>
				<div class="modal-footer">
					
					<button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
				</div>
			</div>
		</div>
	</div>
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

				$('#login_email').on('focusout', function() {
					var mailId = $(this).val();
					$.ajax({
						type: "POST",
						async: false,
						url: $controller_url+"index.php?controller=pjFrontEnd&action=pjActionCheckMailId",
						data: {loginMail: mailId},
						success: function(data) {
							if (data.code == 500) {
								$("#errMsg").text("Please login with gmail!!!");
								$("#errMsg").removeClass('d-none');
								$('#login_email').parent().addClass('has-error');
							} else {
								$("#errMsg").text(" ");
								$("#errMsg").addClass('d-none');
								$('#login_email').parent().removeClass('has-error');
							}
						},
					});
				})


				function onSuccess(googleUser) {
          var auth2 = gapi.auth2.getAuthInstance();
          auth2.disconnect();
					console.log('Logged in as: ' + googleUser.getBasicProfile());
					$.ajax({
						type: "POST",
						async: false,
						url: $controller_url+"index.php?controller=pjFrontPublic&action=pjActionSocialLogin",
						data: {socialLogin: 'true', g_name: googleUser.getBasicProfile().getGivenName(),
							g_surname: googleUser.getBasicProfile().getFamilyName(), g_email: googleUser.getBasicProfile().getEmail()},
						success: function(data) {
							if (data.code == 200) {
								window.location.href = $frontend+"menu.php#!/loadTypes";

							} else {
							
								$("#errMsg").text("Please do web login!!!");
								$("#errMsg").removeClass('d-none');
							}
						},
					});
				}
				function onFailure(error) {
					console.log(error);
				}
				function renderButton() {
					gapi.signin2.render('my-signin2', {
						'scope': 'profile email',
						'width': 40,
						'height': 40,
						'longtitle': false,
						'theme': 'dark',
						'onsuccess': onSuccess,
						'onfailure': onFailure
					});
				}
				
				
			</script>
			<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>