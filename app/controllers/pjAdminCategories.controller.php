<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminCategories extends pjAdmin {
	public function pjActionDeleteCategory() {
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
		if (!pjCategoryModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Category has not been deleted.'));
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Category has been deleted'));
		exit;
	}
	
	public function pjActionDeleteCategoryBulk() {
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
    if (!$this->_post->has('record')) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
    }
    $record = $this->_post->toArray('record');
    if (empty($record)) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
    }
    pjMultiLangModel::factory()->where('model', 'pjCategory')->whereIn('foreign_id', $record)->eraseAll();
    pjCategoryModel::factory()->whereIn('id', $record)->eraseAll();
    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been deleted.'));
    exit;
	}
	
	public function pjActionGetCategory() {
		$this->setAjax(true);
		if ($this->isXHR()) {
			$pjCategoryModel = pjCategoryModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left');
			if ($q = $this->_get->toString('q')) {
		    $pjCategoryModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->toString('status')) {
		    $status = $this->_get->toString('status');
		    if(in_array($status, array('T', 'F'))) {
		      $pjCategoryModel->where('t1.status', $status);
		    }
			}
			$column = 'order';
			$direction = 'ASC';
			if ($this->_get->toString('column') && 
				in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
		    $column = $this->_get->toString('column');
		    $direction = strtoupper($this->_get->toString('direction'));
			}
			$total = $pjCategoryModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages) {
				$page = $pages;
			}
			$data = $pjCategoryModel
			->select(sprintf("t1.*, t2.content AS name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
			->orderBy("`$column` $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
		
	public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
    	$this->sendForbidden();
      return;
    }
    $this->setLocalesData();
    $this->appendJs('jquery-ui.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
    $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminCategories.js');
	}
	
	public function pjActionCreate() {
    $this->setAjax(true);
    if (!pjAuth::factory()->hasAccess()) {
    	self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    if (!$this->_post->toInt('category_create')) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
    }
    $post = $this->_post->raw();
    if($post['packing_fee'] > 99999999999999.99) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => __('price_err_ARRAY_100', true)));
    }
    $pjCategoryModel = pjCategoryModel::factory();
    $data = array();
    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
    $data['order'] = $post['order_no'];
    $id = $pjCategoryModel->setAttributes(array_merge($post, $data))->insert()->getInsertId();
    if ($id !== false && (int) $id > 0) {
      if (isset($post['i18n'])) {
        pjMultiLangModel::factory()->saveMultiLang($post['i18n'], $id, 'pjCategory', 'data');
      }
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Category has been added!'));
    } else {
        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Category could not be added!'));
    }
    exit;
	}
	
	public function pjActionUpdate() {
    $this->setAjax(true);
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    if (!$this->_post->toInt('category_update')) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
    }
    if (!$this->_post->toInt('id')) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
    }
    $post = $this->_post->raw();
    if($post['packing_fee'] > 99999999999999.99) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('price_err_ARRAY_100', true)));
    }
    $pjCategoryModel = pjCategoryModel::factory();
    $data = array();
    $data['order'] = $post['order_no'];
    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
    $pjCategoryModel->reset()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll(array_merge($post, $data));
    if (isset($post['i18n'])) {
      pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjCategory', 'data');
    }
    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Category has been updated!'));
    exit;
	}
	
	public function pjActionSortCategory() {
		$this->setAjax(true);
		if (!$this->isXHR()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		if (!self::isPost()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}
		$post = $this->_post->raw();
		$pjCategoryModel = pjCategoryModel::factory();
		foreach($post['sort'] as $k => $v) {
	    $pjCategoryModel->reset()->set('id', $v)->modify(array('order' => $k+1));
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been sorted.'));
		exit;
	}
	
	public function pjActionSaveCategory() {
    $this->setAjax(true);
    
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    
    if (!pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    $pjCategoryModel = pjCategoryModel::factory();
    $arr = $pjCategoryModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Category not found.'));
    }
    if (!in_array($this->_post->toString('column'), $pjCategoryModel->getI18n())) {
      $pjCategoryModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
    } else {
      pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjCategory', 'data');
    }
    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Category has been updated.'));
    exit;
	}
	
	public function pjActionCreateForm() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    if (!self::isGet()) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    // !MEGAMIND
    $arr['categories'] = $this->getMainCategories();
    $this->set('arr', $arr);
    $this->setLocalesData();
	}
	public function pjActionUpdateForm() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    if (!self::isGet()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    if ($this->_get->toInt('id')) {
      $id = $this->_get->toInt('id');
      $arr = pjCategoryModel::factory()->find($id)->getData();
      if (count($arr) === 0) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Category is not found.'));
      }
      $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjCategory');
      $arr['categories'] = $this->getMainCategories();
      $this->set('arr', $arr);
      // !MEGAMIND
      $this->setLocalesData();
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
    }
	}

	public function getMainCategories() {
		// !MEGAMIND
    $main_categories_arr = pjCategoryModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
      ->select("t1.*, t2.content AS name")
      ->where("t1.status", 1)
      ->where("t1.category_id ", -1)
      ->orderBy("t1.order ASC")
      ->findAll()
      ->getData();
    $main_categories_arr = array_column($main_categories_arr, 'name', 'id');
   	asort($main_categories_arr);
   	return $main_categories_arr;
	}

  public function pjActionUpload() {
    if (!pjAuth::factory()->hasAccess()) {
    	self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (isset($_FILES['csv_file'])) {
      $tmpName = $_FILES['csv_file']['tmp_name'];
      $catAsArray = array_map('str_getcsv', file($tmpName));
      if ($catAsArray) {
        //Removing the first header element
        unset($catAsArray[0]);
        // echo '<pre>'; print_r($catAsArray);
        foreach ($catAsArray as $key=>$catName) {
          $internalization[1] = array('name'=>$catName[0]);
          $data = array(
            'packing_fee'=> 0.00,
            'order'=> $key,
            'locale'=> 1,
            'i18n'=> $internalization,
          );
          $this->insertCategory($data);
        }
      }
      // self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been uploaded.'));
    }
    pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminCategories&action=pjActionIndex");
    exit;
	}
  public function insertCategory($data) {
    // echo '<pre>'; print_r($data);
    $pjCategoryModel = pjCategoryModel::factory();
    $id = $pjCategoryModel->setAttributes($data)->insert()->getInsertId();
    if ($id !== false && (int) $id > 0) {
      if (isset($data['i18n'])) {
        pjMultiLangModel::factory()->saveMultiLang($data['i18n'], $id, 'pjCategory', 'data');
      }
    } 
  }
}
?>