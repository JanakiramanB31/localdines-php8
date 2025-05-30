<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront {
	public function __construct() {
		parent::__construct();
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionMain() {
    $this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
			$catName = strtolower($this->_get->toString('menu_category'));
			switch($catName) {
				case 'noodle_bar':
					$mainCatID = 2;
				break;
				case 'indian':
					$mainCatID = 1;
				break;
				default:
					$mainCatID = 0;
			}
			$this->set('main', $this->getCategories($mainCatID));
      $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
    }
	}
	public function pjActionMainQr() {
    $this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      $this->set('mainQr', $this->getCategories());
		}
		$options = pjOptionModel::factory()
		->where('t1.foreign_id', 2)
		->orderBy('t1.order ASC')
		->findAll()
		->getData();
		$this->set('front_option', $options);
	}
	
	public function pjActionCategories()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('main', $this->getCategories());
	    }
	}

	public function pjActionHome()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        print_r("Hey home");
	    }
	}
	
	public function pjActionCart() {
    $this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
			$cart_box = pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId());
			$price = 0;
			$packing = 0;
      foreach ($cart_box['cart'] as $hash => $item) {
        foreach ($cart_box['product_arr'] as $product) {
          if ($product['id'] == $item['product_id']) {
            $product_price = 0;
            $product_price = $item['price'] * $item['cnt'];
            $packing = $product['packing_fee'] * $item['cnt'];
            $price += $product_price;
            foreach ($item['extra_arr'] as $extra_id => $extra) {
              $price += $extra['price'] * $extra['qty'];
            }
          }
        }
      }
      $arr = pjPriceModel::factory()
      ->where('t1.location_id', $this->_get('d_location_id'))
      ->where('t1.total_from <= ' . $price)
      ->where('t1.total_to >= ' . $price)
      ->limit(1)
      ->findAll()
      ->getData();
        
      $delivery = 0;
      if (count($arr) === 1) {
        $delivery = $arr[0]['price'];
      }
      $this->_set('delivery', $delivery);
      $this->_set('packing', $packing);
      $this->set('cart_box', $cart_box);
    }
	}
	
	public function pjActionTypes() {
    $this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0) {
        $location_arr = pjLocationModel::factory()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
        ->select('t1.*, t2.content AS name, t3.content AS address')
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
        $type = $this->_get('type');
        if ($type !== false) {
	        switch ($type) {
            case 'pickup':
              $p_location_id = $this->_get('p_location_id');
              $p_date = $this->_get('p_date');
              if ($p_location_id !== false && $p_date !== false) {
                $wt_arr = pjAppController::getWorkingTime(pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']), $p_location_id, 'pickup');
                $this->set('wt_arr', $wt_arr);
              }
              $this->set('date', pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']));
            break;
            case 'delivery':
              $d_location_id = $this->_get('d_location_id');
              $d_date = $this->_get('d_date');
              if ($d_location_id !== false && $d_date !== false) {
                $wt_arr = pjAppController::getWorkingTime(pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']), $d_location_id, 'delivery');
                $this->set('wt_arr', $wt_arr);
              }
              $this->set('date', pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']));
            break;
          }
        }
        $country_arr = pjBaseCountryModel::factory()
        ->select('t1.id, t2.content AS country_title')
        ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
        ->orderBy('`country_title` ASC')
        ->findAll()
        ->getData();
        if ($this->isFrontLogged()) {
          $order_arr = pjOrderModel::factory()
          ->select("t1.*")
          ->where('client_id', $this->getClientId())
          ->where('type', 'delivery')
          ->orderBy("t1.created DESC")
          ->findAll()
          ->getData();
          $this->set('order_arr', $order_arr);
        }
        $this->set('country_arr', $country_arr);
        $this->set('location_arr', $location_arr);
        $this->set('status', 'OK');
				$bank_account = pjMultiLangModel::factory()
				->select('t1.content')
				->where('t1.model','pjOption')
				->where('t1.locale', $this->getLocaleId())
				->where('t1.field', 'o_bank_account')
				->limit(1)
				->findAll()->getDataIndex(0);
				if (isset($bank_account['content'])) {
					$this->set('bank_account', $bank_account['content']);
				} else {
					$this->set('bank_account', '');
				}
				if (pjObject::getPlugin('pjPayments') !== NULL) {
					$this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getForeignId()));
					$this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
				} else {
					$this->set('payment_titles', __('payment_methods', true));
				}
      } else {
        $this->set('status', 'ERR');
      }
      $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
    }
	}
	
	public function pjActionLogin() {
  	$this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      if($this->isFrontLogged()) {
        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
      } else {
        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
      }
    }
	}

	public function pjActionSetGuest()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $_SESSION['guest'] = true;
					$_SESSION['guest_otp_check'] = false;
	        $resp = array('code' => 200);
	        pjAppController::jsonResponse($resp);
	    }
	    exit;
	}

	public function pjActionSocialLogin()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
			$s_client = array();
			$s_client["c_name"] = $_REQUEST["g_name"];
			$s_client['surname'] = $_REQUEST['g_surname'];
			$s_client["c_email"] = $_REQUEST["g_email"];
			$client = pjAuthUserModel::factory()
			          ->join("pjClient", 't2.foreign_id = t1.id', 'left outer')
			          ->select('t1.*, t2.*')
					  ->where("t1.email", $s_client["c_email"])
					  ->findAll()
					  ->getData();

		    if(count($client) > 0) {
                if($client[0]['register_type'] == 'S') {
					
					$data = array();
					$data['login_email'] = $client[0]['email'];
					$data['login_password'] = 'staticPassword';
					$data['is_backend'] = 'F';
					$response = pjFrontClient::init($data)->doClientLogin();
					$id = $client[0]['id'];
				} else {
					
					$response = array('status' => "ERR", 'code' => 500);
					pjAppController::jsonResponse($response);
				}
			} else {
				$s_client['c_password'] = 'staticPassword';
				$s_client['status'] = 'T';
				$s_client["c_type"] = "New";
				$s_client["register_type"] = "S";
				$s_client["mobile_delivery_info"] = 0;
				$s_client["mobile_offer"] = 0;
				$s_client["email_receipt"] = 0;
				$s_client["email_offer"] = 0;
				$s_client['locale_id'] = $this->getLocaleId();
				$register_response = pjFrontClient::init(array_merge($s_client))->createClient();

				if ($register_response['code'] == 200) {
					$client_created = pjAuthUserModel::factory()
			          ->select('t1.*')
					  ->where("t1.email", $s_client["c_email"])
					  ->findAll()
					  ->getData();
					if(count($client_created) > 0) {
						$data = array();
						$data['login_email'] = $client_created[0]['email'];
						$data['login_password'] = 'staticPassword';
						$data['is_backend'] = 'F';
						$response = pjFrontClient::init($data)->doClientLogin();
						$id = $client_created[0]['id'];
					}
				}
				
			}
			if ($id > 0) {
				$_SESSION['social_login'] = true;
				pjAppController::jsonResponse($response);
				return $response;
			}
			
	    }
		exit;
	}
	
	public function pjActionProfile()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if($this->isFrontLogged())
	        {
	            $country_arr = pjBaseCountryModel::factory()
	            ->select('t1.id, t2.content AS country_title')
	            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	            ->orderBy('`country_title` ASC')
	            ->findAll()
	            ->getData();
	            
	            $this->set('country_arr', $country_arr);
	            $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	        }else{
	            pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
	        }
	    }
	}

	public function pjActionReview()
	{
	    $this->setAjax(true);
	
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
			if($this->isFrontLogged())
	        {
	            
	        }else{
				$product = $_REQUEST['p_id'];
				$review_info = [$_REQUEST['star'], $_REQUEST['via'], $_REQUEST['page']];
	            $product_arr = pjProductModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	            ->select('t1.*,t2.content AS name')
				
				->where('t1.id', $product)
	            ->findAll()
	            ->getData();
				$this->set('product_arr', $product_arr);
				$this->set('review_info', $review_info);
				$this->set('status', 'OK');
	        }
	    }
		if (self::isPost() && $this->_post->toInt('review_create')) {
			$post = $this->_post->raw();
			print_r($post);
		}
	}
	
	public function pjActionForgot()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	public function pjActionVouchers()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
	        {
	            $this->set('status', 'OK');
	        }else{
	            $this->set('status', 'ERR');
	        }
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	
	public function pjActionCheckout() {
  	$this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0) {
        $country_arr = pjBaseCountryModel::factory()
        ->select('t1.id, t2.content AS country_title')
        ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
        ->orderBy('`country_title` ASC')
        ->findAll()
        ->getData();
        $this->set('country_arr', $country_arr);
        $this->set('status', 'OK');
      } else {
        $this->set('status', 'ERR');
      }
      $cart_box = pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId());
      $price = 0;
      if ($cart_box['cart']) {
	      foreach ($cart_box['cart'] as $hash => $item) {
	        foreach ($cart_box['product_arr'] as $product) {
	          if ($product['id'] == $item['product_id']) {
	            $product_price = 0;
	            $product_price = $item['price'] * $item['cnt'];
	            $price += $product_price;
	            foreach ($item['extra_arr'] as $extra_id => $extra) {
	              $price += $extra['price'] * $extra['qty'];
	            }
	          }
	        }
	      }
	    }
      $arr = pjPriceModel::factory()
      ->where('t1.location_id', $this->_get('d_location_id'))
      ->where('t1.total_from <= ' . $price)
      ->where('t1.total_to >= ' . $price)
      ->limit(1)
      ->findAll()
      ->getData();
	        
      $delivery = 0;
      if (count($arr) === 1) {
        $delivery = $arr[0]['price'];
      }
      $this->_set('delivery', $delivery);
      $this->set('cart_box', $cart_box);
      $bank_account = pjMultiLangModel::factory()
      ->select('t1.content')
      ->where('t1.model','pjOption')
      ->where('t1.locale', $this->getLocaleId())
      ->where('t1.field', 'o_bank_account')
      ->limit(1)
      ->findAll()->getDataIndex(0);
      if (isset($bank_account['content'])) {
				$this->set('bank_account', $bank_account['content']);
			} else {
				$this->set('bank_account', '');
			}
      if (pjObject::getPlugin('pjPayments') !== NULL) {
        $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getForeignId()));
        $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
      } else {
        $this->set('payment_titles', __('payment_methods', true));
      }
    }
	}
	
	public function pjActionPreview() {
    $this->setAjax(true);
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0) {
        $STORE = $_SESSION[$this->defaultStore];
        $price = $STORE['price'];
        if ($price < (float) $this->option_arr['o_minimum_order'] && $STORE['type'] == 'delivery') {
          pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
        }
	            
        $pjLocationModel = pjLocationModel::factory();
        $country_arr = array();
        if (isset($_SESSION[$this->defaultForm]['c_country']) && !empty($_SESSION[$this->defaultForm]['c_country'])) {
	        $country_arr = pjBaseCountryModel::factory()
	        ->select('t1.id, t2.content AS country_title')
	        ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	        ->find($_SESSION[$this->defaultForm]['c_country'])->getData();
        }
        if ($STORE['type'] == 'delivery' && isset($STORE['d_location_id']) && !empty($STORE['d_location_id'])) {
          $location_arr = $pjLocationModel
          ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
          ->select('t1.*, t2.content AS name')
          ->find($STORE['d_location_id'])
          ->getData();
          $this->set('location_arr', $location_arr);
        }
        if ($STORE['type'] == 'pickup' && isset($STORE['p_location_id']) && !empty($STORE['p_location_id'])) {
          $location_arr = $pjLocationModel->reset()
            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
            ->select('t1.*, t2.content AS name')
            ->find($STORE['p_location_id'])
            ->getData();
          $this->set('p_location_arr', $location_arr);
        }
        if (isset($STORE['d_country_id']) && !empty($STORE['d_country_id'])) {
          $d_country_arr = pjBaseCountryModel::factory()
            ->select('t1.id, t2.content AS country_title')
            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
            ->find($STORE['d_country_id'])
            ->getData();
          $this->set('d_country_arr', $d_country_arr);
        }
        $this->set('country_arr', $country_arr);
        $date = null;
        $selected_ts = null;
        $wt_arr = array();
        $type = $this->_get('type');
        if ($type !== false) {
          switch ($type) {
            case 'pickup':
              $p_location_id = $this->_get('p_location_id');
              $p_date = $this->_get('p_date');
              $date = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']);
              if ($p_location_id !== false && $p_date !== false)
              {
                  $wt_arr = pjAppController::getWorkingTime($date, $p_location_id, 'pickup');
              }
              $selected_ts = strtotime($date . ' '. $this->_get('p_time') . ':00');
            break;
            case 'delivery':
              $d_location_id = $this->_get('d_location_id');
              $d_date = $this->_get('d_date');
              $date = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']);
              if ($d_location_id !== false && $d_date !== false)
              {
                  $wt_arr = pjAppController::getWorkingTime($date, $d_location_id, 'delivery');
              }
              $selected_ts = strtotime($date . ' '. $this->_get('d_time') . ':00');
            break;
          }
        }
        if ($selected_ts != null && $date != null && !empty($wt_arr)) {
          $start_time = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
          $end_time = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
            
          if($selected_ts < $start_time) {
            $selected_ts += 86400;
            $this->_set('next_day', date('Y-m-d H:i:s', $selected_ts));
          } else {
            if ($this->_is('next_day')) {
              $this->_unset('next_day');
            }
          }
        } else {
          if ($this->_is('next_day')) {
            $this->_unset('next_day');
          }
        }
        if (pjObject::getPlugin('pjPayments') !== NULL) {
            $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
        } else {
            $this->set('payment_titles', __('payment_methods', true));
        }
        $this->set('status', 'OK');
	    } else {
        $this->set('status', 'ERR');
	    }
    	$this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
    }
	}
	
	
	public function pjActionGetWTime() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $get_date = $this->_get->toString('date');
			$today = date('Y-m-d');
      if ($this->_get->toInt('location_id') > 0 &&  !empty($get_date)) {
        $date = pjDateTime::formatDate($get_date, $this->option_arr['o_date_format']);
        $wt_arr = pjAppController::getWorkingTime($date, $this->_get->toInt('location_id'), $this->_get->toString('type'));
	            
      } else {
        $wt_arr = array('start_hour' => 0, 'end_hour' => 23);
        $this->_set('p_time', '00:00');
        $this->_set('d_time', '00:00');
      }
      $this->tpl['date'] = $date;
      	$this->tpl['wt_arr'] = $wt_arr;
    }
	}
	
	public function pjActionGetTerms() {
		$this->setAjax(true);
		if ($this->isXHR()) {
			$terms_conditions = '';
			$terms_lang_arr = pjMultiLangModel::factory()
				->select('t1.content')
				->where('t1.model','pjOption')
				->where('t1.locale', $this->getLocaleId())
				->where('t1.field', 'o_terms')
				->limit(1)
				->findAll()
				->getDataIndex(0);
			if ($terms_lang_arr) {
				$terms_conditions = $terms_lang_arr['content'];
			}
			$this->set('terms_conditions', $terms_conditions);
		}
	}
	
	public function pjActionGetProducts1() {
    $this->setAjax(true);
    $page = $this->_get->toString('page');
		$hasImage = $this->_get->toString('hasImg');
    if ($category_id = $this->_get->toInt('category_id')) {
      $pjProductModel = pjProductModel::factory();
      $arr = $pjProductModel
	      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	      ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjProduct' AND t3.locale = '%u' AND t3.field = 'description'", $this->getLocaleId()), 'left')
	      ->join('pjProductCategory', 't4.product_id=t1.id', 'left outer')
	      ->select("t1.*, t2.content AS name, t3.content AS description, t4.category_id, (SELECT COUNT(TO.product_id) FROM `".pjReviewModel::factory()->getTable()."` AS `TO` WHERE `TO`.product_id=t1.id AND `TO`.status = 1) AS cnt_reviews")
	      ->where('t4.category_id', $category_id)
	      ->where('t1.order_type', 'takeaway')
	      ->orderBy("t1.is_featured DESC, t2.content ASC")
	      ->findAll()
	      ->getData();
	        
      $pjExtraModel = pjExtraModel::factory();
      $pjProductExtraTable = pjProductExtraModel::factory()->getTable();
      $pjProductPriceModel = pjProductPriceModel::factory();
      $price_arr = array();
      $extra_arr = array();
      $product_id_arr = $pjProductModel->findAll()->getDataPair(null, 'id');
      if (!empty($product_id_arr)) {
        $temp_extra_arr = $pjExtraModel
        ->reset()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
        ->join('pjProductExtra', "t3.extra_id = t1.id", 'left')
        ->where(sprintf("(t1.id IN (SELECT TPE.extra_id FROM `%s` AS TPE WHERE TPE.product_id IN(".join(",", $product_id_arr).") ))", $pjProductExtraTable))
        ->select("t1.*, t2.content AS name, t3.product_id")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
        foreach($temp_extra_arr as $k => $v) {
          $extra_arr[$v['product_id']][] = $v;
        }
        $temp_price_arr = $pjProductPriceModel
        ->reset()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
        ->whereIn('t1.product_id', $product_id_arr)
        ->select("t1.*, t2.content AS price_name")
        ->findAll()
        ->getData();
        foreach($temp_price_arr as $k => $v) {
          $price_arr[$v['product_id']][] = $v;
        }
      }
      foreach ($arr as $k => $product) {
        $product['price_arr'] = array();
        $product['extra_arr'] = array();     
        if (isset($extra_arr[$product['id']])) {
          $product['extra_arr'] = $extra_arr[$product['id']];
        }
        if ($product['set_different_sizes'] == 'T' && isset($price_arr[$product['id']])) {
          $product['price_arr'] = $price_arr[$product['id']];
        }
				$reviews = pjReviewModel::factory()
					->select('t1.*')
					->where('t1.product_id', $product['id'])
					->where('t1.status', '1')
					->findAll()
					->getData();
				$tot_rating = $this->ratingsPercent($reviews);
		    $product['tot_rating'] = $tot_rating;
        $arr[$k] = $product;
      }
			$options = pjOptionModel::factory()
			->where('t1.foreign_id', 2)
			->orderBy('t1.order ASC')
			->findAll()
			->getData();
			$this->set('front_option', $options);
	    $this->set('arr', $arr);
			$this->set('page_type', $page);
			$this->set('has_image', $hasImage);
    }
	}

	public function pjActionGetProducts() {
    $this->setAjax(true);
    $page = $this->_get->toString('page');
	  $hasImage = $this->_get->toString('hasImg');
	  $orderType = 'takeaway';
    if ($category_id = $this->_get->toInt('category_id')) {
      $pjProductModel = pjProductModel::factory();
	    $pjProductModel
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjProduct' AND t3.locale = '%u' AND t3.field = 'description'", $this->getLocaleId()), 'left')
      ->join('pjProductCategory', 't4.product_id=t1.id', 'left outer')
      ->select("t1.*, t2.content AS name, t3.content AS description, t4.category_id, (SELECT COUNT(TO.product_id) FROM `".pjReviewModel::factory()->getTable()."` AS `TO` WHERE `TO`.product_id=t1.id AND `TO`.status = 1) AS cnt_reviews")
      ->where('t4.category_id', $category_id);
      if (ORDER_TYPE) {
        $pjProductModel->where("t1.order_type =",$orderType);
      }
      $pjProductModel->orderBy("t1.is_featured DESC, t2.content ASC");
      $arr = $pjProductModel->findAll()->getData();    
      $pjExtraModel = pjExtraModel::factory();
      $pjProductExtraTable = pjProductExtraModel::factory()->getTable();
      $pjProductPriceModel = pjProductPriceModel::factory();
      $price_arr = array();
      $extra_arr = array();
      $product_id_arr = $pjProductModel->findAll()->getDataPair(null, 'id');
      if (!empty($product_id_arr)) {
        $temp_extra_arr = $pjExtraModel
        ->reset()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
        ->join('pjProductExtra', "t3.extra_id = t1.id", 'left')
        ->where(sprintf("(t1.id IN (SELECT TPE.extra_id FROM `%s` AS TPE WHERE TPE.product_id IN(".join(",", $product_id_arr).") ))", $pjProductExtraTable))
        ->select("t1.*, t2.content AS name, t3.product_id")
        ->orderBy("t1.category_id ASC, name ASC")
        ->findAll()
        ->getData();
        // echo '<pre>'; print_r($temp_extra_arr); echo '</pre>';

        $category_id = 0;
        foreach($temp_extra_arr as $k => $v) {
        	if ($category_id != $v['category_id']) {
        		// $category_name = $v['name'].'_'.$v['category_id'];
						$con_category_id = $v['category_id'] - 1;
        		$category_name = EXTRA_CATEGORY_TYPES["$con_category_id"].'_'.$v['category_id'];
						
        		$category_id = $v['category_id'];
        	} else if (is_null($v['category_id'])) {
        		$category_name = 'Others_0';
        	}
          $extra_arr[$v['product_id']][$category_name][] = $v;
        }
        // echo '<pre>'; print_r($extra_arr); echo '</pre>';
        $temp_price_arr = $pjProductPriceModel
        ->reset()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
        ->whereIn('t1.product_id', $product_id_arr)
        ->select("t1.*, t2.content AS price_name")
        ->findAll()
        ->getData();
        foreach($temp_price_arr as $k => $v) {
          $price_arr[$v['product_id']][] = $v;
        }
      }
      foreach ($arr as $k => $product) {
        $product['price_arr'] = array();
        $product['extra_arr'] = array();     
        if (isset($extra_arr[$product['id']])) {
        	// if ($product['id'] == 418) {
        	// 	echo '<pre>'; print_r($extra_arr[$product['id']]); echo '</pre>';
        	// }
          $product['extra_arr'] = $extra_arr[$product['id']];
        }
        if ($product['set_different_sizes'] == 'T' && isset($price_arr[$product['id']])) {
          $product['price_arr'] = $price_arr[$product['id']];
        }
				$reviews = pjReviewModel::factory()
					->select('t1.*')
					->where('t1.product_id', $product['id'])
					->where('t1.status', '1')
					->findAll()
					->getData();
				$tot_rating = $this->ratingsPercent($reviews);
		    $product['tot_rating'] = $tot_rating;
        $arr[$k] = $product;
      }
			$options = pjOptionModel::factory()
			->where('t1.foreign_id', 2)
			->orderBy('t1.order ASC')
			->findAll()
			->getData();
			$this->set('front_option', $options);
	    $this->set('arr', $arr);
			$this->set('page_type', $page);
			$this->set('has_image', $hasImage);
    }
	}
	
	public function pjActionGetPaymentForm() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $order_id = $this->_get->toInt('order_id');
      $arr = pjOrderModel::factory()
      ->select('t1.*')
      ->find($order_id)
      ->getData();  
      $item_arr = pjOrderItemModel::factory()
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.foreign_id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->select('t1.*, t2.content as name')
      ->where('t1.order_id', $order_id)
      ->where('t1.type', 'product')
      ->findAll()
      ->getData();
      $_arr = array();
      foreach($item_arr as $v) {
      	$_arr[] = stripslashes($v['name']);
      }
      $arr['product_name'] = !empty($_arr) ? join("; ", $_arr) : null;
      $client = pjClientModel::factory()
      ->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
      ->join('pjAuthUser', "t1.foreign_id=t2.id", 'left outer')
      ->find($arr['client_id'])->getData();
      if (pjObject::getPlugin('pjPayments') !== NULL) {
        $pjPlugin = pjPayments::getPluginName($arr['payment_method']);
        if (pjObject::getPlugin($pjPlugin) !== NULL) {
          $this->set('params', $pjPlugin::getFormParams(array('payment_method' => $arr['payment_method']), array(
            'locale_id'	 => $this->getLocaleId(),
            'return_url'	=> $this->option_arr['o_thankyou_page'],
            'id'			=> $arr['id'],
            'foreign_id'	=> $this->getForeignId(),
            'uuid'		  => $arr['uuid'],
            'name'		  => @$client['c_name'],
            'email'		 => @$client['c_email'],
            'phone'		 => @$client['c_phone'],
            'amount'		=> $arr['total'],
            'cancel_hash'   => sha1($arr['uuid'].strtotime($arr['created']).PJ_SALT),
            'currency_code' => $this->option_arr['o_currency'],
          )));
        }
        if ($arr['payment_method'] == 'bank') {
          $bank_account = pjMultiLangModel::factory()->select('t1.content')
          ->where('t1.model','pjOption')
          ->where('t1.locale', $this->getLocaleId())
          ->where('t1.field', 'o_bank_account')
          ->limit(1)
          ->findAll()->getDataIndex(0);
          $this->set('bank_account', $bank_account['content']);
        }
      }
      $this->set('arr', $arr);
      $this->set('get', $this->_get->raw());
    }
	}
	
	
	public function pjActionCancel() {
    $this->setLayout('pjActionCancel');
    $pjOrderModel = pjOrderModel::factory();
    if ($this->_post->check('order_cancel')) {
      $order_arr = $pjOrderModel
	      ->reset()
	      ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	      ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	      ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
					AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
					AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->find($this->_post->toInt('id'))
				->getData();
			if (count($order_arr) > 0) {
		    $pjOrderModel
				->reset()
				->setAttributes(array("id" => $order_arr['id']))
				->modify(array('status' => 'cancelled'));
		    $locale_id = $this->getLocaleId();
		    if ((int) $order_arr['locale_id'] > 0) {
        	$locale_id = $order_arr['locale_id'];
		    }
		    pjAppController::addOrderDetails($order_arr, $locale_id);
		    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'cancel', $locale_id);
		    pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFrontPublic&action=pjActionCancel&err=200');
			}
    } else {
      if ($id = $this->_get->toInt('id')) {	
        $locale_id = $this->getLocaleId();
        $order_arr = $pjOrderModel->reset()->find($id)->getData();
        if (isset($order_arr['locale_id']) && (int) $order_arr['locale_id'] > 0) {
          $locale_id = $order_arr['locale_id'];
          $this->pjActionSetLocale($locale_id);
          $this->loadSetFields(true);
        }
        $arr = $pjOrderModel
	        ->reset()
	        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	        ->join('pjMultiLang', sprintf("t3.model='pjCountry' AND t3.foreign_id=t1.d_country_id AND t3.field='name' AND t3.locale='%u'", $locale_id), 'left outer')
	        ->join('pjMultiLang', sprintf("t4.model='pjCountry' AND t4.foreign_id=t1.location_id AND t4.field='name' AND t4.locale='%u'", $locale_id), 'left outer')
	        ->join('pjAuthUser', "t5.id=t2.foreign_id", 'left outer')
	        ->select('t1.*, t3.content as d_country, t4.content as location, t2.c_title, t5.email AS c_email, t5.name AS c_name, t5.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes')
	        ->find($id)
	        ->getData();
        if (count($arr) == 0) {
          $this->set('status', 2);
        } else {	
          if ($arr['status'] == 'cancelled') {
            $this->set('status', 4);
        	} else {
            $hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
            if ($this->_get->check('hash') != $hash) {
              $this->set('status', 3);
            } else {
              pjAppController::addOrderDetails($arr, $locale_id);
              $this->set('arr', $arr);
              if (pjObject::getPlugin('pjPayments') !== NULL) {
                $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $locale_id));
              } else {
                $this->set('payment_titles', __('payment_methods', true));
              }
            }
          }
        }
      } elseif (!$this->_get->check('err')) {
        $this->set('status', 1);
      }
	  }
	}
	
	private function getCategories($catID = 0)
	{
	    $pjCategoryModel = pjCategoryModel::factory();
	    
	    $category_obj = $pjCategoryModel
	    ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	    ->select(sprintf("t1.*, t2.content as name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
	    ->where('t1.status', 'T');
			if ($catID > 0) {
				$category_obj->where("(t1.category_id = $catID)");
			} else {
				$category_obj->where("(t1.category_id >= 0)");
			}
	    $category_arr =  $category_obj->orderBy("`order` ASC")
	    ->findAll()
	    ->getData();
	    
	    $category_id_arr = array();
	    $product_arr = array();
	    
	    return compact('category_arr', 'product_arr');
	}

	// Function to get the client IP address
	function getClientIp() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function pjActionGetSearchResults() {
		$this->setAjax(true);
		if ($this->isXHR())
	    {
	        $key = $this->_get->toString('key');
			$page_type = $this->_get->toString('page');
			$hasImage = $this->_get->toString('hasImg');
			$this->set('has_image', $hasImage);

			$pjProductModel = pjProductModel::factory();
	        $arr = $pjProductModel
	        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'",$this->getLocaleId()), 'left')
	        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjProduct' AND t3.locale = '%u' AND t3.field = 'description'",$this->getLocaleId()), 'left')
	        ->join('pjProductCategory', 't4.product_id=t1.id', 'left outer')
	        ->select("t1.*, t2.content AS name, t3.content AS description, t4.category_id, (SELECT COUNT(TO.product_id) FROM `".pjReviewModel::factory()->getTable()."` AS `TO` WHERE `TO`.product_id=t1.id AND `TO`.status = 1) AS cnt_reviews")
	        ->where("t2.content LIKE '%$key%' AND t2.model = 'pjProduct' OR t3.content LIKE '%$key%' AND t3.model = 'pjProduct'")
	        ->orderBy("t1.is_featured DESC, t2.content ASC")
	        ->findAll()
	        ->getData();
	        
	        $pjExtraModel = pjExtraModel::factory();
	        $pjProductExtraTable = pjProductExtraModel::factory()->getTable();
	        $pjProductPriceModel = pjProductPriceModel::factory();
	        
	        $price_arr = array();
	        $extra_arr = array();
	        $product_id_arr = $pjProductModel->findAll()->getDataPair(null, 'id');
	        if(!empty($product_id_arr))
	        {
	            $temp_extra_arr = $pjExtraModel
	            ->reset()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	            ->join('pjProductExtra', "t3.extra_id = t1.id", 'left')
	            ->where(sprintf("(t1.id IN (SELECT TPE.extra_id FROM `%s` AS TPE WHERE TPE.product_id IN(".join(",", $product_id_arr).") ))", $pjProductExtraTable))
	            ->select("t1.*, t2.content AS name, t3.product_id")
	            ->orderBy("name ASC")
	            ->findAll()
	            ->getData();
	            foreach($temp_extra_arr as $k => $v)
	            {
	                $extra_arr[$v['product_id']][] = $v;
	            }
	            $temp_price_arr = $pjProductPriceModel
	            ->reset()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
	            ->whereIn('t1.product_id', $product_id_arr)
	            ->select("t1.*, t2.content AS price_name")
	            ->findAll()
	            ->getData();
	            foreach($temp_price_arr as $k => $v)
	            {
	                $price_arr[$v['product_id']][] = $v;
	            }
	        }
	        foreach($arr as $k => $product)
	        {
	            $product['price_arr'] = array();
	            $product['extra_arr'] = array();
	            
	            if(isset($extra_arr[$product['id']]))
	            {
	                $product['extra_arr'] = $extra_arr[$product['id']];
	            }
	            if($product['set_different_sizes'] == 'T' && isset($price_arr[$product['id']]))
	            {
	                $product['price_arr'] = $price_arr[$product['id']];
	            }
				$reviews = pjReviewModel::factory()->select('t1.*')
					   ->where('t1.product_id', $product['id'])
					   ->where('t1.status', '1')
					   ->findAll()
					   ->getData();
				$tot_rating = $this->ratingsPercent($reviews);
			    $product['tot_rating'] = $tot_rating;
	            $arr[$k] = $product;
	        }
	        $this->set('arr', $arr);
			$this->set('page_type', $page_type);
			$options = pjOptionModel::factory()
			->where('t1.foreign_id', 2)
			->orderBy('t1.order ASC')
			->findAll()
			->getData();
			$this->set('front_option', $options);
	    }
	}

	public function pjActionSendOtp() {
    $this->setAjax(true);
    if ($this->isXHR())
    {

    }
	}

	public function pjActionProductRatings() {
		$this->setAjax(true);
		if ($this->isXHR()) {
			$prd_id = $_REQUEST['product_id'];
			$pjProductModel = pjProductModel::factory();
			$product_info = $pjProductModel
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjProduct' AND t3.locale = '%u' AND t3.field = 'description'", $this->getLocaleId()), 'left')
			->join('pjProductCategory', 't4.product_id=t1.id', 'left outer')
			->select("t1.*, t2.content AS name, t3.content AS description, t4.category_id, (SELECT COUNT(TO.product_id) FROM `".pjReviewModel::factory()->getTable()."` AS `TO` WHERE `TO`.product_id=t1.id AND `TO`.status = 1) AS cnt_reviews")
			->where('t1.id', $prd_id)
			->findAll()
			->getData();
			$reviews = pjReviewModel::factory()
			           ->select('t1.*')
					   ->where('t1.product_id', $prd_id)
					   ->where('t1.status', '1')
					   ->findAll()
					   ->getData();
			$tot_rating = $this->ratingsPercent($reviews);
			$product_info[0]['tot_rating'] = $tot_rating;
			$this->set('product_info', $product_info);
			$this->set('reviews', $reviews);
		}
	}

	function ratingsPercent($arr) {
		if(count($arr) > 0) {
            $tot_rate = count($arr) * 5;
			$this_rate = 0;
			foreach($arr as $review) {
				$this_rate = $this_rate + $review['rating'];
			}
			$rate = ($this_rate / $tot_rate) * 100;
			$rate = $rate/20;
			$rate = bcdiv($rate, 1, 1);
		} else {
			$rate = 0;
		}
    return $rate;
	}

	function sendMessage($phone, $msg) {
		$pjSmsApi = new tlSmsApi();
		if (ENVIRONMENT != 'production') {
      $phone = TESTMOBILENUMBER;
    }
		$response = $pjSmsApi
		->setApiKey($this->option_arr['plugin_sms_api_key'])
		->setNumber($phone)
		->setText($msg)
		->setSender(DOMAIN)
		->send();
		if($response['status'] == 'success') { $sts = '1'; } else { $sts = '0'; }
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
	public function pjActionCheckNewOrder() {
		$this->setAjax(true);
    if ($this->isXHR()) {
			$today = date( 'y-m-d', time ());
			$today = $today." "."00:00:00";
			$orders = pjOrderModel::factory()
			          ->select('t1.*')
					  ->where('t1.status', 'pending')
					  ->where('t1.origin', 'web')
            ->where('t1.deleted_order', '0')
					  ->where("(t1.created >= '$today')")
					  ->findAll()
					  ->getData();
			if(count($orders) > 0) {
				$no_of_orders = count($orders);
				return self::jsonResponse(array('status' => 'true', 'orders' => $no_of_orders));
			} else {
				return self::jsonResponse(array('status' => 'false', 'orders' => 'no pending orders'));
			}
		}
		exit;
	}

	public function pjActionGetNewOrder() {
		$this->setAjax(true);
    if ($this->isXHR()) {
			$now = date( 'y-m-d');
			$now = $now." 00:00:00";
			$order = pjOrderModel::factory()
			          ->select('t1.*')
					  ->where('t1.status', 'pending')
					  ->where('t1.origin', 'web')
	                    ->where('t1.deleted_order', '0')
					  ->where('t1.is_viewed', '0')
					  ->where("(t1.delivery_dt >= '$now')")
					  ->orderBy("created DESC")
					  ->limit(1)
					  ->findAll()
					  ->getData();
			if(!empty($order)) {
				return self::jsonResponse(array('status' => 'true', 'order' => $order));
			} else {
				return self::jsonResponse(array('status' => 'false', 'orders' => 'no recent orders'));
			}
		}
		exit;
	}

	public function pjActionOrderViewed() {
		$this->setAjax(true);
        if ($this->isXHR())
        {
			if ($this->_post->toInt('order_id') <= 0)
            {
                echo "invalid parameter";
                exit;
            } else {
				$id =$this-> _post->toInt('order_id');
				$order_arr = pjOrderModel::factory()
				->where('id', $id)
				->findAll()
				->getData();
				if(pjOrderModel::factory()->where('id',$id)->modifyAll(array(
					'is_viewed' => 1
				))->getAffectedRows() == 1) {
					self::jsonResponse(array('status' => 'Ok', 'text' => 'Order has Viewed'));
				} else {
					self::jsonResponse(array('status' => 'ERR', 'text' => 'Something is wrong'));
				}
			}
		}
		exit;
	}
	// Action to get credit balance

  public function pjActionGetCreditBalance() {
    if (!isset($this->option_arr['plugin_sms_api_key'])) {
			self::jsonResponse(array('status' => 'ERR', 'text' => 'Sms api not set'));
          exit;
    } else {
			// Account details
			$apiKey = urlencode($this->option_arr['plugin_sms_api_key']);
			// Prepare data for POST request
			$data = array('apikey' => $apiKey);
		
			// Send the POST request with cURL
			$ch = curl_init('https://api.txtlocal.com/balance'); 
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			if ($response === false) {
				self::jsonResponse(array('status' => 'Error', 'text' => array("sms"=>"0", "mms"=>"0")));
			} else {
				$balance = json_decode($response);
				self::jsonResponse(array('status' => 'OK', 'text' => $balance->balance));
			}
			curl_close($ch);
			exit;
		}
	}

  public function pjActionGetClientMessage() {
		$this->setAjax(true);
    if ($this->isXHR()) {
			$message = pjClientMessagesModel::factory()
				->select('t1.*')
				->where('t1.is_show', '1')
				->limit(1)
				->findAll()
				->getData();
			if(!empty($message)) {
				return self::jsonResponse(array('status' => 'true', 'message' => $message));
			} else {
				return self::jsonResponse(array('status' => 'false', 'message' => 'no recent message'));
			}
		}
  }

	// Action to get user orders 

	public function pjActionMyOrders() {
		$this->setAjax(true); 
    if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
      if ($this->isFrontLogged()) {
				$client = $_SESSION[$this->defaultClient];
        $orders = pjOrderModel::factory()
				->select("t1.*")
				->where("t1.client_id", $client['client_id'])
				->orderBy('t1.id desc')
				->findAll()
				->getData();
				$this->set('my_orders', $orders);
        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
      } else {
        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
      }
    }
	}

	public function pjActionGetAdminTerms() {
		$this->setAjax(true); 
    if ($this->isXHR()) {
      
    }
	}
    
}
?>