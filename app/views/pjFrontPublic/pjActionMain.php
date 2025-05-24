<div class="fdLoader"></div>
<?php 
	$index = $controller->_get->toString('index');
	//echo 'jr '.$controller->defaultStore;
	$STORAGE = @$_SESSION[$controller->defaultStore];
	//echo "<pre>"; print_r($STORAGE); echo "</pre>";
?>
<br />
<div class="container mt-2">
	<?php if(isset($_SESSION['msg']) && $_SESSION['msg'] == 'success'){ ?>
		<!-- <div class="row" style="margin-top: 30px;"> -->
			<div class="alert alert-success alert-dismissible" style="margin-top: 30px;">
				Thank you for the review <strong><?php echo $_SESSION['reviewver']; ?></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<!-- </div>   -->
	<?php   
		unset($_SESSION['msg']);
		unset($_SESSION['reviewver']);
	} 
	?>
	<?php //include_once dirname(__FILE__) . '/elements/nav.php';?>
	<div class="row">
		<div id="fdMain_<?php echo $index; ?>" data-page="Main" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft">
	 		<div class="food-item-tab home-page">
	      <div id="menulist-mob" class="col-md-12">
					<input type="hidden" id="section-menu" />
	      	<div class="foode-item-box-mob">
	          <button class="nav-prev" style="padding: 0px;">
	            <i class="fa fa-angle-left" aria-hidden="true"></i>
	          </button>
	          <ul class="menu-list" role="tablist">
	           	<?php 
	              $i = 0;
	              foreach($tpl['main']['category_arr'] as $k => $v) {
	              $i = $i + 1;
	            ?>        
	          	<li role="presentation" class="clickCategory <?php echo $k==0?'active':'' ?>">
	            <input type="hidden" id="cat_id_input" value="<?php echo $v['id']; ?>" class="cat_id">
							<input type="hidden" class="has_img" value="0">
	            <a href="#tab<?php echo $v['id']; ?>" aria-controls="tab<?php echo $v['id']; ?>" data-toggle="tab"><?php echo $v['name']; ?></a></li>
	          	<?php } ?>
	          </ul> 
	          <button class="nav-next" style="padding: 0px;">
	            <i class="fa fa-angle-right" aria-hidden="true"></i>
	          </button>
	        </div>
	      	<div class="foode-item-box fix mb-60">
	       		<ul  class="nav" role="tablist">
	         		<?php foreach($tpl['main']['category_arr'] as $k => $v) { ?>
	      			<li role="presentation" class="clickCategory <?php echo $k==0?'active':'' ?>"><input type="hidden" id="cat_id_input" class="cat_id" value="<?php echo $v['id']; ?>">
								<input type="hidden" class="has_img" value="0"><a href="#tab<?php echo $v['id']; ?>" aria-controls="tab<?php echo $v['id']; ?>" data-toggle="tab"><?php echo $v['name']; ?></a>
							</li>
	          	<?php } ?>
	        	</ul>          
	        </div>          
	      </div>
				<div class="row my-30 row-search">
					<div class="col-xs-8">
						<div class="row">
							<div class="col-xs-12">
								<div id="searchInput-group">
								<div  class="input-group rounded" style="display: flex;flex-wrap:nowrap;">
									<input type="search" id="searchInput" class="form-control rounded" placeholder="Search" aria-label="Search"
									aria-describedby="search-addon" />
									<span class="input-group-text border-0 search-btn" id="search-addon" style="">
									    <i class="fa fa-search"></i>
									</span>
								</div>
							</div>
						</div>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="btn-group btn-toggle" id="fdImgCheck"> 
							<button class="btn btn-show btn-xs ">SHOW</button>
							<button class="btn btn-hide btn-xs btn-primary active">HIDE</button>
						</div>
					</div>
				</div>
	      <div class="food-item-desc" id="food-items">
	        <div class="col-md-12" style="padding: 0px;">
						<div id="search" style="display: none;">
				    	<div class="d-flex justify-content-between">
								<h4>Search Results</h4>
								<i class="fa fa-close" style="color: #000;margin: 3px;"></i>
							</div>
							<hr><div class="fdSearchResults">
							</div><hr>
						</div>
		                <div class="tab-content">
	                    <?php 
                        foreach($tpl['main']['category_arr'] as $k => $v) {
                      ?>
                        	<div role="tabpanel" class="tab-pane <?php echo $k==0? 'active': ''; ?>" id="tab<?php echo $v['id']; ?>"></div>
	                        <?php
		                    } ?>
		                </div>
		            </div>
		        </div>
		    </div>
	    </div>
			<div  class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		    <div class="row">
				<div class="col-xs-12">
					<div style="text-align: right;" id="cartLocaldines"> 
						<input type="hidden" id="isPostcodeValidated" value="<?php if (isset($STORAGE['post_code']) || $controller->isFrontLogged()) { echo "1";} else { echo "0";} ?>">	
						<input type="hidden" id="orderTypeCounter" value="<?php if (isset($STORAGE['type'])) {
							echo @$STORAGE['type'];
						} else {
							echo "pickup";
						} ?>">	
						<input type="hidden" id="postCodeInSession" value="<?php if (isset($STORAGE['post_code'])) { echo $STORAGE['post_code'];} else { echo "";} ?>">
					</div>
				</div>
				<div id="fdCart_<?php echo $index; ?>" class="col-xs-12">
				   <?php include_once dirname(__FILE__) . '/elements/cart.php';?>
				</div>
			</div>   
		</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
	</div><!-- /.row -->
</div>
<!-- <button id="topBtn">
<i class="fa fa-arrow-up" aria-hidden="true"></i>
</button> -->
<div id="btn-cart" class="fixed-bottom">
    <a href="" id="bottom-btn" class="btn btn-block btn-lg pjFdBtnCart">My Order:<span id="fdMyOrderPrice"> 0.00</span></a>
</div>

<script>
	$(function() {
		if ($(".food-item-desc").length > 0) {
	    $(".search-me").css("display","block");
		} else {
			$(".search-me").css("display","none");
			if ($("#searchInput-group").css("display") == "flex") {
				$("#searchInput-group").css("display", "none");
				$(".logo").css("display", "block");
			}
		}
		$('.btn-toggle').click(function() {
	    $(this).find('.btn').toggleClass('active');  
	    if ($(this).find('.btn-primary').length>0) {
	    	$(this).find('.btn').toggleClass('btn-primary');
	    }
	    if ($(this).find('.btn-danger').length>0) {
	    	$(this).find('.btn').toggleClass('btn-danger');
	    }
	    if ($(this).find('.btn-success').length>0) {
	    	$(this).find('.btn').toggleClass('btn-success');
	    }
	    if ($(this).find('.btn-info').length>0) {
	    	$(this).find('.btn').toggleClass('btn-info');
	    }
		});
		if ($(window).width() <= 320) {  
		  $(".row-search div:nth-of-type(1)").removeClass("col-xs-8");
		  $(".row-search").children("div:nth-of-type(1)").addClass("col-xs-6");
		  $(".row-search").children("div:nth-of-type(2)").removeClass("col-xs-4");
		  $(".row-search").children("div:nth-of-type(2)").addClass("col-xs-6");
		}
	})
	$(window).scroll(function() {
		if ($("#btn-cart").length > 0) {
			var fixedBottom = $("#btn-cart");
			var cartPosition = $("#cartLocaldines").offset().top; 
			var fixedBottomPosition = $("#btn-cart").offset().top; 
			if (fixedBottomPosition >= cartPosition) {
				fixedBottom.css("opacity", 0 );
				fixedBottom.css("width", '0px' );
			} else {
				fixedBottom.css("opacity", 1 );
				fixedBottom.css("width", '100%' );
			}
		}
	});
	$(window).scroll(function() {
		if ($(this).scrollTop()) {
			$('#topBtn').fadeIn();
		} else {
			$('#topBtn').fadeOut();
		}
	});
	$("#topBtn").click(function () {
    $("html, body").animate({scrollTop: 0}, 800);
	});
	// $(window).on('load', function () {
  //   alert("Window Loaded");
  // });
</script>