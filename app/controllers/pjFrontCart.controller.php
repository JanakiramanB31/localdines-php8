<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontCart extends pjFront {
  public function pjActionAddProduct() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($product_id = $this->_post->toInt('product_id')) {
        $extras = array();
        $extra_id_arr = $this->_post->toArray('extra_id');
        if (!empty($extra_id_arr)) {
          foreach ($extra_id_arr as $extra_id => $qty) {
            if (intval($qty) > 0) {
              $extras[$extra_id] = $qty;
            }
          }
        }
        ksort($extras);
        $price_id = null;
        if ($this->_post->check('price_id')) {
          $price_id = $this->_post->toInt('price_id');
          $hash = md5($product_id . $price_id . serialize($extras));
        } else {
          $hash = md5($product_id . serialize($extras));
        }
        $cart = $this->_get('cart');
        if ($cart === false) {
          $cart = array();
        }
        if (!array_key_exists($hash, $cart)) {
          $cart[$hash] = array(
            'product_id' => $product_id,
            'price_id' => $price_id,
            'cnt' => 0,
            'extras' => $extras
          );
        } else {
          $extras = $cart[$hash]['extras'];
          $post = $this->_post->raw();
          foreach ($extras as $eid => $qty) {
            if (isset($post['extra_id'][$eid]) && (int) $post['extra_id'][$eid] > 0) {
              $cart[$hash]['extras'][$eid] += (int) $post['extra_id'][$eid];
            }
          }
        }
        $cart[$hash]['cnt'] += 1;
        $this->_set('cart', $cart);
        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Product is added to your cart.'));
      }
      pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
    }
  }
    
  public function pjActionRemove(){
    $this->isAjax = true;
    if ($this->isXHR()) {
      if ($this->_post->check('hash') && $this->_post->check('extra_id')) {
        $post = $this->_post->raw();
        $cart = $this->_get('cart');
        if ($cart !== false) {
          if (array_key_exists($post['hash'], $cart)) {
            if ((int) $post['extra_id'] > 0) {
              unset($cart[$post['hash']]['extras'][$post['extra_id']]);
            } else {
              unset($cart[$post['hash']]);
            }
            $this->_set('cart', $cart);
            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'The item is removed from cart.'));
          }
        }
        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'The cart does not exist.'));
      }
      pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
    }
  }
  public function pjActionUpdateCart() {
    $this->isAjax = true;
    if ($this->isXHR()) {
      if ($this->_post->check('hash') && $this->_post->check('sign')) {
        $post = $this->_post->raw();
        $cart = $this->_get('cart');
        if ($cart !== false) {
          if (array_key_exists($post['hash'], $cart)) {
            $cnt = $cart[$post['hash']]['cnt'];
            switch ($post['sign']) {
              case '+':
                $cart[$post['hash']]['cnt'] = $cnt + 1;
                $extras = $cart[$post['hash']]['extras'];
                foreach($extras as $eid => $qty) {
                  $unit_qty = (int) $qty / $cnt;
                  $cart[$post['hash']]['extras'][$eid] += $unit_qty;
                }
              break;
                                
              case '-':
                if ($cnt <= 1) {
                  unset($cart[$post['hash']]);
                } else {
                  $cart[$post['hash']]['cnt'] = $cnt - 1;
                  $extras = $cart[$post['hash']]['extras'];
                  foreach($extras as $eid => $qty) {
                    if ($qty >= 1) {
                      $unit_qty = (int) $qty / $cnt;
                      $cart[$post['hash']]['extras'][$eid] -= $unit_qty;
                    }
                  }
                }
              break;
            }
            $this->_set('cart', $cart);
            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'The cart has been updated!'));
          }
        }
        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'The cart does not exist.'));
      }
      pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
    }
  }
  public function pjActionAddPromo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pre = array(
          'd_date' => date('d.m.Y'),
          'delivery_time' => date('H:i'),
          'd_dt' => date('d.m.Y') . ' ' . date('H:i'),
          'p_date' => $this->_get('p_date'),
          'p_time' => $this->_get('p_time'),
          'p_dt' => $this->_get('p_date') . ' ' . $this->_get('p_time'),
          'origin' => 'web'
      );
      $post = $this->_post->raw();
      $resp = pjAppController::getDiscount(array_merge($post, $pre), $this->option_arr);
      $promo_statuses = __('promo_statuses', true, false);
      $resp['text'] = $promo_statuses[$resp['code']];
      if ($resp['code'] == 200) {
        $this->_set('voucher_code', $resp['voucher_code']);
        $this->_set('voucher_type', $resp['voucher_type']);
        $this->_set('voucher_discount', $resp['voucher_discount']);
      } else {
        $this->_set('voucher_code', false);
        $this->_set('voucher_type', '');
        $this->_set('voucher_discount', '');
      }
      pjAppController::jsonResponse($resp);
    }
  }
    
  public function pjActionResetPromo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $this->_set('voucher_code', false);
      $this->_set('voucher_type', '');
      $this->_set('voucher_discount', '');
      pjAppController::jsonResponse("Promo code resetted successfully");
    }
  }
   
  public function pjActionVoucherValidate() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pre = array(
          'type' => 'delivery',
          'd_date' => date('d.m.Y'),
          'd_time' => date('H:i'),
          'd_dt' => date('d.m.Y') . ' ' . date('H:i'),
          'p_date' => $this->_get('p_date'),
          'p_time' => $this->_get('p_time'),
          'p_dt' => $this->_get('p_date') . ' ' . $this->_get('p_time'),
      );
      $post = $this->_post->raw();
      $resp = pjAppController::getDiscount(array_merge($post, $pre), $this->option_arr);
      $promo_statuses = __('promo_statuses', true, false);
      $resp['text'] = $promo_statuses[$resp['code']];
      if($resp['code'] == 200) {
        $this->_set('voucher_code', $resp['voucher_code']);
        $this->_set('voucher_type', $resp['voucher_type']);
        $this->_set('voucher_discount', $resp['voucher_discount']);
      } else {
        $this->_set('voucher_code', false);
        $this->_set('voucher_type', '');
        $this->_set('voucher_discount', '');
      }
      pjAppController::jsonResponse($resp);
    }
  }
  
  public static function getCartInfo($cart, $locale_id) {
    $product_arr = array();
    $items_in_cart = 0;
    if ($cart !== false) {
      $ids = array();
      foreach ($cart as $item) {
        $ids[] = $item['product_id'];
        $items_in_cart += $item['cnt'];
      }
      if (count($ids) > 0) {
        $pjProductModel = pjProductModel::factory();
        $pjProductPriceModel = pjProductPriceModel::factory();
        $pjExtraModel = pjExtraModel::factory();
        $product_arr = $pjProductModel
          ->select('t1.id, MIN(t2.content) AS name, MIN(t4.packing_fee) AS `packing_fee`')
          ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $locale_id), 'left')
          ->join('pjProductCategory', 't3.product_id=t1.id', 'left outer')
          ->join('pjCategory', 't4.id=t3.category_id', 'left outer')
          ->whereIn('t1.id', $ids)
          ->groupBy('t1.id')
          ->findAll()
          ->getData();
        foreach ($cart as $k => $item) {
          $price_arr = array();
          if (!empty($item['price_id'])) {
            $price_arr = $pjProductPriceModel
            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $locale_id), 'left')
            ->select('t1.*, t2.content as size')
            ->find($item['price_id'])
            ->getData();
            if ($price_arr) {
              $cart[$k]['price'] = $price_arr['price'];
              $cart[$k]['size'] = $price_arr['size'];
            } else {
              $cart[$k]['price'] = 0;
              $cart[$k]['size'] = '';
            }
          } else {
            $price_arr = $pjProductModel->reset()->find($item['product_id'])->getData();
            if (!empty($price_arr)) {
              $cart[$k]['price'] = $price_arr['price'];
              $cart[$k]['size'] = '';
            } else {
              $cart[$k]['price'] = 0;
              $cart[$k]['size'] = '';
            }
          }
          $extra_arr = array();
          if (isset($item['extras']) && $item['extras']) {
            foreach ($item['extras'] as $extra_id => $qty) {
              $_arr = $pjExtraModel
              ->reset()
              ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $locale_id), 'left')
              ->select('t1.*, t2.content as name')
              ->whereIn('t1.id', $ids)
              ->find($extra_id)
              ->getData();
              $_arr['qty'] = $qty;
              $extra_arr[$extra_id] = $_arr;
            }
          }
          $cart[$k]['extra_arr'] = $extra_arr;
        }
      }
    }
    return compact('cart', 'items_in_cart', 'product_arr');
  }
}
?>