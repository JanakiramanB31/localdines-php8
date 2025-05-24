
<?php
  //print_r($tpl['front_option'][0]['value']);
  if ($tpl['front_option'][0]['value'] == 1) {
?>
<div class="fdLoader"></div>

<?php $index = $controller->_get->toString('index');?>
<br />

<div class="container  mt-5">
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
<?php //include_once dirname(__FILE__) . '/elements/navbar.php';?>
<div class="row">
	<div class="col-md-2 col-sm-2">
		<?php //include_once dirname(__FILE__) . '/elements/sidenav.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
	<div id="fdMain_<?php echo $index; ?>" data-page="Qr" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft">
	
		 <div class="food-item-tab home-page">
	        <div id="menulist-mob" class="col-md-12">
	            <div class="foode-item-box-mob">
	                <button class="nav-prev" style="padding: 0px;">
	                    <i class="fa fa-angle-left" aria-hidden="true"></i>
	                </button>
	                <ul class="menu-list" role="tablist">
	                    <!-- <ul  class="nav" role="tablist"> -->
	                     <?php 
	                        $i = 0;
	                        //foreach($categories as $category) {
	                        foreach($tpl['mainQr']['category_arr'] as $k => $v) {
	                        $i = $i + 1;
	                        //echo $K;
	                        ?>
	                        
	                        <li role="presentation" class="clickCategory <?php echo $k==0?'active':'' ?>">
	                            <input type="hidden" id="cat_id_input" class="cat_id" value="<?php echo $v['id']; ?>">
	                            <!-- <input type="hidden" id="page_input" value="<?php //echo "qr"; ?>"> -->
								<input type="hidden" class="has_img" value="0">
	                            <a href="#tab<?php echo $v['id']; ?>" aria-controls="tab<?php echo $v['id']; ?>" data-toggle="tab"><?php echo $v['name']; ?></a></li>
	                       
	                        <?php
	                    } ?>
	               
	                </ul>
	                
	                <button class="nav-next" style="padding: 0px;">
	                    <i class="fa fa-angle-right" aria-hidden="true"></i>
	                </button>
	            </div>

	            <div class="foode-item-box fix mb-60">
	                 <ul  class="nav" role="tablist">
	                    <!-- <ul  class="nav" role="tablist"> -->
	                     <?php 
	                        // $i = 0;
	                        //foreach($categories as $category) {
	                        foreach($tpl['mainQr']['category_arr'] as $k => $v) {
	                        //$i = $i + 1;
	                        //echo $K;
	                        ?>
	                        
	                        <li role="presentation" class="clickCategory <?php echo $k==0?'active':'' ?>">
							<input type="hidden" id="cat_id_input" value="<?php echo $v['id']; ?>"><!-- <input type="hidden" id="page_input" value="<?php //echo "qr"; ?>"> -->
							<input type="hidden" class="has_img" value="0">
							<a href="#tab<?php echo $v['id']; ?>" aria-controls="tab<?php echo $v['id']; ?>" data-toggle="tab"><?php echo $v['name']; ?></a></li>
	                       
	                        <?php
	                    } ?>
	               
	                </ul>
	                
	                
	                                                                                                                                                
	            </div>
	            <!-- <div class="foode-item-box fix mb-60 desk-view">
	                
	            </div> -->
	                                        
	        </div>
			<div class="row my-30">
				<div class="col-xs-8">
					<div class="row">
						<!-- <div class="col-xs-2">
							<i class="fa fa-search search-me" style="margin-left: 10px;font-size: 25px;color: #000;"></i>
					        <div class="text-center"><i class="fa fa-close" style="color: #000;margin: 3px;display: none;"></i></div>
						</div> -->
						<div class="col-xs-12">
						    <!-- <div id="searchInput-group" style="display: none;"> -->
							<div id="searchInput-group">
							<div  class="input-group rounded" style="display: flex;flex-wrap:nowrap;">
								<input type="search" id="searchInput" class="form-control rounded" placeholder="Search" aria-label="Search"
								aria-describedby="search-addon" />
								<span class="input-group-text border-0 search-btn" id="search-addon">
								    <i class="fa fa-search"></i>
								</span>
								<!-- <div><i class="fa fa-close bg-primary" style="color: #fff;margin: 3px;"></i></div> -->
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
	        <div class="food-item-desc">
	            <div class="col-md-12" style="padding: 0px;">
					<div id="search" style="display: none;">
					    <div class="d-flex justify-content-between">
							<h4>Search Results</h4>
							<i class="fa fa-close" style="color: #000;margin: 3px;"></i>
						</div>
						
						<hr>
						<div class="fdSearchResults">
						</div>
						<hr>
					</div>
	                <div class="tab-content">
	                    <?php 
	                        foreach($tpl['mainQr']['category_arr'] as $k => $v) {
	                        ?>
	                        
	                        <div role="tabpanel" class="tab-pane <?php echo $k==0? 'active': ''; ?>" id="tab<?php echo $v['id']; ?>"></div>
	                       
	                        <?php
	                    } ?>
	                </div>
	            </div>
	        </div>
	    </div>
    </div>
	<div class="col-md-2 col-sm-2"></div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->

</div>
<!-- <button id="topBtn">
<i class="fa fa-arrow-up" aria-hidden="true"></i>
</button> -->
<?php } else { ?>
<div class="container">
    <div id="no-access" class="mx-auto p-5">
	    <h1>Not Accessible</h1>
	</div>
</div>	
<?php } ?>
<!-- <script src= "<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>review.js?v=1.0.1"></script> -->
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
		if ($(window).width() <= 320) {  
			$(".row-search div:nth-of-type(1)").removeClass("col-xs-8");
			$(".row-search").children("div:nth-of-type(1)").addClass("col-xs-6");
			$(".row-search").children("div:nth-of-type(2)").removeClass("col-xs-4");
			$(".row-search").children("div:nth-of-type(2)").addClass("col-xs-6");
		}
		
	})

</script>
