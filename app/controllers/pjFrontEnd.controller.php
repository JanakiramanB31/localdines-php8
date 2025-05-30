<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontEnd extends pjFront {
	public function __construct() {
		parent::__construct();
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionLoadCss() { 
    $dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
    $dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
    $theme = $this->option_arr['o_theme'];
    if($this->_get->check('layout')) {
      if(in_array($this->_get->toString('layout'), array('theme1', 'theme2', 'theme3', 'theme4', 'theme5', 'theme6', 'theme7', 'theme8', 'theme9', 'theme10'))) {
          $theme = $this->_get->toString('layout');
      }
    }
    $arr = array(
    	array('file' => 'font-awesome.min.css', 'path' => $dm->getPath('font_awesome') . 'css/'),
			array('file' => 'bootstrap.min.css', 'path' => $dm->getPath('bootstrap5') . 'css/'),
			array('file' => 'bootstrap-icons.css', 'path' => $dm->getPath('bootstrap_icons')),
			array('file' => 'boxicons.min.css', 'path' => $dm->getPath('boxicons') . 'css/'),
      array('file' => 'datepicker.css', 'path' => $dm->getPath('pj_bootstrap_datepicker')),
      array('file' => 'calendar.css', 'path' => $dm->getPath('calendarjs')),
      array('file' => 'FoodDelivery.css', 'path' => PJ_CSS_PATH),
			array('file' => 'style_front.css', 'path' => PJ_CSS_PATH),
      array('file' => "themes/$theme.css", 'path' => PJ_CSS_PATH)
    );
    header("Content-Type: text/css; charset=utf-8");
    foreach ($arr as $item) {
      ob_start();
      @readfile($item['path'] . $item['file']);
      $string = ob_get_contents();
      ob_end_clean();
      if ($string !== FALSE) {
        echo str_replace(
          array('../img/', '../fonts/glyphicons', '../fonts/fontawesome', '[URL]', "pjWrapper"),
          array(
	          PJ_INSTALL_URL . PJ_IMG_PATH,
	          PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/fonts/glyphicons',
	          PJ_INSTALL_URL . $dm->getPath('font_awesome') . 'fonts/fontawesome',
	          PJ_INSTALL_URL,
	          "pjWrapperFoodDelivery_" . $theme
          ),
          $string
          ) . "\n";
      }
    }
    exit;
	}
	
	public function pjActionLoad() {
    $this->_get->toString('cat');
    $this->setAjax(false);
    $this->setLayout('pjActionFront');
    ob_start();
    header("Content-Type: text/javascript; charset=utf-8");
    if($this->_get->toInt('hide') > 0) {
      $this->session->setData($this->defaultLangMenu, 'hide');
    } else {
      $this->session->setData($this->defaultLangMenu, 'show');
		}
		if (!$this->session->getData($this->defaultLocale)) {
      $this->session->setData($this->defaultLocale, $this->_get->toInt('locale'));
    }
    $days_off = array();
    $w_arr = pjWorkingTimeModel::factory()->orderBy("t1.location_id ASC")->findAll()->getData();
    foreach ($w_arr as $w) {
      if ($w['p_monday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 1;
      }
      if ($w['p_tuesday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 2;
      }
      if ($w['p_wednesday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 3;
      }
      if ($w['p_thursday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 4;
      }
      if ($w['p_friday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 5;
      }
      if ($w['p_saturday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 6;
      }
      if ($w['p_sunday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['pickup'][] = 0;
      }
      if ($w['d_monday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 1;
      }
      if ($w['d_tuesday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 2;
      }
      if ($w['d_wednesday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 3;
      }
      if ($w['d_thursday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 4;
      }
      if ($w['d_friday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 5;
      }
      if ($w['d_saturday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 6;
      }
      if ($w['d_sunday_dayoff'] == 'T') {
        $days_off[$w['location_id']]['delivery'][] = 0;
      }
    }
    $this->set('days_off', $days_off);
    $dates_off = $dates_on = array();
    $d_arr = pjDateModel::factory()->where("t1.date >= CURDATE()")->findAll()->getData();
    foreach ($d_arr as $date) {
      if ($date['is_dayoff'] == 'T') {
        $dates_off[$date['location_id']][$date['type']][] = $date['date'];
      } else {
        $dates_on[$date['location_id']][$date['type']][] = $date['date'];
      }
    }
    $this->set('dates_on', $dates_on);
    $this->set('dates_off', $dates_off);
    $this->set('menu_category', $this->_get->toString('cat'));
	}
	
	public function pjActionLocale() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($locale_id = $this->_get->toInt('locale_id')) {
        $this->pjActionSetLocale($locale_id);
        $this->loadSetFields(true);
        $day_names = __('day_names', true);
        ksort($day_names, SORT_NUMERIC);
        $months = __('months', true);
        ksort($months, SORT_NUMERIC);
        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale have been changed.', 'opts' => array(
            'day_names' => array_values($day_names),
            'month_names' => array_values($months)
        )));
      }
    }
    exit;
	}
	
	public function pjActionCaptcha() {
    $this->setAjax(true);
    header("Cache-Control: max-age=3600, private");
    $rand = $this->_get->toInt('rand') ?: rand(1, 9999);
    $patterns = 'app/web/img/button.png';
    if(!empty($this->option_arr['o_captcha_background_front']) && $this->option_arr['o_captcha_background_front'] != 'plain') {
      $patterns = PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_IMG_PATH') . 'captcha_patterns/' . $this->option_arr['o_captcha_background_front'];
    }
    $Captcha = new pjCaptcha(PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_WEB_PATH') . 'obj/arialbd.ttf', $this->defaultCaptcha, (int) $this->option_arr['o_captcha_length_front']);
    $Captcha->setImage($patterns)->setMode($this->option_arr['o_captcha_mode_front'])->init($rand);
    exit;
	}
	
	public function pjActionCheckCaptcha() {
    $this->setAjax(true);
    if (!$this->_get->check('captcha') || !$this->_get->toString('captcha') || strtoupper($this->_get->toString('captcha')) != $_SESSION[$this->defaultCaptcha]){
      echo 'false';
    } else {
      echo 'true';
    }
    exit;
	}
	
	public function pjActionCheckReCaptcha() {
    $this->setAjax(true);
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->option_arr['o_captcha_secret_key_front'].'&response='.$this->_get->toString('recaptcha'));
    $responseData = json_decode($verifyResponse);
    echo $responseData->success ? 'true': 'false';
    exit;
	}
	
	public function pjActionCheckEmail() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!$this->_get->check('c_email')) {
        echo 'false';
        exit;
      }
      $c_email = $this->_get->toString('c_email');
      if(empty($c_email)) {
          echo 'false';
          exit;
      }
			$pjClientModel = pjAuthUserModel::factory()->join('pjClient', 't2.foreign_id = t1.id', 'left')->where('t1.email', $c_email);
	    if ($this->isFrontLogged()) {
        $pjClientModel->where('t2.foreign_id !=', $this->getClientId());
	    }
    	echo $pjClientModel->findCount()->getData() == 0 ? 'true' : 'false';
    }
    exit;
	}
	
	public function pjActionUpdateProfile() {
    $this->isAjax = true;
    if ($this->isXHR()) {
      if (self::isPost() && $this->_post->check('profile')) {
        echo '<pre>'; print_r($_SESSION[$this->defaultClient]); echo '<pre>';
        return;
        if ($this->isFrontLogged()) {
          $post = $this->_post->raw();
          $post['u_surname'] = $this->_post->toString('surname');
          $post['c_postcode'] = $this->_post->toString('post_code');
          $id = $this->_post->toInt('id');
          $pjClientModel = pjClientModel::factory();
          pjAuthUserModel::factory()->set('id', $id)->modify($post);
          unset($post['id']);
          $pjClientModel->where('foreign_id', $this->_post->toInt('id'))->limit(1)->modifyAll($post);
          pjFrontClient::init(array('id' => $id))->setClientSession();
          pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('front_profile_updated', true)));
        } else {
          pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_login_err_ARRAY_3', true)));
        }
      }
    }
	}
	
	public function pjActionLogout() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->isFrontLogged()) { 
         unset($_SESSION[$this->defaultUser]);
      	if (isset($_SESSION['guest'])) {
					// echo "comes inside guest";
      		unset($_SESSION['guest']);
					unset($_SESSION['guest_title']);
      		unset($_SESSION['guest_firstname']);
      		unset($_SESSION['guest_surname']);
      		unset($_SESSION['guest_email']);
      		unset($_SESSION['guest_phone']);
					unset($_SESSION['guest_otp_check']);
          unset($_SESSION[$this->defaultUser]);
      	}
				if (isset($_SESSION['social_login'])) {
        		unset($_SESSION['social_login']);
      	} 
      	$this->session->unsetData($this->defaultClient);
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Successfully Logged out!'));
      } 
      exit;
    }
	}
	
	public function  pjActionValidateOtp() {
		$this->setAjax(true);
    if ($this->isXHR()) {
			$otp_entered = $_REQUEST['entered_otp'];
			if ($otp_entered == $_SESSION['otp']) {
				$response = jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'valid OTP'));
				return $response;
			} else {
				$response = jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Invalid OTP'));
				return $response;
			}
		}
	}

	public function pjActionGetPickupLocations() {
    $this->setAjax(true);
  	if ($this->isXHR()) {
      $arr = pjLocationModel::factory()
      ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
      ->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'address'", 'left')
      ->select('t1.*, t2.content AS name, t3.content AS address')
      ->findAll()
      ->getData();
      pjAppController::jsonResponse($arr);
    }
    exit;
	}
	
	public function pjActionGetLocation() {
  	$this->setAjax(true);
    if ($this->isXHR()) {
      if ($id = $this->_get->toInt('id')) {
        $arr = pjLocationModel::factory()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
        ->select('t1.*, t2.content AS name, t3.content AS address')
        ->find($id)
        ->getData();
        
        if ($arr) {
          $arr['status'] = 'OK';
        } else {
          $arr['status'] = 'ERR';
        }
      	pjAppController::jsonResponse($arr);
    	}
    	pjAppController::jsonResponse(array('status' => 'ERR'));
    }
    exit;
	}
	
	public function pjActionGetLocations() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjLocationCoordModel = pjLocationCoordModel::factory();
      $arr = pjLocationModel::factory()
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
      ->select('t1.*, t2.content AS name, t3.content AS address')
      ->findAll()
      ->getData();
	    foreach ($arr as $k => $v) {
        $arr[$k]['coords'] = $pjLocationCoordModel->reset()->where('t1.location_id', $v['id'])->findAll()->getData();
	    }
      pjAppController::jsonResponse($arr);
    }
    exit;
	}

	// To set the type of an order on main page
	public function pjActionSetType() {
		$this->setAjax(true);
    if ($this->isXHR()) {
      if (!empty($this->_get->toString('type'))) {
				$type = $this->_get->toString('type');
				$this->_set('type', $type);
				if ($type == 'delivery') {
					$postcode = $this->_get->toString('postcode');
					$this->_set('post_code', $postcode);
				}
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionSetTypes() {
    $this->setAjax(true);
	  if ($this->isXHR()) {
      if ($this->_post->check('loadTypes')) {
        $type = $this->_post->toString('type');
        $this->_set('type', $type);
        if ($type == 'delivery') {
          $this->_set('d_date', $this->_post->toString('d_date'));
          $this->_set('d_time', $this->_post->toString('d_time'));
          $this->_set('d_location_id', $this->_post->toInt('d_location_id'));
          $this->_set('d_address_1', $this->_post->check('d_address_1') ? $this->_post->toString('d_address_1') : NULL);
          $this->_set('d_address_2', $this->_post->check('d_address_2') ? $this->_post->toString('d_address_2') : NULL);
          $this->_set('d_country_id', $this->_post->check('d_country_id') ? $this->_post->toInt('d_country_id') : NULL);
          $this->_set('post_code', $this->_post->check('post_code') ? $this->_post->toString('post_code') : NULL);
          $this->_set('d_state', $this->_post->check('d_state') ? $this->_post->toString('d_state') : NULL);
          $this->_set('d_city', $this->_post->check('d_city') ? $this->_post->toString('d_city') : NULL);
          $this->_set('d_zip', $this->_post->check('d_zip') ? $this->_post->toString('d_zip') : NULL);
          $this->_set('d_notes', $this->_post->check('d_notes') ? $this->_post->toString('d_notes') : NULL);
          $arr = pjPriceModel::factory()
          ->where('t1.location_id', $this->_get('d_location_id'))
          ->where('t1.total_from <= ' . $this->_get('price'))
          ->where('t1.total_to >= ' . $this->_get('price'))
          ->limit(1)
          ->findAll()
          ->getData();
          $delivery = 0;
          if (count($arr) === 1) {
            $delivery = $arr[0]['price'];
          }
          $this->_set('delivery', $delivery);
          $unset = array('p_location_id', 'p_date', 'p_time', 'p_notes');
        } else {
          $this->_set('p_location_id', $this->_post->toInt('p_location_id'));
          $this->_set('p_date', $this->_post->toString('p_date'));
          $this->_set('p_time', $this->_post->toString('p_time'));
          $this->_set('p_notes', $this->_post->check('p_notes') ? $this->_post->toString('p_notes') : NULL);
          $unset = array('delivery', 'd_location_id', 'd_date', 'd_time', 'd_address_1', 'd_address_2', 'd_country_id', 'd_state', 'd_city', 'd_zip', 'd_notes');
        }
        foreach ($unset as $idx) {
        	if ($this->_is($idx)) {
        		$this->_unset($idx);
        	}
        }
      }
      pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
    }
    exit;
	}
	
	public function pjActionCheckLogin()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $data = array();
	        $data['login_email'] = $this->_post->toString('login_email');
	        $data['login_password'] = $this->_post->toString('login_password');
			$response = pjFrontClient::init($data)->doClientLogin();
			$code = (int) $response['code'];
			$login_err = __('plugin_base_login_err', true);
			$text = '';
			if(isset($login_err[$code]))
			{
				if($code == 5)
				{
					$text = sprintf($login_err[$code], (int)$tpl['option_arr']['o_failed_login_lock_after']);
				}else{
					$text = $login_err[$code];
				}
			} else {
				if ($this->_post->toString('login_remember') == 'on') {
					setCookie('login_email', $data['login_email'], time() + (86400 * 30), "/");
					setCookie('login_password', $data['login_password'], time() + (86400 * 30), "/");
				} else {
					setCookie('login_email', '');
					setCookie('login_password', '');
				}
			}
			pjAppController::jsonResponse(array('status' => $response['status'], 'code' => $code, 'text' => $text));
		    
	    }
	    exit;
	}

	public function pjActioncheckMailId() {
		$this->setAjax(true);
    if ($this->isXHR()) {
      $mailId = $this->_post->toString('loginMail');
			$client = pjAuthUserModel::factory()
			          ->join("pjClient", 't2.foreign_id = t1.id', 'left outer')
			          ->select('t1.*, t2.*')
					  ->where("t1.email", $mailId)
					  ->findAll()
					  ->getData();	
			if (count($client) > 0) {
				if($client[0]['register_type'] != 'S') {
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Can sign up'));
				} else {
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 201, 'text' => 'Signed up with gmail'));
				}
				
	    } else {
          pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 400, 'text' => 'No user found!'));
			}
    }
    exit;
	}
    
	public function pjActionResendOtp() {
    $this->setAjax(true);
    if ($this->isXHR()) {
			$otp = mt_rand(100000, 999999); 
			$_SESSION['otp'] = $otp;
			$msg = sprintf(OTP_MESSAGE, $otp, DOMAIN, WEB_CONTACT_NO);
			$c_phone = $_SESSION[$this->defaultForm]['c_phone'];
			$response = $this->sendMessage($c_phone, $msg);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'OTP Sent', 'msg'=>$msg));
    }
		exit;
	}

	public function pjActionCheckOtp() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $otp_entered = $this->_post->toString('digit-1').$this->_post->toString('digit-2').$this->_post->toString('digit-3')
			               .$this->_post->toString('digit-4').$this->_post->toString('digit-5').$this->_post->toString('digit-6');
			if ($otp_entered == $_SESSION['otp']) {
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'OTP success'));
				unset($_SESSION['otp']);
			} else {
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 500, 'text' => 'OTP invalid'));
			}
			if (isset($_SESSION['guest']) && $_SESSION['guest_otp_check'] == false) {
				$_SESSION['guest_otp_check'] == true;
			} 
    }
	  exit;
	}
	
	public function pjActionSendPassword() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $params = array();
      $params['locale_id'] = $this->getLocaleId();
      $params['email'] = $this->_post->toString('email');
      $resp = pjFrontClient::init($params)->doSendPassword();
      pjAppController::jsonResponse($resp);
    }
    exit;
	}
	
	public function pjActionSaveForm() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->session->has($this->defaultForm)) {
        $this->session->setData($this->defaultForm, array());
      }
	    $this->session->setData($this->defaultForm, $this->_post->raw());
			$c_info = array();
			$c_info['phone'] = $_SESSION[$this->defaultForm]['c_phone'];
      if (isset( $_SESSION[$this->defaultForm]['c_email'])) {
        $c_info['email'] = $_SESSION[$this->defaultForm]['c_email'];
      }
			$client = pjFrontClient::init($c_info)->getClientByPhoneNumber();
			if ($client == FALSE || (isset($_SESSION['guest']) && $_SESSION['guest_otp_check'] == false)) {
				$otp = mt_rand(100000, 999999); 
				$_SESSION['otp'] = $otp;
				$msg = sprintf(OTP_MESSAGE, $otp, DOMAIN, WEB_CONTACT_NO);
				$response = $this->sendMessage($c_info['phone'], $msg);
				$resp = array('code' => 200, 'user' => 'new', 'message'=>$msg);
			} else {
				$resp = array('code' => 200, 'user' => 'old');
			}
	    pjAppController::jsonResponse($resp);
    }
		exit;
	}

	
	public function pjActionSaveOrder() {
  	$this->setAjax(true);
  	if ($this->isXHR()) {
      $STORAGE = $this->session->getData($this->defaultStore);
      $FORM = $this->session->getData($this->defaultForm);
      $check_data = array_merge($STORAGE, $FORM);
      // print_r($STORAGE);
      if (isset($_SESSION['guest'])) {
        $FORM['c_email'] = 'guest_'.uniqid (rand(), true).'@guest.com';
        $check_data['c_email'] = 'guest_'.uniqid (rand(), true).'@guest.com';
      }
      // $data['c_email']
      $response = $this->doubleCheckData($check_data);
      if ($response['status'] == 'ERR') {
        pjAppController::jsonResponse($response);
      }
      $cart = $this->_get('cart');
      $pjOrderModel = pjOrderModel::factory();
      $data = array();
      $data['status'] = $this->option_arr['o_booking_status'];
      $data['price'] = $this->_get('price');
      $data['price_packing'] = $this->_get('packing');
      $data['price_delivery'] = $this->_get('delivery');
      $data['discount'] = $this->_get('discount');
      $data['subtotal'] = $this->_get('subtotal');
      $data['tax'] = $this->_get('tax');
      $data['total'] = $this->_get('total');
      $data['uuid'] = time();
      $data['locale_id'] = $this->getLocaleId();
      $data['ip'] = $_SERVER['REMOTE_ADDR'];
      $data['phone_no'] = $FORM['c_phone'];
      $data['first_name'] = $FORM['c_name'];
      $data['sms_email'] = $FORM['c_email'];
			$data['origin'] = 'Web';
      switch ($this->_get('type')) {
      	case 'pickup':
					$data['type'] = 'pickup & call';
          if ($this->_get('p_time') == 'asap') {
            $data['p_dt'] = pjDateTime::formatDate($this->_get('p_date'), $this->option_arr['o_date_format']) . " " . date('H:i:s');
            $data['p_asap'] = "T";
						$data['p_time'] = 0;
						$data['delivery_dt'] = $data['p_dt'];
          } else {
            if ($this->_is('next_day')) {
              $data['p_dt'] = $this->_get('next_day');
							$data['delivery_dt'] = $data['p_dt'];
            } else {
            	$data['p_dt'] = pjDateTime::formatDate($this->_get('p_date'), $this->option_arr['o_date_format']);
              //. " " . pjDateTime::formatTime($this->_get('p_time'), $this->option_arr['o_time_format']);
            	$pt = new DateTime($data['p_dt']);
							$now = new DateTime(date('Y-m-d H:i:s'));
							$interval = $pt->diff($now);
							$interval_hr = $interval->format("%h");
							$interval_min = $interval->format("%i");
							$p_time = ($interval_hr * 60) + $interval_min;
							$data['p_time'] = $p_time;
							$data['delivery_dt'] = $data['p_dt'];
						}
	                    $data['p_asap'] = "F";
          }
					$STORAGE['p_notes'] == '' ? $STORAGE['p_notes'] = NULL : NULL;
          unset($STORAGE['d_address_1']);
          unset($STORAGE['d_address_2']);
          unset($STORAGE['d_country_id']);
          unset($STORAGE['post_code']);
          unset($STORAGE['d_state']);
          unset($STORAGE['d_city']);
          unset($STORAGE['d_zip']);
          unset($STORAGE['d_notes']);
          unset($STORAGE['d_date']);
          unset($STORAGE['d_time']);
          $data['location_id'] = $this->_get('p_location_id');
        break;
        case 'delivery':
          if ($this->_get('d_time') == 'asap') {
            $data['d_dt'] = pjDateTime::formatDate($this->_get('d_date'), $this->option_arr['o_date_format']) . " " . date('H:i:s');
            $data['d_asap'] = "T";
			      $data['d_time'] = 0;
			      $data['delivery_dt'] = $data['d_dt'];
            } else {
              if ($this->_is('next_day')) {
                $data['d_dt'] = $this->_get('next_day');
              } else {
                $data['d_dt'] = pjDateTime::formatDate($this->_get('d_date'), $this->option_arr['o_date_format']) . " " . pjDateTime::formatTime($this->_get('d_time'), $this->option_arr['o_time_format']);
  							$dt = new DateTime($data['d_dt']);
  							$now = new DateTime(date('Y-m-d H:i:s'));
  							$interval = $dt->diff($now);
  							$interval_hr = $interval->format("%h");
  							$interval_min = $interval->format("%i");
  							$d_time = ($interval_hr * 60) + $interval_min;
  							$data['d_time'] = $d_time;
  							$data['delivery_dt'] = $data['d_dt'];
               }
              $data['d_asap'] = "F";
            }
            $data['location_id'] = $this->_get('d_location_id');
  					$STORAGE['d_address_2'] == '' ? $STORAGE['d_address_2'] = NULL : NULL;
  					$STORAGE['d_notes'] == '' ? $STORAGE['d_notes'] = NULL : NULL;
            unset($STORAGE['p_date']);
            unset($STORAGE['p_time']);
            unset($STORAGE['p_notes']);
        break;
      }
      unset($STORAGE['cart']);
      unset($STORAGE['subtotal']);
      unset($STORAGE['total']);
      unset($STORAGE['delivery']);
      $payment = 'none';
      if (isset($FORM['payment_method'])) {
        $payment = $FORM['payment_method'];
      }
      $is_new_client = false;
      $update_client = false;
      $is_guest = false;
      $pjClientModel = pjClientModel::factory();
      $data['client_id'] = ':NULL';
      if ($this->isFrontLogged()) {
      	if (isset($_SESSION['guest'])) {
        	$is_guest = true;
					$_SESSION['guest_title'] = $FORM['c_title']; 
          $_SESSION['guest_firstname'] = $FORM['c_name']; 
          $_SESSION['guest_surname'] = $FORM['surname'];
          $_SESSION['guest_email'] = $FORM['c_email'];
          $_SESSION['guest_phone'] = $FORM['c_phone'];
        } else {
        	$cnt = $pjClientModel
          ->where('t1.foreign_id', $this->getClientId())
          ->findCount()
          ->getData();
          if($cnt == 0) {
            $is_new_client = true;
          } else {
            $update_client = true;
          }
        }
      } else {
        $is_new_client = true;
      }
			
      if ($is_new_client == true) {
				$isClientByMobile = pjAuthUserModel::factory()->where('t1.phone', $FORM['c_phone'])->findCount()->getData();
				if ($isClientByMobile > 0) {
          $update_client = true;
				} else {
					$FORM['status'] = 'T';
					$FORM['locale_id'] = $this->getLocaleId();
					$FORM['c_type'] = "New";
					$FORM['register_type'] = "W";
					$response = pjFrontClient::init(array_merge($FORM, $STORAGE))->createClient();
					if(isset($response['client_id']) && (int) $response['client_id'] > 0)
					{
						$data['client_id'] = $response['client_id'];
					}
				}
      } else {
      	if (!$is_guest) {
    		  $client = $pjClientModel->reset()->where('foreign_id', $this->getClientId())->findAll()->getDataIndex(0);
          $data['client_id'] = $client['id'];
          $c_type = $this->getClientType($data);
          //print_r($c_type);
           pjClientModel::factory()
	        ->where('id', $client['id'])
	        ->modifyAll(array(
            'c_type' => $c_type
	        ));
      	} else {
  				$data['guest_title'] = $FORM['c_title'];
  			}
      }
      if ($update_client == true) {
        $c_data = array();
        $auth_data = array();
        if (isset($FORM['c_address_1'])) {
          $c_data['c_address_1'] = $FORM['c_address_1'];
        } else {
    		  if ($STORAGE['type'] == 'delivery') {
    			 $c_data['c_address_1'] = $STORAGE['d_address_1'];
    		  }
  	    }
        if (isset($FORM['c_address_2']))  {
          $c_data['c_address_2'] = $FORM['c_address_2'];
        } else {
  				if ($STORAGE['type'] == 'delivery') {
  					$c_data['c_address_2'] = $STORAGE['d_address_2'];
  				}
  			}
        if (isset($FORM['c_country'])) {
          $c_data['c_country'] = $FORM['c_country'];
        }
        if (isset($FORM['post_code'])) {
          $c_data['c_postcode'] = $FORM['post_code'];
        } else {
        	if ($STORAGE['type'] == 'delivery') {
        		$c_data['c_postcode'] = $STORAGE['post_code'];
        	}
        }
        if (isset($FORM['c_state'])) {
          $c_data['c_state'] = $FORM['c_state'];
        }
        if (isset($FORM['c_city'])) {
          $c_data['c_city'] = $FORM['c_city'];
        } else {
        	if ($STORAGE['type'] == 'delivery') {
        		$c_data['c_city'] = $STORAGE['d_city'];
        	}
        }
        if (isset($FORM['c_zip'])) {
          $c_data['c_zip'] = $FORM['c_zip'];
        }
        if (isset($FORM['c_title'])) {
          $c_data['c_title'] = $FORM['c_title'];
        }
        if (isset($FORM['c_company'])) {
          $c_data['c_company'] = $FORM['c_company'];
        }
        if (isset($FORM['c_notes'])) {
          $c_data['c_notes'] = $FORM['c_notes'];
        }
        if (isset($FORM['c_name'])) {
          $auth_data['name'] = $FORM['c_name'];
        }
        if (isset($FORM['surname'])) {
          $auth_data['u_surname'] = $FORM['surname'];
        }
        if (isset($FORM['c_email'])) {
          $auth_data['email'] = $FORM['c_email'];
        }
        if (isset($FORM['c_phone'])) {
          $auth_data['phone'] = $FORM['c_phone'];
        }
        if (!empty($auth_data)) {
  				if (isset($_SESSION[$this->defaultClient])) {
  					$auth_data['id'] = $this->getClientId();
  				} else {
  					$userByMobile = pjAuthUserModel::factory()
  					->where('t1.phone', $FORM['c_phone'])
  					->findAll()
  					->getData();
  					$auth_data['id'] = $userByMobile[0]['id'];
  				}
          pjAuth::init($auth_data)->updateUser();
        }
  			if (isset($_SESSION[$this->defaultClient])) {
  				$pjClientModel->reset()->where('foreign_id', $this->getClientId())->limit(1)->modifyAll($c_data);
  				$client = $pjClientModel->reset()->where('foreign_id', $this->getClientId())->findAll()->getDataIndex(0);
  				pjFrontClient::init(array('id' => $this->getClientId()))->setClientSession();
  			} else {
  				$userByMobile = pjAuthUserModel::factory()
  				->where('t1.phone', $FORM['c_phone'])
  				->findAll()
  				->getData();
  				$pjClientModel->reset()->where('foreign_id', $userByMobile[0]['id'])->limit(1)->modifyAll($c_data);
  				$client = $pjClientModel->reset()->where('foreign_id', $userByMobile[0]['id'])->findAll()->getDataIndex(0);
  				pjFrontClient::init(array('id' => $userByMobile[0]['id']))->setClientSession();
  			}
        $data['client_id'] = $client['id'];
      }
      $data = array_merge($STORAGE, $FORM, $data);
  		$today = date( 'Y-m-d', time () );	
  		$toDay = $today . " " .  "00:00:00";
  		$telephoneOrder = $pjOrderModel->select("t1.*")
  		  ->where("t1.origin", "Telephone")
  		  ->where("(t1.created >= '$toDay')")
  		  ->limit(1)
  		  ->findAll()
  		  ->getData();
  		if (count($telephoneOrder) == 1) {
        $data['chef_id'] = $telephoneOrder[0]['chef_id'];  
  		} else {
  			$data['chef_id'] = NULL;  
  		}
      $data['order_count_of_the_day'] = $this->getTodayOrderCount();
      $order_id = $pjOrderModel->setAttributes($data)->insert()->getInsertId();
      if ($order_id !== false && (int) $order_id > 0) { 
      	$id = $order_id;
      	$id = "W".$id;
        $d_notes = NULL;
        if ($data['p_notes']!= '') {
          $d_notes = $data['p_notes'];
        }
        pjOrderModel::factory()
          ->where('id', $order_id)
          ->modifyAll(array(
            'order_id' => $id,
            'd_notes' => $d_notes
        ));
        $pjOrderItemModel = pjOrderItemModel::factory();
        $pjProductPriceModel = pjProductPriceModel::factory();
        $pjProductModel = pjProductModel::factory();
        $pjExtraModel = pjExtraModel::factory();
        foreach ($cart as $item) {
          $price_id = ':NULL';
          $price = 0;
          if (!empty($item['price_id'])) {
            $price_arr = $pjProductPriceModel->find($item['price_id'])->getData();
            if ($price_arr) {
              $price_id = $price_arr['id'];
              $price = $price_arr['price'];
            }
          } else {
            $price_arr = $pjProductModel->reset()->find($item['product_id'])->getData();
            if (!empty($price_arr)) {
              $price = $price_arr['price'];
            }
          }
          $hash = md5(uniqid(rand(), true));
          $oid = $pjOrderItemModel
          ->reset()
          ->setAttributes(array(
              'order_id' => $order_id,
              'foreign_id' => $item['product_id'],
              'type' => 'product',
              'price_id' => $price_id,
              'price' => $price,
              'hash' => $hash,
              'cnt' => $item['cnt']))
              ->insert();
            $extra_data = array();
            $key = 0;
            foreach ($item['extras'] as $extra_id => $extra_cnt) {
              if ($extra_cnt > 0) {
                $extra_price = 0;
                $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
                  ->select("t1.*, t2.content AS name")
                  // ->where("t1.id", $extra_id )
                  ->orderBy("category_id ASC, name ASC")
                  ->find($extra_id)
                  ->getData();
                // echo '<pre>'; print_r($extra_arr); echo '</pre>';
                if(!empty($extra_arr) && !empty($extra_arr['price'])) {
                  $key++;
                  $extra_price = $extra_arr['price'];
                  $extra_data[] = array (
                      'id'=>$key,
                      'extra_cat_id'=>$extra_arr['category_id'],
                      'extra_index'=>$hash,
                      'extra_name'=>$extra_arr['name'],
                      'extra_sel_id'=>$extra_arr['id'],
                      'extra_price'=>$extra_arr['price'],
                      'extra_count'=>$extra_cnt,
                      'qty_count'=>$extra_cnt,
                    );
                  }
                }
              }
              if (count($extra_data)) {
                $extra_data = json_encode($extra_data);
                $pjOrderItemModel->reset()
                    ->setAttributes(array(
                    'order_id' => $order_id,
                    'foreign_id' => ':NULL',
                    'type' => 'extra',
                    'hash' => $hash,
                    'price_id' => ':NULL',
                    'price' => ':NULL',
                    'cnt' => ':NULL',
                    'special_instruction' => $extra_data,
                    'custom_special_instruction' => ':NULL'
                  ))->insert();
              }
        }
        $order_arr = $pjOrderModel
        ->reset()
        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
        ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
        ->select("t1.*, t2.c_title, t3.email as c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
  			AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
  			AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
  			AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
  			AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
  			->find($order_id)
  			->getData();
  			$pdata = array();
  			$pdata['order_id'] = $order_id;
  			$pdata['payment_method'] = $payment;
  			$pdata['payment_type'] = 'online';
  			$pdata['amount'] = $order_arr['total'];
  			$pdata['status'] = 'notpaid';
  			pjOrderPaymentModel::factory()->setAttributes($pdata)->insert();
  			
  			pjAppController::addOrderDetails($order_arr, $this->getLocaleId());
                
  			pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'confirmation', $this->getLocaleId());
  			
  			$this->session->unsetData($this->defaultStore);
  			$this->session->unsetData($this->defaultForm);
  			$this->session->unsetData($this->defaultCaptcha);
  			$printInfo = $this->pjActionPrintOrder($order_id);
        $json = array('code' => 200, 'text' => "Your order submitted successfully once accepted Confirmation message will be sent to you. [STAG]Close[ETAG]", 'order_id' => $order_id, 'payment' => $payment, 'printInfo'=>$printInfo);
        // pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=index&origin=Web&id=$order_id");
        
      } else {
        $json = array('code' => 100, 'text' => '');
      }

      pjAppController::jsonResponse($json);
    }
	}
	
	public function pjActionConfirm()
	{
	    $this->setAjax(true);
	    if (pjObject::getPlugin('pjPayments') === NULL)
	    {
	        $this->log('pjPayments plugin not installed');
	        exit;
	    }
	    $pjPayments = new pjPayments();
	    $post = $this->_post->raw();
	    $get = $this->_get->raw();
	    $request = array();
	    if(isset($get['payment_method']))
	    {
	        $request = $get;
	    }
	    if(isset($post['payment_method']))
	    {
	        $request = $post;
	    }
	    if($pjPlugin = $pjPayments->getPaymentPlugin($request))
	    {
	        if($uuid = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionGetCustom', 'params' => $request), array('return')))
	        {
	            $pjOrderModel = pjOrderModel::factory();
	            $order_arr = $pjOrderModel
	            ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	            ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	            ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
					AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
					AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->where('t1.uuid', $uuid)
				->limit(1)
				->findAll()->getDataIndex(0);
				
				if (!empty($order_arr))
	            {
	                $params = array(
	                    'request'		=> $request,
	                    'payment_method' => $request['payment_method'],
	                    'foreign_id'	 => $this->getForeignId(),
	                    'amount'		 => $order_arr['total'],
	                    'txn_id'		 => $order_arr['txn_id'],
	                    'order_id'	   => $order_arr['id'],
	                    'cancel_hash'	=> sha1($order_arr['uuid'].strtotime($order_arr['created']).PJ_SALT),
	                    'key'			=> md5($this->option_arr['private_key'] . PJ_SALT)
	                );
	                $response = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
					if($response['status'] == 'OK')
	                {
	                    $this->log("Payments | {$pjPlugin} plugin<br>Order was confirmed. UUID: {$uuid}");
	                    
	                    $pjOrderModel->reset()
	                    ->setAttributes(array('id' => $order_arr['id']))
	                    ->modify(array('status' => $this->option_arr['o_payment_status'], 'is_paid' => 1, 'processed_on' => ':NOW()'));
	                    
	                    pjOrderPaymentModel::factory()->setAttributes(array('order_id' => $order_arr['id'], 'payment_type' => 'online'))
	                    ->modify(array('status' => 'paid'));
	                    $locale_id = $this->getLocaleId();
	                    if((int) $order_arr['locale_id'])
	                    {
	                        $locale_id = $order_arr['locale_id'];
	                    }
	                    pjAppController::addOrderDetails($order_arr, $locale_id);
	                    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'payment', $this->getLocaleId());
	                    
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }elseif($response['status'] == 'CANCEL'){
	                    $this->log("Payments | {$pjPlugin} plugin<br>Payment was cancelled. UUID: {$uuid}");
	                    
	                    $pjOrderModel->reset()
	                    ->setAttributes(array('id' => $order_arr['id']))
	                    ->modify(array('status' => 'cancelled', 'processed_on' => ':NOW()'));
	                    
	                    pjAppController::addOrderDetails($order_arr, $this->getLocaleId());
	                    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'cancel', $this->getLocaleId());
	                    
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }else{
	                    $this->log("Payments | {$pjPlugin} plugin<br>Order confirmation was failed. UUID: {$uuid}");
	                }
	                
	                if(isset($response['redirect']) && $response['redirect'] == true)
	                {
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }
	            }else{
	                $this->log("Payments | {$pjPlugin} plugin<br>Reservation with UUID {$uuid} not found.");
	            }
	            echo $this->option_arr['o_thankyou_page'];
	            exit;
	        }
	    }
	    echo $this->option_arr['o_thankyou_page'];
	    exit;
	}
	public static function pjActionGetSubjectMessage($notification_id, $locale_id)
	{
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    $lang_message = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id', $notification_id)
	    ->where('t1.model','pjNotification')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', 'message')
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    $lang_subject = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id',  $notification_id)
	    ->where('t1.model','pjNotification')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', 'subject')
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    return compact('lang_message', 'lang_subject');
	}
	public static function pjActionConfirmSend($option_arr, $data, $salt, $opt, $locale)
	{
		
      $Email = self::getMailer($option_arr);
	    
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    
	    $admin_email = pjAppController::getAdminEmail();
	    $admin_phone = pjAppController::getAdminPhone();
	    $locale_id = isset($data['locale_id']) && (int) $data['locale_id'] > 0 ? (int) $data['locale_id'] : $locale;
	    
	    $pjNotificationModel = pjNotificationModel::factory();
	    
	    if($opt == 'account' || $opt == 'forgot')
	    {
	        $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
	        if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
	        {
    	        $tokens = pjAppController::getClientTokens($option_arr, $data, PJ_SALT, $locale_id);
    	        $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
    	        $lang_message = $resp['lang_message'];
    	        $lang_subject = $resp['lang_subject'];
    	        $client = pjClientModel::factory()->find($data)->getData();
    	        $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
    	        if (count($lang_message) === 1 && count($lang_subject) === 1 && !empty($auth_client['email']))
    	        {
    	            $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
    	            $message = str_replace($tokens['search'], $tokens['replace'], $message);
    	            $Email
    	            ->setTo($auth_client['email'])
    	            ->setSubject($lang_subject[0]['content'])
    	            ->send($message);
    	        }
	        }
	    } else {
	        $tokens = pjAppController::getTokens($option_arr, $data, PJ_SALT, $locale_id);
            /*Confirmation sent to clients*/
            $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
            if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
            {
	            $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
	            $lang_message = $resp['lang_message'];
	            $lang_subject = $resp['lang_subject'];
	            if (count($lang_message) === 1 && count($lang_subject) === 1)
	            {
	                if ($data['type'] == 'delivery')
	                {
	                    $message = str_replace(array('<br />[Delivery]', '<br />[/Delivery]'), array('', ''), $lang_message[0]['content']);
	                    $message = str_replace(array('[Delivery]<br />', '[/Delivery]<br />'), array('', ''), $message);
	                    $message = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $message);
	                } else {
	                    $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
	                }
	                $message = str_replace($tokens['search'], $tokens['replace'], $message);
	                $Email
	                ->setTo($data['c_email'])
	                ->setSubject($lang_subject[0]['content'])
	                ->send($message);
	            }
            }
            /*Confirmation sent to admin*/
            $notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
            if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
            {
                $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
                $lang_message = $resp['lang_message'];
                $lang_subject = $resp['lang_subject'];
                if (count($lang_message) === 1 && count($lang_subject) === 1)
                {
                    if ($data['type'] == 'delivery')
                    {
                        $message = str_replace(array('<br />[Delivery]', '<br />[/Delivery]'), array('', ''), $lang_message[0]['content']);
                        $message = str_replace(array('[Delivery]<br />', '[/Delivery]<br />'), array('', ''), $message);
                        $message = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $message);
                    } else {
                        $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
                    }
                    $message = str_replace($tokens['search'], $tokens['replace'], $message);
                    
                    $Email
                    ->setTo($admin_email)
                    ->setSubject($lang_subject[0]['content'])
                    ->send($message);
                }
            }
            /*SMS sent to client*/
            if(!empty($data['c_phone']) || !empty($data['phone_no']))
            {
                $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
                {
	                $lang_message = $pjMultiLangModel
	                ->reset()
	                ->select('t1.*')
	                ->where('t1.foreign_id', $notification['id'])
	                ->where('t1.model','pjNotification')
	                ->where('t1.locale', $locale_id)
	                ->where('t1.field', 'message')
	                ->limit(0, 1)
	                ->findAll()
	                ->getData();
	                if (count($lang_message) === 1)
	                {
                     $message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
	                    $params = array(
	                        'text' => $message,
	                        'type' => 'unicode',
	                        'key' => md5($option_arr['private_key'] . PJ_SALT),
                          'sender'=>DOMAIN
	                    );
	                    $params['number'] = $data['c_phone']? $data['c_phone']: $data['phone_no'];
	                    pjBaseSms::init($params)->pjActionSend();
	                }
                }
            }
            /*SMS sent to admin*/
            if(!empty($admin_phone))
            {
                $notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
                {
                    $lang_message = $pjMultiLangModel
                    ->reset()
                    ->select('t1.*')
                    ->where('t1.foreign_id', $notification['id'])
                    ->where('t1.model','pjNotification')
                    ->where('t1.locale', $locale_id)
                    ->where('t1.field', 'message')
                    ->limit(0, 1)
                    ->findAll()
                    ->getData();
                    if (count($lang_message) === 1)
                    {
                        $message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
                        $params = array(
                            'text' => $message,
                            'type' => 'unicode',
                            'key' => md5($option_arr['private_key'] . PJ_SALT),
                            'sender'=>DOMAIN
                        );
                        $params['number'] = $admin_phone;
                        pjBaseSms::init($params)->pjActionSend();
                    }
                }
            }
	    }
	}
	
	private function doubleCheckData($data)
	{
	    $double_check_error = __('double_check_error', true);
	    if($data['type'] == 'delivery')
	    {
	        if(!isset($data['d_location_id']))
	        {
	            return array('status' => 'ERR', 'code' => 106, 'text' => $double_check_error[104]);
	        }
	        if((int) $data['d_location_id'] <= 0)
	        {
	            return array('status' => 'ERR', 'code' => 107, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_1'] === 3 && !isset($data['d_address_1']))
	        {
	            return array('status' => 'ERR', 'code' => 108, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_1'] === 3 && !pjValidation::pjActionNotEmpty($data['d_address_1']))
	        {
	            return array('status' => 'ERR', 'code' => 109, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_2'] === 3 && !isset($data['d_address_2']))
	        {
	            return array('status' => 'ERR', 'code' => 110, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_city'] === 3 && !isset($data['d_city']))
	        {
	            return array('status' => 'ERR', 'code' => 112, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_city'] === 3 && !pjValidation::pjActionNotEmpty($data['d_city']))
	        {
	            return array('status' => 'ERR', 'code' => 113, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_state'] === 3 && !isset($data['d_state']))
	        {
	            return array('status' => 'ERR', 'code' => 114, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_state'] === 3 && !pjValidation::pjActionNotEmpty($data['d_state']))
	        {
	            return array('status' => 'ERR', 'code' => 115, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_zip'] === 3 && !isset($data['d_zip']))
	        {
	            return array('status' => 'ERR', 'code' => 116, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_zip'] === 3 && !pjValidation::pjActionNotEmpty($data['d_zip']))
	        {
	            return array('status' => 'ERR', 'code' => 117, 'text' => $double_check_error[104]);
	        }
	    }else{
	    }
	    if((int) $this->option_arr['o_bf_include_address_1'] === 3 && !isset($data['c_address_1']))
	    {
	        return array('status' => 'ERR', 'code' => 122, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_1'] === 3 && !pjValidation::pjActionNotEmpty($data['c_address_1']))
	    {
	        return array('status' => 'ERR', 'code' => 123, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_2'] === 3 && !isset($data['c_address_2']))
	    {
	        return array('status' => 'ERR', 'code' => 124, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_2'] === 3 && !pjValidation::pjActionNotEmpty($data['c_address_2']))
	    {
	        return array('status' => 'ERR', 'code' => 125, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_city'] === 3 && !isset($data['c_city']))
	    {
	        return array('status' => 'ERR', 'code' => 126, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_city'] === 3 && !pjValidation::pjActionNotEmpty($data['c_city']))
	    {
	        return array('status' => 'ERR', 'code' => 127, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_state'] === 3 && !isset($data['c_state']))
	    {
	        return array('status' => 'ERR', 'code' => 128, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_state'] === 3 && !pjValidation::pjActionNotEmpty($data['c_state']))
	    {
	        return array('status' => 'ERR', 'code' => 129, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_zip'] === 3 && !isset($data['c_zip']))
	    {
	        return array('status' => 'ERR', 'code' => 130, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_zip'] === 3 && !pjValidation::pjActionNotEmpty($data['c_zip']))
	    {
	        return array('status' => 'ERR', 'code' => 131, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_country'] === 3 && !isset($data['c_country']))
	    {
	        return array('status' => 'ERR', 'code' => 132, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_country'] === 3 && (int) $data['c_country'] <= 0)
	    {
	        return array('status' => 'ERR', 'code' => 133, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !isset($data['c_title']))
	    {
	        return array('status' => 'ERR', 'code' => 134, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !pjValidation::pjActionNotEmpty($data['c_title']))
	    {
	        return array('status' => 'ERR', 'code' => 135, 'text' => $double_check_error[104]);
	    }
	    $name_titles = __('personal_titles', true, false);
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !array_key_exists($data['c_title'],$name_titles))
	    {
	        return array('status' => 'ERR', 'code' => 136, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_name'] === 3 && !isset($data['c_name']))
	    {
	        return array('status' => 'ERR', 'code' => 137, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_name'] === 3 && !pjValidation::pjActionNotEmpty($data['c_name']))
	    {
	        return array('status' => 'ERR', 'code' => 138, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !isset($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 139, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !pjValidation::pjActionNotEmpty($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 140, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !pjValidation::pjActionEmail($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 141, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_phone'] === 3 && !isset($data['c_phone']))
	    {
	        return array('status' => 'ERR', 'code' => 142, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_phone'] === 3 && !pjValidation::pjActionNotEmpty($data['c_phone']))
	    {
	        return array('status' => 'ERR', 'code' => 143, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_company'] === 3 && !isset($data['c_company']))
	    {
	        return array('status' => 'ERR', 'code' => 144, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_company'] === 3 && !pjValidation::pjActionNotEmpty($data['c_company']))
	    {
	        return array('status' => 'ERR', 'code' => 145, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_notes'] === 3 && !isset($data['c_notes']))
	    {
	        return array('status' => 'ERR', 'code' => 146, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_notes'] === 3 && !pjValidation::pjActionNotEmpty($data['c_notes']))
	    {
	        return array('status' => 'ERR', 'code' => 147, 'text' => $double_check_error[104]);
	    }
	    if($this->option_arr['o_payment_disable'] == 'No')
	    {
	        if(!isset($data['payment_method']))
	        {
	            return array('status' => 'ERR', 'code' => 148, 'text' => $double_check_error[104]);
	        }
	        if(!pjValidation::pjActionNotEmpty($data['payment_method']))
	        {
	            return array('status' => 'ERR', 'code' => 149, 'text' => $double_check_error[104]);
	        }
	    }
	    return array('status' => 'OK', 'code' => 200, 'text' => "");
	}

	protected function getClientType($data)
	{   

		$regular = 0;
	           	
    	$c_exist_orders = pjOrderModel::factory()
    	                ->select("t1.*")
    	                ->where('t1.client_id',$data['client_id'])
    	                ->findAll()
    	                ->getData();
    	
    	$c_exist_orders_dates = array();
    	
    	foreach ($c_exist_orders as $k => $v) {
    		    $c_exist_orders_dates[] = explode(" ",$v['created'])[0];
    		    
	    	}

	    if (count($c_exist_orders)) {
	    	$weekDates[0] = date('Y-m-d');
	    	for ($i=1; $i < 7; $i++) { 
	    	 	$weekDates[$i] = date('Y-m-d',strtotime("-$i days"));
	    	 	
	    	 } 
	    	 
	    	foreach ($c_exist_orders_dates as $k) {
	    		foreach ($weekDates as $d) {
	    			if ($k == $d) {
	    				$regular = $regular + 1;
	    			}
	    		}
	    	}

	    	if ($regular >= 2) {
	    		return "Regular client";
	    	} else if ($regular == 1) {

	    		$frequent = 1;
	    		$frequentDates = [];
	    		for($j=7; $j < 28; $j++) {
	    			$frequentDates[$j] = date('Y-m-d',strtotime("-$j days"));
	    		}

	    		foreach ($c_exist_orders_dates as $k) {
	    			foreach ($frequentDates as $f) {
	    				if($k == $f) {
	    					$frequent = $frequent + 1;
	    				}
	    			}
	    		}
                
	    		if ($frequent >= 4) {

	    			return "Frequent";
	    		} else {
	    			return "Occasional";
	    		}
	    	} else {
	    		
	    		return "Rare";
	    	}
	    	
    	} 
    	
	}

	function sendMessage($phone, $msg) {
		$pjSmsApi = new tlSmsApi();
		if (ENVIRONMENT != 'production') {
      $phone = TESTMOBILENUMBER;
      return true;
    }
    // echo $this->option_arr['plugin_sms_api_key'];
    // exit;
		$response = $pjSmsApi
			->setApiKey($this->option_arr['plugin_sms_api_key'])
			->setNumber($phone)
			->setText($msg)
			->setSender(DOMAIN)
			->send();
		if ($response == '1') { $sts = '1'; } else { $sts = '0'; }
        pjBaseSmsModel::factory()
		->reset()
		->setAttributes(array(
			'number' => $phone,
			'text' => $msg,
			'status' => $sts,
			'created' => date( 'y-m-d H:i:s', time () )
		))
		->insert();
		return $response;
	}

	public function pjActionCheckPostcode() {

        $this->setAjax(true);
        if ($this->isXHR())
        {
            if ($this->_post->toString('post_code') == '') {
                self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
            }
	        $post_code = $this->_post->toString('post_code');
	        $pc = pjPostalcodeModel::factory()
	        ->select("t1.*")
	        ->where("t1.postal_code",$post_code)
	        ->findAll()
	        ->getData();
	        if (count($pc) > 0) {
	        	self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Post code available for delivery'));
	        } else {
	        	self::jsonResponse(array('status' => 'OK', 'code' => 100, 'text' => 'Post code is not available for delivery'));
	        }
	        
        }

    }

  public function pjActionPrintOrderBefore($id) {
    // $this->checkLogin();
    // if (!pjAuth::factory()->hasAccess()) {
    //   $this->sendForbidden();
    //   return;
    // }
    require_once('sunmiPrinter.php');
    $this->setLayout('pjActionPrint');
    // $id = $this->_get->toInt('id');
    // $source = $this->_get->toString('source');
    $source = '';
    if ($id) {
      // MEGAMIND
      $category_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("t1.order ASC")
        ->findAll()
        ->getData();
      $this->set('categories', $category_arr);
      $printBrowser = true;
      $printMessage = 'failed';
      $data = $this->getOrderItems($id, $printBrowser, true);

      $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
        ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
        ->orderBy('t2.name ASC')
        ->findAll()
        ->getData();
      $this->set('client_arr', $client_arr);
      
      $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
      $this->set('timezone', $timezone);
      $this->set('order_id', $id);
      $special_instructions = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction")
        ->findAll()
        ->getData();
      $this->set('special_instructions', $special_instructions);
      // $this->set('action', $this->getActionName($source));

      if ($printBrowser) {
        $ed = $data['arr']['d_dt'] != '' ? $data['arr']['d_dt'] : $data['arr']['p_dt'];
        $ed_dt = explode(" ", $ed);
        $ed_dt_items = explode('-', $ed_dt[0]);
        $ed_dt[0] = $ed_dt_items[2] . '-' . $ed_dt_items[1] . '-' . $ed_dt_items[0];
        $printer = new SunmiCloudPrinter(384);
        $printer->selectAsciiCharFont(0x80);
        $printer->setLineSpacing(80);
        $printer->setPrintModes(true, true, false);
        $chefName = $data['arr']['chef_name'];
        $estDelivery = $ed_dt[0] . ' ' . $ed_dt[1];
        $orderID = $data['arr']['order_id'];
        $created = date('d-m-Y H:i:s', strtotime($data['arr']['created']));
        if (strtolower($data['arr']['origin']) != 'pos') {
          $printer->setHarfBuzzAsciiCharSize(14);
          // $type = substr($data['arr']['origin'], 0, 3). " - " .ucfirst($data['arr']['type']);
          $type = "Web Order". " - " .ucfirst($data['arr']['type']);
          //Data Printing
          $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
          $printer->appendText("$type");
          $printer->lineFeed();
        }
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
        $printer->setupColumns([150 , SunmiCloudPrinter::ALIGN_LEFT, 0], [300, SunmiCloudPrinter::ALIGN_LEFT, 0]);
        $printer->setHarfBuzzAsciiCharSize(10);
        $printer->printInColumns("TIME OF ORDER:", "$created");
        // $printer->printInColumns("ED:", "$estDelivery");
        $printer->setHarfBuzzAsciiCharSize(12);
        // $printer->printInColumns("OrderID:", "$orderID");
        $printer->appendText("OrderID:   $orderID");
        // $printer->setPrintModes(true, true, false);
        // $tableName = $data['arr']['table_ordered_name']?$data['arr']['table_ordered_name']:$data['arr']['table_name'];
        // if ($tableName) {
        //   $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
        //   $printer->appendText("$tableName");
        //   $printer->lineFeed();
        // }
        $printer->restoreDefaultLineSpacing();
        $printer->lineFeed();
        $printer->setHarfBuzzAsciiCharSize(14);
        $printer->appendText(str_repeat("_", 35));
        $printer->lineFeed();
        $printer->restoreDefaultLineSpacing();
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
        $this->kitchenPrintFormat($printer, $data, $special_instructions, true);
        //Already Printed line order
        if ($data['arr']['d_notes'] != '') { 
          // $printer->lineFeed();
          $printer->appendText(str_repeat("_", 35));
          $printer->lineFeed();
          $printer->appendText(" SPL INSTRUCTION ".$data['arr']['d_notes']);
          $printer->lineFeed();
        } 
        //End of Data printing
        $printer->restoreDefaultLineSpacing();
        $printer->printAndExitPageMode();
        $printer->lineFeed(5);
        $printer->cutPaper(false);
        if (strtolower($source) === 'actionintialprint') {
          $sn = RECEIPT_SN;
        } else {
          // $sn = KITCHEN_SN;
          $sn = RECEIPT_SN;
        }
        $result = $printer->pushContent($sn, sprintf("%s_%010d", $sn, time()));
        $api_result = json_encode($result);
        
        $pjOrderModel = pjOrderModel::factory();
        $pjOrderModel->where('id', $id)->modifyAll(array('api_result_print' => $api_result))->getAffectedRows();
        if (strtolower($result['msg']) == 'success') {
          $printMessage = "Printed";
          //$this->updateKitchenPrint($id);
        } else {
          $printMessage = "Failed";
        }
        return $printMessage;
        // $this->set('printMessage', $printMessage);
        // $this->set('origin', $data['arr']['origin']);
      }
    }

  }

  public function pjActionPrintOrder($id) {
    require_once('sunmiPrinter.php');
    $this->setLayout('pjActionPrint');
    $id = $this->_get->toInt('id');
    $source = $this->_get->toString('source');
    if ($id = $this->_get->toInt('id')) {
      // MEGAMIND
      $category_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("t1.order ASC")
        ->findAll()
        ->getData();
      $this->set('categories', $category_arr);
      $printBrowser = true;
      $printMessage = 'failed';
      $data = $this->getOrderItems($id, $printBrowser, true);

      $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
        ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
        ->orderBy('t2.name ASC')
        ->findAll()
        ->getData();
      $this->set('client_arr', $client_arr);
      
      $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
      $this->set('timezone', $timezone);
      $this->set('order_id', $id);
      $special_instructions = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction")
        ->findAll()
        ->getData();
      $this->set('special_instructions', $special_instructions);
      $this->set('action', $this->getActionName($source));

      if ($printBrowser) {
        $ed = $data['arr']['d_dt'] != '' ? $data['arr']['d_dt'] : $data['arr']['p_dt'];
        $ed_dt = explode(" ", $ed);
        $ed_dt_items = explode('-', $ed_dt[0]);
        $ed_dt[0] = $ed_dt_items[2] . '-' . $ed_dt_items[1] . '-' . $ed_dt_items[0];
        $printer = new SunmiCloudPrinter(384);
        $printer->selectAsciiCharFont(0x80);
        $printer->setLineSpacing(80);
        $printer->setPrintModes(true, true, false);
        $chefName = $data['arr']['chef_name'];
        $estDelivery = $ed_dt[0] . ' ' . $ed_dt[1];
        $orderID = $data['arr']['order_id'];
        $created = date('d-m-Y H:i:s', strtotime($data['arr']['created']));
        if (strtolower($data['arr']['origin']) != 'pos') {
          $printer->setHarfBuzzAsciiCharSize(14);
          $type = substr($data['arr']['origin'], 0, 3). " - " .ucfirst($data['arr']['type']);
          //Data Printing
          $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
          $printer->appendText("$type");
          $printer->lineFeed(1);
        }
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
        $printer->setupColumns([150 , SunmiCloudPrinter::ALIGN_LEFT, 0], [300, SunmiCloudPrinter::ALIGN_LEFT, 0]);
        $printer->setHarfBuzzAsciiCharSize(10);
        $printer->printInColumns("TIME:", "$created");
        $printer->printInColumns("ED:", "$estDelivery");
        $printer->setHarfBuzzAsciiCharSize(14);
        $printer->printInColumns("OrderID:", "$orderID");
        $printer->setPrintModes(true, true, false);
        $tableName = $data['arr']['table_ordered_name']?$data['arr']['table_ordered_name']:$data['arr']['table_name'];
        if ($tableName) {
          $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
          $printer->appendText("$tableName");
          $printer->lineFeed();
        }
        $printer->setHarfBuzzAsciiCharSize(16);
        $printer->appendText(str_repeat("-", 35));
        $printer->lineFeed();
        $printer->restoreDefaultLineSpacing();
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
        $this->kitchenPrintFormat($printer, $data, $special_instructions, true);
        $printer->lineFeed();
        //Already Printed line order
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
        $printer->appendText(str_repeat("-", 35));
        $printer->lineFeed();
        $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
        $printer->restoreDefaultLineSpacing();
        $printer->setPrintModes(false, false, false);
        $this->kitchenPrintFormat($printer, $data, $special_instructions, false);
        // exit;
        if ($data['arr']['d_notes'] != '') { 
          $printer->lineFeed(2);
          $printer->appendText(str_repeat("-", 35));
          $printer->appendText(" SPL INSTRUCTION ".$data['arr']['d_notes']);
          $printer->lineFeed();
        }
        //End of Data printing
        $printer->restoreDefaultLineSpacing();
        $printer->printAndExitPageMode();

        $printer->lineFeed(4);
        $printer->cutPaper(false);
        if (strtolower($source) === 'actionintialprint') {
          $sn = RECEIPT_SN;
        } else {
          $sn = KITCHEN_SN;
        }
        $result = $printer->pushContent($sn, sprintf("%s_%010d", $sn, time()));
        $api_result = json_encode($result);
        
        $pjOrderModel = pjOrderModel::factory();
        $pjOrderModel->where('id', $id)->modifyAll(array('api_result_print' => $api_result))->getAffectedRows();
        if (strtolower($result['msg']) == 'success') {
          $printMessage = "Printed";
          // $this->updateKitchenPrint($id);
        } else {
          $printMessage = "Failed";
        }
        return $printMessage;
        // $this->updateKitchenPrint($id); 
        // $this->set('printMessage', $printMessage);
        // $this->set('origin', $data['arr']['origin']);
      }
    }

  }

}
?>