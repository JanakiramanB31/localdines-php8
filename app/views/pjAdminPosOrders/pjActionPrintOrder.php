<style>
	.center-screen {
	  position: absolute;
	  left: 50%;
	  top: 50%;
	  -webkit-transform: translate(-50%, -50%);
	  transform: translate(-50%, -50%);
	}
</style>
<div id="kPrintContainer" class="center-screen">
	<h2><?php echo $tpl['printMessage']; ?></h2>
	<br/>
<div>
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>&origin=<?php echo $tpl['origin'];?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
</div>