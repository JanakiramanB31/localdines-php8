<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReviews extends pjAdmin {
  public function pjActionGetReviews() {
		$this->setAjax(true);
		if ($this->isXHR()) {
		    $pjReviewModel = pjReviewModel::factory()->join('pjProduct', 't2.id=t1.product_id', 'left outer')
		    ->join('pjMultiLang', "t3.model='pjProduct' AND t3.foreign_id=t1.product_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer');
			if ($q = trim($this->_get->toString('q'))) {
				$pjReviewModel->where("(t1.review LIKE '%$q%' OR t3.content like '%$q%' OR t1.type like '%$q%')");
			}
			if ($this->_get->toString('status') != '') {
		    $status = $this->_get->toInt('status');
		    $pjReviewModel->where('t1.status', $status);
			}
			$column = 'id';
			$direction = 'DESC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
		    $column = $this->_get->toString('column');
		    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjReviewModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages) {
				$page = $pages;
			}

			$pjReviewModel = $pjReviewModel
			->join('pjAuthUser', 't4.id=t1.user_id', 'left outer')
			->select("t1.*,t2.image,t3.content AS product_name, t4.name, t4.phone");
			if ($this->_get->toString('status') != '') {
				$pjReviewModel->where('t1.status', $status);
			}
			$data = $pjReviewModel
			->orderBy("$column $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			foreach($data as $k => $v) {
				$star = $data[$k]['rating'];
				$data[$k]['rating'] = "<span class='star star_$star'></span>";
				$data[$k]['created_at'] = date('d-m-Y', strtotime($v['created_at']));
				if ($v['user_type'] == "client" && $v['name'] != '') {
					$data[$k]['user_type'] = $v['name'];
					$data[$k]['phone'] = $v['phone'];
				} else {
					$data[$k]['user_type'] = 'Guest';
					$data[$k]['phone'] = '';
				}
			}
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
	 	}
		exit;
	}  

	public function pjActionCreate() {
		if (self::isPost() && $this->_post->toInt('review_create')) {
			$post = $this->_post->raw();
			print_r($post);
		}
	}

	public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminReviews.js');
	}

	public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
    	return;
    }
    if (self::isPost() && $this->_post->toInt('client_update') && $this->_post->toInt('id')) {
      $pjClientModel = pjClientModel::factory();
      $id = $this->_post->toInt('id');
      $post = $this->_post->raw();
      $data = array();
      if($this->_post->check('status')) {
        $post['status'] = 'T';
        $data['status'] = 'T';
      } else {
        $post['status'] = 'F';
        $data['status'] = 'F';
      }
      $post['c_postcode'] = $post['post_code'];
      $pjClientModel->where('id', $id)->limit(1)->modifyAll($post);
      $client = $pjClientModel->reset()->find($id)->getData();
      $data['id'] = $client['foreign_id'];
      $data['email'] = $this->_post->toString('c_email');
      $data['password'] = $this->_post->toString('c_password');
      $data['name'] = $this->_post->toString('c_name');
      $data['u_surname'] = $this->_post->toString('surname');
      $data['phone'] = $this->_post->toString('c_phone');
      $old_phone = pjAuthUserModel::factory()
	      ->select("t1.phone")
	      ->where("t1.id",$data['id'])
	      ->findAll()
	      ->getData();
	        
      pjOrderModel::factory()
       	->where("phone_no", $old_phone[0]['phone']) 
     		->where("status", "pending")
       	->modifyAll(array("phone_no" => $data['phone']));    
      pjAuth::init($data)->updateUser();
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminReviews&action=pjActionUpdate&id=".$id."&err=AC01");
    }
    if (self::isGet() && $this->_get->toInt('id')) {
      $id = $this->_get->toInt('id');
      $arr = pjReviewModel::factory()
      	->join('pjMultiLang', "t2.model='pjProduct' AND t2.foreign_id=t1.product_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
      	->select("t1.*, t2.content AS product_name")
    		->find($id)
				->getData();
  		if ($arr['user_id']) {
  			$user_info = pjAuthUserModel::factory()
  			->select("t1.*")
  			->find($arr['user_id'])
  			->getData();
  			$this->set('user_info', $user_info);
  		}
			if (count($arr) === 0) {
		    pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminReviews&action=pjActionIndex&err=AC08");
  		}
      $this->set('arr', $arr);
			$this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminReviews.js');
    }
	}

	public function pjActionDeleteReview() {
		$this->setAjax(true);
		if (!$this->isXHR()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		if (!self::isPost()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}
		if (!pjAuth::factory()->hasAccess()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		if (!($this->_get->toInt('id'))) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
		}
		$pjReviewModel = pjReviewModel::factory();
		$Review = $pjReviewModel->find($this->_get->toInt('id'))->getData();
		if (!$pjReviewModel->reset()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Review has not been deleted.'));
		}
		$product  = pjProductModel::factory()
	  	->where('id', $Review['product_id'])
	  	->findAll()
	  	->getData();
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Review has been deleted'));
		exit;
	}

	public function pjActionSaveReviewStatus() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
      	self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
      }    
      if ($this->_get->toInt('id') <= 0) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
      }
      $rid = $this->_get->toInt('id');
      if ($rid) {
      	$row = pjReviewModel::factory()
        ->where('id', $rid)
        ->modifyAll(array(
            'status' => ":IF(`status`='0','1','0')"
        ))->getAffectedRows();
        $review = pjReviewModel::factory()
	        ->select("t1.*")
	        ->where('id', $rid)
	        ->findAll()
	        ->getData();
        $product_rate = pjProductModel::factory()
	        ->select("t1.no_of_ratings")
	        ->where('id', $review[0]['product_id'])
	        ->findAll()
	        ->getData();
      }
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Review status has been changed.'));
    }
    exit;
	}

	public function getUserInfo($id) {
		$u_id = $id;
		$u_Info = pjAuthUserModel::factory()
			->select("t1.*")
			->find($u_id)
			->getData(); 
		return $u_Info;
	}

	public function pjActionVoucherMessage() {
		$this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
      }
      if ($this->_post->toInt('phone') <= 0) {
      	self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
      }
      $phone_no = $this->_post->toInt('phone');
      $msg = $this->_post->toString('tq_msg');
      $api_key = 'DHenxeCCLHs37RPOgLAoeqy3HNQzKfLNoq1aK3cWBLP9cf';
      $ch = curl_init('https://api.voodoosms.com/sendsms');
      $msg = json_encode(
        [
          'to' => '447792995419',
          'from' => DOMAIN,
          'msg' => $msg,
          'schedule' => "now",
          'sandbox' => true
        ]
      );
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $api_key
      ]);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      $response = json_decode($response);
      curl_close($ch);
      if ($response->count == 1) {
        $text = __('plugin_base_sms_test_sms_sent_to', true) . ' ' . '447466708066';
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'title' => __('plugin_base_sms_sent', true), 'text' => $response));
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Thank yoe message has been sent.'));
      } else {
        $text = $response->error->msg;
        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'title' => __('plugin_base_sms_failed_to_send', true), 'text' => $text));
      }
    }
    exit;
	}
}
?>