<?php 
if ( !defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}

class pjAdminProducts extends pjAdmin {
  public function pjActionCheckPrices() {
    $this->setAjax(true);
    $post =$this->_post->raw();
    if (isset($post['set_different_sizes']) && $post['set_different_sizes']=='T') {
      if (isset($post['index_arr']) && $post['index_arr'] !='') {
        $index_arr =explode("|", $post['index_arr']);
        foreach ($index_arr as $k => $v) {
          if (strpos($v, 'fd') !==false) {
            if ($post['product_price'][$v] > 99999999999999.99) {
              self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> __('price_err_ARRAY_100', true)));
            }
          }
        }
      }
    } else {
      if ($post['price'] > 99999999999999.99) {
        self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> __('price_err_ARRAY_100', true)));
      }
    }
    self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> ''));
  }

  public function pjActionCheckProductType() {
    $this->setAjax(true);
    if ( !$this->isXHR()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'Missing headers.'));
    }

    if ( !self::isPost()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'HTTP method not allowed.'));
    }
    $post = $this->_post->raw();
    if ($post) {
      $categories = pjCategoryModel::factory()
        ->whereIn('id', $post['cat_array'])
        ->select('id, product_type')
        ->groupBy('product_type')
        ->findAll()->getData();
      $product_types = array_column($categories, 'product_type');
      if (in_array("none", $product_types) && count($product_types) == 1) {
        self::jsonResponse(array('status'=> true, 'code'=> 200, 'text'=> 'null'));
      } elseif (in_array("both", $product_types) || in_array("non-veg", $product_types)) {
        self::jsonResponse(array('status'=> false, 'code'=> 200, 'text'=> false));
      } else {
        self::jsonResponse(array('status'=> false, 'code'=> 200, 'text'=> true));
      }
      exit;  
    } else {
      self::jsonResponse(array('status'=> false, 'code'=> 200, 'text'=> true));
    }
  }

  public function pjActionCreate() {
    $post_max_size = pjUtil::getPostMaxSize();

    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionIndex&err=AP05");
    }

    $this->checkLogin();

    if ( !pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }

    if (self::isPost() && $this->_post->toInt('product_create')) {
      $pjProductModel =pjProductModel::factory();
      $data =array();
      $post =$this->_post->raw();

      if ($this->_post->check('is_featured')) {
        $data['is_featured'] = 1;
      } else {
        $data['is_featured'] = 0;
      }

      if ($this->_post->check('is_kitchen')) {
        $data['is_kitchen'] = 1;
      } else {
        $data['is_kitchen'] = 0;
      }

      if ($this->_post->check('is_web_orderable')) {
        $data['is_web_orderable'] = 1;
      } else {
        $data['is_web_orderable'] = 0;
      }

      if ($this->_post->check('is_veg')) {
        $data['is_veg'] = 1;
      } else {
        $data['is_veg'] = 0;
      }

      if ($this->_post->check('is_vat')) {
        $data['is_vat'] = 1;
      } else {
        $data['is_vat'] = 0;
      }

      if ($this->_post->check('set_different_sizes')) {
        $data['set_different_sizes']="T";
      } else {
        $data['set_different_sizes']="F";
      }

      $data['counter_number']  = $post['counter_number'];

      $data['order']=$pjProductModel->getLastOrder();
      echo '<pre>'; print_r($data);
      echo '<pre>'; print_r($post);
      exit;
      $id = $pjProductModel->setAttributes(array_merge($post, $data))->insert()->getInsertId();

      if ($id  !== false && (int)$id > 0) {
        $err ='AP03';
        $pjMultiLangModel =pjMultiLangModel::factory();
        $pjProductPriceModel =pjProductPriceModel::factory();

        if (isset($post['i18n'])) {
          $pjMultiLangModel->saveMultiLang($post['i18n'], $id, 'pjProduct', 'data');

          if ($post['set_different_sizes'] == 'T') {
            if (isset($post['index_arr']) && $post['index_arr'] !='') {
              $index_arr =explode("|", $post['index_arr']);

              foreach ($index_arr as $k => $v) {
                if (strpos($v, 'fd') !== false) {
                  $p_data =array();
                  $p_data['product_id'] = $id;
                  $p_data['price'] = $post['product_price'][$v];
                  $price_id = $pjProductPriceModel->reset()->setAttributes($p_data)->insert()->getInsertId();

                  if ($price_id  !== false && (int)$price_id > 0) {
                    foreach ($post['i18n'] as $locale => $locale_arr) {
                      foreach ($locale_arr as $field => $content) {
                        if (is_array($content)) {
                          $insert_id =$pjMultiLangModel->reset()->setAttributes(array('foreign_id'=> $price_id, 'model'=> 'pjProductPrice', 'locale'=> $locale, 'field'=> $field, 'content'=> $content[$v], 'source'=> 'data'))->insert()->getInsertId();
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }

        if (isset($_FILES['image'])) {
          if ($_FILES['image']['error'] == 0) {
            if (getimagesize($_FILES['image']["tmp_name"]) !=false) {
              $Image = new pjImage();

              if ($Image->getErrorCode() !== 200) {
                $Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));

                if ($Image->load($_FILES['image'])) {
                  $resp =$Image->isConvertPossible();

                  if ($resp['status'] === true) {
                    $hash =md5(uniqid(rand(), true));
                    $image_path =PJ_UPLOAD_PATH . 'products/' . $id . '_' . $hash . '.' . $Image->getExtension();
                    $Image->loadImage($_FILES['image']["tmp_name"]);
                    $Image->resizeSmart(270, 220);
                    $Image->saveImage($image_path);
                    $pjProductModel->reset()->where('id', $id)->limit(1)->modifyAll(array('image'=> $image_path));
                  }
                }
              }
            } else {
              $err ='AP09';
            }
          } else if ($_FILES['image']['error'] !=4) {
            $err ='AP09';
          }
        }

        $pjProductCategoryModel =pjProductCategoryModel::factory();

        if (isset($post['category_id']) && is_array($post['category_id']) && count($post['category_id']) > 0) {
          $pjProductCategoryModel->begin();

          foreach ($post['category_id'] as $category_id) {
            $pjProductCategoryModel->reset()->set('product_id', $id)->set('category_id', $category_id)->insert();
          }

          $pjProductCategoryModel->commit();
        }

        $pjProductExtraModel =pjProductExtraModel::factory();

        if (isset($post['extra_id']) && is_array($post['extra_id']) && count($post['extra_id']) > 0) {
          $pjProductExtraModel->begin();

          foreach ($post['extra_id'] as $extra_id) {
            $pjProductExtraModel->reset()->set('product_id', $id)->set('extra_id', $extra_id)->insert();
          }

          $pjProductExtraModel->commit();
        }

        if ($err  !='AP03') {
          pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionUpdate&id=$id&err=AP09");
        }
      } else {
        $err ='AP04';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionIndex&err=$err");
    }

    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('category_arr', pjCategoryModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjCategory' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->where('t1.status', 'T')->orderBy('`order` ASC')->findAll()->getData());
      $this->set('extra_arr', pjExtraModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->orderBy('name ASC')->findAll()->getData());
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('jasny-bootstrap.min.js', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('pjAdminProducts.js');
    }
  }

  public function pjActionDeleteProduct() {
    $this->setAjax(true);

    if ( !$this->isXHR()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'Missing headers.'));
    }

    if ( !self::isPost()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'HTTP method not allowed.'));
    }

    if ( !pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 102, 'text'=> 'Access denied.'));
    }

    if ( !($this->_get->toInt('id'))) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 103, 'text'=> 'Missing, empty or invalid parameters.'));
    }

    $pjProductModel =pjProductModel::factory();
    $arr =$pjProductModel->find($this->_get->toInt('id'))->getData();

    if ( !$arr) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 103, 'text'=> 'Product not found.'));
    }

    $id =$this->_get->toInt('id');

    if ($pjProductModel->setAttributes(array('id'=> $id))->erase()->getAffectedRows()==1) {
      if (file_exists(PJ_INSTALL_PATH . $arr['image'])) {
        @unlink(PJ_INSTALL_PATH . $arr['image']);
      }

      pjMultiLangModel::factory()->where('model', 'pjProduct')->where('foreign_id', $id)->eraseAll();
      pjProductCategoryModel::factory()->where('product_id', $id)->eraseAll();
      pjProductExtraModel::factory()->where('product_id', $id)->eraseAll();
      pjProductPriceModel::factory()->where('product_id', $id)->eraseAll();
      self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> 'Product has been deleted'));
    }

    else {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 105, 'text'=> 'Product has not been deleted.'));
    }

    exit;
  }

  public function pjActionDeleteProductBulk() {
    $this->setAjax(true);

    if ( !$this->isXHR()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'Missing headers.'));
    }

    if ( !self::isPost()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'HTTP method not allowed.'));
    }

    if ( !pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 102, 'text'=> 'Access denied.'));
    }

    if ( !$this->_post->has('record')) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 103, 'text'=> 'Missing, empty or invalid parameters.'));
    }

    $record =$this->_post->toArray('record');

    if (empty($record)) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 104, 'text'=> 'Missing, empty or invalid parameters.'));
    }

    $pjProductModel =pjProductModel::factory();
    $arr =$pjProductModel->whereIn('id', $record)->findAll()->getData();

    foreach ($arr as $v) {
      if (file_exists(PJ_INSTALL_PATH . $v['image'])) {
        @unlink(PJ_INSTALL_PATH . $v['image']);
      }
    }

    $pjProductModel->reset()->whereIn('id', $record)->eraseAll();
    pjMultiLangModel::factory()->where('model', 'pjProduct')->whereIn('foreign_id', $record)->eraseAll();
    pjProductCategoryModel::factory()->whereIn('product_id', $record)->eraseAll();
    pjProductExtraModel::factory()->whereIn('product_id', $record)->eraseAll();
    pjProductPriceModel::factory()->whereIn('product_id', $record)->eraseAll();
    self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> 'Product(s) has been deleted.'));
    exit;
  }

  public function pjActionGetProduct() {
    $this->setAjax(true);

    if ($this->isXHR()) {
      $pjProductModel = pjProductModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left');

      if ($this->_get->toString('status') != '') {
        $status = $this->_get->toString('status');
        if (in_array($status, array(1, 0))) {
          $pjProductModel->where('t1.status', $status);
        }
      }

      if ($this->_get->toString('order_type') != '') {
        $orderType = $this->_get->toString('order_type');
        $pjProductModel->where('t1.order_type', $orderType);
      } else if (ORDER_TYPE) { 
        $pjProductModel->where('t1.order_type', 'eatin');
      }

      if ($q = trim($this->_get->toString('q'))) {
        $pjProductModel->where("(t2.content LIKE '%$q%')");
      }

      // if ($status = $this->_get->toString('status')) {
      //   $status = strtolower($status);
      //   switch($status) {
      //     case 'active':
      //       $pjProductModel->where('t1.status', 1);
      //     break;
      //     case 'inactive':
      //       $pjProductModel->where('t1.status', 0);
      //     break;
      //   }
      // }
      if ($category_id =$this->_get->toInt('category_id')) {
        $pjProductModel->where("(t1.id IN (SELECT TPC.product_id FROM `" . pjProductCategoryModel::factory()->getTable() . "` AS TPC WHERE TPC.category_id='" . $category_id . "'))");
      }

      $column ='is_featured';
      $direction ='DESC';

      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column =$this->_get->toString('column');
        $direction =strtoupper($this->_get->toString('direction'));
      }

      $total =$pjProductModel->findCount()->getData();
      $rowCount =$this->_get->toInt('rowCount') ? : 10;
      $pages =ceil($total / $rowCount);
      $page =$this->_get->toInt('page') ? : 1;
      $offset =((int)$page - 1) * $rowCount;

      if ($page > $pages) {
        $page =$pages;
      }

      $pjProductPriceModel =pjProductPriceModel::factory();
      $data =$pjProductModel->select("t1.*, t2.content AS name")->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();

      foreach ($data as $k => $v) {
        if ($v['set_different_sizes']=='T') {
          $_arr =$pjProductPriceModel->reset()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'price_name'", 'left')->select('t1.*, t2.content as price_name')->where('product_id', $v['id'])->findAll()->getData();
          $price_arr =array();

          foreach ($_arr as $price) {
            $price_arr[]=$price['price_name'] . ': ' . pjCurrency::formatPrice($price['price']);
          }

          $v['price']=join("<br/>", $price_arr);
        }

        else {
          $v['price'] = pjCurrency::formatPrice($v['price']);
        }

        // if ($v['status'] == 1) {
        //     $v['status'] = 'Active';
        // } elseif ($v['status'] == 0) {
        //     $v['status'] = 'Inactive';
        // }

        $data[$k]=$v;
      }

      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }

    exit;

  }

  public function pjActionIndex() {
    $this->checkLogin();

    if ( !pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }

    $categories =pjCategoryModel::factory()
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->where('t1.status', 'T')
      ->where('t1.category_id != ', -1)
      ->select(sprintf("t1.*, t2.content AS name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
      ->findAll()->getData();
    $this->set('categories', $categories);
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendJs('pjAdminProducts.js');
  }

  public function pjActionSaveProduct() {
    $this->setAjax(true);
    $this->setAjax(true);

    if ( !$this->isXHR()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'Missing headers.'));
    }

    if ( !self::isPost()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'HTTP method not allowed.'));
    }

    if ( !pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 102, 'text'=> 'Access denied.'));
    }

    $pjProductModel =pjProductModel::factory();
    $arr =$pjProductModel->find($this->_get->toInt('id'))->getData();

    if ( !$arr) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 103, 'text'=> 'Product not found.'));
    }

    if ( !in_array($this->_post->toString('column'), $pjProductModel->getI18n())) {
      $pjProductModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column')=> $this->_post->toString('value')));
    }

    else {
      pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId()=> array($this->_post->toString('column')=> $this->_post->toString('value'))), $this->_get->toInt('id'), 'pjProduct', 'data');
    }

    self::jsonResponse(array('status'=> 'OK', 'code'=> 201, 'text'=> 'Product has been updated.'));
    exit;
  }

  public function pjActionUpdate() {
    $post_max_size =pjUtil::getPostMaxSize();

    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionIndex&err=AP05");
    }

    $this->checkLogin();

    if ( !pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }

    if (self::isPost() && $this->_post->toInt('product_update')) {
      $data =array();
      $post =$this->_post->raw();

      if ($this->_post->check('is_featured')) {
        $data['is_featured']=1;
      } else {
        $data['is_featured']=0;
      }

      if ($this->_post->check('is_kitchen')) {
        $data['is_kitchen'] = 1;
      } else {
        $data['is_kitchen'] = 0;
      }

      if ($this->_post->check('is_web_orderable')) {
        $data['is_web_orderable'] = 1;
      } else {
        $data['is_web_orderable'] = 0;
      }

      if ($this->_post->check('is_veg')) {
        $data['is_veg'] = 1;
      } else {
        $data['is_veg'] = 0;
      }
      if ($this->_post->check('is_vat')) {
        $data['is_vat'] = 1;
      } else {
        $data['is_vat'] = 0;
      }

      if ($this->_post->check('set_different_sizes')) {
        $data['set_different_sizes']="T";
      } else {
        $data['set_different_sizes']="F";
      }

      
       $data['counter_number']  = $post['counter_number'];
    
      

      $pjProductModel =pjProductModel::factory();
      $err ='AP01';
      $id =$this->_post->toInt('id');
      $arr =$pjProductModel->find($id)->getData();

      if (empty($arr)) {
        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionIndex&err=AP08");
      }

      if (isset($_FILES['image'])) {
        if ($_FILES['image']['error']==0) {
          if (getimagesize($_FILES['image']["tmp_name"]) !=false) {
            if ( !empty($arr['image'])) {
              @unlink(PJ_INSTALL_PATH . $arr['image']);
            }

            $Image =new pjImage();

            if ($Image->getErrorCode() !==200) {
              $Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));

              if ($Image->load($_FILES['image'])) {
                $resp =$Image->isConvertPossible();

                if ($resp['status']===true) {
                  $hash =md5(uniqid(rand(), true));
                  $image_path =PJ_UPLOAD_PATH . 'products/' . $id . '_' . $hash . '.' . $Image->getExtension();
                  $Image->loadImage($_FILES['image']["tmp_name"]);
                  $Image->resizeSmart(270, 220);
                  $Image->saveImage($image_path);
                  $data['image']=$image_path;
                }
              }
            }
          }

          else {
            $err ='AP10';
          }
        } else if ($_FILES['image']['error'] !=4) {
          $err ='AP10';
        }
      }

      $pjProductModel->reset()->where('id', $id)->limit(1)->modifyAll(array_merge($post, $data));

      if (isset($post['i18n'])) {
        $pjMultiLangModel =pjMultiLangModel::factory();
        foreach ($post['i18n'] as $locale => $locale_arr) {
          foreach ($locale_arr as $field => $content) {
            if ( !is_array($content)) {
              $sql =sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES (NULL, :foreign_id, :model, :locale, :field, :update_content, :source) ON DUPLICATE KEY UPDATE `content`= :update_content, `source`= :source; ", $pjMultiLangModel->getTable());
              $foreign_id =$id;
              $model ='pjProduct';
              $source ='data';
              $update_content =$content;
              $modelObj =$pjMultiLangModel->reset()->prepare($sql)->exec(compact('foreign_id', 'model', 'locale', 'field', 'update_content', 'source'));
            }
          }
        }
        $pjProductPriceModel =pjProductPriceModel::factory();
        if ($post['set_different_sizes']=='T') {
          if (isset($post['index_arr']) && $post['index_arr'] !='') {
            $index_arr =explode("|", $post['index_arr']);

            foreach ($index_arr as $k => $v) {
              if (strpos($v, 'fd') !==false) {
                $p_data =array();
                $p_data['product_id']=$post['id'];
                $p_data['price']=$post['product_price'][$v];
                $price_id =$pjProductPriceModel->reset()->setAttributes($p_data)->insert()->getInsertId();

                if ($price_id  !==false && (int)$price_id > 0) {
                  foreach ($post['i18n'] as $locale => $locale_arr) {
                    foreach ($locale_arr as $field => $content) {
                      if (is_array($content)) {
                        $insert_id =$pjMultiLangModel->reset()->setAttributes(array('foreign_id'=> $price_id, 'model'=> 'pjProductPrice', 'locale'=> $locale, 'field'=> $field, 'content'=> $content[$v], 'source'=> 'data'))->insert()->getInsertId();
                      }
                    }
                  }
                }
              } else {
                $p_data =array();
                $p_data['price']=$post['product_price'][$v];
                $pjProductPriceModel->reset()->where('id', $v)->limit(1)->modifyAll($p_data);

                foreach ($post['i18n'] as $locale => $locale_arr) {
                  foreach ($locale_arr as $field => $content) {
                    if (is_array($content)) {
                      $sql =sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES (NULL, :foreign_id, :model, :locale, :field, :update_content, :source) ON DUPLICATE KEY UPDATE `content`= :update_content, `source`= :source; ", $pjMultiLangModel->getTable());
                        $foreign_id =$v;
                        $model ='pjProductPrice';
                        $source ='data';
                        $update_content =$content[$v];
                        $modelObj =$pjMultiLangModel->reset()->prepare($sql)->exec(compact('foreign_id', 'model', 'locale', 'field', 'update_content', 'source'));
                    }
                  }
                }
              }
            }
          }

          if (isset($post['remove_arr']) && $post['remove_arr'] !='') {
            $remove_arr =explode("|", $post['remove_arr']);
            $pjMultiLangModel->reset()->where('model', 'pjProductPrice')->whereIn('foreign_id', $remove_arr)->eraseAll();
            $pjProductPriceModel->reset()->whereIn('id', $remove_arr)->eraseAll();
          }

          $pjProductModel->reset()->where('id', $post['id'])->limit(1)->modifyAll(array('price'=> ':NULL'));
        } else {
          $id_arr =$pjProductPriceModel->where('product_id', $post['id'])->findAll()->getDataPair("id", "id");
          $pjMultiLangModel->reset()->where('model', 'pjProductPrice')->whereIn('foreign_id', $id_arr);
          $pjProductPriceModel->reset()->where('product_id', $post['id'])->eraseAll();
        }
      }

      $pjProductCategoryModel =pjProductCategoryModel::factory();
      $pjProductCategoryModel->where('product_id', $post['id'])->eraseAll();

      if (isset($post['category_id']) && is_array($post['category_id']) && count($post['category_id']) > 0) {
        $pjProductCategoryModel->reset()->begin();

        foreach ($post['category_id'] as $category_id) {
          $pjProductCategoryModel->reset()->set('product_id', $post['id'])->set('category_id', $category_id)->insert();
        }

        $pjProductCategoryModel->commit();
      }

      $pjProductExtraModel =pjProductExtraModel::factory();
      $pjProductExtraModel->where('product_id', $post['id'])->eraseAll();

      if (isset($post['extra_id']) && is_array($post['extra_id']) && count($post['extra_id']) > 0) {
        $pjProductExtraModel->reset()->begin();

        foreach ($post['extra_id'] as $extra_id) {
          $pjProductExtraModel->reset()->set('product_id', $post['id'])->set('extra_id', $extra_id)->insert();
        }

        $pjProductExtraModel->commit();
      }

      if ($err =='AP01') {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionIndex&err=AP01&category=" . $post['category'] . "&page=" . $post['page']);
      } else {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionUpdate&id=" . $id . "&err=AP10");
      }
    }

    if (self::isGet() && $this->_get->toInt('id')) {
      $id =$this->_get->toInt('id');
      $page =$this->_get->toInt('page');
      $category =$this->_get->toInt('category') !='' ? $this->_get->toInt('category') : 'all';
      $this->set('page', $page);
      $this->set('category', $category);
      $pjMultiLangModel =pjMultiLangModel::factory();
      $arr =pjProductModel::factory()->find($id)->getData();

      if (count($arr)===0) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionIndex&err=AP08");
      }

      $arr['i18n']=$pjMultiLangModel->getMultiLang($arr['id'], 'pjProduct');
      $categories = pjProductCategoryModel::factory()->where("product_id", $id)->findAll()->getDataPair("category_id", "category_id");
      $category_types = pjCategoryModel::factory()->whereIn("id", $categories)->select('product_type')->groupBy('product_type')->findAll()->getData();
      $category_types = array_column($category_types, 'product_type');
      $category_types = $this->getCategoryTypejson($category_types);
      $this->set('arr', $arr);
      $this->setLocalesData();
      $this->set('category_arr', pjCategoryModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjCategory' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->where('t1.status', 'T')->orderBy('`order` ASC')->findAll()->getData());
      $this->set('extra_arr', pjExtraModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->orderBy('name ASC')->findAll()->getData());
      $this->set('category_id_arr', $categories);
      $this->set('category_types', $category_types);
      $this->set('extra_id_arr', pjProductExtraModel::factory()->where("product_id", $id)->findAll()->getDataPair("extra_id", "extra_id"));

      if ($arr['set_different_sizes']=='T') {
        $size_arr =pjProductPriceModel::factory()->where('product_id', $id)->findAll()->getData();

        foreach ($size_arr as $k => $v) {
          $size_arr[$k]['i18n']=pjMultiLangModel::factory()->getMultiLang($v['id'], 'pjProductPrice');
        }

        $this->set('size_arr', $size_arr);
      }

      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('jasny-bootstrap.min.js', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('pjAdminProducts.js');
    }
  }

  public function pjActionDeleteImage() {
    $this->setAjax(true);

    if ( !$this->isXHR()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'Missing headers.'));
    }

    if ( !self::isPost()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'HTTP method not allowed.'));
    }

    if ( !pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 102, 'text'=> 'Access denied.'));
    }

    if ( !($this->_get->toInt('id'))) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 103, 'text'=> 'Missing, empty or invalid parameters.'));
    }

    $id =$this->_get->toInt('id');
    $pjProductModel =pjProductModel::factory();
    $arr =$pjProductModel->find($id)->getData();

    if (empty($arr)) {
      self::jsonResponse(array('status'=> 'ERR', 'code'=> 104, 'text'=> 'Product not found.'));
    }

    if ( !empty($arr['image'])) {
      @unlink(PJ_INSTALL_PATH . $arr['image']);
    }

    $data =array();
    $data['image']=':NULL';
    $pjProductModel->reset()->where(array('id'=> $id))->limit(1)->modifyAll($data);
    self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> 'Product image has been deleted.'));
  }

  public function pjActionSaveFeatured() {
    $this->setAjax(true);

    if ($this->isXHR()) {
      if ( !self::isPost()) {
        self::jsonResponse(array('status'=> 'ERR', 'code'=> 100, 'text'=> 'HTTP method not allowed.'));
      }

      if ($this->_get->toInt('id') <=0) {
        self::jsonResponse(array('status'=> 'ERR', 'code'=> 101, 'text'=> 'Missing, empty or invalid parameters.'));
      }

      $id =$this->_get->toInt('id');
      pjProductModel::factory()->where('id', $id)->modifyAll(array('is_featured'=> ":IF(`is_featured`='0','1','0')"));
      pjProductModel::factory()->where('parent_id', $id)->modifyAll(array('is_featured'=> ":IF(`is_featured`='0','1','0')"));
      self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> 'Featured product has been set.'));
    }

    exit;
  }

  public function pjActionDuplicate() {
  $id = $this->_get->toInt('id');
  if ($id) {
    $selectedProduct = pjProductModel::factory()->find($id)->getData();
    // $this->pr($selectedProduct);
    if ($selectedProduct) {
      //product Query
      $pjProductModel = pjProductModel::factory();
      $sql = "INSERT INTO food_delivery_products (parent_id, set_different_sizes, price, image, counter_number, is_featured, is_kitchen, is_web_orderable, is_veg, is_vat, `order`, preparation_time, status, no_of_ratings ) SELECT id, set_different_sizes, price, image, counter_number, is_featured, is_kitchen, is_web_orderable, is_veg, is_vat, `order`, preparation_time, status, no_of_ratings FROM food_delivery_products WHERE id = $id";
      $productID = $pjProductModel->reset()->prepare($sql)->exec()->getInsertId();
      $langArr = pjMultiLangModel::factory()->where("foreign_id", $id)->where('model', 'pjProduct')->findAll()->getData();
      $pjMultiLangModel = pjMultiLangModel::factory();
      if ($langArr) {
        $langSql = "INSERT INTO food_delivery_plugin_base_multi_lang (foreign_id, `model`, locale, field, content, source) ";
        $arrLength = count($langArr);
        $count = 1;
        foreach ($langArr as $lang) {
          $selectSql = '';
          $selectSql = "SELECT $productID, model, locale, field, content, source from food_delivery_plugin_base_multi_lang where id = ".$lang['id'];
          $execQuery = $langSql.$selectSql;
          $pjMultiLangModel->reset()->prepare($execQuery)->exec();
        } 
      }
      if ($selectedProduct['set_different_sizes'] == 'T') {
         $priceArr  = pjProductPriceModel::factory()->where("product_id", $id)->findAll()->getData();
         $langSql = "INSERT INTO food_delivery_plugin_base_multi_lang (foreign_id, `model`, locale, field, content, source) ";
         if ($priceArr) {
          $pjProductPriceModel = pjProductPriceModel::factory();
          foreach($priceArr as $price) {
            $priceID = $pjProductPriceModel->reset()->set('product_id', $productID)->set('price', $price['price'])->insert()->getInsertId(); 
            $priceLangArr = pjMultiLangModel::factory()->where("foreign_id", $price['id'])->where('model', 'pjProductPrice')->findAll()->getData();
            foreach ($priceLangArr as $lang) {
              $selectSql = '';
              $selectSql = "SELECT $priceID, model, locale, field, content, source from food_delivery_plugin_base_multi_lang where id = ".$lang['id'];
              $execQuery = $langSql.$selectSql;
              $pjMultiLangModel->reset()->prepare($execQuery)->exec();
            } 
          }
        }  
      }
      $catArr = pjProductCategoryModel::factory()->where("product_id", $id)->findAll()->getData();
      if ($catArr) {
        $pjProductCategoryModel = pjProductCategoryModel::factory();
        $pjProductCategoryModel->begin();
        foreach ($catArr as $category) {
          $pjProductCategoryModel->reset()->set('product_id', $productID)->set('category_id', $category['category_id'])->insert();
        }
        $pjProductCategoryModel->commit();
      }

      $extraArr = pjProductExtraModel::factory()->where("product_id", $id)->findAll()->getData();
      if ($extraArr) {
        $pjProductExtraModel = pjProductExtraModel::factory();
        $pjProductExtraModel->begin();
        foreach ($extraArr as $extra) {
          $pjProductExtraModel->reset()->set('product_id', $productID)->set('extra_id', $extra['extra_id'])->insert();
        }
          $pjProductExtraModel->commit();
      }
       pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionUpdate&id=$productID");
        
      // self::jsonResponse(array('status'=> 'OK', 'code'=> 200, 'text'=> 'Featured product has been set.'));
      }
    }
  // exit;
}
  public function pjActionDuplicateAll() {
    $allActiveProducts = pjProductModel::factory()->where('status', 1)->where('order_type', 'eatin')->findAll()->getDataPair(null, "id");
    $this->pr($allActiveProducts);
    foreach($allActiveProducts as $productID) {
      $this->pjActionDuplicate($productID);
    }
    exit;
  }

  protected function getCategoryTypejson($array) {
    $response = ['status'=> '', 'text'=> ''];
    if(in_array('none', $array) && count($array) == 1) {
      $response['status'] = true;
      $response['text'] = 'null';
    } elseif (in_array('non-veg', $array) || in_array('both', $array)) {
      $response['status'] = false;
      $response['text'] = false;
    } else {
      $response['status'] = false;
      $response['text'] = true;
    }
    return $response;
  }


  public function pjActionUpload() {
    $post_max_size = pjUtil::getPostMaxSize();

    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminProducts&action=pjActionIndex&err=AP05");
    }

    $this->checkLogin();

    if ( !pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }

    if (self::isPost()) {
      $pjProductModel =pjProductModel::factory();
      if (isset($_FILES['csv_file'])) {
        $tmpName = $_FILES['csv_file']['tmp_name'];
        $prodAsArray = array_map('str_getcsv', file($tmpName));
        if ($prodAsArray) {
          //Removing the first header element
          unset($prodAsArray[0]);
          // echo '<pre>'; print_r($prodAsArray);
          // exit;
          $category_arr = pjCategoryModel::factory()->select('t1.id, t2.content AS name')->join('pjMultiLang', "t2.model='pjCategory' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->where('t1.status', 'T')->orderBy('`order` ASC')->findAll()->getData();
          // print_r($category_arr);
          $category_arr = array_combine(array_column($category_arr, 'name'),$category_arr);
          // print_r($category_arr);
          foreach ($prodAsArray as $key=>$product) {
            $internalization[1] = array('name'=>$product[1], 'description'=>$product[2]);
            $category_id = $category_arr[$product[0]]['id'];
            $data = array(
              'category_id'=>array($category_id),
              'price'=> $product[3],
              'order_type'=>'eatin',
              'locale'=> 1,
              'set_different_sizes'=> 'F',
              'i18n'=> $internalization,
            );
            $this->pjCreateProductViaCSV($data);
            $data['order_type'] = 'takeaway';
            $this->pjCreateProductViaCSV($data);
          }
        }
        // self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Products has been uploaded.'));
      }
      // echo '<pre>'; print_r($data);
      // echo '<pre>'; print_r($post);
      // exit;
      // self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Sorry please try after sometimes'));
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionIndex");
    }

    if (self::isGet()) {
      $this->setLocalesData();
      $this->set('extra_arr', pjExtraModel::factory()->select('t1.*, t2.content AS name')->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')->orderBy('name ASC')->findAll()->getData());
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('jasny-bootstrap.min.js', PJ_THIRD_PARTY_PATH . 'jasny/');
      $this->appendJs('pjAdminProducts.js');
    }
  }

  public function pjCreateProductViaCSV($post) {
    $pjProductModel =pjProductModel::factory();
    $id = $pjProductModel->setAttributes($post)->insert()->getInsertId();

      if ($id  !== false && (int)$id > 0) {
        $err ='AP03';
        $pjMultiLangModel =pjMultiLangModel::factory();
        $pjProductPriceModel =pjProductPriceModel::factory();

        if (isset($post['i18n'])) {
          $pjMultiLangModel->saveMultiLang($post['i18n'], $id, 'pjProduct', 'data');

          if ($post['set_different_sizes'] == 'T') {
            if (isset($post['index_arr']) && $post['index_arr'] !='') {
              $index_arr =explode("|", $post['index_arr']);

              foreach ($index_arr as $k => $v) {
                if (strpos($v, 'fd') !== false) {
                  $p_data =array();
                  $p_data['product_id'] = $id;
                  $p_data['price'] = $post['product_price'][$v];
                  $price_id = $pjProductPriceModel->reset()->setAttributes($p_data)->insert()->getInsertId();

                  if ($price_id  !== false && (int)$price_id > 0) {
                    foreach ($post['i18n'] as $locale => $locale_arr) {
                      foreach ($locale_arr as $field => $content) {
                        if (is_array($content)) {
                          $insert_id =$pjMultiLangModel->reset()->setAttributes(array('foreign_id'=> $price_id, 'model'=> 'pjProductPrice', 'locale'=> $locale, 'field'=> $field, 'content'=> $content[$v], 'source'=> 'data'))->insert()->getInsertId();
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }


        $pjProductCategoryModel =pjProductCategoryModel::factory();

        if (isset($post['category_id']) && is_array($post['category_id']) && count($post['category_id']) > 0) {
          $pjProductCategoryModel->begin();

          foreach ($post['category_id'] as $category_id) {
            $pjProductCategoryModel->reset()->set('product_id', $id)->set('category_id', $category_id)->insert();
          }

          $pjProductCategoryModel->commit();
        }

        $pjProductExtraModel =pjProductExtraModel::factory();

        if (isset($post['extra_id']) && is_array($post['extra_id']) && count($post['extra_id']) > 0) {
          $pjProductExtraModel->begin();

          foreach ($post['extra_id'] as $extra_id) {
            $pjProductExtraModel->reset()->set('product_id', $id)->set('extra_id', $extra_id)->insert();
          }

          $pjProductExtraModel->commit();
        }

        if ($err  !='AP03') {
          pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminProducts&action=pjActionUpdate&id=$id&err=AP09");
        }
      } else {
        $err ='AP04';
      }
  }
}

?>