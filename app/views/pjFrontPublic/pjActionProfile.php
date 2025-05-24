<div class="fdLoader"></div>
<?php 
$index = $controller->_get->toString('index');
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();
?>
<br />
<div class="container">
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob">
		
		<div class="panel panel-default">
			<?php //include_once dirname(__FILE__) . '/elements/header.php';?>
			
			<div class="panel-body  pjFdPanelBody">
				<form id="fdProfileForm_<?php echo $index;?>" action="" method="post" class="form-horizontal" data-toggle="validator">
					<input type="hidden" name="profile" value="1" />
					<input type="hidden" name="id" value="<?php echo $CLIENT['id'];?>" />
					
					<div class="alert alert-success alert-dismissible pjFdAlert" role="alert" style="display:none;">
						<i class="fa fa-info-circle"></i> 
						<span id="fdProfileMessage_<?php echo $index;?>"></span>
					</div>
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_title'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<select name="c_title" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_title'] == 3) ? ' required' : NULL; ?>" dat-msg-required="<?php __('front_title_required');?>">
								<option value="">----</option>
								<?php
								$title_arr = pjUtil::getTitles();
								$name_titles = __('personal_titles', true, false);
								foreach ($title_arr as $v)
								{
									?><option value="<?php echo $v; ?>"<?php echo isset($CLIENT['c_title']) ? (@$CLIENT['c_title'] == $v ? ' selected="selected"' : NULL) :  NULL; ?>><?php echo $name_titles[$v]; ?></option><?php
								}
								?>
							</select>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php echo "First Name"; ?></label>

						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" class="form-control required" name="name" value="<?php echo isset($CLIENT['name']) ? htmlspecialchars(stripslashes(@$CLIENT['name'])) : NULL; ?>" data-msg-required="<?php __('front_name_required');?>"/>

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php echo "Surname"; ?></label>

						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" class="form-control required" name="surname" value="<?php echo isset($CLIENT['u_surname']) ? htmlspecialchars(stripslashes(@$CLIENT['u_surname'])) : NULL; ?>" data-msg-required="<?php __('front_name_required');?>"/>

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_email'); ?></label>

						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="email" class="form-control email required" name="email" value="<?php echo isset($CLIENT['email']) ? htmlspecialchars(stripslashes(@$CLIENT['email'])) : NULL; ?>" data-msg-required="<?php __('front_email_address_required');?>" data-msg-email="<?php __('front_email_not_valid');?>"/>

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_password'); ?></label>

						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="password" name="password" value="<?php echo isset($CLIENT['password']) ? htmlspecialchars(stripslashes(@$CLIENT['password'])) : NULL; ?>" class="form-control required" data-msg-required="<?php __('front_password_required');?>">
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_phone'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="phone" class="form-control" value="<?php echo isset($CLIENT['phone']) ? htmlspecialchars(stripslashes(@$CLIENT['phone'])) : NULL; ?>" data-msg-required="<?php __('front_phone_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_company'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_company" class="form-control" value="<?php echo isset($CLIENT['c_company']) ? htmlspecialchars(stripslashes(@$CLIENT['c_company'])) : NULL; ?>" data-msg-required="<?php __('front_company_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_notes'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<textarea name="c_notes" class="form-control" style="height: 60px;" data-msg-required="<?php __('front_notes_required');?>"><?php echo isset($CLIENT['c_notes']) ? htmlspecialchars(stripslashes(@$CLIENT['c_notes'])) : NULL; ?></textarea>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_address_line_1'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_address_1" class="form-control" value="<?php echo isset($CLIENT['c_address_1']) ? htmlspecialchars(stripslashes(@$CLIENT['c_address_1'])) : NULL; ?>" data-msg-required="<?php __('front_address1_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_address_line_2'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_address_2" class="form-control" value="<?php echo isset($CLIENT['c_address_2']) ? htmlspecialchars(stripslashes(@$CLIENT['c_address_2'])) : NULL; ?>" data-msg-required="<?php __('front_address2_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_city'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_city" class="form-control" value="<?php echo isset($CLIENT['c_city']) ? htmlspecialchars(stripslashes(@$CLIENT['c_city'])) : NULL; ?>" data-msg-required="<?php __('front_city_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label">County</label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_state" class="form-control" value="<?php echo isset($CLIENT['c_state']) ? htmlspecialchars(stripslashes(@$CLIENT['c_state'])) : NULL; ?>" data-msg-required="<?php __('front_state_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php echo "Postcode"; ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="post_code" class="form-control" value="<?php echo isset($CLIENT['c_postcode']) ? htmlspecialchars(stripslashes(@$CLIENT['c_postcode'])) : NULL; ?>" data-msg-required="<?php __('front_state_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<!-- <div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_zip'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<input type="text" name="c_zip" class="form-control" value="<?php echo isset($CLIENT['c_zip']) ? htmlspecialchars(stripslashes(@$CLIENT['c_zip'])) : NULL; ?>" data-msg-required="<?php __('front_zip_required');?>"/>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div> -->
					<!-- /.form-group -->
					<div class="form-group">
						<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label"><?php __('front_country'); ?></label>
						<div class="col-lg-9 col-md-8 col-sm-8">
							<select name="c_country" class="form-control" data-msg-required="<?php __('front_country_required');?>">
								<option value="">-- <?php __('front_choose'); ?> --</option>
								<?php
								foreach ($tpl['country_arr'] as $country)
								{
									?><option value="<?php echo $country['id']; ?>"<?php 
									//echo isset($CLIENT['c_country']) ? (@$CLIENT['c_country'] == $country['id'] || $country['country_title'] == 'United Kingdom' ? ' selected="selected"' : NULL) : NULL; 
									echo $country['id'] == 235 ? ' selected="selected"' : NULL;
									?>><?php echo stripslashes($country['country_title']); ?></option><?php
								}
								?>
							</select>
							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="submit" class="btn btn-default text-uppercase fdButtonProfile"><?php __('front_button_save');?></button>
							<button type="button" class="btn btn-default text-uppercase fdStartOver">Close</button>
							
						</div><!-- /.col-sm-offset-2 col-sm-10 -->
					</div><!-- /.form-group -->
				</form><!-- /.form-horizontal -->
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
	</div><!-- /.col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft -->
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->
</div>
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