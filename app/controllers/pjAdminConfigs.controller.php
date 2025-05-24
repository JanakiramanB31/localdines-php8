<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminConfigs extends pjAdmin {
  public $dataTypes = array('array','boolean','json', 'string');
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
    $this->appendJs('pjAdminConfig.js');
  }

  public function pjActionGetConfig() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjConfigModel = pjConfigModel::factory();
      $pjConfigModel = $pjConfigModel
        ->select("t1.*,date_format(t1.created_at, '%m-%d-%Y') as date,t1.id as id,t1.key as name,t1.value, CASE t1.is_active WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' END as is_active");
      if ($q = $this->_get->toString('q')) {
        $pjConfigModel = $pjConfigModel->where("(t1.key LIKE '%$q%' OR t1.value LIKE '%$q%')");
      }
        
      $column = 'name';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjConfigModel->findCount()->getData();
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

      $data = $pjConfigModel
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
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminConfigs&action=pjActionIndex&err=AP05");
    }
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjConfigModel = pjConfigModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post) {
        $data['key'] = $post['key'];
        $data['value'] = $post['value'];
        $data['type'] = $post['type'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjConfigModel->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminConfigs&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('dataTypes', $this->dataTypes);
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('pjAdminConfig.js');
    }
  }

  public function pjActionDeleteConfig() {
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
    $pjConfigModel = pjConfigModel::factory();
    $arr = $pjConfigModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Config not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjConfigModel->setAttributes(array('config_id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Config has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Config has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('id')) {
      $pjConfigModel = pjConfigModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('id');
      if ($post) { 
        $data['key'] = $post['key'];
        
        if ($post['type'] == 'json') {
          $data['value'] = json_encode($post['value']);
        } else {
          $data['value'] = str_replace('"', "'", $post['value']);
        }
        $data['type'] = $post['type'];
        $data['is_active'] = $post['is_active'];
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjConfigModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
        $this->updateConfigFile();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminConfigs&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $id = $this->_get->toInt('id');
      $arr = pjConfigModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminConfigs&action=pjActionIndex&err=AP08");
      } 
      $this->set('arr', $arr);
      $this->set('dataTypes', $this->dataTypes);
      $this->setLocalesData();
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('pjAdminConfig.js');
    }
  }

  function updateConfigFile() {
    $adminConfigList = pjConfigModel::factory()->findAll()->getData();
    $configFile = dirname(__FILE__).'/../config/userconfig.gen.php';
    
    // First clear the file (don't reuse $file variable)
    $handle = fopen($configFile, "w");
    if ($handle === false) {
        throw new Exception("Failed to open config file for writing");
    }
    fwrite($handle, "");
    fclose($handle);
    
    // Now write the content
    $handle = fopen($configFile, "w");
    if ($handle === false) {
        throw new Exception("Failed to open config file for writing");
    }
    
    $configContent = '<?php ';
    if ($adminConfigList) {
        foreach($adminConfigList as $adminConfig) {
            switch($adminConfig['type']) {
                case "array":
                    $configContent .= 'define("'.$adminConfig['key'].'",'.$adminConfig['value'].');';
                    break;
                default:
                    $configContent .= 'define("'.$adminConfig['key'].'","'.addslashes($adminConfig['value']).'");';
            }
        }
        $configContent .= '?>';
    }
    
    fwrite($handle, $configContent);
    fclose($handle);
  }
}
?>