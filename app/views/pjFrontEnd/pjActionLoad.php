<?php
mt_srand();
$index = mt_rand(1, 9999);
$front_messages = __('front_messages', true, false);
$login_messages = __('login_messages', true, false);
$forgot_messages = __('forgot_messages', true, false);
$month_arr = __('months', true, false);
ksort($month_arr);
$layout = $controller->_get->check('layout') ? $controller->_get->toString('layout') : $tpl['option_arr']['o_theme'];
$required_url = APP_URL;
$front_url = APP_HOME_URL;
//echo $required_url;
?>
<?php include_once dirname(__FILE__) . '/elements/nav.php';?>
<!-- <div style="background-color: #393737;padding: 25px 0px;"></div>
<div style="background-color: #000;padding: 35px 0px;"></div>
<?php //echo $controller->isFrontLogged() ? 'yes' : 'no';?> -->
<div id="pjWrapperFoodDelivery_<?php echo $layout;?>" style="min-height: 70vh;">
	<div id="fdContainer_<?php echo $index; ?>" class="container-fluid pjFdContainer"></div>
	<div class="modal fade" id="pjFdTermsAndConditions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-lg">
	    	<div class="modal-content"></div>
	  	</div>
	</div>
	<div class="modal fade pjTbModal" id="pjFdWrongCaptchaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
			    <div class="modal-body">
			    	<?php __('frotn_incorrect_captcha');?>
			    </div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('front_close');?></button>
		      	</div>
	    	</div>
	  	</div>
	</div>
</div>
<?php include_once dirname(__FILE__). '/elements/foot.php'; ?>
<script type="text/javascript">
var pjQ = pjQ || {},
	FoodDelivery_<?php echo $index; ?>;
var $controller_url = '<?php echo $required_url ?>';
var $frontend = '<?php echo $front_url ?>';
(function () {
	"use strict";
	var daysOff = [],
		datesOff = [],
		datesOn = [];
	<?php
	if (isset($tpl['days_off']) && is_array($tpl['days_off']))
	{
		foreach ($tpl['days_off'] as $location_id => $type_arr)
		{
			printf("daysOff[%u] = [];", $location_id);
			foreach ($type_arr as $type => $days_off)
			{
				printf("daysOff[%u]['%s'] = [];", $location_id, $type);
				if (count($days_off) > 0)
				{
					printf("daysOff[%u]['%s'] = [%s];", $location_id, $type, join(",", $days_off));
				}
			}
		}
	}
	if (isset($tpl['dates_off']) && is_array($tpl['dates_off']))
	{
		foreach ($tpl['dates_off'] as $location_id => $type_arr)
		{
			printf("datesOff[%u] = [];", $location_id);
			foreach ($type_arr as $type => $dates_off)
			{
				printf("datesOff[%u]['%s'] = [];", $location_id, $type);
				if (count($dates_off) > 0)
				{
					printf("datesOff[%u]['%s'] = ['%s'];", $location_id, $type, join("','", $dates_off));
				}
			}
		}
	}
	if (isset($tpl['dates_on']) && is_array($tpl['dates_on']))
	{
		foreach ($tpl['dates_on'] as $location_id => $type_arr)
		{
			printf("datesOn[%u] = [];", $location_id);
			foreach ($type_arr as $type => $dates_on)
			{
				printf("datesOn[%u]['%s'] = [];", $location_id, $type);
				if (count($dates_on) > 0)
				{
					printf("datesOn[%u]['%s'] = ['%s'];", $location_id, $type, join("','", $dates_on));
				}
			}
		}
	}
	?>
	var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),
		
	loadCssHack = function(url, callback){
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = url;

		document.getElementsByTagName('head')[0].appendChild(link);

		var img = document.createElement('img');
		img.onerror = function(){
			if (callback && typeof callback === "function") {
				callback();
			}
		};
		img.src = url;
	},
	loadRemote = function(url, type, callback) {
		if (type === "css" && isSafari) {
			loadCssHack(url, callback);
			return;
		}
		var _element, _type, _attr, scr, s, element;
		
		switch (type) {
		case 'css':
			_element = "link";
			_type = "text/css";
			_attr = "href";
			break;
		case 'js':
			_element = "script";
			_type = "text/javascript";
			_attr = "src";
			break;
		}
		
		scr = document.getElementsByTagName(_element);
		s = scr[scr.length - 1];
		element = document.createElement(_element);
		element.type = _type;
		if (type == "css") {
			element.rel = "stylesheet";
		}
		if (element.readyState) {
			element.onreadystatechange = function () {
				if (element.readyState == "loaded" || element.readyState == "complete") {
					element.onreadystatechange = null;
					if (callback && typeof callback === "function") {
						callback();
					}
				}
			};
		} else {
			element.onload = function () {
				if (callback && typeof callback === "function") {
					callback();
				}
			};
		}
		element[_attr] = url;
		s.parentNode.insertBefore(element, s.nextSibling);
	},
	loadScript = function (url, callback) {
		loadRemote(url, "js", callback);
	},
	loadCss = function (url, callback) {
		loadRemote(url, "css", callback);
	},
	getSessionId = function () {
		return sessionStorage.getItem("session_id") == null ? "" : sessionStorage.getItem("session_id");
	},
	createSessionId = function () {
		if(getSessionId()=="") {
			sessionStorage.setItem("session_id", "<?php echo session_id(); ?>");
		}
	},
	options = {
		server: "<?php echo PJ_INSTALL_URL; ?>",
		folder: "<?php echo PJ_INSTALL_URL; ?>",
		layout: "<?php echo $layout;?>",
		index: <?php echo $index; ?>,
		hide: <?php echo $controller->_get->check('hide') ? $controller->_get->toInt('hide') : 0; ?>,
		locale: <?php echo $controller->_get->check('locale')? $controller->_get->toInt('locale') : $controller->pjActionGetLocale(); ?>,
				
		startDay: <?php echo (int) $tpl['option_arr']['o_week_start']; ?>,
		googleAPIKey: "<?php echo isset($tpl['option_arr']['o_google_maps_api_key']) && !empty($tpl['option_arr']['o_google_maps_api_key']) ? 'key=' . $tpl['option_arr']['o_google_maps_api_key'] . '&' : ''; ?>",
		dateFormat: "<?php echo $tpl['option_arr']['o_date_format']; ?>",
		dayNames: ["<?php echo join('","', __('day_short_names', true, false)); ?>"],
		monthNamesFull: ["<?php echo join('","', $month_arr); ?>"],
		daysOff: daysOff,
		datesOff: datesOff,
		datesOn: datesOn,
		menu_category: "<?php echo $tpl['menu_category']; ?>",

		messages: {
			1: "<?php echo pjSanitize::clean($front_messages[1]); ?>",
			2: "<?php echo pjSanitize::clean($front_messages[2]); ?>",
			3: "<?php echo pjSanitize::clean($front_messages[3]); ?>",
			4: "<?php echo pjSanitize::clean($front_messages[4]); ?>",
			5: "<?php echo pjSanitize::clean($front_messages[5]); ?>",
			6: "<?php echo pjSanitize::clean($front_messages[6]); ?>",
			7: "<?php echo pjSanitize::clean($front_messages[7]); ?>",
			8: "<?php echo pjSanitize::clean($front_messages[8]); ?>",
			9: "<?php echo pjSanitize::clean($front_messages[9]); ?>",
			10: "<?php echo pjSanitize::clean($front_messages[10]); ?>",
			11: "<?php echo pjSanitize::clean($front_messages[11]); ?>",
			12: "<?php echo pjSanitize::clean($front_messages[12]); ?>",
		},
		login_messages: {
			100: "<?php echo pjSanitize::clean($login_messages[100]); ?>",
			101: "<?php echo pjSanitize::clean($login_messages[101]); ?>",
			102: "<?php echo pjSanitize::clean($login_messages[102]); ?>"
		},
		forgot_messages: {
			100: "<?php echo pjSanitize::clean($forgot_messages[100]); ?>",
			101: "<?php echo pjSanitize::clean($forgot_messages[101]); ?>",
			200: "<?php echo pjSanitize::clean($forgot_messages[200]); ?>"
		},
		email_exiting_message: "<?php echo pjSanitize::clean(__('front_existing_email', true));?>"
	};
	<?php
	$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
	$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
	//echo $dm->getPath('calendarjs'); 
	?>
	loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('storage_polyfill'); ?>storagePolyfill.min.js", function () {
		if (isSafari) {
			createSessionId();
			options.session_id = getSessionId();
		}else{
			options.session_id = "";
		}
		loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('pj_jquery'); ?>pjQuery.min.js", function () {
			loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('pj_validate'); ?>pjQuery.validate.min.js", function () {
				loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('calendarjs'); ?>calendar.js", function () {
					loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('jquery'); ?>jquery.min.js", function () {
					loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('bootstrap5')."/js/"; ?>bootstrap.bundle.min.js", function () {
						
					loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('pj_bootstrap'); ?>pjQuery.bootstrap.min.js", function () {
						loadScript("<?php echo PJ_INSTALL_URL . $dm->getPath('pj_bootstrap_datepicker'); ?>pjQuery.bootstrap-datepicker.js", function () {
							<?php if($tpl['option_arr']['o_captcha_type_front'] == 'google'): ?>
						    loadScript('https://www.google.com/recaptcha/api.js', function () {
                            <?php endif; ?>
    							loadScript("<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>pjFoodDelivery.js?v=1.0.1", function () {
									loadScript("<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>main_front.js?v=1.0.1", function () {
									loadScript("<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>review.js?v=1.0.1", function(){
    								FoodDelivery_<?php echo $index; ?> = new FoodDelivery(options);
									});
    							});
								
							
    							});
							<?php if($tpl['option_arr']['o_captcha_type_front'] == 'google'): ?>
                            });
						    <?php endif; ?>
						});
					});
					});
					});
				});
			});
		});
	});
})();
</script>
