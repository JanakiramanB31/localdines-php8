<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminMasters extends pjAdmin {

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
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminMaster.js');
  }

  public function pjActionGetMaster() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjMasterModel = pjMasterModel::factory();
      $pjMasterModel = $pjMasterModel
        ->select("t1.*,date_format(t1.created_at, '%m-%d-%Y') as date,t1.name as product_name, t2.name as c_name")
        ->join('pjMasterType', 't2.id=t1.master_type_id', 'left outer');

      if ($q = $this->_get->toString('q')) {
        $pjMasterModel = $pjMasterModel->where("(t1.name LIKE '%$q%' OR t2.name LIKE '%$q%')");
      }
        
      $column = 'c_name';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjMasterModel->findCount()->getData();
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

      $data = $pjMasterModel
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
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminMasters&action=pjActionIndex&err=AP05");
    }
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjMasterModel = pjMasterModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post['master']  && $post['name']) {
        $data['master_type_id'] = $post['master'];
        $data['name'] = $post['name'];
        $data['address'] = $post['address'];
        $data['postal_code'] = $post['postal_code'];
        $data['contact_person'] = $post['contact_person'];
        $data['contact_number'] = $post['contact_number'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjMasterModel->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminMasters&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('masters_types', pjMasterTypeModel::factory()
        ->select('t1.*')
        ->where('t1.is_active', '1')
        ->orderBy('`name` ASC')
        ->findAll()
        ->getData());
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminMaster.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }

  public function pjActionDeleteMaster() {
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
    $pjMasterModel = pjMasterModel::factory();
    $arr = $pjMasterModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Master not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjMasterModel->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Master has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Master has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('Master_id')) {
      $pjMasterModel = pjMasterModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('Master_id');
      if ($post['master']  && $post['name']) {
        $data['master_type_id'] = $post['master'];
        $data['name'] = $post['name'];
        $data['address'] = $post['address'];
        $data['postal_code'] = $post['postal_code'];
        $data['contact_person'] = $post['contact_person'];
        $data['contact_number'] = $post['contact_number'];
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjMasterModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminMasters&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $id = $this->_get->toInt('id');
      $arr = pjMasterModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminMasters&action=pjActionIndex&err=AP08");
      } 
      $this->set('arr', $arr);
      $this->setLocalesData();
      $this->set('masters_types', pjMasterTypeModel::factory()
        ->select('t1.*')
        ->where('t1.is_active', '1')
        ->orderBy('`name` ASC')
        ->findAll()
        ->getData());
  
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminMaster.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }
}
?>