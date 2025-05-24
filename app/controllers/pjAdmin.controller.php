<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController {

	public $defaultUser = 'admin_user';
	public $thumbSize = 'thumb_size';
	public $previewSize = 'preview_size';
	public $requireLogin = true;
	
	public function __construct($requireLogin=null) {
		if (isset($_SESSION[$this->defaultUser]) && $_SESSION[$this->defaultUser]['role_id'] == 3 ) {
			unset($_SESSION[$this->defaultUser]);
			pjUtil::redirect(PJ_FRONT_URL);
	  	exit;
		}
		$this->setLayout('pjActionAdmin');
		if (!is_null($requireLogin) && is_bool($requireLogin)) {
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin) {
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview'))) {
				if (!$this->isXHR()) {
	    		pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjBase&action=pjActionLogin");
				} else {
					header('HTTP/1.1 401 Unauthorized');
					exit;
				}
			}
		}
		
		$inherits_arr = array(
			'pjAdminOrders::pjActionFormatPrice' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionGetExtras' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionGetOrder' => 'pjAdminOrders::pjActionIndex',
			'pjAdminOrders::pjActionGetPrices' => 'pjAdminOrders::pjActionCreate',
	    'pjAdminOrders::pjActionGetTotal' => 'pjAdminOrders::pjActionCreate',
	    'pjAdminOrders::pjActionGetClient' => 'pjAdminOrders::pjActionCreate',
	    'pjAdminOrders::pjActionCheckPickup' => 'pjAdminOrders::pjActionCreate',
	    'pjAdminOrders::pjActionCheckDelivery' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionCheckClientEmail' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionPrintOrder' => 'pjAdminOrders::pjActionUpdate',
			'pjAdminOrders::pjActionReminderEmail' => 'pjAdminOrders::pjActionUpdate',
	    'pjAdminCategories::pjActionCreate' => 'pjAdminCategories::pjActionCreateForm',
	    'pjAdminExtras::pjActionCreate' => 'pjAdminExtras::pjActionCreateForm',
			'pjAdminExtras::pjActionUpdate' => 'pjAdminExtras::pjActionCreateForm',
			'pjAdminExtras::pjActionGetExtra' => 'pjAdminExtras::pjActionIndex',
			'pjAdminExtras::pjActionSaveExtra' => 'pjAdminExtras::pjActionIndex',
    	'pjAdminClients::pjActionGetClient' => 'pjAdminClients::pjActionIndex',
  		'pjAdminClients::pjActionCheckEmail' => 'pjAdminClients::pjActionCreate',
  		'pjAdminClients::pjActionSaveClient' => 'pjAdminClients::pjActionUpdate',
			'pjAdminOptions::pjActionNotificationsSetContent' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionNotificationsGetContent' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionNotificationsGetMetaData' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionPaymentOptions' => 'pjAdminOptions::pjActionPayments',
			'pjAdminOptions::pjActionUpdateFrontOption' => 'pjAdminOptions::pjActionFrontEnd',
			'pjAdminTime::pjActionGetDate' => 'pjAdminTime::pjActionIndex',
    	'pjAdminTime::pjActionSaveCustom' => 'pjAdminTime::pjActionIndex',
    	'pjAdminTime::pjActionResetForm' => 'pjAdminTime::pjActionIndex',
    	'pjAdminTime::pjActionGetCustomDate' => 'pjAdminTime::pjActionIndex',
			'pjAdminReports::pjActionGenerate' => 'pjAdminReports::pjActionIndex',
			'pjAdminReports::pjActionPrint' => 'pjAdminReports::pjActionIndex',		
			'pjAdminLocations::pjActionGetLocation' => 'pjAdminLocations::pjActionIndex',
			'pjAdminLocations::pjActionSaveLocation' => 'pjAdminLocations::pjActionIndex',
			'pjAdminLocations::pjActionGetCoords' => 'pjAdminLocations::pjActionCreate',		
			'pjAdmin::pjActionVerifyAPIKey' => 'pjBaseOptions::pjActionApiKeys',
			'pjAdminTime::pjActionCheckDefaultTime' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionCheckCustomTime' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionDeleteDate' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionDeleteDateBulk' => 'pjAdminTime::pjActionIndex',
			'pjAdminCategories::pjActionGetCategory' => 'pjAdminCategories::pjActionIndex',
			'pjAdminCategories::pjActionUpdate' => 'pjAdminCategories::pjActionIndex',
			'pjAdminCategories::pjActionSaveCategory' => 'pjAdminCategories::pjActionIndex',
			'pjAdminProducts::pjActionGetProduct' => 'pjAdminProducts::pjActionIndex',
			'pjAdminProducts::pjActionSaveProduct' => 'pjAdminProducts::pjActionIndex',
			'pjAdminProducts::pjActionDeleteImage' => 'pjAdminProducts::pjActionUpdate',
    	'pjAdminProducts::pjActionCheckPrices' => 'pjAdminProducts::pjActionCreate',
    	'pjAdminProducts::pjActionCheckPrices' => 'pjAdminProducts::pjActionUpdate',
			'pjAdminOptions::pjActionDeliveryForm' => 'pjAdminOptions::pjActionOrderForm',
			'pjAdminPosOrders::pjActionFormatPrice' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetExtras' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetOrder' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPosOrders::pjActionGetPosOrder' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPosOrders::pjActionGetPrices' => 'pjAdminPosOrders::pjActionCreate',
    	'pjAdminPosOrders::pjActionGetTotal' => 'pjAdminPosOrders::pjActionCreate',
    	'pjAdminPosOrders::pjActionGetClient' => 'pjAdminPosOrders::pjActionCreate',
    	'pjAdminPosOrders::pjActionCheckPickup' => 'pjAdminPosOrders::pjActionCreate',
    	'pjAdminPosOrders::pjActionCheckDelivery' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionCheckClientEmail' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionPrintOrder' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionReminderEmail' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionGetProductPrices' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionGetSearchResults' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionGetSingleOrderInfo' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPosOrders::pjActionGetProductSizes' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetProductSizes' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionGetDescription' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetDescription' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionGetSpecialInstructionTypes' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetSpecialInstructionTypes' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionValidateTable' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionSalePrint' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionInitialPrint' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionKitchenPrintUpdate' => 'pjAdminPosOrders::pjActionUpdate',
			'pjAdminPosOrders::pjActionOpenDrawer' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionInitialKPrint' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetPendingOrders' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionCancelOrder' => 'pjAdminPosOrders::pjActionCreate',
			'pjAdminPosOrders::pjActionGetReasonList' => 'pjAdminPosOrders::pjActionCreate',
      // Gokul edit 
      'pjAdminPosOrders::pjActionViewSpecialInstructionTypes' => 'pjAdminPosOrders::pjActionCreate',
      'pjAdminPosOrders::pjActionCreateEatin' => 'pjAdminPosOrders::pjActionCreate',
      'pjAdminPosOrders::pjActionCreateTelephone' => 'pjAdminPosOrders::pjActionCreate',
      'pjAdminPosOrders::pjActionAddCustomProduct' => 'pjAdminPosOrders::pjActionCreate',
      
			// Default allowed actions for special instructions
			'pjAdminSpecialInstructions::pjActionGetSpecialInstruction'  => 'pjAdminSpecialInstructions::pjActionIndex',
			'pjAdminSpecialInstructions::pjActionUpdate'                 => 'pjAdminSpecialInstructions::pjActionIndex',
			'pjAdminSpecialInstructions::pjActionSaveSpecialInstruction' => 'pjAdminSpecialInstructions::pjActionIndex',
			'pjAdminReports::pjActionPOSXIndex' => 'pjAdminReports::pjActionIndex',	
			'pjAdminReports::pjActionPOSZIndex' => 'pjAdminReports::pjActionIndex',	
			'pjAdminReports::pjActionPOSGenerate' => 'pjAdminReports::pjActionIndex',	
			'pjAdminReports::pjActionPOSXPrint' => 'pjAdminReports::pjActionIndex',	
			'pjAdminReports::pjActionPOSZPrint' => 'pjAdminReports::pjActionIndex',	
			'pjAdminReports::pjActionUpdateZViewReport' => 'pjAdminReports::pjActionIndex',
			'pjAdminReports::pjActionTopProductsReport' => 'pjAdminReports::pjActionIndex',
			'pjAdminReports::pjActionCheckOrderTime' => 'pjAdminReports::pjActionIndex',
			'pjAdminReports::pjActionGetTopProductsReport' => 'pjAdminReports::pjActionIndex',
			//Expense
			'pjAdminExpense::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminExpense::pjActionGetExpense' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminExpense::pjActionCreate' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminExpense::pjActionUpdate' => 'pjAdminPosOrders::pjActionIndex',
			//Masters
			'pjAdminMasters::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminMasters::pjActionCreate' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminMasters::pjActionGetMaster' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminMasters::pjActionDeleteMaster' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminMasters::pjActionUpdate' => 'pjAdminPosOrders::pjActionIndex',
			//Postalcode
			'pjAdminPostalcode::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPostalcode::pjActionCreate' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPostalcode::pjActionGetPostalcode' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPostalcode::pjActionDeletePostalcode' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPostalcode::pjActionUpdate' => 'pjAdminPosOrders::pjActionIndex',
			//Sms
			'pjAdminSms::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminSms::pjActionGetSms' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminPosOrders::pjActionReturnOrderItem' => 'pjAdminPosOrders::pjActionIndex',
			//Incomes
			'pjAdminIncomes::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminIncomes::pjActionCreate' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminIncomes::pjActionGetIncome' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminIncomes::pjActionDeleteIncome' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminIncomes::pjActionUpdate' => 'pjAdminPosOrders::pjActionIndex',
			//Configs
			'pjAdminConfigs::pjActionIndex' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminConfigs::pjActionCreate' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminConfigs::pjActionGetConfig' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminConfigs::pjActionDeleteConfig' => 'pjAdminPosOrders::pjActionIndex',
			'pjAdminConfigs::pjActionUpdate' => 'pjAdminPosOrders::pjActionIndex',
			//products
			'pjAdminProducts::pjActionCheckProductType' => 'pjAdminPosOrders::pjActionIndex',
		);
		
		if ($_REQUEST['controller'] == 'pjAdminOptions' && isset($_REQUEST['next_action'])) {
			switch ($_REQUEST['next_action']) {
				case 'pjActionOrders':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrders';
				break;
				case 'pjActionTerm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionTerm';
				break;
				case 'pjActionOrderForm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrderForm';
				break;
				case 'pjActionDeliveryForm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrderForm';
				break;
				case 'pjActionPrintOrder':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionPrintOrder';
				break;
			}
		}
		pjRegistry::getInstance()->set('inherits', $inherits_arr);
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
		if (@$_REQUEST['controller'] == 'pjAdmin' && @$_REQUEST['action'] == 'pjActionMessages') {
			return true;
		} else {
			if (!pjAuth::factory()->hasAccess()) {
				$this->sendForbidden();
				return false;
			}
			return true;
		}
	}

	public function afterFilter() {
		parent::afterFilter();
		if ($this->isLoged() && !in_array(@$_REQUEST['action'], array('pjActionLogin'))) {
	    $this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		}
  }

	public function beforeRender() {
		
	}

	public function pjActionMessages() {
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}

	public function setLocalesData() {
    $locale_arr = pjLocaleModel::factory()
      ->select('t1.*, t2.file')
      ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
      ->where('t2.file IS NOT NULL')
      ->orderBy('t1.sort ASC')->findAll()->getData();
    $lp_arr = array();
    foreach ($locale_arr as $item) {
      $lp_arr[$item['id']."_"] = $item['file'];
    }
    $this->set('lp_arr', $locale_arr);
    $this->set('locale_str', pjAppController::jsonEncode($lp_arr));
    $this->set('is_flag_ready', $this->requestAction(array('controller' => 'pjBaseLocale', 'action' => 'pjActionIsFlagReady'), array('return')));
  }

  public function pjActionVerifyAPIKey() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_api_key_text_ARRAY_100', true)));
      }
      $option_key = $this->_post->toString('key');
      if (!array_key_exists($option_key, $this->option_arr)) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('plugin_base_api_key_text_ARRAY_101', true)));
      }
      $option_value = $this->_post->toString('value');
      if (empty($option_value)) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => __('plugin_base_api_key_text_ARRAY_102', true)));
      }
      $html = '';
      $isValid = false;
      switch ($option_key) {
        case 'o_google_maps_api_key':
          $address = preg_replace('/\s+/', '+', $this->option_arr['o_timezone']);
          $api_key_str = $option_value;
          $gfile = "https://maps.googleapis.com/maps/api/geocode/json?key=".$api_key_str."&address=".$address;
          $Http = new pjHttp();
          $response = $Http->request($gfile)->getResponse();
          $geoObj = pjAppController::jsonDecode($response);
          $geoArr = (array) $geoObj;
          if ($geoArr['status'] == 'OK')
          {
              $isValid = true;
          }
        break;
        default:
          // API key for an unknown service. We can't verify it so we assume it's correct.
          $isValid = true;
    	}
      if ($isValid) {
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('plugin_base_api_key_text_ARRAY_200', true), 'html' => $html));
      } else {
        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => __('plugin_base_api_key_text_ARRAY_103', true), 'html' => $html));
      }
    }
    exit;
  }

  public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $pjOrderModel = pjOrderModel::factory();
    $cnt_delivery_orders = $pjOrderModel->where("type", "delivery")->where("DATE(t1.d_dt) = CURDATE()")->findCount()->getData();
    $amount_delivery_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->where("type", "delivery")->where("DATE(t1.d_dt) = CURDATE()")->findAll()->getData();
    $cnt_pickup_orders = $pjOrderModel->reset()->where("type", "pickup")->where("DATE(t1.p_dt) = CURDATE()")->findCount()->getData();
    $amount_pickup_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->where("type", "pickup")->where("DATE(t1.p_dt) = CURDATE()")->findAll()->getData();
    $cnt_orders = $pjOrderModel->reset()->where("DATE(t1.d_dt) = CURDATE() or DATE(t1.p_dt) = CURDATE()")->findCount()->getData();
    $amount_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->where("DATE(t1.d_dt) = CURDATE() or DATE(t1.p_dt) = CURDATE()")->findAll()->getData();
    
    $this->set('cnt_delivery_orders', $cnt_delivery_orders);
    $this->set('amount_delivery_orders', !empty($amount_delivery_orders) ? $amount_delivery_orders[0]['amount'] : 0);
    $this->set('cnt_pickup_orders', $cnt_pickup_orders);
    $this->set('amount_pickup_orders', !empty($amount_pickup_orders) ? $amount_pickup_orders[0]['amount'] : 0);
    $this->set('cnt_orders', $cnt_orders);
    $this->set('amount_orders', !empty($amount_orders) ? $amount_orders[0]['amount'] : 0);
		$role_id = $this->getRoleId();
		if ($role_id == 4) {
			$timezone = $this->option_arr['o_timezone']? $this->option_arr['o_timezone']: ADMIN_TIME_ZONE;
      date_default_timezone_set($timezone);
			$today = date( 'Y-m-d', time () );	
			$toDay = $today . " " .  "00:00:00";
			$latest_delivery = $pjOrderModel
			->reset()
			->join('pjClient', "t2.id=t1.client_id", 'left outer')
			->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
			->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
			->select('t1.*, t4.name AS client_name, t3.content as location')
			->where("type", "delivery")
			->where("(t1.d_dt >= '$toDay')")
			->orderBy("d_dt DESC")
			->limit(6)
			->findAll()
			->getData();
			
			$latest_pickup = $pjOrderModel
			->reset()
			->join('pjClient', "t2.id=t1.client_id", 'left outer')
			->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
			->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
			->select('t1.*, t4.name AS client_name, t3.content as location')
			->where("type", "pickup")
			//Added by JR
			->where('deleted_order', 0)
			//End of it
			->where("(t1.p_dt >= '$toDay')")
			->orderBy("p_dt DESC")
			->limit(6)
			->findAll()
			->getData();
		} else {
			$latest_delivery = $pjOrderModel
			->reset()
			->join('pjClient', "t2.id=t1.client_id", 'left outer')
			->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
			->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
			->select('t1.*, t4.name AS client_name, t3.content as location')
			->where("type", "delivery")
			//Added by JR
			->where('deleted_order', 0)
			//End of it
			->orderBy("d_dt DESC")
			->limit(6)
			->findAll()
			->getData();
			
			$latest_pickup = $pjOrderModel
			->reset()
			->join('pjClient', "t2.id=t1.client_id", 'left outer')
			->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
			->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
			->select('t1.*, t4.name AS client_name, t3.content as location')
			->where("type", "pickup")
			//Added by JR
			->where('deleted_order', 0)
			//End of it
			->orderBy("p_dt DESC")
			->limit(6)
			->findAll()
			->getData();
		}

    $this->set('latest_delivery', $latest_delivery);
    $this->set('latest_pickup', $latest_pickup);  
    $location_arr = pjWorkingTimeModel::factory()
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.location_id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->select('t1.*, t2.content as location_title')
      ->findAll()
      ->getData();
    $week_day = strtolower(date("l"));
    $current_time = time();
    $current_date = date('Y-m-d');
    foreach($location_arr as $k => $v) {
    	$p_start_time = strtotime($current_date . ' ' . $v['p_' . $week_day . '_from']);
			$p_end_time = strtotime($current_date . ' ' . $v['p_' . $week_day . '_to']);	
    	if ($p_end_time < $p_start_time) {
				$p_end_time += 86400;
			}			
      if ($p_start_time <= $current_time && $current_time <= $p_end_time) {
        $v['pickup'] = __('lblOpened', true);
      } else {
        $v['pickup'] = __('lblClosed', true);
      }
            
      $d_start_time = strtotime($current_date . ' ' . $v['d_' . $week_day . '_from']);
			$d_end_time = strtotime($current_date . ' ' . $v['d_' . $week_day . '_to']);	
    	if ($d_end_time < $d_start_time) {
				$d_end_time += 86400;
			}	
      if ($d_start_time <= $current_time && $current_time <= $d_end_time) {
        $v['delivery'] = __('lblOpened', true);
      } else {
        $v['delivery'] = __('lblClosed', true);
      }
      $location_arr[$k] = $v;
    }
    $this->set('location_arr', $location_arr);
    $this->appendCss('dashboard.css');
  }
  public function isClient() {
    return true;
	}
	public function pr($data) {
		echo '<pre>'; print_r($data); echo '</pre>';
	}
	public function pr_die($data) {
		echo '<pre>'; print_r($data); echo '</pre>'; die;
	}
	public function getCategoryList() {
		return $category_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("t1.order ASC")
        ->findAll()
        ->getData();
	}
	public function getProduct($productID) {
		$pjProductModel = pjProductModel::factory();
		return $pjProductModel->reset()->join('pjProductCategory', "t1.id = t2.product_id") ->select(sprintf("t1.*, t2.*, (SELECT COUNT(*) FROM `%s` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras",pjProductExtraModel::factory()
      ->getTable()))->find($productID)->getData();
	}
	public function saveOrderItems($post, $order_id, $delivered_return = false) {
		// $this->pr($post);
		// exit;
		if (isset($post['product_id']) && count($post['product_id']) > 0) {
      $pjOrderItemModel = pjOrderItemModel::factory();
      $pjProductPriceModel = pjProductPriceModel::factory();
      $pjProductModel = pjProductModel::factory();
      $pjExtraModel = pjExtraModel::factory();
      $category_arr = $this->getCategoryList();
      $category_order = array_column($category_arr, 'order', 'id');
      $returnOrCancelProducts = array();
      $returnOrCancelReasons = array();
      if (array_key_exists('return_or_cancel', $post)) {
      	$returnOrCancelProducts = array_filter($post['return_or_cancel']);
      	if (array_key_exists('return_or_cancel_reason', $post)) {
      		$returnOrCancelReasons = array_filter($post['return_or_cancel_reason']);
      	}
      }
      if (!$delivered_return) {
	      foreach ($post['product_id'] as $k => $pid) {
	      	$product = array();
	      	$item_order = ":NULL";
	      	$hash = md5(uniqid(rand() , true));
	      	if ($pid) {
	      		$product = $this->getProduct($pid);
	        	$item_order = $category_order[$product['category_id']];
	      	} 
	        if (strpos($k, 'new_') === 0) {
	          list($type, $custom_name, $price_id, $price, $print) = $this->getPriceDetails($pid, $k, $post, $product);
	          $oid = $pjOrderItemModel->reset()
	            ->setAttributes(array(
	            'order_id' => $order_id,
	            'foreign_id' => $pid,
	            'type' => $type,
	            'hash' => $hash,
	            'custom_name'=>$custom_name,
	            'item_order'=> $item_order,
	            'price_id' => $price_id,
	            'price' => $price,
	            'cnt' => $post['cnt'][$k],
	            'print' => $print,
	            'special_instruction' => $post['special_instruction'][$k],
	            'custom_special_instruction' => $post['custom_special_instruction'][$k]
	          ))->insert()
	            ->getInsertId();
	          if ($product['cnt_extras'] && $oid !== false && (int)$oid > 0) {
	            if (array_key_exists($k, $post['extras']) && isset($post['extras'][$k]) && $post['extras'][$k]!= "") {
	               $pjOrderItemModel->reset()
	                  ->setAttributes(array(
	                  'order_id' => $order_id,
	                  'foreign_id' => ':NULL',
	                  'type' => 'extra',
	                  'hash' => $hash,
	                  'price_id' => ':NULL',
	                  'price' => ':NULL',
	                  'cnt' => ':NULL',
	                  'special_instruction' => $post['extras'][$k],
	                  'custom_special_instruction' => ':NULL'
	                ))->insert();
	            }
	          }
	        } 

	        else if (strpos($k, '_RC') !== false) { 
	        	list($type, $custom_name, $price_id, $price, $print) = $this->getPriceDetails($pid, $k, $post, $product);
	        	$parentKey = str_replace("_RC","",$k);
	        	$pjParentOrder = pjOrderItemModel::factory();
	          $parentRow = $pjParentOrder->where('hash', $parentKey)->where('order_id', $order_id)->findAll()->getData();
	        	$cancelOrReturnReason = $post['return_or_cancel_reason'][$k];
	      		$productStatus = strtolower($post['return_or_cancel'][$k]);
	      		
	        	$oid = $pjOrderItemModel->reset()
	            ->setAttributes(array(
	            'order_id' => $order_id,
	            'foreign_id' => $pid,
	            'type' => $type,
	            'hash' => $hash,
	            'custom_name'=>$parentRow[0]['custom_name'],
	            'item_order'=> $item_order,
	            'price_id' => $price_id,
	            'price' => $price,
	            'cnt' => $post['cnt'][$k],
	            'print' => $print,
	            'special_instruction' => $post['special_instruction'][$k],
	            'custom_special_instruction' => $post['custom_special_instruction'][$k],
	            'status'=>$productStatus,
	            'cancel_or_return_reason'=>$cancelOrReturnReason,
	            'parent_hash'=>$k
	          ))->insert()
	          ->getInsertId();
	          if ((int)$oid > 0) {
	          	
	          	$pjOrder = pjOrderItemModel::factory();
	          	$rows = $pjOrder->where('hash', $parentKey)->where('order_id', $order_id)->modifyAll(array('cnt' => $post['cnt'][$parentKey]))->getAffectedRows();

	          }
	        }
	      }
    	}

      if (count($returnOrCancelReasons)) {
      	foreach ($returnOrCancelReasons as $key => $reason) {
      		$pjOrder = pjOrderItemModel::factory();
      		$cancelOrReturnReason = $reason;
      		$productStatus = strtolower($post['return_or_cancel'][$key]);
      		if ($delivered_return) {
      			$rows = $pjOrder->where('hash', $key)->where('order_id', $order_id)->modifyAll(array('status' => $productStatus, 'cancel_or_return_reason'=>$cancelOrReturnReason))->getAffectedRows();
      		} else {
      			$rows = $pjOrder->where('hash', $key)->where('order_id', $order_id)->modifyAll(array('status' => $productStatus, 'cancel_or_return_reason'=>$cancelOrReturnReason, 'print' => 0))->getAffectedRows();
      		}
      	}
      }
    }
    return true;
	}

	public function getPriceDetails($pid, $prdHash, $post, $product) {
		$type = "product";
		$price = 0;
    $print = $custom_name = $price_id = ":NULL";
		$pjProductPriceModel = pjProductPriceModel::factory();
		if ($pid == 0) {	
      $price = $post['price_id'][$prdHash];
      $type = "custom";
      $custom_name = $post['product_description'][$prdHash];
    } else  {
    	if ($product['set_different_sizes'] == 'T') {
	      $price_id = $post['price_id'][$prdHash];
	      $price_arr = $pjProductPriceModel->reset()->find($price_id)->getData();
	      if ($price_arr) {
	        $price = $price_arr['price'];
	      }
    	} else {
      	$price = $product['price']; 
    	}
    	if (!$product['is_kitchen']) {
      	$print = $post['cnt'][$prdHash];
      }
    } 
    return array($type, $custom_name, $price_id, $price, $print);
	}
	//Megamind
  public function getRestaurantTables() {
    $pjTableModel = pjTableModel::factory();
    $tables_arr = $pjTableModel->select('id, name')
      ->where('status', 1)
      ->findAll()
      ->getData();
    $table_list = [];
    foreach ($tables_arr as $table) {
      $table_list[$table['id']] = $table['name'];
    }
    return $table_list;
  }
  public function getOrderItems($id, $isForPrinting=false, $kPrint=false) {
    if ($id) {
      $korder = pjOrderModel::factory()->select("t1.*")->find($id)->getData();
      $korder['kprint'] = 1;

      $pjOrder = pjOrderModel::factory();
      $pjOrder->where('id', $id)->limit(1)->modifyAll($korder);

      $pjOrderModel = pjOrderModel::factory();

      $arr = $pjOrderModel
    ->join('pjAuthUser', "t2.id=t1.chef_id", 'left')
    ->join('pjTable', "t3.id=t1.table_name", 'left')
      ->select("t1.*, t2.name AS chef_name, t3.name as table_ordered_name,
              AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,
              AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
              AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
              AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
      ->find($id)
    ->getData();
      if (empty($arr)) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08");
      }
     
      $pjProductPriceModel = pjProductPriceModel::factory();
      $oi_arr = array();
      $pjOrderItemObject = pjOrderItemModel::factory()->where('t1.order_id', $arr['id']);
      $_oi_arr = $pjOrderItemObject->orderBy("status ASC, item_order ASC")->findAll()->getData();

      if ($kPrint) {
        foreach ($_oi_arr as $key=>$item) {
          if ($item['print'] != 0) {
            unset ($_oi_arr[$key]);
          }
        }
      }
      if (empty($_oi_arr)) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
      }
      $product_ids = array_column($_oi_arr, 'foreign_id');

      $product_arr = pjProductModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()) , 'left')
        ->select(sprintf("t1.*, t2.content AS name,
                (SELECT GROUP_CONCAT(extra_id SEPARATOR '~:~') FROM `%s` WHERE product_id = t1.id GROUP BY product_id LIMIT 1) AS allowed_extras,
                (SELECT COUNT(*) FROM `%s` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras", pjProductExtraModel::factory()
        ->getTable() , pjProductExtraModel::factory()
        ->getTable()))
        ->orderBy("name ASC")
        ->whereIn('t1.id', $product_ids)
        ->findAll()
        ->toArray('allowed_extras', '~:~')
        ->getData();
      $product_arr = array_combine(array_column($product_arr, 'id'),$product_arr);
      $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t1.*, t2.content AS name")
          ->where("t1.id IN (SELECT TPE.extra_id FROM `" . pjProductExtraModel::factory()
          ->getTable() . "` AS TPE)")->orderBy("name ASC")
          ->whereIn('t1.id', $product_ids)
          ->findAll()
          ->getData();
      $extras = array();
      $oi_extras = array();
      foreach ($extra_arr as $extra) {
        $extras[$extra['id']] = $extra;
      }
      foreach ($_oi_arr as $item) {
      	$item['is_kitchen'] = 1;
        $item['is_web_orderable'] = 1;
        $item['is_veg'] = 1;
        if ($item['type'] == 'product') {
          $item['price_arr'] = $pjProductPriceModel->reset()
            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()) , 'left')
            ->select("t1.*, t2.content AS price_name")
            ->where('product_id', $item['foreign_id'])->findAll()
            ->getData();
            if ($item['price_id'] != '') {
              foreach ($item['price_arr'] as $price_data) {
                if ($price_data['id'] == $item['price_id']) {
                  $item['size'] = $price_data['price_name'];
                  break;
                }
              }
            } else {
              $item['size'] = '';
            }
            $item['product_name'] = $product_arr[$item['foreign_id']]['name'];
            $item['is_kitchen'] = $product_arr[$item['foreign_id']]['is_kitchen'];
            $item['is_web_orderable'] = $product_arr[$item['foreign_id']]['is_web_orderable'];
            $item['is_veg'] = $product_arr[$item['foreign_id']]['is_veg'];
        }
        if ($item['type'] == 'extra') {
          $extras = json_decode($item['special_instruction']);
          $oi_extras[$item['hash']] = $extras;
        } else {
					if ($item['special_instruction']) {
						$item['js_special_instruction'] = 1;
					}
					if ($item['custom_special_instruction']) {
						$item['js_custom_special_instruction'] = 1;
					}
          $oi_arr[] = $item;
        }
				
      }
      if ($isForPrinting) {
        return compact('arr', 'product_arr', 'oi_arr', 'oi_extras');
      } else {
        $this->set('arr', $arr);
        $this->set('product_arr', $product_arr);
        $this->set('oi_arr', $oi_arr);
        $this->set('oi_extras', $oi_extras);
      }
    }
  }

  function getDateFormatted($date) {
    $dt = explode(" ", $date);
    $dtArr = explode("-", $dt[0]);
    $dt = $dtArr[2] . "-" . $dtArr[1] . "-" . $dtArr[0] . " " . $dt[1];
    return $dt;
  }

  public function savePaymentResponse($post, $order_id, $method='sale', $payment_vendor='dojo') {
  	$pjPaymentResponseModel = pjPaymentResponseModel::factory();
  	$paymentID = $pjPaymentResponseModel->reset()
      ->setAttributes(array(
      'order_id' => $order_id,
      'method' => $method,
      'payment_vendor' => $payment_vendor,
      'response' => $post['response'],
      'is_active' => 1,
    ))->insert()
      ->getInsertId();
    return $paymentID;
  }

  public function kitchenPrintFormat($printer, $data, $special_instructions, $newItem = false) {
    foreach ($data['oi_arr'] as $k => $oi) {
      if ($oi['is_kitchen']) {
        $lineItem = '';
        if ($oi['special_instruction'] || array_key_exists($oi['hash'], $data['oi_extras'])) {
          for ($i = 0, $counter = 0; $i < $oi['cnt'] ; $i++, $counter++) {
            $lineItem = '';
            if ($oi['type'] == 'custom') {
              $lineItem = "1 x ".strtoupper($oi['custom_name']);
            } else {
              $lineItem = "1 x ".strtoupper($oi['product_name'])." ".$oi['size'];
            }
            
            $printer->appendText("$lineItem");
            if (array_key_exists($oi['hash'], $data['oi_extras']) && isset($data['oi_extras'][$oi['hash']][$counter])) { 
              $extra = $data['oi_extras'][$oi['hash']][$counter];
              $printer->lineFeed();
              $printer->appendText(" - ".$extra->extra_name ." x ".$extra->extra_count);
            }
            if ($oi['special_instruction']) {
              $obj = json_decode($oi['special_instruction'], true);
              if (isset($obj[$counter])) {
                if ($obj[$counter]['ids']) {
                  $printer->lineFeed();
                  $selected_ins_arr = explode(',', $obj[$counter]['ids']);
                  foreach ($selected_ins_arr as $ins) {
                    foreach ($special_instructions as $instruction) {
                      if ($ins == $instruction['id']) {
                        $printer->appendImage($instruction['image'], 0, 30);
                      }
                    }
                  }
                }
                if ($obj[$counter]['cus_ins']) {
                  $printer->lineFeed();
                  $printer->appendText(str_repeat(" ", 4).$obj[$counter]['cus_ins']."\n");
                } 
              }
            }
            $printer->lineFeed();
          }
        } else {
          if ($oi['type'] == 'custom') {
            $lineItem = $oi['cnt']." x ".strtoupper($oi['custom_name']);
          } else {
            $lineItem = $oi['cnt']." x ".strtoupper($oi['product_name'])." ".$oi['size'];
          }
          $printer->appendText("$lineItem");
          $printer->lineFeed();
        }
      } 
    }
  }

  public function getTodayOrderCount() {
		$pjOrderModel = pjOrderModel::factory();
		$todayOrderCnt = $pjOrderModel->where('date(t1.created) >= date(now())')->findCount()->getData();
		return $todayOrderCnt ? $todayOrderCnt + 1: 1;

	}

}
?>