<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminExtras extends pjAdmin
{
    public function pjActionDeleteExtra()
    {
        $this->setAjax(true);
        
        if (!$this->isXHR())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
        }
        if (!self::isPost())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
        }
        if (!pjAuth::factory()->hasAccess())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
        }
        if (!($this->_get->toInt('id')))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
        }
        if (!pjExtraModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Extra has not been deleted.'));
        }
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Extra has been deleted'));
        exit;
    }
    
    public function pjActionDeleteExtraBulk()
    {
        $this->setAjax(true);
        if (!$this->isXHR())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
        }
        if (!self::isPost())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
        }
        if (!pjAuth::factory()->hasAccess())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
        }
        if (!$this->_post->has('record'))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
        }
        $record = $this->_post->toArray('record');
        if (empty($record))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
        }
        pjMultiLangModel::factory()->where('model', 'pjExtra')->whereIn('foreign_id', $record)->eraseAll();
        pjExtraModel::factory()->whereIn('id', $record)->eraseAll();
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Extra(s) has been deleted.'));
        exit;
    }
	
	public function pjActionGetExtra()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjExtraModel = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left');
			if ($q = $this->_get->toString('q'))
			{
			    $pjExtraModel->where("(t2.content LIKE '%$q%')");
			}
			$column = 'name';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjExtraModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			
			$data = $pjExtraModel
			->select(sprintf("t1.*, t2.content AS name, (SELECT COUNT(t3.product_id) FROM `%s` AS t3 WHERE t3.extra_id=t1.id) as products", pjProductExtraModel::factory()->getTable()))
			->orderBy("$column $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			foreach($data as $k => $v)
			{
			    $v['price'] = pjCurrency::formatPrice($v['price']);
				$data[$k] = $v;
			}
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
		
	public function pjActionIndex()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
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
	    $this->appendJs('pjAdminExtras.js');
	}
	
	public function pjActionCreate()
	{
	    $this->setAjax(true);
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!$this->_post->toInt('extra_create'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
			print_r($post);
			exit;
	    if($post['price'] > 99999999999999.99)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('price_err_ARRAY_100', true)));
	    }
	    $pjExtraModel = pjExtraModel::factory();
	    $id = $pjExtraModel->setAttributes($post)->insert()->getInsertId();
	    if ($id !== false && (int) $id > 0)
	    {
	        if (isset($post['i18n']))
	        {
	            pjMultiLangModel::factory()->saveMultiLang($post['i18n'], $id, 'pjExtra', 'data');
	        }
	        $pjProductExtraModel = pjProductExtraModel::factory();
	        if (isset($post['product_id']) && is_array($post['product_id']) && count($post['product_id']) > 0)
	        {
	          $productsIds = $this->getUniqueProductIDS($post);  
						$pjProductExtraModel->begin();
	            // foreach ($post['product_id'] as $product_id)
	            foreach ($productsIds as $product_id)
	            {
	                $pjProductExtraModel
	                ->reset()
	                ->set('product_id', $product_id)
	                ->set('extra_id', $id)
	                ->insert();
	            }
	            $pjProductExtraModel->commit();
	        }
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Extra has been added!'));
	    } else {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Extra could not be added!'));
	    }
	    exit;
	}
	
	public function pjActionUpdate()
	{
	    $this->setAjax(true);
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!$this->_post->toInt('extra_update'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    if (!$this->_post->toInt('id'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
	    if($post['price'] > 99999999999999.99)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('price_err_ARRAY_100', true)));
	    }
	    $id = $this->_post->toInt('id');
	    $pjExtraModel = pjExtraModel::factory();
	    $pjExtraModel->reset()->where('id', $id)->limit(1)->modifyAll($post);
	    if (isset($post['i18n']))
	    {
	        pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjExtra', 'data');
	    }
	    $pjProductExtraModel = pjProductExtraModel::factory();
	    $pjProductExtraModel->where('extra_id', $id)->eraseAll();
	    if (isset($post['product_id']) && is_array($post['product_id']) && count($post['product_id']) > 0)
	    {
	      //Fetching the records matching both eatin and takeaway
				$productsIds = $this->getUniqueProductIDS($post);
				$pjProductExtraModel->begin();
	        // foreach ($post['product_id'] as $product_id)
	        foreach ($productsIds as $product_id)
	        {
	            $pjProductExtraModel
	            ->reset()
	            ->set('product_id',$product_id)
	            ->set('extra_id', $id)
	            ->insert();
	        }
	        $pjProductExtraModel->commit();
	    }
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Category has been updated!'));
	    exit;
	}

	public function getUniqueProductIDS($post) {
		$product_arr = pjProductModel::factory()
				->select("t1.id, t1.parent_id")
				->whereIn('t1.id', $post['product_id'])
				->orwhereIn('t1.parent_id', $post['product_id'])
				->findAll()->getData();
				// print_r($product_arr);
				$ids = array_column($product_arr, 'id');
				$parent_ids = array_column($product_arr, 'parent_id');
				// Merge the two arrays and remove duplicates
				$unique_values = array_unique(array_merge($ids, $parent_ids));
				$unique_values = array_filter($unique_values, function($value) {
					return $value != 0;
				});
				return $productsIds = array_values($unique_values);
	}
	
	public function pjActionSaveExtra()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    
	    if (!pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    $pjExtraModel = pjExtraModel::factory();
	    $arr = $pjExtraModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Extra is not found.'));
	    }
	    if (!in_array($this->_post->toString('column'), $pjExtraModel->getI18n()))
	    {
	        $pjExtraModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjExtra', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Category has been updated.'));
	    
	    exit;
	}
	
	public function pjActionCreateForm()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    $this->setLocalesData();
	    
	    $product_arr = pjProductModel::factory()
	    ->select("t1.*, t2.content as name")
	    ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	    ->findAll()->getData();
	    $this->set('product_arr', $product_arr);
	}
	public function pjActionUpdateForm()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if ($this->_get->toInt('id'))
	    {
	        $id = $this->_get->toInt('id');
	        $arr = pjExtraModel::factory()->find($id)->getData();
	        if (count($arr) === 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Extra is not found.'));
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjExtra');
	        $this->set('arr', $arr);
	        
	        $this->setLocalesData();
	        
	        $product_arr = pjProductModel::factory()
	        ->select("t1.*, t2.content as name")
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->findAll()->getData();
	        $this->set('product_arr', $product_arr);
	        
	        $extra_product_id_arr = pjProductExtraModel::factory()->where('extra_id', $id)->findAll()->getDataPair(NULL, 'product_id');
	        $this->set('extra_product_id_arr', $extra_product_id_arr);
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
	    }
	}

	public function pjActionUpload() {
    if (!pjAuth::factory()->hasAccess()) {
    	self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (isset($_FILES['csv_file'])) {
      $tmpName = $_FILES['csv_file']['tmp_name'];
      $extraAsArray = array_map('str_getcsv', file($tmpName));
      if ($extraAsArray) {
        //Removing the first header element
        unset($extraAsArray[0]);
        echo '<pre>'; print_r($extraAsArray);
				// exit;
				// print_r(EXTRA_CATEGORY_TYPES);
        foreach ($extraAsArray as $key=>$extraName) {
          $internalization[1] = array('name'=>$extraName[1]);
					$category_id = array_search($extraName[0], EXTRA_CATEGORY_TYPES) + 1;
          $data = array(
            'price'=> 0.00,
            'category_id'=> $category_id,
            'locale'=> 1,
            'i18n'=> $internalization,
          );
					print_r($data);
          $this->insertExtra($data);
        }
      }
      // self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been uploaded.'));
    }
    pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminExtras&action=pjActionIndex");
    exit;
	}
  public function insertExtra($data) {
    // echo '<pre>'; print_r($data);
    $pjExtraModel = pjExtraModel::factory();
    $id = $pjExtraModel->setAttributes($data)->insert()->getInsertId();
    if ($id !== false && (int) $id > 0) {
      if (isset($data['i18n'])) {
        pjMultiLangModel::factory()->saveMultiLang($data['i18n'], $id, 'pjExtra', 'data');
      }
    } 
  }

	public function insertExtraIDForProducts() {
		$pjProductExtraModel = pjProductExtraModel::factory();
		for ($product_id = 251; $product_id <= 258; $product_id++) {
			$pjProductExtraModel->begin();
			for ($extra_id = 1; $extra_id <= 27; $extra_id++) {
					$pjProductExtraModel
						->reset()
						->set('product_id', $product_id)
						->set('extra_id', $extra_id)
						->insert();
			}
			$pjProductExtraModel->commit();
		}
		echo "Manual execution";
		exit;
	}
}
?>