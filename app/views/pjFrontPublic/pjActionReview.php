<?php
print_r($tpl['review_info']);
$user = "guest";
?>
<?php //include 'qrHeader.php'; ?>
        <!--Breadcrubs start-->
        <div style="margin-top: 10px;">
            <div class="container">
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
                        <h4><?php echo $tpl['product_arr'][0]['name']; ?></h4>
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
                            <form id="reviewFrm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjFrontPublic&amp;action=pjActionReview">
                                <input type="hidden" name="product_id" value="<?php echo $prd_id; ?>">
                                <input type="hidden" name="type" value="<?php echo $tpl['review_info'][2] == 'Main'? 'Web' : 'Qr'; ?>">
                                <input type="hidden" name="user_type" value="<?php echo $user; ?>">
                                <?php if (isset($_SESSION['client'])) { ?>
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['client']['client_id']; ?>">
                            <?php } ?>
                                <div id="review-rate">
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
                                <div id="hiddenDiv" style="display: <?php echo $redirect == 'link' ? 'none' : 'block'; ?>;margin-top: 10px;">

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
                                        <input type="hidden" name="guest_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];; ?>">
                                        <div class="form-group">
                                            <!-- <label>Username</label> -->
                                            <input type="text" name="guest_un" class="form-control" placeholder="Enter your name">
                                        </div>
                                        <div class="form-group">
                                            <!-- <label>EmailAddress</label> -->
                                            <input type="email" name="guest_email" class="form-control" placeholder="Enter your email ID">
                                        </div>
                                    <?php } ?>
                                    <div id="review_terms" class="form-group">
                                        <input type="checkbox" name="terms">
                                        <p>I accept the <a href="terms.php">Terms of Use</a> & <a href="privacy.php">Privacy Policy</a></p>
                                        <span id="review-terms_error" class="text-danger" style="display: none;margin-top: 10px;">Please accept our terms and policy!</span>
                                    </div>
                                    <div class="text-center">
                                        <button name="submit" type="submit">Submit</button>
                                    </div>
                                    
                                    
                                </div>
                                
                            </form>
						</div>
        			</div>
        		</div>
            </div>
        </div>
        <!--elements end-->
<?php //include 'footer.php'; ?>
<script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>review.js?v=1.0.1"></script>