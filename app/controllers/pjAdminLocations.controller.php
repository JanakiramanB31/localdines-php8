<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminLocations extends pjAdmin
{
	public function pjActionCreate()
	{
	    if ($this->_post->check('location_create'))
	    {
        $data = array();
        $post = $this->_post->raw();
        $id = pjLocationModel::factory(array_merge($post, $data))->insert()->getInsertId();
        if ($id !== false && (int) $id > 0)
        {
          $pjWorkingTimeModel = pjWorkingTimeModel::factory();
          $pjWorkingTimeModel->init($id);
          if (isset($post['data']))
          {
            $pjLocationCoordModel = pjLocationCoordModel::factory();
            foreach ($post['data'] as $type => $coords)
            {
              foreach ($coords as $hash => $d)
              {
                $pjLocationCoordModel->reset()->setAttributes(array(
                  'location_id' => $id,
                  'type' => $type,
                  'hash' => md5($hash),
                  'data' => $d
                ))->insert();
              }
            }
        	}
	        if (isset($post['i18n']))
	        {
	            pjMultiLangModel::factory()->saveMultiLang($post['i18n'], $id, 'pjLocation', 'data');
	        }
        	$err = 'AL03';
     	 		pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminLocations&action=pjActionUpdate&id=$id&err=$err");
      	}
	    } else {
        $this->setLocalesData();
    	  $api_key_str = isset($this->option_arr['o_google_maps_api_key']) && !empty($this->option_arr['o_google_maps_api_key']) ? 'key=' . $this->option_arr['o_google_maps_api_key'] . '&' : '';
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('', 'https://maps.googleapis.com/maps/api/js?'.$api_key_str.'libraries=drawing', TRUE);
				$this->appendJs('pjAdminLocations.js');
	    }
	}
		
	public function pjActionDeleteLocation()
	{
    $this->setAjax(true);
    if ($this->isXHR())
    {
      if (!pjAuth::factory()->hasAccess())
      {
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
      }
      $id = $this->_get->toInt('id');
      if (pjLocationModel::factory()->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1)
      {
        pjMultiLangModel::factory()->where('model', 'pjLocation')->where('foreign_id', $id)->eraseAll();
        pjLocationCoordModel::factory()->where('location_id', $id)->eraseAll();
        pjWorkingTimeModel::factory()->where('location_id', $id)->eraseAll();
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Location has been deleted.'));
      } else {
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Location has not been deleted.'));
      }
    }
    exit;
	}
	
	public function pjActionDeleteLocationBulk()
	{
    $this->setAjax(true);
    if ($this->isXHR())
    {
      if (!pjAuth::factory()->hasAccess())
      {
      	self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
      }
      $record = $this->_post->toArray('record');
      if (count($record))
      {
        pjLocationModel::factory()->whereIn('id', $record)->eraseAll();
        pjMultiLangModel::factory()->where('model', 'pjLocation')->whereIn('foreign_id', $record)->eraseAll();
        pjLocationCoordModel::factory()->whereIn('location_id', $record)->eraseAll();
        pjWorkingTimeModel::factory()->whereIn('location_id', $record)->eraseAll();
        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Location(s) has been deleted.'));
      }
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Location(s) has not been deleted.'));
    }
    exit;
	}
	
	public function pjActionGetLocation()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjLocationModel = pjLocationModel::factory()
			->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
			->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'address'", 'left');
			
			if ($q = $this->_get->toString('q'))
			{
				$pjLocationModel->where("(t2.content LIKE '%$q%' OR t3.content LIKE '%$q%')");
			}
			$column = 'name';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjLocationModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjLocationModel
			->select('t1.*, t2.content AS name, t3.content AS address')
			->orderBy("$column $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
		
	public function pjActionIndex()
	{
	    $this->set('has_access_create', pjAuth::factory('pjAdminLocations', 'pjActionCreate')->hasAccess());
	    $this->set('has_access_update', pjAuth::factory('pjAdminLocations', 'pjActionUpdate')->hasAccess());
	    
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminLocations.js');
	}
	
	public function pjActionSaveLocation()
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
	    
	    $arr = pjLocationModel::factory()->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Location not found.'));
	    }
	    
	    $pjLocationModel = pjLocationModel::factory();
	    if (!in_array($this->_post->toString('column'), $pjLocationModel->getI18n()))
	    {
	        $pjLocationModel->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjLocation', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Location has been updated.'));
	    
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		if (!pjAuth::factory()->hasAccess())
		{
		    $this->sendForbidden();
		    return;
		}
		if (self::isPost() && $this->_post->toInt('location_update') && $this->_post->toInt('id'))
		{
		    $post = $this->_post->raw();
		    pjLocationModel::factory()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll($post);
		    if (isset($post['i18n']))
		    {
		        pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjLocation', 'data');
		    }
		    $pjLocationCoordModel = pjLocationCoordModel::factory();
		    $pjLocationCoordModel->where('location_id', $this->_post->toInt('id'))->eraseAll();
		    if (isset($post['data']))
		    {
		        foreach ($post['data'] as $type => $coords)
		        {
		            foreach ($coords as $hash => $d)
		            {
		                $pjLocationCoordModel->reset()->setAttributes(array(
		                    'location_id' => $post['id'],
		                    'type' => $type,
		                    'hash' => md5($hash),
		                    'data' => $d
		                ))->insert();
		            }
		        }
		    }
		    pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminLocations&action=pjActionUpdate&id=".$post['id']."&err=AL01");
		}
		if (self::isGet() && $this->_get->toInt('id'))
		{
		    $id = $this->_get->toInt('id');
		    $arr = pjLocationModel::factory()->find($id)->getData();
		    if (count($arr) === 0)
		    {
		        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminLocations&action=pjActionIndex&err=AL08");
		    }
		    $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjLocation');
		    $this->set('coord_arr', pjLocationCoordModel::factory()->where('location_id', $id)->findAll()->getData());
		    
		    $this->set('arr', $arr);
		    
		    $api_key_str = isset($this->option_arr['o_google_maps_api_key']) && !empty($this->option_arr['o_google_maps_api_key']) ? 'key=' . $this->option_arr['o_google_maps_api_key'] . '&' : '';
		    $this->setLocalesData();
		    
		    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
		    $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
		    $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
		    $this->appendJs('', 'https://maps.googleapis.com/maps/api/js?'.$api_key_str.'libraries=drawing', TRUE);
		    $this->appendJs('pjAdminLocations.js');
		}
	}
	
	public function pjActionPrice()
	{
		$this->checkLogin();
		if (!pjAuth::factory()->hasAccess())
		{
		    $this->sendForbidden();
		    return;
		}
		if (self::isPost() && $this->_post->toInt('price_update') && $this->_post->toInt('location_id'))
		{
		    $location_id = $this->_post->toInt('location_id');
		    $post = $this->_post->raw();
		    $pjPriceModel = pjPriceModel::factory();
		    $pjPriceModel->where('location_id', $location_id)->eraseAll();
		    if (isset($post['price']) && count($post['price']) > 0)
		    {
		        foreach ($post['price'] as $k => $price)
		        {
		            if ((float) $post['total_from'][$k] >= 0 && (float) $post['total_to'][$k] > 0 && (float) $post['total_from'][$k] <= (float) $post['total_to'][$k])
		            {
		                $pjPriceModel->reset()->setAttributes(array(
		                    'location_id' => $post['location_id'],
		                    'total_from' => $post['total_from'][$k],
		                    'total_to' => $post['total_to'][$k],
		                    'price' => $post['price'][$k]
		                ))->insert();
		            }
		        }
		    }
		    $value = '1|0::0';
		    if($this->_post->check('o_add_tax'))
		    {
		        $value = '1|0::1';
		    }
		    pjOptionModel::factory()
		    ->where('foreign_id', $this->getForeignId())
		    ->where('`key`', 'o_add_tax')
		    ->limit(1)
		    ->modifyAll(array('value' => $value));
		    pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminLocations&action=pjActionPrice&id=".$location_id."&err=AL09");
		}
		if (self::isGet() && $this->_get->toInt('id'))
		{
	    $this->set('arr', pjPriceModel::factory()->where('location_id', $this->_get->toInt('id'))->orderBy("t1.total_from ASC, t1.total_to ASC")->findAll()->getData());
	    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
	    $this->appendJs('pjAdminLocations.js');
		}
	}
	
	public function pjActionGetCoords()
	{
		$this->setAjax(true);
		if ($this->isXHR())
		{
		  $i18n_arr = $this->_post->toI18n('i18n');
		  $data = pjAppController::getCoords($i18n_arr[$this->getLocaleId()]['address'], $this->option_arr);
			if (is_array($data['lat']) && $data['lat'][0] == 'NULL' && is_array($data['lng']) && $data['lng'][0] == 'NULL')
			{
				$data = array();
			}
			pjAppController::jsonResponse($data);
		}
		exit;
	}
}
?>