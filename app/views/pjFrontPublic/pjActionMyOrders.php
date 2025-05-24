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
				<?php if(count($tpl['my_orders']) > 0) { ?>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
								<th scope="col">Order ID</th>
								<th scope="col">Order Date</th>
								<th scope="col">Type</th>
								<th scope="col">Total</th>
								<th scope="col">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($tpl['my_orders'] as $order) { ?>
								<tr>
									<th scope="row"><?php echo $order['order_id']; ?></th>
									<td><?php echo date('d-m-Y', strtotime($order['created'])); ?></td>
									<td><?php echo $order['type']; ?></td>
									<td><?php echo pjCurrency::formatPrice($order['total']); ?></td>
									<td><?php echo $order['status']; ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
			    
				<?php } else { ?>
					<div>
						<div class="p-5 bg-light text-secondary rounded text-center">
							<h3>"No orders made!"</h3> 
						</div>
					</div>
				<?php } ?>
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
		<div><a href='#' style='float: right; margin-bottom: 10px;' class='btn btn-default alert-link fdStartOver'>Close	</a>
		</div>
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