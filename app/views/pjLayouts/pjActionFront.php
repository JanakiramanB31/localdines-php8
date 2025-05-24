<?php
require $content_tpl;
$content = ob_get_contents();
ob_end_clean();

$content = preg_replace('/\r\n|\n|\t/', '', $content);
$content = str_replace("'", "\"", $content);
// print_r($content);
// exit;
$pattern = '|<script.*>(.*)</script>|';
if (preg_match($pattern, $content, $matches))
{
	$content = preg_replace($pattern, '', $content);
}
?>
(function () {
	var i, src, script, div,
		element = null,
		scripts = document.getElementsByTagName("script");
	for (i = 0; i < scripts.length; i++) 
	{
		src = scripts[i].src;
		if(src.indexOf("<?php echo PJ_INSTALL_FOLDER;?>index.php?controller=pjFrontEnd&action=pjActionLoad<?php $controller->_get->check('locale') ? printf('&locale=%u', $controller->_get->toInt('locale')) : NULL; ?><?php $controller->_get->check('hide') ? printf('&hide=%u', $controller->_get->toInt('hide')) : NULL; ?>") !== -1)
		{
			element = scripts[i];
			break;
		}
	}
	div = document.createElement('div');
	div.innerHTML = '<?php echo $content;?>';
	if(element !== null)
	{
		element.parentNode.insertBefore(div, element);
	}else{
		document.body.appendChild(div);
	}
	<?php
	if ($matches)
	{
		?>
		script = document.createElement('script');
		script.text = '<?php echo $matches[1];?>';
		if(element !== null)
		{
			element.parentNode.insertBefore(script, element);
		}else{
			document.body.appendChild(script);
		}
		<?php
	}
	?>
})();