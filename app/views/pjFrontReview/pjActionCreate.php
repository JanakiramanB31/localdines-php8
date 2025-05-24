<?php
//print_r($tpl['product_arr']);
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : '';
//print_r($CLIENT);
if ($CLIENT != '') {
    $user = "client";
} else {
    $user = "guest";
}

?>
    <div id="review_container">    <!--Breadcrubs start-->
        <div style="margin-top: 10px;">
            <div class="container review_container-product">
                <div class="row">
                    <div id="reviewItem" class="col-md-12">
                        <div class="breadcurbs-inner text-center">
                            <h3 class="breadcrubs-title">
                               Review
                            </h3>
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php if ($tpl['product_arr'] && array_key_exists(0, $tpl['product_arr'])) { ?>
                        <h4><?php echo $tpl['product_arr'][0]['name']; ?></h4>
                        <?php } ?>
                        <!-- <img src="<?php //echo $img_path.$prd_info[0]['image']; ?>" class="product-image"> -->
                        <!-- <img src="image.jpg" class="product-image"> -->
                    </div>
                </div>
            </div>
        </div>
        <!--Breadcrubs end-->
        <!--elements start-->
        <div class="elements" style="padding-bottom: 50px;">
            <div class="container">
                <div class="row">
        			<div class="food-item-tab home-page">
        				<div class="col-md-12">
                            <form id="reviewFrm" method="post" action="<?php echo APP_URL; ?>index.php?controller=pjFrontReview&amp;action=pjActionCreate">
                                <input type="hidden" name="review_create" value="1">
                                <input type="hidden" name="product_id" value="<?php echo $tpl['product_arr'][0]['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $tpl['review_info'][2] == 'Main'? 'Web' : 'Qr'; ?>">
                                
                                <?php if ($user == 'client') { ?>
                                <input type="hidden" name="user_type" value="<?php echo "client"; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $CLIENT['id']; ?>">
                            <?php } else { ?> 
                                <input type="hidden" name="user_type" value="<?php echo "guest"; ?>">
                            <?php } ?>
                                <div id="review-rate">
                                    <div>
                                        <h3>Rate Our product</h3>
                                        <div class="food-review-rate" style="">
                                            <a class="fa fa-star star-one" aria-hidden="true"></a>
                                            <a class="fa fa-star star-two" aria-hidden="true"></a>
                                            <a class="fa fa-star star-three" aria-hidden="true"></a>
                                            <a class="fa fa-star star-four" aria-hidden="true"></a>
                                            <a class="fa fa-star star-five" aria-hidden="true"></a>
                                            <input type="hidden" name="rating" id="ratingJs" value="<?php echo $tpl['review_info'][0]; ?>">
                                        </div>
                                        
                                    </div>
                                    <div>
                                    <?php if ($tpl['product_arr'] && $tpl['product_arr'][0]["cnt_reviews"] > 0) { ?>
                                        <a href="#" class="btn btn-review" data-product="<?php echo $tpl['product_arr'][0]['id']; ?>" id="all_reviews">View all reviews</a>
                                    <?php } ?>
                                    </div>
                                    
                                </div>
                                <div id="hiddenDiv" style="display: <?php echo $tpl['review_info'][1] == 'link' ? 'none' : 'block'; ?>;margin-top: 10px;">

                                    <div id="review-msg" class="form-group">
                                        <!-- <label>Tell us about our product</label> -->
                                        <textarea name="review" class="form-control" rows="8" placeholder="Write us about our product..."></textarea>
                                       <!--  <span id="review-msg_error" class="text-danger" style="display: none;margin-top: 10px;"></span> -->
                                    </div>
                                    <div id="review-title" class="form-group">
                                        <!-- <label>Add Review Title</label> -->
                                        <input type="text" name="review_title" class="form-control" placeholder="Review title">
                                        <!-- <span id="review-title_error" class="text-danger" style="display: none;margin-top: 10px;"></span> -->
                                    </div>
                                    <?php 
                                    
                                    if ($user == "guest") { ?>
                                        <input type="hidden" name="guest_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                                        <div class="form-group">
                                            <!-- <label>Username</label> -->
                                            <input type="text" name="guest_un" class="form-control" placeholder="Enter your name">
                                        </div>
                                        <div class="form-group">
                                            <!-- <label>EmailAddress</label> -->
                                            <input type="email" name="guest_email" class="form-control" placeholder="Enter your email ID">
                                        </div>
                                    <?php } ?>
                                    <!-- <div id="review_terms" class="form-group">
                                        <input type="checkbox" name="terms">
                                        <p>I accept the <a href="terms.php">Terms of Use</a> & <a href="privacy.php">Privacy Policy</a></p>
                                        <span id="review-terms_error" class="text-danger" style="display: none;margin-top: 10px;">Please accept our terms and policy!</span>
                                    </div> -->
                                    <div id="review_terms" class="form-group checkoutCheckbox">
								
								<div class="d-flex justify-content-start">
									<div class="checkBox">
										<input id="fdAgree" name="terms" type="checkbox" class="required" data-err="<?php __('front_agree_required');?>" style="margin-top: 0px;display:none;" />
										<label id="checkoutCheck" for="fdAgree"></label>
									</div>
									<label id="termsLabel"><?php __('front_agree');?>&nbsp;<a href="#" id="terms" data-target="#pjFdTermsAndConditions" data-toggle="modal"><?php __('front_terms_conditions');?></a></label>
									
								</div>
                                <span id="review-terms_error" class="text-danger" style="display: none;margin-top: 10px;">Please accept our terms and policy!</span>
								<!-- <div class="help-block with-errors"><ul class="list-unstyled"></ul></div> -->
							</div><!-- /.form-group -->
                                    <div class="text-center">
                                        <button name="submit" type="submit" class="">Submit</button>
                                    </div>
                                    

                                    
                                    
                                </div>
                                
                                
                            </form>
						</div>
        			</div>
        		</div>
            </div>
        </div>
        <!--elements end-->
    </div>
<script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>review.js"></script>
<!-- <script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>stars.js"></script> -->
<script>
     $("#review_terms input").click(function() {
      if ($(this).prop("checked")) {
        $("#review-terms_error").css("display","none");
      } else {
        $("#review-terms_error").css("display","block");
      }
    })
</script>