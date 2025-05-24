<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminPostalcode extends pjAdmin {
	public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLocalesData();
    $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
    $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminPostalcode.js');
	}

  public function pjActionGetPostalcode() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjPostalcodeModel = pjPostalcodeModel::factory();
      $pjPostalcodeModel = $pjPostalcodeModel
        ->select("t1.*,date_format(t1.created_at, '%m-%d-%Y') as date,t1.name as product_name, t1.postal_code as c_name");
      if ($q = $this->_get->toString('q')) {
        $pjPostalcodeModel = $pjPostalcodeModel->where("(t1.name LIKE '%$q%' OR t1.postal_code LIKE '%$q%')");
      }
      $column = 'c_name';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }
      $total = $pjPostalcodeModel->findCount()->getData();
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

      $data = $pjPostalcodeModel
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData();
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionCreate() {
    $post_max_size = pjUtil::getPostMaxSize();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPostalcode&action=pjActionIndex&err=AP05");
    }
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjPostalcodeModel = pjPostalcodeModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post['name']) {
        $data['name'] = $post['name'];
        $data['postal_code'] = $post['postal_code'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjPostalcodeModel->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPostalcode&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminPostalcode.js');
    }
  }

  public function pjActionDeletePostalcode() {
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
    $pjPostalcodeModel = pjPostalcodeModel::factory();
    $arr = $pjPostalcodeModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Postalcode not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjPostalcodeModel->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Postalcode has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Postalcode has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('Postalcode_id')) {
      $pjPostalcodeModel = pjPostalcodeModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('Postalcode_id');
      if ($post['name']) {
        $data['name'] = $post['name'];
        $data['postal_code'] = $post['postal_code'];
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjPostalcodeModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPostalcode&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $id = $this->_get->toInt('id');
      $arr = pjPostalcodeModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminPostalcode&action=pjActionIndex&err=AP08");
      } 
      $this->set('arr', $arr);
      $this->setLocalesData();
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminPostalcode.js');
    }
  }
	 
}
?>