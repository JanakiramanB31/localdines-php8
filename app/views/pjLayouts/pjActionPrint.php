<!doctype html>
<html>
	<head>
		<title><?php __('script_name') ?></title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo PJ_INSTALL_URL . PJ_CSS_PATH; ?>print.css" media="screen, print" />
		<!-- <link type="text/css" rel="stylesheet" href="/third-party/sweetalert/1.0.0/sweetalert.css" media="screen, print" /> -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>EscPosPrinter.js""></script>
		<!-- <script src="/third-party/sweetalert/1.0.0/sweetalert.min.js"></script> -->
	</head>
	<body>
		<div id="container">
			<?php require $content_tpl; ?>
		</div>
		
		<script type="text/javascript">
			// if (window.print) {
			// 	window.print();
			// }

		</script>
		<script type="text/javascript">
		  function printDiv(divName) {
		  	if (window.print) {
		  		var printContents = document.getElementById(divName).innerHTML;
		     	var originalContents = document.body.innerHTML;
		     	document.body.innerHTML = printContents;
		     	window.print();
		     	document.body.innerHTML = originalContents;
		  	}
		  }
		</script>
	</body>
</html>