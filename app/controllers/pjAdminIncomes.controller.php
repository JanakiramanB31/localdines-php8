<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminIncomes extends pjAdmin {

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
    $this->appendJs('pjAdminIncome.js');
  }

  public function pjActionGetIncome() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjIncomeModel = pjIncomeModel::factory();
      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        // $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
        // $from = $date_from->format('Y-m-d'). " " . " 00:00:00";
        // $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
        // $to = $date_to->format('Y-m-d'). " " . "23:59:59";
        $from = date('Y-m-d', strtotime($this->_get->toString('date_from'))). " 00:00:00";
        $to = date('Y-m-d', strtotime($this->_get->toString('date_to'))). " 23:59:59";
      }

      $pjIncomeModel = $pjIncomeModel
      ->select("t1.*,date_format(t1.income_date, '%d-%m-%Y') as date, t2.name as c_name")
      ->where("(t1.income_date >= '$from' AND t1.income_date <= '$to')")
      ->join('pjMaster', 't2.id=t1.master_id', 'left outer');

      if ($q = $this->_get->toString('q')) {
        $pjIncomeModel = $pjIncomeModel->where("( t2.name LIKE '%$q%')");
      }

      $column = 'c_name';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjIncomeModel->findCount()->getData();
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

      $data = $pjIncomeModel
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
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminIncomes&action=pjActionIndex&err=AP05");
    }

    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjIncomeModel = pjIncomeModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post['master']  && $post['amount']) {
        $incomeDate = $post['income_date'];
        // $incomeDate = str_replace('/','-', $incomeDate);
        $data['master_id'] = $post['master'];
        $data['description'] = $post['description'];
        $data['amount'] = $post['amount'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['income_date'] = date('Y-m-d', strtotime($post['income_date']));
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjIncomeModel->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminIncomes&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('masters', pjMasterModel::factory()
        ->select('t1.*')
        ->where('t1.is_active', '1')
        ->where('t1.master_type_id', '2')
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
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminIncome.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }

  public function pjActionDeleteIncome() {
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
    $pjIncomeModel = pjIncomeModel::factory();
    $arr = $pjIncomeModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Income not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjIncomeModel->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Income has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Income has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('Income_id')) {
      $pjIncomeModel = pjIncomeModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('Income_id');
      if ($post['master']  && $post['amount']) {
        $data['master_id'] = $post['master'];
        $data['description'] = $post['description'];
        $data['amount'] = $post['amount'];
        $data['income_date'] = date('Y-m-d',strtotime($post['income_date']));
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjIncomeModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminIncomes&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $id = $this->_get->toInt('id');
      $arr = pjIncomeModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminIncomes&action=pjActionIndex&err=AP08");
      } 
      $this->set('arr', $arr);
      $this->setLocalesData();
      $this->set('masters', pjMasterModel::factory()
        ->select('t1.*')
        ->where('t1.is_active', '1')
        ->where('t1.master_type_id', '2')
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
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminIncome.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }  
}
?>