<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminSpecialInstructions extends pjAdmin
{
	
    // To display special instructions
	public function pjActionIndex()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		$special_instructions = pjSpecialInstructionModel::factory()
		->where('t1.status', 'T')
		->where('t1.type', 'parent')
		->findAll()
		->getData();
		$this->set('special_instructions', $special_instructions);
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
	    $this->appendJs('jasny-bootstrap.min.js',  PJ_THIRD_PARTY_PATH . 'jasny/');
	    $this->appendJs('pjAdminSpecialInstructions.js');
	}

	public function pjActionIndexTypes()
	{
		$this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		$special_instructions = pjSpecialInstructionModel::factory()
		->where('t1.status', 'T')
		->where('t1.type', 'parent')
		->findAll()
		->getData();
		$this->set('special_instructions', $special_instructions);
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
	    $this->appendJs('jasny-bootstrap.min.js',  PJ_THIRD_PARTY_PATH . 'jasny/');
	    $this->appendJs('pjAdminSpecialInstructions.js');
	}

	public function pjActionDeleteSpecialInstruction()
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
		if (!pjSpecialInstructionModel::factory()->set('id', $this->_get->toInt('id'))->erase()->getAffectedRows())
		{
			
		    self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Special Instruction has not been deleted.'));
		} else {
			$children = pjSpecialInstructionModel::factory()->whereIn("parent_id", $this->_get->toInt('id'))->eraseAll();
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction has been deleted'));
		exit;
	}
	
	public function pjActionDeleteSpecialInstructionBulk()
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
	    pjMultiLangModel::factory()->where('model', 'pjSpecialInstruction')->whereIn('foreign_id', $record)->eraseAll();
	    pjSpecialInstructionModel::factory()->whereIn('id', $record)->eraseAll();
		pjSpecialInstructionModel::factory()->whereIn('parent_id', $record)->eraseAll();
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been deleted.'));
	    exit;
	}
	
	public function pjActionGetSpecialInstruction()
	{
		$this->setAjax(true);
	    
	    if ($this->isXHR())
		{
			
			$pjCategoryModel = pjSpecialInstructionModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left');
			if ($q = $this->_get->toString('q'))
			{
			    $pjCategoryModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('T', 'F')))
			    {
			        $pjCategoryModel->where('t1.status', $status);
			    }
			}
			$column = 'parent_id';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}
			$total = $pjCategoryModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjCategoryModel
			->select("t1.*, t2.content AS instruction")
			->where("t1.status", "T")
			->where("t1.type", "child")
			->orderBy("`$column` $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}

	public function pjActionGetSpecialInstructionType()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjCategoryModel = pjSpecialInstructionModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left');
			if ($q = $this->_get->toString('q'))
			{
			    $pjCategoryModel->where("(t2.content LIKE '%$q%')");
			}
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('T', 'F')))
			    {
			        $pjCategoryModel->where('t1.status', $status);
			    }
			}
			$column = 'order';
			$direction = 'ASC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}
			$total = $pjCategoryModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjCategoryModel
			->select("t1.*, t2.content AS special_instruction_type")
			->where("t1.status", "T")
			->where("t1.type", "parent")
			->orderBy("`$column` $direction")
			->limit($rowCount, $offset)
			->findAll()
			->getData();
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
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
	    if (!$this->_post->toInt('special_instruction_create'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
	    
	    $pjSpecialInstructionModel = pjSpecialInstructionModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
	    $data['order'] = $pjSpecialInstructionModel->getLastOrder();
		$data['parent_id'] = $post['special_instruction_type'];
		$data['type'] = 'child';
		$id = $pjSpecialInstructionModel->setAttributes(array_merge($post, $data))->insert()->getInsertId();

		if ($id !== false && (int) $id > 0 && isset($_FILES['image']))
		{
			if($_FILES['image']['error'] == 0)
			{
				
				if(getimagesize($_FILES['image']["tmp_name"]) != false)
				{
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200)
					{
						
						$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
						if ($Image->load($_FILES['image']))
						{
							$resp = $Image->isConvertPossible();
							if ($resp['status'] === true)
							{
								$hash = md5(uniqid(rand(), true));
								$image_path = PJ_UPLOAD_PATH . 'special_instructions/' . $id . '_' . $hash . '.' . $Image->getExtension();
								
								$Image->loadImage($_FILES['image']["tmp_name"]);
								$Image->resizeSmart(270, 220);
								$Image->saveImage($image_path);
								
								$pjSpecialInstructionModel->reset()->where('id', $id)->limit(1)->modifyAll(array('image'=>$image_path));
								
							}
						}
					}
				}else{
					$err = 'AP09';
				}
			}else if($_FILES['image']['error'] != 4){
				$err = 'AP09';
			}
		}
		
	    if ($id !== false && (int) $id > 0)
	    {
	        if (isset($post['i18n']))
	        {
				$post['i18n'][1]['name'] = ucfirst($post['i18n'][1]['name']);
	            pjMultiLangModel::factory()->saveMultiLang($post['i18n'], $id, 'pjSpecialInstruction', 'data');
	        }
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction has been added!'));
	    } else {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Special Instruction could not be added!'));
	    }
	    exit;
	}

	public function pjActionCreateType()
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
	    if (!$this->_post->toInt('special_instruction_create_type'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
	    
	    $pjSpecialInstructionModel = pjSpecialInstructionModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
	    $data['order'] = $pjSpecialInstructionModel->getLastOrder();
		$data['parent_id'] = NULL;
		$data['type'] = 'parent';
		$id = $pjSpecialInstructionModel->setAttributes(array_merge($post, $data))->insert()->getInsertId();

	    if ($id !== false && (int) $id > 0)
	    {
	        if (isset($post['i18n']))
	        {
				$post['i18n'][1]['name'] = ucfirst($post['i18n'][1]['name']);
	            pjMultiLangModel::factory()->saveMultiLang($post['i18n'], $id, 'pjSpecialInstruction', 'data');
	        }
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction has been added!'));
	    } else {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Special Instruction could not be added!'));
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
	    if (!$this->_post->toInt('category_update'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    if (!$this->_post->toInt('id'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
	    $pjSpecialInstructionModel = pjSpecialInstructionModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
		$data['parent_id'] = $post['special_instruction_type'];
	    $pjSpecialInstructionModel->reset()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll(array_merge($post, $data));
		$id = $this->_post->toInt('id');
	    $arr = $pjSpecialInstructionModel->find($id)->getData();
		if ($id > 0 && isset($_FILES['image']))
		{
			if($_FILES['image']['error'] == 0)
			{
				if(getimagesize($_FILES['image']["tmp_name"]) != false)
				{
					if(!empty($arr['image']))
					{
						@unlink(PJ_INSTALL_PATH . $arr['image']);
					}
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200)
					{
						$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
						if ($Image->load($_FILES['image']))
						{
							$resp = $Image->isConvertPossible();
							if ($resp['status'] === true)
							{
								$hash = md5(uniqid(rand(), true));
								$image_path = PJ_UPLOAD_PATH . 'special_instructions/' . $id . '_' . $hash . '.' . $Image->getExtension();
								
								$Image->loadImage($_FILES['image']["tmp_name"]);
								$Image->resizeSmart(270, 220);
								$Image->saveImage($image_path);
								$data['image'] = $image_path;
								$pjSpecialInstructionModel->reset()->where('id', $id)->limit(1)->modifyAll(array_merge($post, $data));
							}
						}
					}
				}else{
					$err = 'AP09';
				}
			}else if($_FILES['image']['error'] != 4){
				$err = 'AP09';
			}
		}
		
	    if (isset($post['i18n']))
	    {
			$post['i18n'][1]['name'] = ucfirst($post['i18n'][1]['name']);
	        pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjSpecialInstruction', 'data');
	    }
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction has been updated!'));
	    exit;
	}

	public function pjActionUpdateType()
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
	    if (!$this->_post->toInt('category_update'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    if (!$this->_post->toInt('id'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $post = $this->_post->raw();
	    $pjSpecialInstructionModel = pjSpecialInstructionModel::factory();
	    $data = array();
	    $data['status'] = $this->_post->check('status') ? 'T' : 'F';
	    $pjSpecialInstructionModel->reset()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll(array_merge($post, $data));
		$id = $this->_post->toInt('id');
	    $arr = $pjSpecialInstructionModel->find($id)->getData();
		
	    if (isset($post['i18n']))
	    {
			$post['i18n'][1]['name'] = ucfirst($post['i18n'][1]['name']);
	        pjMultiLangModel::factory()->updateMultiLang($post['i18n'], $post['id'], 'pjSpecialInstruction', 'data');
	    }
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction has been updated!'));
	    exit;
	}
	
	public function pjActionSortSpecialInstruction()
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
		$post = $this->_post->raw();
		$pjCategoryModel = pjCategoryModel::factory();
		foreach($post['sort'] as $k => $v)
		{
		    $pjCategoryModel->reset()->set('id', $v)->modify(array('order' => $k+1));
		}
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Categories has been sorted.'));
		exit;
	}
	
	public function pjActionSaveSpecialInstruction()
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
	    $pjCategoryModel = pjCategoryModel::factory();
	    $arr = $pjCategoryModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Location not found.'));
	    }
	    if (!in_array($this->_post->toString('column'), $pjCategoryModel->getI18n()))
	    {
	        $pjCategoryModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjCategory', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Category has been updated.'));
	    
	    exit;
	}

	public function pjActionSaveSpecialInstructionType()
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
	    $pjSpecialInstructionModel = pjSpecialInstructionModel::factory();
	    $arr = $pjSpecialInstructionModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Location not found.'));
	    }
	    if (!in_array($this->_post->toString('column'), $pjSpecialInstructionModel->getI18n()))
	    {
	        $pjSpecialInstructionModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    } else {
	        pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($this->_post->toString('column') => $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjSpecialInstruction', 'data');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Special Instruction has been updated.'));
	    
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
		$special_instruction_types = pjSpecialInstructionModel::factory()
		->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
		->select("t1.*, t2.content AS type")
		->where('t1.status', 'T')
		->where('t1.type', 'parent')
		->findAll()
		->getData();
		$this->set('special_instruction_types', $special_instruction_types);
	    $this->setLocalesData();
	}
	public function pjActionCreateTypeForm()
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
	        $arr = pjSpecialInstructionModel::factory()->find($id)->getData();
	        if (count($arr) === 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Special Instruction is not found.'));
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjSpecialInstruction');
	        $this->set('arr', $arr);
	        $special_instruction_types = pjSpecialInstructionModel::factory()
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjSpecialInstruction' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->select("t1.*, t2.content AS type")
			->where('t1.status', 'T')
			->where('t1.type', 'parent')
			->findAll()
			->getData();
			$this->set('special_instruction_types', $special_instruction_types);
	        $this->setLocalesData();
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
	    }
	}

	public function pjActionUpdateTypeForm()
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
	        $arr = pjSpecialInstructionModel::factory()->find($id)->getData();
	        if (count($arr) === 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Special Instruction is not found.'));
	        }
	        $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjSpecialInstruction');
	        $this->set('arr', $arr);
	        
	        $this->setLocalesData();
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing parameters.'));
	    }
	}

	public function pjActionDeleteImage()
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
		$id = $this->_get->toInt('id');
		$pjProductModel = pjSpecialInstructionModel::factory();
		$arr = $pjProductModel->find($id)->getData(); 
		if(empty($arr))
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Special Instruction not found.'));
		}
		if(!empty($arr['image']))
		{
		    @unlink(PJ_INSTALL_PATH . $arr['image']);
		}
		$data = array();
		$data['image'] = ':NULL';
		$pjProductModel->reset()->where(array('id' => $id))->limit(1)->modifyAll($data);
		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Special Instruction image has been deleted.'));
	}
}
?>