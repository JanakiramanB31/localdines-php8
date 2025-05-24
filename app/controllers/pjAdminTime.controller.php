<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminTime extends pjAdmin
{
	public function pjActionIndex()
	{
	    $this->checkLogin();
	    
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isPost() && $this->_post->toInt('working_time') && $this->_post->toInt('location_id'))
	    {
	        $location_id = $this->_post->toInt('location_id');
	        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
	        $arr = $pjWorkingTimeModel->find($location_id)->getData();
	        $data = array();
	        $data['location_id'] = $location_id;
	        $types = array('p_' => 'pickup', 'd_' => 'delivery');
	        $weekDays = pjUtil::getWeekdays();
	        $post = $this->_post->raw();
	        foreach ($types as $prefix => $type)
	        {
	            foreach ($weekDays as $day)
	            {
	                if (!isset($post[$prefix . $day . '_dayoff']))
	                {
	                    $data[$prefix . $day . '_dayoff'] = "F";
	                    $data[$prefix . $day . '_from'] = date('H:i', strtotime($post[$prefix . $day . '_from']));
	                    $data[$prefix . $day . '_to'] = date('H:i', strtotime($post[$prefix . $day . '_to']));
	                }else{
	                    $data[$prefix . $day . '_dayoff'] = "T";
	                    $data[$prefix . $day . '_from'] = ":NULL";
	                    $data[$prefix . $day . '_to'] = ":NULL";
	                }
	            }
	        }
	        if (count($arr) > 0)
	        {
	            $pjWorkingTimeModel->reset()->setAttributes(array('location_id' => $location_id))->erase();
	        }
	        $pjWorkingTimeModel->reset()->setAttributes($data)->insert();
	        
	        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionIndex&id=".$location_id."&err=AT01");
	    }
	    if (self::isGet() && $this->_get->toInt('id'))
	    {
	        $arr = pjLocationModel::factory()->find($this->_get->toInt('id'))->getData();
	        if (count($arr) === 0)
	        {
	            pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminLocations&action=pjActionIndex&err=AL08");
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjLocation');
	        $this->set('arr', $arr);
	        $this->set('wt_arr', pjWorkingTimeModel::factory()->find($this->_get->toInt('id'))->getData());
	        
	        $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
	        $this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
	        $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	        $this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	        $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	        $this->appendJs('pjAdminTime.js');
	    }
	}
	public function pjActionCheckDefaultTime()
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
	    $classes = array();
	    $post = $this->_post->raw();
	    $types = array('p_' => 'pickup', 'd_' => 'delivery');
	    $weekDays = pjUtil::getWeekdays();
	    foreach ($types as $prefix => $type)
	    {
	        foreach ($weekDays as $day)
	        {
	            if (!isset($post[$prefix . $day . '_dayoff']))
	            {
	                if(strtotime($post[$prefix . $day . '_from']) >= strtotime($post[$prefix . $day . '_to']))
	                {
	                    $classes[] = $type . 'WorkingDay_' . $day;
	                }
	            }
	        }
	    }
	    if(!empty($classes))
	    {
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }else{
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	}
	public function pjActionCheckCustomTime()
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
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	}
	public function pjActionSaveCustom()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (self::isPost() && $this->_post->toInt('custom_time') && $this->_post->toInt('location_id'))
	        {
	            $date = pjDateTime::formatDate($this->_post->toString('date'), $this->option_arr['o_date_format']);
	            $pjDateModel = pjDateModel::factory();
	            $pjDateModel->where('location_id', $this->_post->toInt('location_id'))->where('type', $this->_post->toString('type'))->where('date', $date)->eraseAll();
	            if($this->_post->toInt('id'))
	            {
	                $pjDateModel->reset()->setAttributes(array('id' => $this->_post->toInt('id')))->erase();
	            }
	            $data = array();
	            $data['location_id'] = $this->_post->toInt('location_id');
	            $data['start_time'] = date('H:i', strtotime($this->_post->toString('start')));
	            $data['end_time'] = date('H:i', strtotime($this->_post->toString('end')));
	            if(!$this->_post->check('is_dayoff'))
	            {
	                $data['is_dayoff'] = 'F';
	            }else{
	                $data['is_dayoff'] = 'T';
	            }
	            $data['date'] = $date;
	            $data['type'] = $this->_post->toString('type');
	            $pjDateModel->reset()->setAttributes($data)->insert();
	            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'location_id' => $this->_post->toInt('location_id')));
	        }
	    }
	    exit;
	}
	public function pjActionResetForm()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (self::isPost() && $this->_post->toInt('location_id'))
	        {
	            
	        }
	    }
	}
	public function pjActionGetCustomDate()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (self::isGet() && $this->_get->toInt('id'))
	        {
	            $arr = pjDateModel::factory()->find($this->_get->toInt('id'))->getData();
	            $this->set('arr', $arr);
	        }
	    }
	}
	public function pjActionGetDate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjDateModel = pjDateModel::factory();
			
			$column = 'date';
			$direction = 'DESC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}
			if ($this->_get->toInt('location_id'))
			{
			    $pjDateModel->where('location_id', $this->_get->toInt('location_id'));
			}

			$total = $pjDateModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
			    $page = $pages;
			}
			$data = array();
			$data = $pjDateModel->select('t1.*')
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
				
			$yesno = __('_yesno', true, false);
			$types = __('types', true, false);
			foreach($data as $k => $v){
			    if(!empty($v['start_time']) && !empty($v['end_time']))
				{
					$v['start_time'] = date($this->option_arr['o_time_format'], strtotime($v['start_time']));
					$v['end_time'] = date($this->option_arr['o_time_format'], strtotime($v['end_time']));
				}
				$v['is_dayoff'] = $yesno[$v['is_dayoff']];
				$v['type'] = $types[$v['type']];
				$data[$k] = $v;
			}
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionDeleteDate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if (pjDateModel::factory()->setAttributes(array('id' => $this->_get->toInt('id')))->erase()->getAffectedRows() == 1)
			{
			    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Date has not been deleted.'));
			} else {
			    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Date has not been deleted.'));
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteDateBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    $record = $this->_post->toArray('record');
		    if (count($record))
		    {
		        pjDateModel::factory()->whereIn('id', $record)->eraseAll();
		        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Date(s) has been deleted.'));
			}
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Date(s) has not been deleted.'));
		}
		exit;
	}
}
?>