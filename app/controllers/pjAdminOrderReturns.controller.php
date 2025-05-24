<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminOrderReturns extends pjAdmin {

  public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
    }
    $this->setLocalesData();
    $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
    $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminOrderReturn.js');
  }

  public function pjActionGetReturnOrderList() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjOrderReturn = pjOrderReturnModel::factory()
        ->join('pjMultiLang', "t2.model='pjProduct' AND t2.foreign_id=t1.product_id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')
        ->where('t1.product_id != 0');

      $pjOrderReturnCustom = pjOrderReturnModel::factory()->where('t1.product_id', 0);

      if ($q = $this->_get->toString('q')) {
          $pjOrderReturn = $pjOrderReturn->where("(t2.content LIKE '%$q%')");
          $pjOrderReturnCustom = $pjOrderReturnCustom->where("(product_name LIKE '%$q%')");
      }
      
      $column = 'created_at';
      $direction = 'DESC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjOrderReturn->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      if ($this->_get->toInt('page')) {
        $page = $this->_get->toInt('page') ?: 1;
      } else {
        $page = 1;
      }
      
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages) {
        $page = $pages;
      }

      $pjOrderReturnCustom = $pjOrderReturnCustom
        ->select('t1.*')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData();

      $pjOrderReturn = $pjOrderReturn
        ->select('t1.*, t2.content as product_name')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData();

      $data = array_merge($pjOrderReturnCustom, $pjOrderReturn);
      foreach ($data as $k => $value) {
        $data[$k]['purchase_date'] = date('d-m-Y', strtotime($data[$k]['purchase_date']));
        $data[$k]['return_date'] = date('d-m-Y', strtotime($data[$k]['return_date']));
      }
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionCreate() {

    $post_max_size = pjUtil::getPostMaxSize();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminOrderReturns&action=pjActionIndex&err=AP05");
    }
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjOrderReturn = pjOrderReturnModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post['quantity'] && $post['price'] && $post['amount'] && $post['reason'] && $post['purchase_date']) {
        $id = $post['product_id'];
        $name = pjMultiLangModel::factory()->where("(model='pjProduct' AND foreign_id=$id AND field='name' AND locale='" . $this->getLocaleId() . "')")->findAll()->getData();
        if($post['size']) {
          $data['size'] = $post['size'];
        }
        $data['order_id'] = $post['order_id'];
        $data['product_id'] = $post['product_id'];
        $data['reason'] = $post['reason'];
        $data['price'] = $post['price'];
        $data['qty'] = $post['quantity'];
        $data['amount'] = $post['amount'];
        $data['purchase_date'] = date('Y-m-d', strtotime($post['purchase_date']));
        $data['return_date'] = date('Y-m-d', strtotime($post['return_date']));
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if ($post['product_id'] == 0) {
          $data['product_name'] = $post['product_name'];
        }
        $err = 'AP09';

        $pjOrderReturn->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOrderReturns&action=pjActionIndex&err=$err");
    }

    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('product_arr', pjProductModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjProduct' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->where('t1.status', 1)->orderBy('`order` ASC')->findAll()->getData());

      $purchase_date = date('Y-m-d', strtotime('-1 day'));
      $return_date = date('Y-m-d');  
      $this->set('purchase_date', $purchase_date);
      $this->set('return_date', $return_date);

      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('datepicker.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminOrderReturn.js');
    }
  }

  public function pjActionDelete() {
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
    $pjOrderReturn = pjOrderReturnModel::factory();
    $arr = $pjOrderReturn->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Company not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjOrderReturn->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Company has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Company has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjOrderReturn = pjOrderReturnModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('id');
      if ($post['quantity'] && $post['price'] && $post['amount'] && $post['reason'] && $post['purchase_date'] && $id) {
        $data['product_id'] = $post['product_id'];
        $data['order_id'] = $post['order_id'];
        $data['reason'] = $post['reason'];
        $data['price'] = $post['price'];
        $data['qty'] = $post['quantity'];
        $data['amount'] = $post['amount'];
        $data['purchase_date'] = date('Y-m-d', strtotime($post['purchase_date']));
        $data['return_date'] = date('Y-m-d', strtotime($post['return_date']));
        $data['updated_at'] = date("Y-m-d H:i:s");
        if ($post['product_id'] == 0) {
          $data['product_name'] = $post['product_name'];
        }
        if($post['size']) {
          $data['size'] = $post['size'];
        }
        $err = 'AP09';
        $pjOrderReturn->reset()->where('id', $id)->limit(1)->modifyAll($data);
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOrderReturns&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();

      $id = $this->_get->toInt('id');
      $arr = pjOrderReturnModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrderReturns&action=pjActionIndex&err=AP08");
      } 
      if($arr['product_id'] != 0) {
        $product_information = $this->singleProductInformation($arr['product_id']);
        if($product_information[0]['set_different_sizes'] == 'T') {
          $this->set('different_size_product', $product_information[0]['size']);
        }
      }
      
      $this->set('arr', $arr);


      $this->set('product_arr', pjProductModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjProduct' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->where('t1.status', 1)->orderBy('`order` ASC')->findAll()->getData());
      

      $this->set('purchase_date', $arr['purchase_date']);
      $this->set('return_date', $arr['return_date']);

      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('datepicker.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminOrderReturn.js');
    }
  }

  public function pjActionGetProductInformation() {
    $this->setAjax(true);
    $post =$this->_post->raw();
    $product_information = $this->singleProductInformation($post['product_id']);

    if (count($product_information)) {
      self::jsonResponse(array('status'=> true, 'code'=> 200, 'res'=> $product_information));
    } else {
      self::jsonResponse(array('status'=> false, 'code'=> 200, 'res'=> "not found"));
    }
    exit;
  }

  protected function singleProductInformation($id) {
    $product_information = pjProductModel::factory()
      ->whereIn('id', $id)
      ->findAll()->getData();

    if (count($product_information)) {
      if ($product_information[0]['set_different_sizes'] == 'T') {
        $price_arr = pjProductPriceModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')
          ->select("t1.*, t2.content AS price_name")
          ->where("product_id", $id)->orderBy("price_name ASC")
          ->findAll()
          ->getData();
        $product_information[0]['size'] = $price_arr;
      }
      return $product_information;
    } else {
      return null;
    }
  }
   
}
?>