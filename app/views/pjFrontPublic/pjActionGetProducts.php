<?php
//$imgState;
// if(isset($tpl['arr'])) {
// 	$imgState = $tpl['has_image'];
// 	echo "<pre>";print_r($tpl['has_image']); 
// } else {
// 	echo "<pre>";print_r($tpl['has_image']); 
// }

//exit;
if(isset($tpl['arr']) && !empty($tpl['arr']))
{
	$category_id = $controller->_get->toInt('category_id');

	foreach($tpl['arr'] as $product)
	{
		if ($product['status'] == 1) {
		
		// $image_path = 'https://placehold.it/220x200';

		if(!empty($product['image'])) {
			$image_path = PJ_INSTALL_URL . $product['image'];
		} else {
			$image_path = PJ_INSTALL_URL.'app/web/img/backend/no_image.png';
		}
		
		?>
		<div class="panel panel-default pjFdProduct">
			<form style="overflow: hidden;" action="" method="post">
				<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
				<input type="hidden" name="page_type" value="<?php print_r($tpl['page_type']); ?>" />
				<div class="panel-heading pjFdProductHead" id="headingInner<?php echo $category_id?><?php echo $product['id']?>">
					<h2 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordionInner<?php echo $category_id;?>" href="#collapseInner<?php echo $category_id;?><?php echo $product['id']?>" aria-expanded="<?php echo $tpl['has_image'] == 1 ? 'true' : 'false'; ?>" aria-controls="collapseInner<?php echo $category_id;?><?php echo $product['id']?>">
							<div class="row prdRow">
								<div class="col-md-8 col-sm-8 col-xs-7 text-capitalize pjFdProductName" style="display: flex;flex-direction: column;justify-content: center;">
									<p class="product-fullname" style="margin-left: 5px;margin-bottom: 0px;">
										<?php echo pjSanitize::clean($product['name']);?> 
										<span class="upDown">
											<i class="fa fa-chevron-down" style="margin-left: 7px;"></i>
											<i class="fa fa-chevron-up" style="margin-left: 7px;"></i>
		                </span>
		                <?php if ($product['is_veg']) { ?>
		                <span style="color:green">
		                	<i class="fa fa-leaf" style="margin-left: 7px;"></i>
		                </span>
		                <?php } ?>
								  </p>    
									<p class="product-shortname" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
								   <?php
								    $prd_name =  pjSanitize::clean($product['name']);
										if (strlen($prd_name) > 6) {
											$show_name = substr($prd_name, 0, 6);
									 		echo $prd_name;
										} else {
									   	echo $prd_name;
								   	}             
								   ?>
								  </p>
									
								</div><!-- /.col-md-10 col-sm-10 col-xs-10 -->
								

								<div class="col-md-2 col-sm-2 col-xs-3 text-right pjFdProductPrice" style="display: flex;flex-direction: column;justify-content: center;">
								<p style="margin-bottom: 0px;">
								<?php
								if ($product['set_different_sizes'] == 'F' || count($product['price_arr']) == 0)
								{
								    echo pjCurrency::formatPrice($product['price']);
								} 
								?></p>
								</div>
								
								<!-- <div class="col-md-2 col-sm-2 col-xs-2">
								
								</div> -->
								<?php if ($product['is_web_orderable']) { ?>
								<div class="col-md-2 col-sm-2 col-xs-2 pjFdProductAdd" style="display: flex;align-items: center;flex-direction: row;justify-content: space-around;">
									<!-- <div class="row">
										<div class="col-xs-6"> -->
										<?php
										if($tpl['page_type'] == 'Main')
										{ ?>
											<!-- <i class="fa fa-plus pjFdBtnOrder fdProductOrder" data-id="<?php //echo $product['id'];?>"></i> -->
											<button class="pjFdBtnOrder fdProductOrder" role="button" data-id="<?php echo $product['id'];?>" style="width: 50px;"><i class="fa fa-plus"></i></button>
											<?php } ?>
										<!-- </div> -->
										<!-- <div class="upDown">
											<i class="fa fa-chevron-down" style="margin-left: 7px;"></i>
											<i class="fa fa-chevron-up" style="margin-left: 7px;"></i>
										</div> -->
									<!-- </div> -->
									
							    	
							    </div>
							  <?php } ?>
								<!-- /.col-md-2 col-sm-2 col-xs-2 -->
							</div>
						</a>
					</h2><!-- /.panel-title -->
				</div><!-- /#headingInnerOne.panel-heading -->
				<div class="panel-collapse collapse<?php echo $tpl['has_image'] == 1 ? ' in' : NULL; ?>" id="collapseInner<?php echo $category_id;?><?php echo $product['id']?>" role="tabpanel" aria-labelledby="headingInner<?php echo $category_id;?><?php echo $product['id']?>">
					<div class="panel-body pjFdProductBody">
						<div class="row pjFdProductContent">
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<div class="row">
									<?php //if($tpl['has_image'] == 1) { ?>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<img src="<?php echo $image_path;?>" alt="Responsive image" class="img-responsive" />
									</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-8 -->
									<?php //} ?>
                                    <?php if(!empty($product['description'])) { ?>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
											<p><?php echo nl2br(pjSanitize::clean($product['description']));?></p>
										</div><!-- /.col-lg-8 col-md-8 col-sm-8 col-xs-12 -->
									<?php } ?>
									
								</div>
							</div><!-- /.col-lg-10 col-md-10 col-sm-12 col-xs-12 -->

							
								<?php
								if($product['set_different_sizes'] == 'T' && count($product['price_arr']) > 0 && $tpl['page_type'] == 'Main')
								{
									?>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 pjFdColCustom">
									<select id="fdSelectSize_<?php echo $product['id'];?>" name="price_id" class="form-control" data-id="<?php echo $product['id'];?>" style="padding-left: 10px;">
										<?php
										foreach($product['price_arr'] as $price)
										{
										    ?><option value="<?php echo $price['id']?>"><?php echo $price['price_name']?>: <?php echo pjCurrency::formatPrice($price['price']);?></option><?php
										} 
										?>
									</select>
									<br/>
									</div>
									<?php
								}  
								?>
								
								<!-- <button class="btn btn-default btn-block text-uppercase pjFdBtnOrder fdProductOrder" role="button" data-id="<?php //echo $product['id'];?>"><?php //__('front_order');?></button> -->
							<!-- /.col-lg-2 col-md-2 col-sm-3 col-xs-4 -->
						</div><!-- /.row -->

						<br />
						<?php
						$product_id = $product['id'];
						if ($product['is_web_orderable'] && !empty($product['extra_arr']) && $tpl['page_type'] == 'Main')
						{ 
							include PJ_VIEWS_PATH . 'pjFrontPublic/elements/extras.php';
						} 
						?>
						<?php if($tpl['front_option'][0]['value'] == 1) { ?>
						<div class="row">
                            <div class="col-md-6 stars-group_static" style="margin-bottom: 10px;">
							  <a data-star="0" data-via="link" data-product="<?php echo $product_id; ?>" class="food-review-text">Write a review
							    <span class="review_cnt">(<?php echo $product['cnt_reviews'] ?>)</span>
							  </a>
                              <div class="d-flex">
								<span class="tot-rate" data-product="<?php echo $product_id; ?>"></span><span style="font-size: 11px;margin-top: 3px;">(<?php echo $product['tot_rating']; ?>/5)</span>
								<input type='hidden' name="total_review_rate" value=<?php echo $product['tot_rating']; ?>>
							  </div>
							  
							</div>
                              
                            
                            
                            <div id="product_stars" class="col-md-6 stars-group">
                                <a data-star="1" data-via="rate" data-product="<?php echo $product_id; ?>" class="fa fa-star star-one" aria-hidden="true"></a>
                                <a data-star="2" data-via="rate" data-product="<?php echo $product_id; ?>" class="fa fa-star star-two" aria-hidden="true"></a>
                                <a data-star="3" data-via="rate" data-product="<?php echo $product_id; ?>" class="fa fa-star star-three" aria-hidden="true"></a>
                                <a data-star="4" data-via="rate" data-product="<?php echo $product_id; ?>" class="fa fa-star star-four" aria-hidden="true"></a>
                                <a data-star="5" data-via="rate" data-product="<?php echo $product_id; ?>" class="fa fa-star star-five" aria-hidden="true"></a>
                                <!-- <input type='hidden' name="total_review_rate" value=<?php echo $product['tot_rating']; ?>> -->
                            </div>
							<!-- food-review 
							food-review-rate -->
                        </div>
						
						<?php } ?>
						<!-- <div class="row">
							<div class="col-md-6">
								<p> <a href=""> Write a review </a> (6)</p>
								<p>4.3</p><i class="fa fa-star"></i>
							</div>
							<div class="col-md-6">
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
							</div>
						</div> -->
					</div><!-- /.panel-body -->
				</div><!-- /#collapseInnerOne.panel-collapse collapse -->
			</form>
		</div><!-- /.panel panel-default -->
		<?php
	}
	}
}
?>
<script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>stars.js?v=1.0.1"></script>
<!-- <script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>review.js?v=1.0.1"></script> -->
 <script>
	//  $(document).ready(function () {
    //     var tot_rating_element =  $("input[name='total_review_rate']");
	// 	tot_rating_element.each(function() {
	// 	var $this = $(this);
    //     var tot_rating =  parseFloat($this.val()).toFixed(1);
        
    //     if (isFloat(tot_rating)) {
    //         var floatPart = parseFloat(tot_rating % 1).toFixed(1);
    //         var intPart = Math.ceil(tot_rating);
    //         if (intPart == 1) {
    //             var bg = "red";
    //             $this.siblings(".star-one").css("background-color","red");
    //             floatStar(intPart,floatPart,bg);
    //         } else if (intPart == 2) {
    //             var bg = "orange";
    //             $this.siblings(".star-one,.star-two").css("background-color","orange");
    //             floatStar(intPart,floatPart,bg);
    //         } else if (intPart == 3) {
    //             var bg = "#fbce00";
    //             $this.siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
    //             floatStar(intPart,floatPart,bg);
    //         } else if (intPart == 4) {
    //             var bg = "#73cf11";
    //             $this.siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
    //             floatStar(intPart,floatPart,bg);
    //         } else if (intPart == 5){
    //             var bg = "#42b67a";
    //             $this.siblings(".food-review-rate a").css("background-color","#42b67a");
    //             floatStar(intPart,floatPart,bg);
    //         }
            
    //     } else {
    //         if (tot_rating == 1) {
    //             $this.siblings(".star-one").css("background-color","red");
    //         } else if (tot_rating == 2) {
    //             $this.siblings(".star-one,.star-two").css("background-color","orange");
    //         } else if (tot_rating == 3) {
    //             $this.siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
    //         } else if (tot_rating == 4) {
    //             $this.siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
    //         } else if (tot_rating == 5){
    //             $this.siblings(".food-review-rate a").css("background-color","#42b67a")
    //         }
    //     }
	// });

    //     function isFloat(value) {
    //         if(value % 1 === 0){
    //             return false;
    //         } else{
    //             return true;
    //         }
    //     } 

    //     function floatStar(int,float,bg) {
    //         var elem = $("#tot_rate").children("a").eq(int-1);
    //         float = float.toString();
    //         float = float.split(".")[1];
    //         var floatPercent = float+"0"+"%";
    //         var intPercent = (10-float) +"0"+"%";
    //         var value = "linear-gradient(to right,"+bg+" "+floatPercent+" , #dcdce6"+" "+intPercent+")";
    //         //console.log(intPercent+"-"+floatPercent+"-"+bg);
    //         //elem.css({background-image: value});
    //         elem.css({
    //             background: value
    //         });
            
    //             //elem.css({background: "-webkit-gradient(linear, left top, left bottom, from(#ccc), to(#000))" });
    //     }
	// })

	/*******************************************************/

	$(document).ready(function () {
        var tot_rating_element =  $("input[name='total_review_rate']");
		tot_rating_element.each(function() {
		var $this = $(this);
        var tot_rating = parseFloat($this.val());
        var sibling = $this.siblings(".tot-rate");
		if(tot_rating == 1) {
			console.log("comes one");
		}
        switch (true) { 
            case (tot_rating == 0) : 
				sibling.addClass("zeroStar");
				break;
			case (tot_rating > 0 && tot_rating <= 0.5) : 
				sibling.addClass("pointFive");
				break;
			case (tot_rating > 0.5 && tot_rating <= 1) : 
				sibling.addClass("oneStar");
				break;
			case (tot_rating > 1 && tot_rating <= 1.5) : 
				sibling.addClass("oneFiveStar");
				break;		
			case (tot_rating > 1.5 && tot_rating <= 2) : 
				sibling.addClass("twoStar");
				break;
			case (tot_rating > 2 && tot_rating <= 2.5) : 
				sibling.addClass("twoFiveStar");
			    break;
			case (tot_rating > 2.5 && tot_rating <= 3) : 
				sibling.addClass("threeStar");
				break;
			case (tot_rating > 3 && tot_rating <= 3.5) : 
				sibling.addClass("threeFiveStar");
				break;
			case (tot_rating > 3.5 && tot_rating <= 4) : 
				sibling.addClass("fourStar");
				break;
			case (tot_rating > 4 && tot_rating <= 4.5) : 
				sibling.addClass("fourFiveStar");
				break;
			case (tot_rating > 4.5 && tot_rating <= 5) : 
				sibling.addClass("fiveStar");
				break;
			default:
			    sibling.addClass("zeroStar");
		}
	    });
	    $('.loadMore').off('click').on('click', function(event) {
	    	event.preventDefault();
		  	var childClass = this.id.replace('parent', 'child'); 
		  	console.log(childClass);
		  	$('.'+childClass).slideToggle();
		  	// var tableID = '#productsExtras-'+productID;
		  	// var $rows = $(tableID+' tr');
		  	// var totalRows = $rows.length;
		  	// $rows.filter(':lt(' + (totalRows) + ')').css('display','table-row');
		  	// if ($rows.filter(':hidden').length === 0) {
		  	// 	$(this).hide();
		  	// 	$('#loadLess-'+productID).show();
		  	// }
		});
		// $('.loadLess').off('click').on('click', function(event) {
	  //   	event.preventDefault();
		//   	var productID = this.id.match(/\d+/); 
		//   	var tableID = '#productsExtras-'+productID;
		//   	var $rows = $(tableID+' tr');
		//   	var totalRows = $rows.length;
		//   	$rows.filter(':nth-child(n+3)').css('display','none');
		//   	if ($rows.filter(':hidden').length !== 0) {
		//   		$(this).hide();
		//   		$('#loadMore-'+productID).show();
		//   	}
		// });
	})
</script> 
