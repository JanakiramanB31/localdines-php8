<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminPosOrders extends pjAdmin {
  public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    } else {
      $role_id = $this->getRoleId();
      $this->set('role_id', $role_id);
    }
    if ($this->_get->toString('origin')) {
      $origin_type = ucfirst($this->_get->toString('origin'));
    } else {
      $origin_type = 'Pos';
    }
    $this->set('origin_type', $origin_type);
    $this->set('posCount', $this->pendingOrderCount('Pos'));
    $this->set('telCount', $this->pendingOrderCount('Telephone'));
    $this->set('webCount', $this->pendingOrderCount('Web'));
    $this->set('table_list', $this->getRestaurantTables());
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminPos.js');
    $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
    $this->appendCss('VirtualKeyboard/keyboard.css');
    $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
    $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
    $this->appendJs('jquery.cookie-consent.min.js');
  }

  public function pjActionCreateEatin() {
    $this->checkLogin();
    $this->setLayout('pjActionOrder');
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->set('order_title', 'Eat In');
    $this->appendJs('eatin.js');
    $this->getInculdeData('eatin');
    $arr['table_name'] = '';
    $this->set('selTableName', '');
    $this->set('arr', $arr);
  }

  public function getInculdeData($orderType = 'eatin') {
    if (self::isGet()) {
      $country_arr = pjBaseCountryModel::factory()->select('t1.id, t2.content AS country_title')
        ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')
        ->orderBy('`country_title` ASC')
        ->findAll()
        ->getData();
      $this->set('country_arr', $country_arr);

      $product_arr = array();
      $pjProductModel = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.preparation_time,t1.image, t1.order_type, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
        ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->where('is_featured=1');
        if (ORDER_TYPE) {
          $pjProductModel->where("t1.order_type = ",$orderType);
        }
        $pjProductModel->groupBy('t1.id, t1.set_different_sizes, t1.price')
        ->limit(12)
        ->findAll();
      $hot_products_arr = $pjProductModel->getData();
      $this->set('hot_products_arr', $hot_products_arr);

      $postal_codes = pjPostalcodeModel::factory()->select("t1.*")
        ->findAll()
        ->getData();
      $this->set('postal_codes', $postal_codes);

      // MEGAMIND
      $client_info = pjOrderModel::factory()->join('pjClient', "t2.id = t1.client_id")
        ->select('t1.id, t1.surname,t1.phone_no, t1.sms_email, t1.post_code, t1.d_address_1, t1.d_address_2, t1.d_city, t1.first_name, t1.client_id, t1.kprint, t2.c_title, t1.type, t1.is_paid, t1.order_despatched, t1.mobile_delivery_info, t1.mobile_offer, t1.email_offer, t1.email_receipt, t1.created, t1.preparation_time')
        ->findAll()
        ->getData();
      $this->set('client_info', $client_info);

      $chef_arr = pjAuthUserModel::factory()->select("t1.id, t1.name")
        ->where('role_id', '5')
        ->findAll()
        ->getData();
      $this->set('chef_arr', $chef_arr);

      if (!isset($_SESSION['chef']) || $_SESSION['chef'] == 0) {
        $_SESSION['chef'] = $chef_arr[0]['id'];
      }

      // !MEGAMIND
      $category_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS name")
        ->where("t1.status", 1)
        ->orderBy("t1.order ASC")
        ->findAll()
        ->getData();

      $this->set('category_arr', $category_arr);
      $category_list = [];
      if (SUB_CATEGORY) {
        foreach ($category_arr as $category) {
          if ($category['category_id'] != '' && $category['category_id'] > 0) {
            $category_list[$category['category_id']][$category['id']] = $category['name'];
          } else {
            $category_list[0][$category['id']] = $category['name'];
          }
        }
      } else {
        foreach ($category_arr as $category) {
          $category_list[$category['id']] = $category['name'];
        }
      }
      // echo '<pre>'; print_r($category_list);
      // exit;
      $this->set('category_list', $category_list);
      // !MEGAMIND
      $this->set('table_list', $this->getRestaurantTables());

      $location_arr = pjLocationModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
      $this->set('location_arr', $location_arr);

      $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
        ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
        ->where('t2.status', 'T')
        ->orderBy('t2.name ASC')
        ->findAll()
        ->getData();
      $this->set('client_arr', $client_arr);

      if (pjObject::getPlugin('pjPayments') !== NULL) {
        $this->set('payment_option_arr', pjPaymentOptionModel::factory()
          ->getOptions($this->getForeignId()));
        $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId() , $this->getLocaleId()));
      } else {
        $this->set('payment_titles', __('payment_methods', true));
      }

      $this->appendCss('jquery.bootstrap-touchspin.min.css', PJ_THIRD_PARTY_PATH . 'touchspin/');
      $this->appendJs('jquery.bootstrap-touchspin.min.js', PJ_THIRD_PARTY_PATH . 'touchspin/');
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('moment-with-locales.min.js', PJ_THIRD_PARTY_PATH . 'moment/');
      $this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
      $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendCss('bootstrap-select.min.css', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/css/');
      $this->appendJs('bootstrap-select.min.js', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/js/');  
    }
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminPos.js');
    $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
    $this->appendCss('VirtualKeyboard/keyboard.css');
    $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
    $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
  }

  public function pjActionCreateTelephone() {
    $this->checkLogin();
    $this->setLayout('pjActionOrder');
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->getInculdeData();
    $this->set('order_title', 'Telephone');
  }

  public function pjActionCreate() {
    $this->checkLogin();
    $this->setLayout('pjActionOrder');
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('order_create')) {
      $post_total = $this->getTotal();
      $post = $this->_post->raw();
      $data = array();
      $data['uuid'] = time();
      $data['ip'] = pjUtil::getClientIp();
      $data['locale_id'] = $this->getLocaleId();
      $data['call_start'] = $this->_post->toString('call_start');
      $data['call_end'] = date('h:i:s A');
      if ($this->_post->toString('override_postcode')) {
        $data['override_postcode'] = 1;
      } else {
        $data['override_postcode'] = 0;
      }
      $client_name = $this->_post->toString('c_name');
      if ($client_name) {  
        $data['first_name'] = $client_name;
      } else {
        $data['first_name'] = ":NULL";
      }
      $data['chef_id'] = $this->session->getData('chef');
      $data['origin'] = 'Telephone';
      $c_data = array();
      $c_data['c_phone'] = $this->_post->toString('phone_no');
      $client_exist = pjClientModel::factory()->join("pjAuthUser", "t2.id = t1.foreign_id")
        ->select("t1.*, t2.phone")
        ->where("t2.phone", $c_data['c_phone'])->findAll()
        ->getData();
      if ($client_exist) {
        $data['client_id'] = $client_exist[0]['id'];
        $c_update = array();
        $c_update['c_type'] = $this->getClientType($data);
        if ($client_exist[0]['c_address_1'] == '' || $client_exist[0]['c_city'] == '' 
          || $client_exist[0]['c_postcode'] == '') {
          $c_update['c_address_1'] = $post['d_address_1'];
          $c_update['c_address_2'] = $post['d_address_2'];
          $c_update['c_city'] = $post['d_city'];
          $c_update['c_postcode'] = $post['post_code'];
        } else if ($client_exist[0]['c_address_1'] != $post['d_address_1'] || $client_exist[0]['c_address_2'] != $post['d_address_2'] || $client_exist[0]['c_city'] != $post['d_city'] || $client_exist[0]['c_postcode'] != $post['post_code'] && $post['type']) {

          if ($this->_post->check('type')) {
            $c_update['c_address_1'] = $post['d_address_1'];
            $c_update['c_address_2'] = $post['d_address_2'];
            $c_update['c_city'] = $post['d_city'];
            $c_update['c_postcode'] = $post['post_code'];
          }
        }
        $c_update['mobile_delivery_info'] = $this->_post->toBool('mobile_delivery_info');
        $c_update['mobile_offer'] = $this->_post->toBool('mobile_offer');
        $c_update['email_receipt'] = $this->_post->toString('email_receipt');
        $c_update['email_offer'] = $this->_post->toString('email_offer');
        pjClientModel::factory()->where('id', $client_exist[0]['id'])->modifyAll($c_update);
      } else {
        $c_data['c_title'] = $this->_post->toString('c_title');
        $c_data['c_name'] = $this->_post->toString('c_name');
        $c_data['u_surname'] = $this->_post->toString('surname');
        $c_data['c_email'] = $this->_post->toString('sms_email');
        $c_data['c_type'] = "New";
        $c_data['c_address_1'] = $this->_post->toString('d_address_1');
        $c_data['c_address_2'] = $this->_post->toString('d_address_2');
        $c_data['c_city'] = $this->_post->toString('d_city');
        $c_data['post_code'] = rtrim($this->_post->toString('post_code'));
        $c_data['password'] = $c_data['c_name'] . $data['uuid'];
        $c_data['status'] = 'T';
        $c_data['locale_id'] = $this->getLocaleId();
        $c_data['mobile_delivery_info'] = $this->_post->toBool('mobile_delivery_info');
        $c_data['mobile_offer'] = $this->_post->toBool('mobile_offer');
        $c_data['email_receipt'] = $this->_post->toString('email_receipt');
        $c_data['email_offer'] = $this->_post->toString('email_offer');
        $response = pjFrontClient::init($c_data)->createClient();
        if (isset($response['client_id']) && (int)$response['client_id'] > 0) {
          $data['client_id'] = $response['client_id'];
        }
      }
      if ($this->_post->check('is_paid')) {
        $data['is_paid'] = 1;
      } else {
        $data['is_paid'] = 0;
      }

      if (!$this->_post->check('type')) {
        $data['type'] = 'delivery';
        if (!empty($post['d_date']) && !empty($post['delivery_time'])) {
          $data['p_time'] = 0;
          $data['d_time'] = $this->_post->toInt('d_time');
          $d_date = $post['d_date'];
          $d_time = $post['delivery_time'];
          if (count(explode(" ", $d_time)) == 2) {
            list($_time, $_period) = explode(" ", $d_time);
            $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
          } else {
            $time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
          }
          $data['d_dt'] = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']) . ' ' . $time;
          $data['delivery_dt'] = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']) . ' ' . $time;
        }
        if ($this->_post->toInt('d_location_id')) {
          $data['location_id'] = $this->_post->toInt('d_location_id');
        }
      } else {
        $data['type'] = 'pickup';
        if (!empty($post['p_date']) && !empty($post['pickup_time'])) {
          $data['d_time'] = 0;
          $data['p_time'] = $this->_post->toInt('p_time');
          $p_date = $post['p_date'];
          $p_time = $post['pickup_time'];
          if (count(explode(" ", $p_time)) == 2) {
            list($_time, $_period) = explode(" ", $p_time);
            $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
          } else {
            $time = pjDateTime::formatTime($p_time, $this->option_arr['o_time_format']);
          }
          $data['p_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time;
          $data['delivery_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time; 
        }
        if ($this->_post->toInt('p_location_id')) {
          $data['location_id'] = $this->_post->toInt('p_location_id');
        }
      }
      if ($this->_post->toString('payment_method') == 'creditcard') {
        $data['cc_exp'] = $this
          ->_post
          ->toString('cc_exp_month') . "/" . $this
          ->_post
          ->toString('cc_exp_year');
      }
      if (!empty($post['vouchercode'])) {
        $post['voucher_code'] = $post['vouchercode'];
      }
      $data['order_count_of_the_day'] = $this->getTodayOrderCount();
      $id = pjOrderModel::factory(array_merge($post, $data, $post_total))->insert()->getInsertId();
      $order_id = "T" . $id;
      pjOrderModel::factory()->where('id', $id)->modifyAll(array(
        'order_id' => $order_id,

      ));
      if ($id !== false && (int)$id > 0) {
        $this->saveOrderItems($post, $id, false);
        $err = 'AR07';
      } else {
        $err = 'AR04';
      }
      if (KITCHEN_PRINT) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=index&origin=Tel&id=$id");
      } else {
         pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
      }
      
    }
    $this->getInculdeData('takeaway');
    $arr['table_name'] = 'Take Away';
    $this->set('arr', $arr);
    $this->set('order_title', 'Take Away');
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    $this->setLayout('pjActionOrder');
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('order_update') && $this->_post->toInt('id')) {

      $pjOrderModel = pjOrderModel::factory();
      $pjOrderItemModel = pjOrderItemModel::factory();
      $pjProductPriceModel = pjProductPriceModel::factory();
      $pjExtraModel = pjExtraModel::factory();
      $pjProductModel = pjProductModel::factory();

      $id = $this->_post->toInt('id');
      $post = $this->_post->raw();
      $arr = $pjOrderModel->find($id)->getData();
      // echo '<pre>'; print_r();echo '</pre>'; exit;
      if (empty($arr)) {
        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08&origin=Telephone");
      }
      if ($this->_post->toString('override_postcode')) {
        $post['override_postcode'] = 1;
      } else {
        $post['override_postcode'] = 0;
      }

      $new_client_id = NULL;
      $c_data['phone'] = $this->_post->toInt('phone_no');
      $client_exist = pjClientModel::factory()->join("pjAuthUser", "t2.id = t1.foreign_id", "left")
        ->select("t1.*, t2.phone")
        ->where("t2.phone", $c_data['phone'])->findAll()
        ->getData();

      if (!$client_exist) {
        $c_data = array();
        $c_data['c_phone'] = $this->_post->toInt('phone_no');
        $c_data['c_title'] = $this->_post->toString('c_title');
        $c_data['c_name'] = $this->_post->toString('c_name');
        $c_data['u_surname'] = $this->_post->toString('surname');
        $c_data['c_email'] = $this->_post->toString('sms_email');
        $c_data['c_address_1'] = $this->_post->toString('d_address_1');
        $c_data['c_address_2'] = $this->_post->toString('d_address_2');
        $c_data['c_city'] = $this->_post->toString('d_city');
        $c_data['status'] = 'T';
        $c_data['post_code'] = rtrim($this->_post->toString('post_code'));
        $c_data['locale_id'] = $this->getLocaleId();
        $c_update['mobile_delivery_info'] = $this->_post->toBool('mobile_delivery_info');
        $c_update['mobile_offer'] = $this->_post->toBool('mobile_offer');
        $c_update['email_receipt'] = $this->_post->toString('email_receipt');
        $c_update['email_offer'] = $this->_post->toString('email_offer');
        if (isset($response['client_id']) && (int)$response['client_id'] > 0) {
          $new_client_id = $response['client_id'];
        }
      } else {
        $c_update = array();
        if ($client_exist[0]['c_address_1'] == '' || $client_exist[0]['c_city'] == '' || $client_exist[0]['c_postcode'] == '')
        {
          $c_update['c_address_1'] = $post['d_address_1'];
          $c_update['c_address_2'] = $post['d_address_2'];
          $c_update['c_city'] = $post['d_city'];
          $c_update['c_postcode'] = $post['post_code'];

        } else if ($client_exist[0]['c_address_1'] != $post['d_address_1'] || $client_exist[0]['c_address_2'] != $post['d_address_2'] || $client_exist[0]['c_city'] != $post['d_city'] || $client_exist[0]['c_postcode'] != $post['post_code'] && $post['type']) {

          if ($this->_post->check('type')) {
            $c_update['c_address_1'] = $post['d_address_1'];
            $c_update['c_address_2'] = $post['d_address_2'];
            $c_update['c_city'] = $post['d_city'];
            $c_update['c_postcode'] = $post['post_code'];
          }
        }
        $c_update['mobile_delivery_info'] = $this->_post->toBool('mobile_delivery_info');
        $c_update['mobile_offer'] = $this->_post->toBool('mobile_offer');
        $c_update['email_receipt'] = $this->_post->toString('email_receipt');
        $c_update['email_offer'] = $this->_post->toString('email_offer');

        pjClientModel::factory()->where('id', $client_exist[0]['id'])->modifyAll($c_update);
      }

      if (isset($post['product_id']) && count($post['product_id']) > 0) {
        $keys = array_keys($post['product_id']);
        $pjOrderItemModel->reset()->where('order_id', $id)->whereNotIn('hash', $keys)->eraseAll();
        if ($id !== false && (int)$id > 0) {
          $this->saveOrderItems($post, $id, false);
          if ($post['response']) {
            $this->savePaymentResponse($post, $id);
          }
          $err = 'AR07';
        } else {
          $err = 'AR04';
        }
      }
      $data = array();
      $data['ip'] = pjUtil::getClientIp();
      if ($this->_post->check('is_paid')) {
        $data['is_paid'] = 1;
        $data['payment_method'] = $post['payment_method'];
        $data['status'] = 'delivered';
      } else {
        $data['is_paid'] = 0;
      }
      if (!$this->_post->check('type')) {
        $data['type'] = 'delivery';
        if (!empty($post['d_date']) && !empty($post['delivery_time'])) {
          $d_date = $post['d_date'];
          $d_time = $post['delivery_time'];
          if (count(explode(" ", $d_time)) == 2) {
            list($_time, $_period) = explode(" ", $d_time);
            $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
          } else {
            $time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
          }
          $data['d_dt'] = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']) . ' ' . $time;
          $data['delivery_dt'] = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']) . ' ' . $time;
        }
        if ($this->_post->toInt('d_location_id')) {
          $data['location_id'] = $this->_post->toInt('d_location_id');
        }
        unset($post['p_dt']);
        $data['p_dt'] = ':NULL';
        unset($post['p_time']);
        $data['p_time'] = 0;
      } else {
        $data['type'] = $post['origin'] == 'web' ? 'pickup & call' : 'pickup';
        if (!empty($post['p_date']) && !empty($post['pickup_time'])) {
          $p_date = $post['p_date'];
          $p_time = $post['pickup_time'];

          if (count(explode(" ", $p_time)) == 2) {
            list($_time, $_period) = explode(" ", $p_time);
            $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
          } else {
            $time = pjDateTime::formatTime($p_time, $this->option_arr['o_time_format']);
          }
          $data['p_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time;
          $data['delivery_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time;

        }
        if ($this->_post->toInt('p_location_id')) {
          $data['location_id'] = $this->_post->toInt('p_location_id');
        }
        unset($post['d_dt']);
        $data['d_dt'] = ':NULL';
        unset($post['d_time']);
        $data['d_time'] = 0;
      }
      $data['client_id'] = $new_client_id;
      if (array_key_exists('res_table_name', $post)) {
        $data['table_name'] = $post['res_table_name'];
      } 
      if (array_key_exists('total_persons', $post)) {
        $data['total_persons'] = $post['total_persons'];
      }     
      $post_data = $this->getTotal();
      $post["first_name"] = $post["c_name"];
      $post["guest_title"] = $post["c_title"];
      if (!empty($post['vouchercode'])) {
        $post['voucher_code'] = $post['vouchercode'];
      }
      $pjOrderModel->reset()
        ->where('id', $id)->limit(1)
        ->modifyAll(array_merge($post, $data, $post_data));

      $err = 'AR01';
      $origin = ucfirst($post["origin"]);
      if ($data['is_paid']) {
        if ($data['payment_method'] == 'cash') {
          $this->pjActionCashDrawer();
        }
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionInitialPrint&id=$id");
      } else {
        if (KITCHEN_PRINT) {
          pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=index&origin=Pos&id=$id");
        } else {
          pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionCreate");
        }
      }
    }
    $id = $this->_get->toInt('id');
    $arr = pjOrderModel::factory()->join('pjClient', "t2.id=t1.client_id", 'left outer')
      ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
      ->select("t1.*,t3.name as client_name, t2.c_title, t3.email as c_email, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip,t2.c_notes,t2.mobile_delivery_info AS c_mobileDeliveryInfo,t2.mobile_offer AS c_mobileOffer,t2.email_receipt AS c_emailReceipt,t2.email_offer AS c_emailOffer,
            AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,
            AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
            AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
            AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`,
            AES_DECRYPT(t3.password, '" . PJ_SALT . "') AS `c_password`")
      ->find($id)->getData();
      if (count($arr) <= 0) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08&type=Telephone");
      }
    $this->set('arr', $arr);
    $this->getInculdeData();
    $pjProductPriceModel = pjProductPriceModel::factory();
    $oi_arr = array();
    $oi_extras = array();
    $_oi_arr = pjOrderItemModel::factory()->where('t1.order_id', $arr['id'])->findAll()->getData();
    foreach ($_oi_arr as $item) {
      if ($item['type'] == 'product') {
        $item['price_arr'] = $pjProductPriceModel->reset()
          ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()) , 'left')
          ->select("t1.*, t2.content AS price_name")
          ->where('product_id', $item['foreign_id'])->findAll()
          ->getData();
      } else if ($item['type'] == 'extra') {
        $oi_extras[$item['hash']] = $item;
      }
      $oi_arr[] = $item;
    }
    $this->set('oi_extras', $oi_extras);
    $product_ids = array_column($oi_arr, 'foreign_id');
    $product_arr = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.preparation_time, t1.image, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
          ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
          ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->whereIn("t1.id", $product_ids)->groupBy('t1.id, t1.set_different_sizes, t1.price')
          ->findAll()
          ->getData();
    $product_arr = array_combine(array_column($product_arr, 'id'),$product_arr);
    $category = pjProductCategoryModel::factory()->select('t1.*')
      ->findAll()
      ->getData();
    $this->set('product_arr', $product_arr);
    foreach ($oi_arr as $oi => $o) {
      foreach ($category as $k => $v) {
        if ($o['foreign_id'] == $v['product_id']) {
          $oi_arr[$oi]['category'] = $v['category_id'];
        }
      }
    }
    $this->set('oi_arr', $oi_arr);
    $spcl_ins = pjOrderItemModel::factory()->where('t1.order_id', $id)->findAll()->getData();
    $this->set('spcl_ins', $spcl_ins);
    $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
      ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
      ->orderBy('t2.name ASC')
      ->findAll()
      ->getData();
    $this->set('client_arr', $client_arr);

    if (pjObject::getPlugin('pjPayments') !== NULL) {
      $this->set('payment_option_arr', pjPaymentOptionModel::factory()
        ->getOptions($this->getForeignId()));
      $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId() , $this->getLocaleId()));
    } else {
      $this->set('payment_titles', __('payment_methods', true));
    }

    $extra_arr = pjExtraModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()) , 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
    $this->set('extra_arr', $extra_arr);

    $special_instructions = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
      ->select("t1.*, t2.content AS instruction")
      ->findAll()
      ->getData();
    $this->set('special_instructions', $special_instructions);
    $selTableName = '';
    $table_list = $this->getRestaurantTables();
    if ($arr['origin'] == 'Pos') {
      if (array_key_exists($arr['table_name'], $table_list)) {
        $total_persons = $arr['total_persons'];
        $order_title = 'Eat In';
        $selTableName = $table_list[$arr['table_name']].' Count '.$total_persons;
      } else {
        $order_title = 'Take Away';
      }
    } else if ($arr['origin'] == 'Telephone') {
      $order_title = 'Telephone';
    } else {
      $order_title = 'Web';
    }
    $this->set('order_title', $order_title);
    $this->set('selTableName', $selTableName);
  }

  /* Added by JR */
  public function pjActionGetProductsForCategory() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $product_arr = [];
      $category_id = $this->_post->toInt('category_id');
      $orderType = $this->_post->toString('order_type');
      $category_arr = pjProductCategoryModel::factory()->select('t1.product_id')
        ->whereIn("t1.category_id", $category_id)->findAll()
        ->getData();
      if ($category_arr) {
        $category_arr = array_column($category_arr, 'product_id');
         $pjProductModel = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.image, t1.counter_number, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
          ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
          ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left');
          if (ORDER_TYPE) {
            $pjProductModel->where("t1.order_type = ",$orderType);
          }
          $pjProductModel->whereIn("t1.id", $category_arr)->groupBy('t1.id, t1.set_different_sizes, t1.price')
          ->orderBy("counter_number ASC")
          ->findAll();
          $product_arr = $pjProductModel->getData();
      }
      $this->set('product_arr', $product_arr);
      // $this->pr($product_arr);
      $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->join('pjProductExtra', "t3.extra_id = t1.id")
        ->select("t1.*, t2.content AS name, t3.product_id")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();

      $this->set('extras', $extra_arr);

      $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
        ->select("t1.*, t2.content AS price_name")
        ->orderBy("price_name ASC")
        ->findAll()
        ->getData();
      $category = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t2.content AS name")
          ->find($category_id)
          ->getData();
      $name = '';
      if ($category) {
        $name = $category['name'];
      } 
      $this->set('category', $name);
      $this->set('price_arr', $price_arr);
    }
  }
  // MEGAMIND
  public function pjActionGetSearchResults() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjOrderModel = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.image, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
        ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left');
      if ($q = $this->_get->toString('q')) {
        $product_arr = $pjOrderModel->where("(t2.content LIKE '%$q%')")->findAll()->getData();
        $this->set('product_arr', $product_arr);
      }
      $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->join('pjProductExtra', "t3.extra_id = t1.id")
        ->select("t1.*, t2.content AS name, t3.product_id")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
      $this->set('extras', $extra_arr);

      $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
        ->select("t1.*, t2.content AS price_name")
        ->orderBy("price_name ASC")
        ->findAll()
        ->getData();
      $this->set('price_arr', $price_arr);
    }
  }
  // !MEGAMIND
  public function pjActionGetPrices() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($product_id = $this->_get->toInt('product_id')) {
        $arr = pjProductModel::factory()->find($product_id)->getData();
        if (!empty($arr)) {
          if ($arr['set_different_sizes'] == 'T') {
            $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
              ->select("t1.*, t2.content AS price_name")
              ->where("product_id", $product_id)->orderBy("price_name ASC")
              ->findAll()
              ->getData();
            $this->set('price_arr', $price_arr);
          }
          //MEGAMIND
          $product_category = pjProductCategoryModel::factory()->where("product_id", $product_id)->findAll()
            ->getData();
          if ($product_category) {
            $arr['category_id'] = $product_category[0]['category_id'];
          }
          //MEGAMIND
        }
        //Added by JR to get product description
        $pjMultiLangModel = pjMultiLangModel::factory();
        $prodarr['i18n'] = $pjMultiLangModel->getMultiLang($product_id, 'pjProduct');
        $arr['description'] = $prodarr['i18n'][$this->getLocaleId() ]['description'];
        //End of it;
        $this->set('arr', $arr);
      }
    }
  }

  public function pjActionGetProductPrices() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($product_id = $this->_get->toInt('product_id')) {
        $arr = pjProductModel::factory()->find($product_id)->getData();
        if (!empty($arr)) {
          if ($arr['set_different_sizes'] == 'T') {
            $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
              ->select("t1.*, t2.content AS price_name")
              ->where("product_id", $product_id)->orderBy("price_name ASC")
              ->findAll()
              ->getData();
            $this->set('price_arr', $price_arr);
          }
          //MEGAMIND
          $product_category = pjProductCategoryModel::factory()->where("product_id", $product_id)->findAll()
            ->getData();
          if ($product_category) {
            $arr['category_id'] = $product_category[0]['category_id'];
          }
          //MEGAMIND
        }
        //Added by JR to get product description
        $pjMultiLangModel = pjMultiLangModel::factory();
        $prodarr['i18n'] = $pjMultiLangModel->getMultiLang($product_id, 'pjProduct');
        $arr['description'] = $prodarr['i18n'][$this->getLocaleId() ]['description'];
        //End of it;

        $this->set('arr', $arr);

        $product_arr = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.preparation_time, t1.image, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
          ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
          ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->where("t1.id", $product_id)->groupBy('t1.id, t1.set_different_sizes, t1.price')
          ->findAll()
          ->getData();
        $this->set('product_arr', $product_arr);

        $category_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t1.*, t2.content AS name")
          ->where("t1.status", 1)
          ->orderBy("t1.order ASC")
          ->findAll()
          ->getData();
        $this->set('category_arr', $category_arr);
        $category_list = [];
        foreach ($category_arr as $category) {
          $category_list[$category['id']] = $category['name'];
        }
        $this->set('category_list', $category_list);
      }
      if ($size_id = $this->_get->toInt('size_id')) {
        $this->set('size_id', $size_id);
      }
    }
  }

  public function pjActionGetTotal() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      pjAppController::jsonResponse($this->getTotal());
    }
    exit;
  }
   protected function getTotal() {
    $is_null = true;
    $product_id_arr = $this->_post->toArray('product_id');
    $post = $this->_post->raw();
    foreach ($product_id_arr as $v) {
      if ((int)$v > 0) {
        $is_null = false;
      } else if ($v == 0) {
        $is_null = false;
      }
    }
    if ($is_null == false) {
      $price = 0;
      $discount = 0;
      $subtotal = 0;
      $price_packing = 0;
      $price_delivery = 0;
      $tax = 0;
      $extras_total = 0;
      $total = 0;
      $price_format = "";
      $discount_format = "";
      $packing_format = "";
      $subtotal_format = "";
      $delivery_format = "";
      $tax_format = "";
      $total_format = "";
      $extras_format = "";
      
      $pjProductPriceModel = pjProductPriceModel::factory();
      $pjExtraModel = pjExtraModel::factory();
      $product_arr = pjProductModel::factory()
        ->select('t1.id, t1.set_different_sizes, t1.price, MIN(t3.packing_fee) AS `packing_fee`')
        ->join('pjProductCategory', 't2.product_id=t1.id', 'left outer')
        ->join('pjCategory', 't3.id=t2.category_id', 'left outer')
        ->whereIn("t1.id", $product_id_arr)->groupBy('t1.id, t1.set_different_sizes, t1.price')
        ->findAll()
        ->getData();
      $extra_arr = $pjExtraModel->findAll()->getData();
      $cnt_arr = $this->_post->toArray('cnt');
      $returnOrCancelProducts = array();
      if (array_key_exists('return_or_cancel', $post)) {
        $returnOrCancelProducts = array_filter($post['return_or_cancel']);
      }
      foreach ($product_id_arr as $hash => $product_id) {
        $_price = 0;
        $extra_price = 0;
        if (is_array($returnOrCancelProducts) && array_key_exists($hash, $returnOrCancelProducts)) {
          continue;
        } 
        if ($product_id == 0) {
          $_price = $post['price_id'][$hash];
          $price += $_price * $cnt_arr[$hash];
        }
        foreach ($product_arr as $product) {
          $_price = 0;
          $extra_price = 0;
          if ($product['id'] == $product_id) {
            if ($product['set_different_sizes'] == 'T') {
              $price_id_arr = $this->_post->toArray('price_id');
              $price_arr = $pjProductPriceModel->reset()->find($price_id_arr[$hash])->getData();
              if ($price_arr) {
                $_price = $price_arr['price'];
              }
            } else {
              $_price = $product['price'];
            }
            $extra_id_arr = $this->_post->toArray('extras');
            $product_price = $_price * $cnt_arr[$hash];
            $price_packing += $product['packing_fee'] * $cnt_arr[$hash];
            if (array_key_exists($hash, $extra_id_arr) && isset($extra_id_arr[$hash])) {
              $decode_extras = json_decode(stripslashes($extra_id_arr[$hash]));
              if ($decode_extras) {
                foreach ($decode_extras as $i=>$selectedExtra) {
                  foreach ($extra_arr as $extra) {
                    if ($extra['id'] == $selectedExtra->extra_sel_id) {
                      $extra_price += $extra['price'] * $selectedExtra->extra_count;
                      break;
                    }
                  }
                }
              }
            }
            $extras_total += $extra_price;
            $_price = $product_price;
            $price += $_price;
            break;
          }
        }
      }
      if ($this->_post->has('delivery_fee')) {
        $d_fee = $this->_post->toFloat('delivery_fee');
        if ($d_fee) {
          $price_delivery = $d_fee;
        }
      }
      if ($this->_post->has('vouchercode') && $this->_post->has('vouchercode') != '') {
        $post = $this->_post->raw();
        $resp = pjAppController::getDiscount($post, $this->option_arr);
        if ($resp['code'] == 200) {
          $voucher_discount = $resp['voucher_discount'];
          switch ($resp['voucher_type']) {
            case 'percent':
              $discount = (($price + $price_packing) * $voucher_discount) / 100;
            break;
            case 'amount':
              $discount = $voucher_discount;
            break;
          }
        }
      }
      if ($discount > $price + $extras_total + $price_packing) {
        $discount = $price + $extras_total + $price_packing;
      }
      $subtotal = $price +  $extras_total + $price_packing + $price_delivery - $discount;
      if (!empty($this->option_arr['o_tax_payment'])) {
        if ($this->option_arr['o_add_tax'] == '1' && $this->_post->has('type')) {
          $tax = (($subtotal - $price_delivery) * $this->option_arr['o_tax_payment']) / 100;
        } else {
          $tax = ($subtotal * $this->option_arr['o_tax_payment']) / 100;
        }
      }
      $total = $subtotal + $tax;
      $price_format = pjCurrency::formatPrice($price);
      $discount_format = pjCurrency::formatPrice($discount);
      $packing_format = pjCurrency::formatPrice($price_packing);
      $delivery_format = pjCurrency::formatPrice($price_delivery);
      $subtotal_format = pjCurrency::formatPrice($subtotal);
      $tax_format = pjCurrency::formatPrice($tax);
      $total_format = pjCurrency::formatPrice($total);
      $extras_format = pjCurrency::formatPrice($extras_total);
      return compact('price', 'discount', 'price_packing', 'price_delivery', 'subtotal', 'tax', 'total', 'price_format', 'discount_format', 'packing_format', 'delivery_format', 'subtotal_format', 'tax_format', 'total_format', 'extras_total', 'extras_format');
    }
    return array(
      'price' => 'NULL'
    );
  }

  public function pjActionFormatPrice() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    $prices = $this->_post->toArray('prices');
    if ($prices) {
      foreach ($prices as $index => $price) {
        $prices[$index] = pjCurrency::formatPrice($price);
      }
    } else {
      $prices = array();
    }
    
    self::jsonResponse(array(
      'status' => 'OK',
      'code' => 200,
      'prices' => $prices
    ));
  }
  // public function pjActionGetExtras() {
  //   $this->setAjax(true);

  //   if ($this->isXHR()) {
  //     if ($product_id = $this->_get->toInt('product_id')) {
  //         $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
  //         ->select("t1.*, t2.content AS name")
  //         ->where("t1.id IN (SELECT TPE.extra_id FROM `" . pjProductExtraModel::factory()
  //         ->getTable() . "` AS TPE WHERE TPE.product_id=" . $product_id . ")")
  //         ->orderBy("category_id ASC, name ASC")
  //         ->findAll()
  //         ->getData();
  //         if ($this->_get->toString('hidden_extra_val')) {
  //           $extra_val = $this->_get->toString('hidden_extra_val');
  //           $this->set('extra_val', $extra_val);
  //         }
  //         if($this->_get->toInt('edit')) {
  //           $edit = 1;
  //         } else {
  //           $edit = 0;
  //         }
  //       $index = $this->_get->toString('index');
  //       $qty = $this->_get->toInt('product_qty');
  //       $extra_count = $this->_get->toInt('hidden_extra_count');
  //       $this->set('qty', $qty);
  //       $this->set('extra_arr', $extra_arr);
  //       $this->set('index', $index);
  //       $this->set('extra_count', $extra_count);
  //       $this->set("edit",$edit);
  //     }
  //   }
  // }

  public function pjActionGetExtras() {
    $this->setAjax(true);

    if ($this->isXHR()) {
      if ($product_id = $this->_get->toInt('product_id')) {
          $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t1.*, t2.content AS name")
          ->where("t1.id IN (SELECT TPE.extra_id FROM `" . pjProductExtraModel::factory()
          ->getTable() . "` AS TPE WHERE TPE.product_id=" . $product_id . ")")
          ->orderBy("category_id desc, name ASC")
          ->findAll()
          ->getData();
          if ($this->_get->toString('hidden_extra_val')) {
            $extra_val = $this->_get->toString('hidden_extra_val');
            $this->set('extra_val', $extra_val);
          }
          if ($this->_get->toInt('edit')) {
            $edit = 1;
          } else {
            $edit = 0;
          }
        $index = $this->_get->toString('index');
        $qty = $this->_get->toInt('product_qty');
        $extra_count = $this->_get->toInt('hidden_extra_count');
        $this->set('qty', $qty);
        $this->set('extra_arr', $extra_arr);
        $this->set('index', $index);
        $this->set('extra_count', $extra_count);
        $this->set("edit", $edit);
      }
    }
  }

  public function pjActionGetDescription() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($product_id = $this->_post->toInt('product_id')) {
        $product_arr = pjProductModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t1.*, t2.content AS name, (SELECT COUNT(*) FROM `" . pjProductExtraModel::factory()
          ->getTable() . "` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras")
          ->where("t1.id", $product_id)->findAll()
          ->getData();
        $pjMultiLangModel = pjMultiLangModel::factory();
        $prodarr['i18n'] = $pjMultiLangModel->getMultiLang($product_id, 'pjProduct');
        $description = $prodarr['i18n'][$this->getLocaleId() ]['description'];
        if ($description != '') {
          self::jsonResponse(array(
            'status' => 'OK',
            'code' => 200,
            'description' => $description,
            'product' => $product_arr[0]['name']
          ));
        } else {
          self::jsonResponse(array(
            'status' => 'OK',
            'code' => 201,
            'description' => "empty",
            'product' => $product_arr[0]['name']
          ));
        }
      }
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100
      ));
    }
  }

  protected function getClientType($data) {
    $regular = 0;
    $c_exist_orders = pjOrderModel::factory()->select("t1.*")
      ->where('t1.client_id', $data['client_id'])->findAll()
      ->getData();
    $c_exist_orders_dates = array();
    foreach ($c_exist_orders as $k => $v) {
      $c_exist_orders_dates[] = explode(" ", $v['created']) [0];
    }

    if (count($c_exist_orders)) {
      $weekDates[0] = date('Y-m-d');
      for ($i = 1;$i < 7;$i++) {
        $weekDates[$i] = date('Y-m-d', strtotime("-$i days"));
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
        for ($j = 7;$j < 28;$j++) {
          $frequentDates[$j] = date('Y-m-d', strtotime("-$j days"));
        }
        foreach ($c_exist_orders_dates as $k) {
          foreach ($frequentDates as $f) {
            if ($k == $f) {
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

  /********************************************************************************************************************/

  public function pjActionCheckClientEmail() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!$this->_get->toString('c_email')) {
        echo 'false';
        exit;
      }
      $pjClientModel = pjAuthUserModel::factory()->join("pjClient", 't2.foreign_id = t1.id', 'left outer')
        ->where('t1.email', $this
        ->_get
        ->toString('c_email'));
      echo $pjClientModel->findCount()->getData() == 0 ? 'true' : 'false';
    }
    exit;
  }

  // MEGAMIND
  public function pjActionCheckClientPhoneNumber() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        echo "HTTP method not allowed";
        exit;
      }
      if ($this->_post->toInt('value') <= 0) {
        echo "new user";
        exit;
      }
      $client_info = pjClientModel::factory()
      ->join('pjAuthUser', "t2.id = t1.foreign_id")
        ->select('t1.*, t2.name,t2.u_surname,t2.email')
        ->where('t2.phone', $this
        ->_post
        ->toString('value'))
        ->limit(1)
        ->findAll()
        ->getData();

      if (count($client_info) == 0) {
        echo "new user";
        exit;
      } else {
        self::jsonResponse($client_info);
        exit;
      }
    }
    exit;
  }

  // !MEGAMIND
  public function pjActionGetOrder() {
    $this->setAjax(true);
    $statusExists = false;
    if ($this->isXHR()) {
      $origin = $this->_get->toString('origin')?$this->_get->toString('origin'):'Telephone';
      $pjOrderModel = pjOrderModel::factory()
        ->where("(t1.origin = '$origin')")->join('pjClient', "t2.id=t1.client_id", 'left outer')
        ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
      if (strtolower($origin) == 'web') {
        $pjOrderModel->where("(t1.is_viewed > 0)");
      }
      if ($q = $this->_get->toString('q')) {
        // MEGAMIND
        $q = preg_replace("/[^0-9]/", "", $q );
        $pjOrderModel->where("(t1.id='$q' OR t1.uuid = '$q' OR t1.surname LIKE '%$q%' 
					OR t3.email LIKE '%$q%' OR t3.phone = '$q' OR t1.post_code = '$q')");
        // !MEGAMIND
      }
      if ($this->_get->toString('status')) {
        $statusExists = true;
        $status = $this->_get->toString('status');
        // MEGAMIND (Added delivered status)
        if (in_array($status, array(
          'confirmed',
          'cancelled',
          'pending',
          'delivered'
        ))) {
        // !MEGAMIND
          $pjOrderModel->where('t1.status', $status);
        }
      } 
      if ($this->_get->toString('origin')) {
        $origin = $this->_get->toString('origin');
        // MEGAMIND (Added delivered status)
        if (in_array($origin, array(
          'Telephone',
          'Web',
          'Pos'
        ))) {
        // !MEGAMIND
          $pjOrderModel->where('t1.origin', $origin);
        }
      }

      if ($this->_get->toString('type')) {
        $statusExists = true;
        $type = $this->_get->toString('type');
        if (in_array($type, array(
          'pickup',
          'delivery'
        ))) {
          if ($type == 'pickup') {
            //for web orders pickup and call
            $pjOrderModel->whereIn('t1.type', array('pickup', 'pickup & call'));
          } else {
            $pjOrderModel->where('t1.type', $type);
          }
        }
        // MEGAMIND (Added delivered status)
        if (in_array($type, array(
          'confirmed',
          'cancelled',
          'pending',
          'delivered'
        ))) {
        // !MEGAMIND
          $pjOrderModel->where('t1.status', $type);
        }
        if (in_array($type, array(
          'deleted'
        ))) {
          $pjOrderModel->where('t1.deleted_order', 1);
        } else {
          $pjOrderModel->where('t1.deleted_order', 0);
        }
      } else {
        $pjOrderModel->where('t1.deleted_order', 0);
      }
      if (!$statusExists) {
        $pjOrderModel->whereIn('t1.status', array('pending', 'confirmed'));
      }
      if ($client_id = $this->_get->toInt('client_id')) {
        $pjOrderModel->where('t1.client_id', $client_id);
      }
      $column = 'delivery_dt';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')) , array(
        'ASC',
        'DESC'
      ))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }
      $data = array();
      $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
      date_default_timezone_set($timezone);
      $today = date('Y-m-d', time());
      $toDay = $today . " " . "00:00:00";
      if ($this->_get->toString('type') != 'all' && $this->_get->toString('type') != 'delivered') {
        $col = 'delivery_dt';
        $dir = 'ASC';
        $total = $pjOrderModel->where("t1.delivered_customer", 0)
          ->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")->findCount()
          ->getData();
        $rowCount = $this->_get->toInt('rowCount') ? : 10;
        $pages = ceil($total / $rowCount);
        $page = $this->_get->toInt('page') ? : 1;
        $offset = ((int)$page - 1) * $rowCount;
        if ($page > $pages) {
          $page = $pages;
        }
        $data = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
							AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,	
							AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
          ->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")
          ->orderBy("$col $dir")->limit($rowCount, $offset)->findAll()
          ->getData();
      } else if ($this->_get->toString('type') == 'delivered') {
        $col = 'delivery_dt';
        $dir = 'ASC';
        $total = $pjOrderModel->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")->findCount()->getData();
        $rowCount = $this->_get->toInt('rowCount') ? : 10;
        $pages = ceil($total / $rowCount);
        $page = $this->_get->toInt('page') ? : 1;
        $offset = ((int)$page - 1) * $rowCount;
        if ($page > $pages) {
          $page = $pages;
        }
        $data = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
								AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,	
								AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
								AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
								AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
          ->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")
          ->orderBy("$col $dir")->limit($rowCount, $offset)->findAll()
          ->getData();
      } else if ($this->_get->toString('type') == 'all') {
        $total = $pjOrderModel->findCount()->getData();
        $rowCount = $this->_get->toInt('rowCount') ? : 10;
        $pages = ceil($total / $rowCount);
        $page = $this->_get->toInt('page') ? : 1;
        $offset = ((int)$page - 1) * $rowCount;
        if ($page > $pages) {
          $page = $pages;
        }
        $data = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type,
								AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,	
								AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
								AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
								AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
          ->orderBy("$column DESC")->limit($rowCount, $offset)->findAll()
          ->getData();
      }
      foreach ($data as $k => $v) {
        // MEGAMIND
        if ($v['surname'] == '') {
          $data[$k]['surname'] = $v['first_name'];
        }
        $data[$k]['address'] = $v['d_address_1'] . ' ' . $v['d_address_2'] . ' ' . $v['d_city'];
        $v['post_code'] == '0' || strtolower($v['type']) == 'pickup'? $data[$k]['post_code'] = '' : $data[$k]['post_code'] = $v['post_code'];
        $data[$k]['c_type'] = $v['c_type'];
        $v['sms_sent_time'] == "" ? $data[$k]['sms_sent_time'] = '-' : $data[$k]['sms_sent_time'] = explode(" ", $v['sms_sent_time']) [1];
        if ((isset($v['p_dt']) && (explode(" ", $v['p_dt']) [0]) > $today) || (isset($v['d_dt']) && explode(" ", $v['d_dt']) [0] > $today)) {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = $this->getDateFormatted($v['p_dt']) : $data[$k]['expected_delivery'] = $this->getDateFormatted($v['d_dt']);
        } else {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = explode(" ", $v['p_dt']) [1] : $data[$k]['expected_delivery'] = explode(" ", $v['d_dt']) [1];
        }
        if (((isset($v['p_dt']) && explode(" ", $v['p_dt']) [0] == explode(" ", $today) [0])) || ((isset($v['d_dt']) && explode(" ", $v['d_dt']) [0] == explode(" ", $today) [0]))) {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = explode(" ", $v['p_dt']) [1] : $data[$k]['expected_delivery'] = explode(" ", $v['d_dt']) [1];
        } else {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = $this->getDateFormatted($v['p_dt']) : $data[$k]['expected_delivery'] = $this->getDateFormatted($v['d_dt']);
        }
        if ($v['delivered_time'] == null) {
          $data[$k]['deliver_t'] = $data[$k]['expected_delivery'];
          $data[$k]['deliver_sts'] = "none";
        } else {
          if (explode(" ", $v['delivered_time']) [1] > explode(" ", $v['delivery_dt']) [1]) {
            $data[$k]['deliver_sts'] = "failure";
          } else {
            $data[$k]['deliver_sts'] = "success";
          }
          $data[$k]['deliver_t'] = $this->getDateFormatted($v['delivered_time']);
        }
        $v['delivered_time'] == "" ? $data[$k]['delivered_time'] = '-' : $data[$k]['delivered_time'] = explode(" ", $v['delivered_time']) [1];
        $data[$k]['total'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($v['total'])."</strong>";
        $data[$k]['client_name'] = pjSanitize::clean($v['client_name']);
        if ($v['is_paid'] == "1") {
          // if ($v['payment_method'] != '' && strtolower($v['payment_method']) == 'bank') {
          $payment_method = strtolower($v['payment_method']);
          if (in_array($payment_method, array('card', 'bank'))) {
            $data[$k]['payment_method'] = 'Card';
          } else {
            $data[$k]['payment_method'] = 'Cash';
          }
        } else {
          $data[$k]['payment_method'] = '';
        }
        // !MEGAMIND
      }

      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetPosOrder() {
    $this->setAjax(true);
    $statusExists = false;
    if ($this->isXHR()) {
      $pjOrderModel = pjOrderModel::factory();
      if ($q = $this->_get->toString('q')) {
        // MEGAMIND
      	$q = preg_replace("/[^0-9]/", "", $q );
				$pjOrderModel->where("(t1.id LIKE '%$q%' OR t1.uuid LIKE '%$q%' OR t1.surname LIKE '%$q%')");
        // !MEGAMIND
      }
      if ($this->_get->toString('status')) {
        $status = $this->_get->toString('status');
        // MEGAMIND (Added delivered status)
        if (in_array($status, array(
          'confirmed',
          'cancelled',
          'pending',
          'delivered'
        ))) {
        // !MEGAMIND
          $pjOrderModel->where('t1.status', $status);
        }
      }

      if ($this->_get->toString('type')) {
        $statusExists = true;
        $type = $this->_get->toString('type');
        if (in_array($type, array('pickup','delivery'))) {
          $pjOrderModel->where('t1.type', $type);
        }
        // MEGAMIND (Added delivered status)
        if (in_array($type, array('confirmed', 'cancelled', 'pending', 'delivered')))
        // !MEGAMIND
        {
          $pjOrderModel->where('t1.status', $type);
        }

        if (in_array($type, array('deleted')))
        // !MEGAMIND
        {
          $pjOrderModel->where('t1.deleted_order', 1);
        } else {
          $pjOrderModel->where('t1.deleted_order', 0);
        }
      } else {
        $pjOrderModel->where('t1.deleted_order', 0);
      }
      if (!$statusExists) {
        $pjOrderModel->whereIn('t1.status', array('pending', 'confirmed'));
      }

      $column = 'created';
      $direction = 'DESC';
      $today = date('Y-m-d', time());
      $toDay = $today . " " . "00:00:00";
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC','DESC')))
      {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      if ($this->_get->toString('type') == 'all') {
      	$total = $pjOrderModel->where("t1.origin", "Pos")
        ->findCount()
        ->getData();
      } else {
      	$total = $pjOrderModel->where("t1.origin", "Pos")
     		->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")
        ->findCount()
        ->getData();
      }
      $rowCount = $this->_get->toInt('rowCount') ? : 10;
      $pages = ceil($total / $rowCount);
      $page = $this->_get->toInt('page') ? : 1;
      $offset = ((int)$page - 1) * $rowCount;
      if ($page > $pages)
      {
        $page = $pages;
      }
      $data = array();
      $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
      date_default_timezone_set($timezone);
      if ($this->_get->toString('type') == 'all') {
        $data = $pjOrderModel->select("t1.*")
          ->orderBy("$column $direction, t1.status ASC")
        	->limit($rowCount, $offset)->findAll()
        	->getData();
      } else {
        $data_unordered = $pjOrderModel->select("t1.*")
          ->orderBy("$column $direction")->limit($rowCount, $offset)
          ->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")
        	 ->orderBy("t1.status ASC, $column $direction")
        	->findAll()
        	->getData();
        $pending = array();
        $delivered = array();
        $data_ordered = array();
        foreach ($data_unordered as $item => $val) {
          if ($val['status'] == "pending") {
            array_push($pending, $val);
          } else {
            array_push($delivered, $val);
          }
        }
        foreach ($pending as $pokey => $po) {
          array_push($data_ordered, $po);
        }
        foreach ($delivered as $dokey => $do) {
          array_push($data_ordered, $do);
        }
        $data = $data_ordered;
      }
      $table_list = $this->getRestaurantTables();
      foreach ($data as $k => $v) {
        // MEGAMIND
        $v['sms_sent_time'] == "" ? $data[$k]['sms_sent_time'] = '-' : $data[$k]['sms_sent_time'] = explode(" ", $v['sms_sent_time']) [1];
        if (($v['p_dt'] != '' && explode(" ", $v['p_dt']) [0] == explode(" ", $today) [0]) || ($v['p_dt'] != '' && explode(" ", $v['d_dt']) [0] == explode(" ", $today) [0])) {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = explode(" ", $v['p_dt']) [1] : $data[$k]['expected_delivery'] = explode(" ", $v['d_dt']) [1];
        } else {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = $this->getDateFormatted($v['p_dt']) : $data[$k]['expected_delivery'] = $this->getDateFormatted($v['d_dt']);
        }
        if ($v['delivered_time'] == null) {
          $data[$k]['deliver_t'] = $data[$k]['expected_delivery'];
          $data[$k]['deliver_sts'] = "none";
        }
        else {
          if (explode(" ", $v['delivered_time']) [1] > explode(" ", $v['delivery_dt']) [1]) {
            $data[$k]['deliver_sts'] = "failure";
          } else {
            $data[$k]['deliver_sts'] = "success";
          }
          $data[$k]['deliver_t'] = $v['delivered_time'];
        }
        $v['delivered_time'] == "" ? $data[$k]['delivered_time'] = '-' : $data[$k]['delivered_time'] = explode(" ", $v['delivered_time']) [1];
        $data[$k]['total'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($v['total'])."</strong>";
        if (array_key_exists($v['table_name'], $table_list)) {
          $data[$k]['table_name'] = $table_list[$v['table_name']];
        }
        $data[$k]['table_name'] = "<strong class='list-pos-type'>".$data[$k]['table_name']."</strong>";
        // !MEGAMIND 
        if ($data[$k]['is_paid'] == 1) {
        //   if (strtolower($data[$k]['payment_method']) == 'bank') {
            $payment_method = strtolower($data[$k]['payment_method']);
          if (in_array($payment_method, array('card', 'bank'))) {
            $data[$k]['payment_method'] = 'Card';
          } else {
            $data[$k]['payment_method'] = 'Cash';
          }
        } else {
          $data[$k]['payment_method'] = '';
        }
        $data[$k]['order_date'] = date("d-m-Y", strtotime($v['created']));
      }
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionSaveOrder() {
    $this->setAjax(true);

    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }

    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }

    if (!pjAuth::factory($this->_get->toString('controller') , 'pjActionUpdate')->hasAccess()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
    $pjOrderModel = pjOrderModel::factory();
    $arr = $pjOrderModel->find($this->_get->toInt('id'))->getData();

    if (!$arr) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 103,
        'text' => 'Order not found.'
      ));
    }
    if ($this->_post->toString('column') == 'delivered_customer' || $this->_post->toString('column') == 'order_despatched')
    {

    } else {
      $pjOrderModel->reset()->where('id', $this->_get
        ->toInt('id'))
        ->limit(1)
        ->modifyAll(array(
        $this->_post->toString('column') => $this->_post->toString('value')
      ));
    }
    self::jsonResponse(array(
      'status' => 'OK',
      'code' => 201,
      'text' => 'Order has been updated.'
    ));
    exit;
  }

  public function pjActionExportOrder() {
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
    if (!$this->_post->has('record')) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 103,
        'text' => 'Missing, empty or invalid parameters.'
      ));
    }
    $record = $this->_post->toArray('record');
    if (empty($record)) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 104,
        'text' => 'Missing, empty or invalid parameters.'
      ));
    }
    $arr = pjOrderModel::factory()->whereIn('id', $record)->findAll()
      ->getData();
    $csv = new pjCSV();
    $csv->setHeader(true)
      ->setName("Orders-" . time() . ".csv")
      ->process($arr)->download();
    $this->checkLogin();
    exit;
  }

  public function pjActionDeleteOrder() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
    if (!($this->_get->toInt('id'))) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 103,
        'text' => 'Missing, empty or invalid parameters.'
      ));
    }
    // $pjOrderModel = pjOrderModel::factory();
    // $arr = $pjOrderModel->find($this->_get->toInt('id'))->getData();
    $id = $this->_get->toInt('id');
    $arr = pjOrderModel::factory()
          ->join('pjTable', "t2.id=t1.table_name", 'left')
          ->select("t1.*, t2.name as table_ordered_name")
          ->find($id)
          ->getData();
    if (!$arr) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 103,
        'text' => 'Order not found.'
      ));
    }
    // MEGAMIND
    /*if (pjOrderModel::factory()->where('id', $id)->modifyAll(array(
      'deleted_order' => ":IF(`deleted_order`='0','1','0')"
    ))->getAffectedRows() == 1) */
    if ($arr && pjOrderModel::factory()->whereIn("id", $id)->eraseAll()->getAffectedRows() === 1)
    {
      // !MEGAMIND
      // $this->pr($arr);
      pjOrderItemModel::factory()->whereIn("order_id", $id)->eraseAll();
      $status = array('pending', 'cancelled');
      if (in_array($arr['status'], $status) && KITCHEN_PRINT) {
        $this->pjActionKPrintCanDelOrder($arr, "Removed");
      }
      $count = $this->pendingOrderCount($arr['origin']);
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => 'Order has been deleted',
        'count' => $count,
        'origin' => $arr['origin']
      ));
    } else {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 105,
        'text' => 'Order has not been deleted.'
      ));
    }
    exit;
  }

  public function pjActionDeleteOrderBulk() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    $role_id = $this->getRoleId();
    if (!pjAuth::factory()->hasAccess() || $role_id != 1) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
    if (!$this->_post->has('record')) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 103,
        'text' => 'Missing, empty or invalid parameters.'
      ));
    }
  
    $orderIDS = $this->_post->toArray('record');
    $selectedRecords = pjOrderModel::factory()
      ->join('pjTable', "t2.id=t1.table_name", 'left')
      ->select("t1.status, t1.id, t1.origin, t2.name as table_ordered_name")
      ->whereIn('t1.id', $orderIDS)
      ->findAll()
      ->getData();
    if (empty($orderIDS)) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 104,
        'text' => 'Missing, empty or invalid parameters.'
      ));
    }
    // $this->pr($selectedRecords);
    // exit;
    // MEGAMIND
    // pjOrderModel::factory()->whereIn('id', $record)->modifyAll(array(
    //   'deleted_order' => ":IF(`deleted_order`='0','1','0')"
    // ));
    // pjOrderModel::factory()->whereIn("id", $record)->eraseAll()->getAffectedRows() === 1)
    // !MEGAMIND
    if ($selectedRecords && pjOrderModel::factory()->whereIn("id", $orderIDS)->eraseAll()->getAffectedRows() > 0)
    {
      // !MEGAMIND
      // $this->pr($arr);
      pjOrderItemModel::factory()->whereIn("order_id", $orderIDS)->eraseAll();
      // $status = array('pending', 'cancelled');
      // if (in_array($arr['status'], $status) && KITCHEN_PRINT) {
      //   $this->pjActionKPrintCanDelOrder($arr, "Removed");
      // }
      // $count = $this->pendingOrderCount($arr['origin']);
      // echo 'came here';
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => 'Order(s) has been deleted.',
        // 'count' => $count,
        // 'origin' => $arr['origin']
      ));
      // exit;
    } else {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 105,
        'text' => 'Order(s) has not been deleted.'
      ));
    }
    exit;
  }

  public function pjActionInitialKPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
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
      $printBrowser = false;
      $printMessage = 'failed';
      $data = $this->getOrderItems($id, $printBrowser, false);

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
    }
  }

  public function pjActionPrintOrder() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
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
        // if ($result && strtolower($result['msg']) == 'success') {
        if (is_array($result) && array_key_exists('msg', $result) && strtolower($result['msg']) == 'success') {
          $printMessage = "Printed";
          $this->updateKitchenPrint($id);
        } else {
          $printMessage = "Failed";
        }
        // $this->updateKitchenPrint($id); 
        $this->set('printMessage', $printMessage);
        $this->set('origin', $data['arr']['origin']);
      }
    }

  }

  // public function kitchenPrintFormat($printer, $data, $special_instructions, $newItem = false) {
  //   foreach ($data['oi_arr'] as $k => $oi) {
  //     if ($oi['is_kitchen']) {
  //       $lineItem = '';
  //       if ($oi['special_instruction'] || array_key_exists($oi['hash'], $data['oi_extras'])) {
  //         for ($i = 0, $counter = 0; $i < $oi['cnt'] ; $i++, $counter++) {
  //           $lineItem = '';
  //           if ($oi['type'] == 'custom') {
  //             $lineItem = "1 x ".strtoupper($oi['custom_name']);
  //           } else {
  //             $lineItem = "1 x ".strtoupper($oi['product_name'])." ".$oi['size'];
  //           }
            
  //           $printer->appendText("$lineItem");
  //           if (array_key_exists($oi['hash'], $data['oi_extras']) && isset($data['oi_extras'][$oi['hash']][$counter])) { 
  //             $extra = $data['oi_extras'][$oi['hash']][$counter];
  //             $printer->lineFeed();
  //             $printer->appendText(" - ".$extra->extra_name ." x ".$extra->extra_count);
  //           }
  //           if ($oi['special_instruction']) {
  //             $obj = json_decode($oi['special_instruction'], true);
  //             if (isset($obj[$counter])) {
  //               if ($obj[$counter]['ids']) {
  //                 $printer->lineFeed();
  //                 $selected_ins_arr = explode(',', $obj[$counter]['ids']);
  //                 foreach ($selected_ins_arr as $ins) {
  //                   foreach ($special_instructions as $instruction) {
  //                     if ($ins == $instruction['id']) {
  //                       $printer->appendImage($instruction['image'], 0, 30);
  //                     }
  //                   }
  //                 }
  //               }
  //               if ($obj[$counter]['cus_ins']) {
  //                 $printer->lineFeed();
  //                 $printer->appendText(str_repeat(" ", 4).$obj[$counter]['cus_ins']."\n");
  //               } 
  //             }
  //           }
  //           $printer->lineFeed();
  //         }
  //       } else {
  //         if ($oi['type'] == 'custom') {
  //           $lineItem = $oi['cnt']." x ".strtoupper($oi['custom_name']);
  //         } else {
  //           $lineItem = $oi['cnt']." x ".strtoupper($oi['product_name'])." ".$oi['size'];
  //         }
  //         $printer->appendText("$lineItem");
  //         $printer->lineFeed();
  //       }
  //     } 
  //   }
  // }
  public function kitchenPrintFormat($printer, $data, $special_instructions, $newItem = false) {
    // $this->pr($data);
    // $this->pr($data['product_arr']);
    // $this->pr($data['oi_arr']);
    // exit;
    // foreach ($data['product_arr'] as $product) {
      foreach ($data['oi_arr'] as $k => $oi) {
        // $this->pr($oi);
        $lineItem = '';
        // if ((($newItem && ($oi['cnt'] != $oi['print'])) || (!$newItem && ($oi['print']))) && $oi['foreign_id'] == $product['id']) {
        if ((($newItem && ($oi['cnt'] != $oi['print'])) || (!$newItem && ($oi['print']))) && array_key_exists($oi['foreign_id'], $data['product_arr'])) {
          $product = $data['product_arr'][$oi['foreign_id']];
          if ($newItem) {
            $lineItem = ($oi['cnt'] - $oi['print']);
          } else {
            $lineItem = $oi['print'];
          }
          $lineItem .= " x ".strtoupper($product['name'])." ".$oi['size'];
          if ($oi['cancel_or_return_reason']) {
            $lineItem .= ' - CANCELLED';
          }
          // echo $lineItem;
          // echo '<br/>';
          $printer->appendText("$lineItem");
          if ($oi['special_instruction'] != '') {
            $printer->lineFeed();
            $spcl_ins = $oi['special_instruction'];
            $spcl_ins_arr = explode(",", $spcl_ins);
              foreach ($spcl_ins_arr as $ins) {
                foreach ($special_instructions as $instruction) {
                  if ($ins == $instruction['id']) {
                    $printer->appendImage($instruction['image'], 0);
                    // echo $instruction['image'];
                  }
                }
              }
            } 
            if (array_key_exists($oi['hash'], $data['oi_extras'])) { 
              $proExtra = $data['oi_extras'][$oi['hash']]; 
              foreach($proExtra as $extra) {
                // $printer->appendText(" - ".$extra['extra_name'] ." x ".$extra['cnt'] ."\n");
                $printer->appendText(" - ".$extra->extra_name ." x ".$extra->extra_count ."\n");
                // echo " - ".$extra->extra_name ." x ".$extra->extra_count  ."<br>";
              } 
            }
            if ($oi['custom_special_instruction'] != '')
            {
              $customs = json_decode($oi['custom_special_instruction']);
              foreach ($customs as $custom) {
                $printer->appendText(" - \n");
                // $printer->appendText(str_repeat(" ", 4).$oi['custom_special_instruction']."\n");
                $printer->appendText(str_repeat(" ", 4).$custom->cus_ins."\n");
                // echo str_repeat(" ", 4).$custom->cus_ins."<br/>";
              }
            } else {
              $printer->appendText("\n");
            }
        }
      }
    // }
  }
  public function pjActionReminderEmail() {
    $this->setAjax(true);
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      if ($this->_post->toInt('send_email') && $this->_post->toString('to') && $this
        ->_post
        ->toString('subject') && $this
        ->_post
        ->toString('message') && $this
        ->_post
        ->toInt('id')) {
        $Email = self::getMailer($this->option_arr);
        $r = $Email->setTo($this
          ->_post
          ->toString('to'))
          ->setSubject($this
          ->_post
          ->toString('subject'))
          ->send($this
          ->_post
          ->toString('message'));

        if (isset($r) && $r) {
          pjAppController::jsonResponse(array(
            'status' => 'OK',
            'code' => 200,
            'text' => __('lblEmailSent', true, false)
          ));
        }
        pjAppController::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => __('lblFailedToSend', true, false)
        ));
      }
    }
    if (self::isGet()) {
      if ($id = $this->_get->toInt('id')) {
        $pjOrderModel = pjOrderModel::factory();

        $arr = $pjOrderModel->join('pjClient', "t2.id=t1.client_id", 'left outer')
          ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
          ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
    						AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,	
    						AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
    						AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
    						AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
          ->find($id)->getData();
        if (!empty($arr)) {
          $locale_id = $this->getLocaleId();
          if (isset($arr['locale_id']) && (int)$arr['locale_id'] > 0) {
            $locale_id = $arr['locale_id'];
          }
          pjAppController::addOrderDetails($arr, $locale_id);

          $tokens = pjAppController::getTokens($this->option_arr, $arr, PJ_SALT, $locale_id);
          $notification = pjNotificationModel::factory()->where('recipient', 'client')
            ->where('transport', 'email')
            ->where('variant', 'confirmation')
            ->findAll()
            ->getDataIndex(0);
          if ((int)$notification['id'] > 0 && $notification['is_active'] == 1) {
            $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
            $lang_message = $resp['lang_message'];
            $lang_subject = $resp['lang_subject'];
            if (count($lang_message) === 1 && count($lang_subject) === 1) {
              if ($arr['type'] == 'delivery') {
                $message = str_replace(array(
                  '<br />[Delivery]',
                  '<br />[/Delivery]'
                ) , array(
                  '',
                  ''
                ) , $lang_message[0]['content']);
                $message = str_replace(array(
                  '[Delivery]<br />',
                  '[/Delivery]<br />'
                ) , array(
                  '',
                  ''
                ) , $message);
                $message = str_replace(array(
                  '[Delivery]',
                  '[/Delivery]'
                ) , array(
                  '',
                  ''
                ) , $message);
              } else {
                $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
              }
              $subject_client = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
              $message_client = str_replace($tokens['search'], $tokens['replace'], $message);
              $this->set('arr', array(
                'id' => $id,
                'client_email' => $arr['c_email'],
                'message' => $message_client,
                'subject' => $subject_client
              ));
            }
          }
        } else {
          exit;
        }
      } else {
        exit;
      }
    }
  }

  public function pjActionGetClient() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $client_arr = pjClientModel::factory()->find($this->_get->toInt('id'))->getData();
      $user = pjAuth::init(array(
        'id' => $client_arr['foreign_id']
      ))->getUser();
      $client = array_merge($user, $client_arr);
      pjAppController::jsonResponse($client);
    }
    exit;
  }

  public function pjActionCheckPickup() {
    $this->setAjax(true);
    if ($this->isXHR()){
      if (!$this->_post->toString('type') || !$this->_post->toInt('p_location_id')) {
        echo 'true';
        exit;
      }
      if ($this->_post->toString('type') != 'pickup') {
        echo 'true';
        exit;
      }
      $type = $this->_post->toString('type');
      $p_location_id = $this->_post->toInt('p_location_id');
      $date_time = $this->_post->toString('p_dt');
      if (count(explode(" ", $date_time)) == 3) {
        list($_date, $_time, $_period) = explode(" ", $date_time);
        $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
      } else {
        list($_date, $_time) = explode(" ", $date_time);
        $time = pjDateTime::formatTime($_time, $this->option_arr['o_time_format']);
      }
      $date = pjDateTime::formatDate($_date, $this->option_arr['o_date_format']);
      $wt_arr = pjAppController::getWorkingTime($date, $p_location_id, $type);
      if ($wt_arr == false) {
        echo 'false';
        exit;
      }
      $ts = strtotime($date . ' ' . $time);
      $start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
      $end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');

      if ($end_ts <= $start_ts) {
        $end_ts += 86400;
      }

      if ($ts >= $start_ts && $ts <= $end_ts) {
        echo 'true';
      } else {
        if ($ts < $start_ts) {
          $date = date('Y-m-d', ($ts - 86400));
          $wt_arr = pjAppController::getWorkingTime($date, $p_location_id, $type);
          if ($wt_arr == false) {
            echo 'false';
            exit;
          }
          $start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
          $end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');

          if ($end_ts <= $start_ts) {
            $end_ts += 86400;
          }
          if ($ts >= $start_ts && $ts <= $end_ts) {
            echo 'true';
          } else {
            echo 'false';
          }
        } else {
          echo 'false';
        }
      }
    }
    exit;
  }

  public function pjActionCheckDelivery() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!$this->_post->toString('type') || !$this->_post->toInt('d_location_id')) {
        echo 'true';
        exit;
      }
      if ($this->_post->toString('type') != 'delivery') {
        echo 'true';
        exit;
      }
      $type = $this->_post->toString('type');
      $d_location_id = $this->_post->toInt('d_location_id');
      $d_date = $this->_post->toString('d_date');
      $d_time = $this->_post->toString('d_time');
      if (count(explode(" ", $d_time)) == 2) {
        list($_time, $_period) = explode(" ", $d_time);
        $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
      } else {
        $time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
      }
      $date = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']);
      $wt_arr = pjAppController::getWorkingTime($date, $d_location_id, $type);

      if ($wt_arr == false) {
        echo 'false';
        exit;
      }
      $ts = strtotime($date . ' ' . $time);
      $start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
      $end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');

      if ($end_ts <= $start_ts) {
        $end_ts += 86400;
      }

      if ($ts >= $start_ts && $ts <= $end_ts) {
        echo 'true';
      } else {
        if ($ts < $start_ts) {
          $date = date('Y-m-d', ($ts - 86400));
          $wt_arr = pjAppController::getWorkingTime($date, $d_location_id, $type);
          if ($wt_arr == false) {
            echo 'false';
            exit;
          }
          $start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
          $end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');

          if ($end_ts <= $start_ts) {
            $end_ts += 86400;
          }
          if ($ts >= $start_ts && $ts <= $end_ts) {
            echo 'true';
          } else {
            echo 'false';
          }
        } else {
          echo 'false';
        }
      }
    }
    exit;
  }

  // MEGAMIND
  public function pjActionSaveOrderDespatched() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }
      if ($this->_get->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $id = $this->_get->toInt('id');
      pjOrderModel::factory()
        ->where('id', $id)->modifyAll(array(
        'order_despatched' => ":IF(`order_despatched`='0','1','0')"
      ));
      $data = pjOrderModel::factory()->select("t1.phone_no, t1.order_despatched, t1.type, t1.order_id")
        ->find($id)->getData(); 
      if ($data['order_despatched']) {
        switch(strtolower($data['type'])) {
          case 'delivery': 
            $msg = DELIVERY_DESPATCHED;
            $responseText = DELIVERY_RES_DESPATCHED;
          break;
          default:
            $msg = PICKUP_DESPATCHED;
            $responseText = PICKUP_RES_DESPATCHED;
        }
        $responseText = sprintf($responseText, $data['order_id']);
        $msg = str_replace("%ORDER_ID%", $data['order_id'], $msg);
        if (ENVIRONMENT == 'production') {
          $response = $this->sendMessage($data['phone_no'], $msg);
        } else {
          $response = 1;
        }
        if ($response == '1') {
          $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
          date_default_timezone_set($timezone);
          $_ss_time = date('y-m-d H:i:s', time());
          pjOrderModel::factory()->where('id', $id)->modifyAll(array(
            'sms_sent_time' => $_ss_time
          ));
          $text = __('plugin_base_sms_test_sms_sent_to', true) . ' ' . '447466708066';
          self::jsonResponse(array(
            'status' => 'OK',
            'code' => 200,
            'title' => __('plugin_base_sms_sent', true) ,
            'text' => $responseText
          ));
        } else {
          $text = $response['errors'][0]['message'];
          self::jsonResponse(array(
            'status' => 'ERR',
            'code' => 103,
            'title' => __('plugin_base_sms_failed_to_send', true) ,
            'text' => $text
          ));
        }
      }
    }
    exit;
  }
  public function pjActionSaveOrderPaid() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }

      if ($this->_get->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $gid = $this->_get->toInt('id');
      if ($gid) {
        pjOrderModel::factory()->where('id', $gid)->modifyAll(array(
          'is_paid' => ":IF(`is_paid`='0','1','0')"
        ));
      }
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => 'Your order has paid.'
      ));
    }
    exit;
  }

  public function pjActionSaveDeliveredCustomer() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }

      if ($this->_get->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $id = $this->_get->toInt('id');
      $row = pjOrderModel::factory()->where('id', $id)->modifyAll(array(
        'delivered_customer' => ":IF(`delivered_customer`='0','1','0')"
      ))->getAffectedRows();

      $data = pjOrderModel::factory()->select("t1.phone_no, t1.delivered_customer, origin")
        ->find($id)->getData();
      $msg = "Your Order has Delivered";
      if ($data['delivered_customer'] == 1) {
        $response['status'] = 'success';
        if ($response['status'] == 'success') {
          pjOrderModel::factory()->where('id', $id)->modifyAll(array(
            'status' => "delivered"
          ));
          //date_default_timezone_set('Asia/Kolkata');
          $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
          date_default_timezone_set($timezone);
          $delivered_time = date('y-m-d H:i:s', time());
          pjOrderModel::factory()->where('id', $id)->modifyAll(array(
            'delivered_time' => $delivered_time
          ));
          $text = __('plugin_base_sms_test_sms_sent_to', true) . ' ' . '447466708066';
          $count = $this->pendingOrderCount($data['origin']);
          self::jsonResponse(array(
            'status' => 'OK',
            'code' => 200,
            'title' => __('plugin_base_sms_sent', true) ,
            'text' => $response,
            'count' => $count,
            'origin' => $data['origin']
          ));
        } else {
          $text = $response['errors'][0]['message'];
          self::jsonResponse(array(
            'status' => 'ERR',
            'code' => 103,
            'title' => __('plugin_base_sms_failed_to_send', true) ,
            'text' => $text,
            'count' => $count,
            'origin' => $data['origin']
          ));
        }
      }

      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => 'Your order has delivered.'
      ));
    }
    exit;
  }

  public function pjActionGetDelayMessage() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }
      if ($this->_post->toInt('value') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $id = $this->_post->toInt('value');

      $msg = pjDelayMessagesModel::factory()->select('t1.*')
        ->where('t1.id', $id)->findAll()
        ->getData();

      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => $msg[0]['message']
      ));
    }

  }

  public function pjActionGetReasonList() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }
      if ($this->_post->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $id = $this->_post->toInt('id');
      $pjOrderModel = pjOrderModel::factory()->where('t1.deleted_order', 0)
        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
        ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
      $order = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
            AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,  
            AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
            AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
            AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
        ->where("t1.id", $id)
      ->findAll()
      ->getData();
      $titles = array();
      $defaultType = array('R', 'B');
      if ($order) {
        $delayQueryObj = pjDelayMessagesModel::factory()->select('t1.id, t1.title');
        if (strtolower($order[0]['origin']) == 'web') {
          if ($order[0]['is_viewed'] < 2) {
            $defaultType = array('A', 'B');
          } 
        } 
        $titles = $delayQueryObj->whereIn('type', $defaultType)->orderBy("order_by ASC")->findAll()->getData();
        $titles = array_column($titles, 'title', 'id');
      }
      $this->set('titles', $titles);
    }
  }

  public function pjActionGetProducts() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $product_arr = [];
      $category_id = $this->_post->toInt('category_id');
      $category_arr = pjProductCategoryModel::factory()->select('t1.product_id')
        ->whereIn("t1.category_id", $category_id)->findAll()
        ->getData();
      if ($category_arr) {
        $category_arr = array_column($category_arr, 'product_id');
        $product_arr = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status')
          ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->whereIn("t1.id", $category_arr)->groupBy('t1.id, t1.set_different_sizes, t1.price')
          ->findAll()
          ->getData();
      }
      $this->set('product_arr', $product_arr);
      $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->join('pjProductExtra', "t3.extra_id = t1.id")
        ->select("t1.*, t2.content AS name, t3.product_id")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
      $this->set('extras', $extra_arr);
      $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
        ->select("t1.*, t2.content AS price_name")
        ->orderBy("price_name ASC")
        ->findAll()
        ->getData();
      $this->set('price_arr', $price_arr);
    }
  }

  public function pjActionSetSession() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_post->toInt('chef_id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $chef = $this
        ->_post
        ->toInt('chef_id');
      $_SESSION['chef'] = $chef;
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'text' => 'success'
      ));
    }
  }
  public function pjActionDelayMessage() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isPost()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }
      if ($this->_post->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      if ($this->_post->toString('delay_msg') == "") {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 102,
          'text' => 'Message is empty'
        ));
      } else {
        $msg = $this->_post->toString('delay_msg');
      }
      $id = $this->_post->toInt('id');
      $data = pjOrderModel::factory()->select("t1.phone_no, t1.is_viewed, t1.origin")->find($id)->getData();
      $response = $this->sendMessage($data['phone_no'], $msg);
      if ($response == '1') {
        if ($data && strtolower($data['origin']) === 'web' && $data['is_viewed'] == 1) {
          $pjOrder = pjOrderModel::factory();
          $pjOrder->where('id', $id)->modifyAll(array('is_viewed' => 2))->getAffectedRows();
        }
        $text = __('plugin_base_sms_test_sms_sent_to', true) . ' ' . $data['phone_no'];
        self::jsonResponse(array(
          'status' => 'OK',
          'code' => 200,
          'text' => 'Delay message has been sent.'
        ));
      } else {
        $text = $response['errors'][0]['message'];
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 103,
          'title' => __('plugin_base_sms_failed_to_send', true) ,
          'text' => $text
        ));
      }
    }
    exit;
  }

  public function pjActionCheckPostcode() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_post->toString('post_code') == '') {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $post_code = $this->_post->toString('post_code');
      $pc = pjPostalcodeModel::factory()->select("t1.*")
        ->where("t1.postal_code", $post_code)->findAll()
        ->getData();
      if (count($pc) > 0) {
        self::jsonResponse(array(
          'status' => 'OK',
          'code' => 200,
          'text' => 'Post code available for delivery'
        ));
      } else {
        self::jsonResponse(array(
          'status' => 'OK',
          'code' => 100,
          'text' => 'Post code is not available for delivery'
        ));
      }
    }
  }

  public function pjActionCheckNewOrder() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $today = date('y-m-d', time());
      $today = $today . " " . "00:00:00";
      $orders = pjOrderModel::factory()->select('t1.*')
        ->where('t1.status', 'pending')
        ->where('t1.origin', 'web')
        ->where("(t1.created >= '$today')")->findAll()
        ->getData();
      if (count($orders) > 0) {
        $no_of_orders = count($orders);
        return self::jsonResponse(array(
          'status' => 'true',
          'orders' => $no_of_orders
        ));
      } else {
        return self::jsonResponse(array(
          'status' => 'false',
          'orders' => 'no pending orders'
        ));
      }
    }
    exit;
  }

  public function pjActionGetNewOrder() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $now = date('y-m-d H:i', time());
      $now = $now . ":00";
      $order = pjOrderModel::factory()->select('t1.*')
        ->where('t1.status', 'pending')
        ->where('t1.origin', 'web')
        ->where("(t1.created >= '$now')")->orderBy("created DESC")
        ->limit(1)
        ->findAll()
        ->getData();

      if (!empty($order)) {
        return self::jsonResponse(array(
          'status' => 'true',
          'order' => $order
        ));
      } else {
        return self::jsonResponse(array(
          'status' => 'false',
          'orders' => 'no recent orders'
        ));
      }
    }
    exit;
  }

  public function pjActionConfirmOrder() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_post->toInt('order_id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_post->toInt('order_id');
        $order_arr = pjOrderModel::factory()->where('id', $id)->findAll()
          ->getData();
        if (pjOrderModel::factory()->where('id', $id)->modifyAll(array(
          'status' => "confirmed"
        ))->getAffectedRows() == 1) {
          pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'confirmation', $this->getLocaleId());
          self::jsonResponse(array(
            'status' => 'Ok',
            'text' => 'Order has confirmed'
          ));
        } else {
          self::jsonResponse(array(
            'status' => 'ERR',
            'text' => 'Something is wrong'
          ));
        }
      }
    }
    exit;
  }

  public function pjActionOrderViewed() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_post->toInt('order_id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_post->toInt('order_id');
        $order_arr = pjOrderModel::factory()->where('id', $id)->findAll()->getData();
        if (pjOrderModel::factory()
          ->where('id', $id)->modifyAll(array(
          'is_viewed' => 1
        ))->getAffectedRows() == 1) {
          self::jsonResponse(array(
            'status' => 'Ok',
            'text' => 'Order has Viewed'
          ));
        } else {
          self::jsonResponse(array(
            'status' => 'ERR',
            'text' => 'Something is wrong'
          ));
        }
      }
    }
    exit;
  }

  public function pjActionCancelOrder() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_get->toInt('id') <= 0) {
        echo "Invalid parameter";
        exit;
      } else {
        $id = $this->_get->toInt('id');
        if (pjOrderModel::factory()
          ->where('id', $id)->modifyAll(array(
          'status' => "cancelled"
        ))->getAffectedRows() == 1) { 
          // if (1) {
          // $arr = pjOrderModel::factory()->find($id)->getData();
          $arr = pjOrderModel::factory()
          ->join('pjTable', "t2.id=t1.table_name", 'left')
          ->select("t1.*, t2.name as table_ordered_name")
          ->find($id)
          ->getData();
          // $pjOrderModel = pjOrderModel::factory();

          // $this->pr($arr);
          if (KITCHEN_PRINT) {
           $this->pjActionKPrintCanDelOrder($arr, "Cancelled");
          }
          $count = $this->pendingOrderCount($arr['origin']);
          self::jsonResponse(array(
            'status' => 'OK',
            'code' => 200,
            'text' => 'Order has been cancelled',
            'count' => $count,
            'origin' => $arr['origin'],
          ));
        } else {
          self::jsonResponse(array(
            'status' => 'ERR',
            'text' => 'Something is wrong'
          ));
        }
      }
    }
    exit;
  }

  function sendMessage($phone, $msg) {
    $pjSmsApi = new tlSmsApi();
    if (ENVIRONMENT != 'production') {
      $phone = TESTMOBILENUMBER;
    }
    $response = $pjSmsApi
    ->setApiKey('NTY1NGVmYWI4N2Y2ODA2YzllYzQwOTFhZWVjOWNlMGQ=')
    ->setNumber($phone)
    ->setText($msg)
    ->setSender(DOMAIN)
    ->send();
    if ($response == '1') {
      $sts = '1';
    } else {
      $sts = '0';
    }
    pjBaseSmsModel::factory()->reset()
      ->setAttributes(array(
      'number' => $phone,
      'text' => $msg,
      'status' => $sts,
      'api_response'=>json_encode($response),
      'created' => date('y-m-d H:i:s', time())
    ))->insert();
    return $response;
  }

  public function pjActionGetSingleOrderInfo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_get->toInt('id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_get->toInt('id');
        $pjOrderModel = pjOrderModel::factory()->where('t1.deleted_order', 0)
          ->join('pjClient', "t2.id=t1.client_id", 'left outer')
          ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
        $order = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
							AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,	
							AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
        ->find($id)
        ->getData();
        $this->getOrderItems($id, false);
        $role_id = $this->getRoleId();

        if ($order["surname"] == '' || is_null($order["surname"]) || $order["surname"] === 0) {
          $order["surname"] = $order["surname"] = $order["first_name"];
        }
        if ($role_id != ADMIN_R0LE_ID && $order['status'] == 'delivered') {   
          if ($order["surname"]) {
            $order["surname"] = substr($order["surname"], 0, 2).str_repeat("*", 10); 
          }
          if ($order["sms_email"]) {
            $order["sms_email"] = substr($order["sms_email"], 0, 2).str_repeat("*", (strLen($order['sms_email']) - 2));
          }
          if ($order["phone_no"]) {
            $order["phone_no"] = substr($order["phone_no"], 0, 2).str_repeat("*", (strLen($order['phone_no']) - 2));
          }
        }
        if($order["d_address_2"]) {
          $address = $order["d_address_1"].",<br/>".$order["d_address_2"].",<br/>".$order["d_city"];
        } else { 
          $address = $order["d_address_1"].",<br/>".$order["d_city"];
        }
        $order['sms_sent_time'] = $order['sms_sent_time'] == ''  ?  "-" : date("d-M-Y H:m:s", strtotime($order['sms_sent_time']));
        $order['delivered_time'] = $order['delivered_time'] == ''  ? "-" : date("d-M-Y H:m:s", strtotime($order['delivered_time']));
        if ($order['client_id'] == NULL && $order['origin'] == "web") {
          $order['c_type'] = "guest";
        }
        $order['address'] = $address;
        $this->set('order_details', $order);
      }
    }
  }

  public function pjActionGetProductSizes() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_post->toInt('product_id') <= 0){
        echo "Invalid parameter";
        exit;
      } else {
        $product_id = $this->_post->toInt('product_id');
        $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
          ->select("t1.*, t2.content AS price_name")
          ->where("product_id", $product_id)->orderBy("price_name ASC")
          ->findAll()
          ->getData();
        $this->set('price_arr', $price_arr);
      }
    }
  }

  public function pjActionGetSpecialInstructions() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if (!self::isGet()) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 100,
          'text' => 'HTTP method not allowed.'
        ));
      }
      if ($this->_get->toInt('id') <= 0) {
        self::jsonResponse(array(
          'status' => 'ERR',
          'code' => 101,
          'text' => 'Missing, empty or invalid parameters.'
        ));
      }
      $type_id = $this->_get->toInt('id');
      $selected_ins = $this->_get->toString('selected_ins');
      if ($selected_ins != '') {
        $selected_ins_arr = explode(",", $selected_ins);
        $selected_ins_arr = array_filter($selected_ins_arr);
      } else {
        $selected_ins_arr = array();
      }
      $spcl_ins_arr = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction_type")
        ->where("t1.type", "child")
        ->where("t1.parent_id", $type_id)->findAll()
        ->getData();
      $this->set('special_instructions', $spcl_ins_arr);
      $this->set('selected_ins_arr', $selected_ins_arr);
    }
  }

  public function pjActionGetSpecialInstructionTypes() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $spcl_ins_arr = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction_type")
        ->where("t1.type", "parent")
        ->findAll()
        ->getData();
      $spcl_ins_child_arr = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction_type")
        ->where("t1.type", "child")
        ->findAll()
        ->getData();
      $selected_ins = $this->_get->toString('selected_ins');
      $qty = $this->_get->toString('qty');
      if ($selected_ins != '') {
        $selected_ins_arr = json_decode($selected_ins, true);
      } else {
        $selected_ins_arr = array();
      }
      $this->set('special_instructions', $spcl_ins_arr);
      $this->set('special_instructions_children', $spcl_ins_child_arr);
      $this->set('selected_ins_arr', $selected_ins_arr);
      $this->set('qty', $qty);
      $this->set('page', 'qty_1');
    }
  }

  public function pjActionViewSpecialInstructionTypes() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $selected_ins = $this->_get->toString('selected_ins');
      if ($selected_ins != '') {
        $selected_ins_arr = json_decode($selected_ins, true);
      } else {
        $selected_ins_arr = array();
      }
      $this->set('selected_ins_arr', $selected_ins_arr);
    }
  }

  public function pjActionCreateEpos() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('order_create')) {
      $post_total = $this->getTotal();
      $post = $this->_post->raw();
      $data = array();
      $data['uuid'] = time();
      $data['ip'] = pjUtil::getClientIp();
      $data['locale_id'] = $this->getLocaleId();
      $data['origin'] = 'Pos';
      $data['is_paid'] = $post['is_paused'] == "1" ? 0 : 1;
      $data['status'] = $post['is_paused'] == "1" ? "pending" : "delivered";
      $data['payment_method'] = "cash";
      $data['type'] = 'pickup';
      $data['d_time'] = 0;
      $data['p_time'] = 0;
      $data['p_dt'] = date("Y-m-d H:i:s");
      $data['delivery_dt'] = date("Y-m-d H:i:s");
      $data['surname'] = "POS";
      $data['first_name'] = "POS";
      $data['table_name'] = $post['res_table_name'];
      if(strtolower($post['payment_method']) == "cash") {
        $data['payment_method']= 'cash';
      } elseif(strtolower($post['payment_method']) == "split") {
        $data['payment_method']= 'split';
        $data['cash_amount']= $post['cash_amount'];
      } else {
        $data['payment_method']= 'bank';
      }
      $default_chef = pjAuthUserModel::factory()->select("t1.*")
        ->where('role_id', '5')
        ->limit(1)
        ->findAll()
        ->getData();
      $data['chef_id'] = $default_chef[0]['id'];
      if ($this->_post->toString('payment_method') == 'creditcard') {
        $data['cc_exp'] = $this->_post->toString('cc_exp_month') . "/" . $this->_post->toString('cc_exp_year');
      }
      if (!empty($post['vouchercode'])) {
        $post['voucher_code'] = $post['vouchercode'];
      }
      $data['order_count_of_the_day'] = $this->getTodayOrderCount();
      $id = pjOrderModel::factory(array_merge($post, $data, $post_total))->insert()->getInsertId();
      $order_id = "P" . $id;
      pjOrderModel::factory()->where('id', $id)->modifyAll(array(
        'order_id' => $order_id
      ));

      if ($id !== false && (int)$id > 0) {
        $this->saveOrderItems($post, $id, false);
        if ($post['response']) {
          $this->savePaymentResponse($post, $id);
        }
        $err = 'AR07';
      } else {
        $err = 'AR04';
      }
      if ($post['is_paused']) {
        if (KITCHEN_PRINT) {
          pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=index&origin=Pos&id=$id");
        } else {
          pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
        }
      } else {
        if ($data['payment_method'] == 'cash') {
          // $this->pjActionCashDrawer();
        }
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionInitialPrint&id=$id");
      }
    }
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminPos.js');
  }

  private function pjActionCashDrawer() {
    require_once('sunmiPrinter.php');
    $printer = new SunmiCloudPrinter(384);
    $printer->openDrawer();
    $sn = RECEIPT_SN;
    $result = $printer->pushContent($sn, sprintf("%s_%010d", $sn, time()));
    return $api_result = json_encode($result);
  }

  public function pjActionUpdateEpos() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('order_update') && $this->_post->toInt('id')) {
      $pjOrderModel = pjOrderModel::factory();
      $pjOrderItemModel = pjOrderItemModel::factory();
      $pjProductPriceModel = pjProductPriceModel::factory();
      $pjExtraModel = pjExtraModel::factory();
      $pjProductModel = pjProductModel::factory();
      $id = $this->_post->toInt('id');
      $post = $this->_post->raw();
      $arr = $pjOrderModel->find($id)->getData();
      if (empty($arr)) {
        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08");
      }

      if (isset($post['product_id']) && count($post['product_id']) > 0) {
        $keys = array_keys($post['product_id']);
        $pjOrderItemModel->reset()->where('order_id', $id)->whereNotIn('hash', $keys)->eraseAll();
        if ($id !== false && (int)$id > 0) {
          $this->saveOrderItems($post, $id, false);
          if ($post['response']) {
            $this->savePaymentResponse($post, $id);
          }
        $err = 'AR07';
        } else {
          $err = 'AR04';
        }
      }
      $data = array();
      $data['ip'] = pjUtil::getClientIp();
      $data['is_paid'] = $post['is_paid'];
      $data['type'] = $post['origin'] == 'web' ? 'pickup & call' : 'pickup';
      $data['status'] = $post['is_paused'] == 0 ? 'delivered' : 'pending';
      if(strtolower($post['payment_method']) == "cash") {
        $data['payment_method']= 'cash';
      } elseif(strtolower($post['payment_method']) == "split") {
        $data['payment_method']= 'split';
        $data['cash_amount']= $post['cash_amount'];
      } else {
        $data['payment_method']= 'bank';
      }
      if (!empty($post['p_date']) && !empty($post['pickup_time'])) {
        $p_date = $post['p_date'];
        $p_time = $post['pickup_time'];

        if (count(explode(" ", $p_time)) == 2) {
          list($_time, $_period) = explode(" ", $p_time);
          $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
        } else {
          $time = pjDateTime::formatTime($p_time, $this->option_arr['o_time_format']);
        }
        $data['p_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time;
        $data['delivery_dt'] = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']) . ' ' . $time;
      }
      if ($this->_post->toInt('p_location_id')) {
        $data['location_id'] = $this->_post->toInt('p_location_id');
      }

      $data['d_time'] = 0;

      $post_data = $this->getTotal();

      if (!empty($post['vouchercode'])) {
        $post['voucher_code'] = $post['vouchercode'];
      }
      $data['table_name'] = $post['res_table_name'];
      $pjOrderModel->reset()
        ->where('id', $id)->limit(1)
        ->modifyAll(array_merge($post, $data, $post_data));
      $err = 'AR01';
      if ($post['is_paused']) {
        if (KITCHEN_PRINT) {
          pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=index&origin=Pos&id=$id");
        } else {
           pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
        }
      } else {
        if ($data['payment_method'] == 'cash') {
          $this->pjActionCashDrawer();
        }
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionInitialPrint&id=$id");
      }
    }
  }

  public function sortStringFoo($a, $b) {
    return strcmp($a['status'], $b['status']);
  }

  //Megamind
  public function pjActionValidateTable() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $post = $this->_post->raw();
      $table_id = $this->_post->toInt('table_id');
      $pjOrderModel = pjOrderModel::factory();
      $recordCount = $pjOrderModel->where('origin', 'Pos')
        ->where('status', 'pending')
        ->where('deleted_order', 0)
        ->where('date(t1.created) >= date(now())')
        ->where("table_name", "$table_id")->findCount()
        ->getData();
      if ($recordCount) {
        self::jsonResponse(array(
          'status' => 'OK',
          'code' => 200,
          'text' => 'Already pending order in queue for this table!',
          'count' => $recordCount
        ));
      } else {
        self::jsonResponse(array(
          'status' => 'OK',
          'code' => 200,
          'count' => $recordCount,
          'text' => ''
        ));
      }
    }
    exit;
  }

  public function pjActionSalePrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
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
      $this->getOrderItems($id, false);
      $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
        ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
        ->orderBy('t2.name ASC')
        ->findAll()
        ->getData();
      $this->set('client_arr', $client_arr);
      $location_arr = pjLocationModel::factory()
      ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
      ->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'address'", 'left')
        ->select("t1.*, t2.content AS name, t3.content as address")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
      $special_instructions = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
        ->select("t1.*, t2.content AS instruction")
        ->findAll()
        ->getData();
      $this->set('special_instructions', $special_instructions);
      $this->set('location_arr', $location_arr);
      $timezone = $this->option_arr['o_timezone'] ? $this->option_arr['o_timezone'] : ADMIN_TIME_ZONE;
      $this->set('timezone', $timezone);
      $this->set('action', 'pjActionIndex');
      $options = pjRegistry::getInstance()->get('options');       
      $currency = $options['o_currency'];
      $curSign = pjCurrency::getCurrencySign($currency, false);
      $this->set('currency', $currency);
      $this->set('curSign', $curSign);
      $this->appendJs('EscPosPrinter.js');
    }
  }

  public function pjActionInitialPrint() {
    $this->setLayout('pjActionPrint');
    $this->appendJs('EscPosPrinter.js');
    $this->pjActionSalePrint();
  }

  public function pjActionKitchenPrintUpdate() {
  	$order_id = $this->_post->toInt('order_id');
  	$this->setAjax(true);
    if ($this->isXHR() && $order_id) {
      $pjOrder = pjOrderItemModel::factory();
      $pjOrder->where('order_id', $order_id)->modifyAll(array('print' => ':cnt'))->getAffectedRows();
    }
    exit;
  }

  public function pjActionOpenDrawer() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if ($this->isXHR()) {
      require_once('sunmiPrinter.php');
      $printer = new SunmiCloudPrinter(384);
      $printer->openDrawer();
      $sn = RECEIPT_SN;
      $result = $printer->pushContent($sn, sprintf("%s_%010d", $sn, time()));
      $api_result = json_encode($result);
      if (strtolower($result['msg']) == 'success') {
        $apiMessage = "Opened";
      } else {
        $apiMessage = "Failed";
      }
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'message' => $apiMessage,
        'apiMessage' => $api_result
      ));
    } else {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 400,
        'text' => 'Some Error'
      ));
    }
  }

  public function pjActionGetPendingOrders() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if ($this->isXHR()) {
      $queryOrigin = ucfirst($this->_post->toString('origin'));
      $count = $this->pendingOrderCount($queryOrigin);
      self::jsonResponse(array(
        'status' => 'OK',
        'code' => 200,
        'message' => $count
      ));
    } else {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 400,
        'text' => 'Some Error'
      ));
    }
  }

  public function pjActionAddCustomProduct() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $product_id = $this->_post->toString('product_id');
      $post = $this->_post->raw();
      if ($product_id) {
        $product_arr['price'] = $post['price'];
        $product_arr['qty'] = $post['quantity'];
        $product_arr['total'] =  $post['price'] * $post['quantity'];
        $product_arr['description'] =  'Custom - '.$post['name'];
        $this->set('product_arr', $product_arr);
      }
    }
  }

  public function pjActionReturnOrderItem() {
    $this->checkLogin();
    $this->setLayout('pjActionOrder');
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('order_update') && $this->_post->toInt('id')) {
      $post_total = $this->getTotal();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('id');
      echo '<pre>'; print_r($post); exit;
      if ($this->saveOrderItems($post, $id, true)) {
        $rows =  pjOrderModel::factory()->where('id', $id)->modifyAll(array('total' => $post_total['total']))->getAffectedRows();
      }
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
    }
    $id = $this->_get->toInt('id');
    $arr = pjOrderModel::factory()->join('pjClient', "t2.id=t1.client_id", 'left outer')
      ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
      ->select("t1.*,t3.name as client_name, t2.c_title, t3.email as c_email, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip,t2.c_notes,t2.mobile_delivery_info AS c_mobileDeliveryInfo,t2.mobile_offer AS c_mobileOffer,t2.email_receipt AS c_emailReceipt,t2.email_offer AS c_emailOffer,
            AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,
            AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
            AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
            AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`,
            AES_DECRYPT(t3.password, '" . PJ_SALT . "') AS `c_password`")
      ->find($id)->getData();
      if (count($arr) <= 0) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08&type=Telephone");
      }
    $this->set('arr', $arr);
    $this->getInculdeData();
    $pjProductPriceModel = pjProductPriceModel::factory();
    $oi_arr = array();
    $oi_extras = array();
    $_oi_arr = pjOrderItemModel::factory()->where('t1.order_id', $arr['id'])->findAll()->getData();
    foreach ($_oi_arr as $item) {
      if ($item['type'] == 'product') {
        $item['price_arr'] = $pjProductPriceModel->reset()
          ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()) , 'left')
          ->select("t1.*, t2.content AS price_name")
          ->where('product_id', $item['foreign_id'])->findAll()
          ->getData();
      } else if ($item['type'] == 'extra') {
        $oi_extras[$item['hash']] = $item;
      }
      $oi_arr[] = $item;
    }
    $this->set('oi_extras', $oi_extras);
    $product_ids = array_column($oi_arr, 'foreign_id');
    $product_arr = pjProductModel::factory()->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status, t1.preparation_time, t1.image, (SELECT COUNT(*) FROM `' . pjProductExtraModel::factory()
          ->getTable() . '` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras')
          ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->whereIn("t1.id", $product_ids)->groupBy('t1.id, t1.set_different_sizes, t1.price')
          ->findAll()
          ->getData();
    $product_arr = array_combine(array_column($product_arr, 'id'),$product_arr);
    $category = pjProductCategoryModel::factory()->select('t1.*')
      ->findAll()
      ->getData();
    $this->set('product_arr', $product_arr);
    foreach ($oi_arr as $oi => $o) {
      foreach ($category as $k => $v) {
        if ($o['foreign_id'] == $v['product_id']) {
          $oi_arr[$oi]['category'] = $v['category_id'];
        }
      }
    }
    $this->set('oi_arr', $oi_arr);
    $spcl_ins = pjOrderItemModel::factory()->where('t1.order_id', $id)->findAll()->getData();
    $this->set('spcl_ins', $spcl_ins);
    $client_arr = pjClientModel::factory()->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
      ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
      ->orderBy('t2.name ASC')
      ->findAll()
      ->getData();
    $this->set('client_arr', $client_arr);

    if (pjObject::getPlugin('pjPayments') !== NULL) {
      $this->set('payment_option_arr', pjPaymentOptionModel::factory()
        ->getOptions($this->getForeignId()));
      $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId() , $this->getLocaleId()));
    } else {
      $this->set('payment_titles', __('payment_methods', true));
    }

    $extra_arr = pjExtraModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()) , 'left')
        ->select("t1.*, t2.content AS name")
        ->orderBy("name ASC")
        ->findAll()
        ->getData();
    $this->set('extra_arr', $extra_arr);

    $special_instructions = pjSpecialInstructionModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
      ->select("t1.*, t2.content AS instruction")
      ->findAll()
      ->getData();
    $this->set('special_instructions', $special_instructions);
    $selTableName = '';
    $table_list = $this->getRestaurantTables();
    if ($arr['origin'] == 'Pos') {
      if (array_key_exists($arr['table_name'], $table_list)) {
        $total_persons = $arr['total_persons'];
        $order_title = 'Eat In';
        $selTableName = $table_list[$arr['table_name']].' Count '.$total_persons;
      } else {
        $order_title = 'Take Away';
      }
    } else if ($arr['origin'] == 'Telephone') {
      $order_title = 'Telephone';
    } else {
      $order_title = 'Web';
    }
    $this->set('order_title', $order_title);
    $this->set('selTableName', $selTableName);
  }
  private function pendingOrderCount($queryOrigin) {
    $today = date('Y-m-d', time());
    $toDay = $today . " " . "00:00:00";
    $status = array('pending', 'confirmed');
    return $count = $pjOrderModel = pjOrderModel::factory()->where('t1.deleted_order', 0)
        ->whereIn('t1.status', $status)
        ->where("t1.origin", "$queryOrigin")
        ->where("(t1.d_dt >= '$toDay' OR t1.p_dt >= '$toDay')")
        ->findCount()
        ->getData();
  }
  private function updateKitchenPrint($order_id) {
    if ($order_id) {
      $pjOrder = pjOrderItemModel::factory();
      $pjOrder->where('order_id', $order_id)->modifyAll(array('print' => ':cnt'))->getAffectedRows();
    }
  }

  private function getActionName($source) {
  	$actionName = 'pjActionCreate';
  	switch($source) {
  		case 'index':
  			$actionName = 'pjActionIndex';
  		break;
  		default: 
  			$actionName = 'pjActionCreate';
  	}
  	return $actionName;
  }

  private function pjActionKPrintCanDelOrder($data, $message="Cancelled") {
    require_once('sunmiPrinter.php');
    $this->setLayout('pjActionPrint');
    $printBrowser = true;
    $printMessage = 'failed';
    $ed = $data['d_dt'] != '' ? $data['d_dt'] : $data['p_dt'];
    $ed_dt = explode(" ", $ed);
    $ed_dt_items = explode('-', $ed_dt[0]);
    $ed_dt[0] = $ed_dt_items[2] . '-' . $ed_dt_items[1] . '-' . $ed_dt_items[0];
    $printer = new SunmiCloudPrinter(384);
    $printer->selectAsciiCharFont(0x80);
    $printer->setLineSpacing(80);
    $printer->setPrintModes(true, true, false);
    $estDelivery = $ed_dt[0] . ' ' . $ed_dt[1];
    $orderID = $data['order_id'];
    $created = date('d-m-Y H:i:s', strtotime($data['created']));
    if (strtolower($data['origin']) != 'pos') {
      $printer->setHarfBuzzAsciiCharSize(14);
      $type = substr($data['origin'], 0, 3). " - " .ucfirst($data['type']);
      //Data Printing
      $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
      $printer->appendText("$type");
      // echo $type. '<br/>';
      $printer->lineFeed(1);
    }
    $printer->setAlignment(SunmiCloudPrinter::ALIGN_LEFT);
    $printer->setupColumns([150 , SunmiCloudPrinter::ALIGN_LEFT, 0], [300, SunmiCloudPrinter::ALIGN_LEFT, 0]);
    $printer->setHarfBuzzAsciiCharSize(10);
    $printer->printInColumns("TIME:", "$created");
    // echo "TIME:" .$created.'<br/>';
    $printer->printInColumns("ED:", "$estDelivery");
    // echo "ED:" .$estDelivery.'<br/>';
    $printer->setHarfBuzzAsciiCharSize(14);
    $printer->printInColumns("OrderID:", "$orderID");
    // echo "OrderID: $orderID". '<br/>';
    $printer->setPrintModes(true, true, false);
    $tableName = $data['table_ordered_name']?$data['table_ordered_name']:$data['table_name'];
    if ($tableName) {
      $printer->setAlignment(SunmiCloudPrinter::ALIGN_CENTER);
      $printer->appendText("$tableName");
      // echo $tableName. '<br/>';
      $printer->lineFeed();
    }
    $printer->setHarfBuzzAsciiCharSize(16);
    $printer->appendText(str_repeat("-", 35));
    // echo str_repeat("-", 35).'<br/>';
    $printer->lineFeed();
    $printer->restoreDefaultLineSpacing();
    $printer->appendText("OrderID $orderID - $message");
    $printer->lineFeed();
    // echo "$orderID - $message". '<br/>';
    $printer->appendText(str_repeat("-", 35));
    // echo str_repeat("-", 35).'<br/>';
    $printer->lineFeed();
    $printer->setPrintModes(false, false, false);
    //End of Data printing
    $printer->restoreDefaultLineSpacing();
    $printer->printAndExitPageMode();
    $printer->lineFeed(4);
    $printer->cutPaper(false);
    $sn = KITCHEN_SN;
    $result = $printer->pushContent($sn, sprintf("%s_%010d", $sn, time()));
    if (is_array($result) && array_key_exists('msg', $result) && strtolower($result['msg']) == 'success') {
      $printMessage = "Printed";
    } else {
      $printMessage = "Failed";
    }
    return $printMessage;
  }
}
?>
