<?php
if (!defined("ROOT_PATH"))
{
    header("HTTP/1.1 403 Forbidden");
    exit;
}
class pjFront extends pjAppController
{
    public $defaultCaptcha = 'pjFoodDelivery_Captcha';
    
    public $defaultLocale = 'pjFoodDelivery_LocaleId';
    
    public $defaultClient = 'pjFoodDelivery_Client';
    
    public $defaultLangMenu = 'pjFoodDelivery_LangMenu';
    
    public $defaultStore = 'pjFoodDelivery_Store';
    
    public $defaultForm = 'pjFoodDelivery_Form';
    
    public function __construct()
    {
        $this->setLayout('pjActionFront');
        
        self::allowCORS();
    }
    
    public function _get($key)
    {
        if ($this->_is($key))
        {
            return $_SESSION[$this->defaultStore][$key];
        }
        return false;
    }
    
    public  function _is($key)
    {
        return isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore][$key]);
    }
    
    public  function _set($key, $value)
    {
        $_SESSION[$this->defaultStore][$key] = $value;
        return $this;
    }
    
    public  function _unset($key)
    {
        if ($this->_is($key))
        {
            unset($_SESSION[$this->defaultStore][$key]);
        }
    }
    
    public function isFrontLogged()
    {
        if (isset($_SESSION[$this->defaultUser]) && count($_SESSION[$this->defaultUser]) > 0)
        {
            $user = pjAuth::init(array('id' => $this->getClientId()))->getUser();
            if($user['status'] == 'F')
            {
                $this->session->unsetData($this->defaultUser);
                return false;
            }
            return true;
        }
        if (isset($_SESSION['social_login'])) {
            return true;
        }
        if (isset($_SESSION['guest'])) {
            return true;
        }
        return false;
    }

    public function hasPostcode()
    {
        if (isset($_SESSION[$this->defaultClient]) && count($_SESSION[$this->defaultClient]) > 0)
        {
            $client = $_SESSION[$this->defaultClient];
            if($client['c_postcode'] != '')
            {
                return true;
            } else {
                return false;
            }
            
        } 

        if (isset($_SESSION['guest'])) {
            return false;
        }
        
        return false;
    }
    
    public function getClientId()
    {
        return isset($_SESSION[$this->defaultClient]) && array_key_exists('id', $_SESSION[$this->defaultClient]) ? $_SESSION[$this->defaultClient]['id'] : FALSE;
    }
    
    public function afterFilter()
    {
        if (!$this->_get->check('hide') || ($this->_get->check('hide') && $this->_get->toInt('hide') !== 1) &&
            in_array($this->_get->toString('action'), array('pjActionMain', 'pjActionTypes', 'pjActionLogin', 'pjActionVouchers','pjActionForgot', 'pjActionProfile', 'pjActionCheckout', 'pjActionPreview')))
        {
            $locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
            ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
            ->where('t2.file IS NOT NULL')
            ->orderBy('t1.sort ASC')->findAll()->getData();
            
            $this->set('locale_arr', $locale_arr);
        }
    }
    
    public function beforeFilter()
    {
        return parent::beforeFilter();
    }
    
    public function beforeRender()
    {
        if ($this->_get->check('iframe'))
        {
            $this->setLayout('pjActionIframe');
        }
    }
    
    public function pjActionGetLocale()
    {
        return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
    }
    
    public function isXHR()
    {
        return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
    }
    
    protected static function allowCORS()
    {
        $install_url = parse_url(PJ_INSTALL_URL);
        if($install_url['scheme'] == 'https'){
            header('Set-Cookie: '.session_name().'='.session_id().'; SameSite=None; Secure');
        }
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
        header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            exit;
        }
    }
    
    protected function pjActionSetLocale($locale)
    {
        if ((int) $locale > 0)
        {
            $_SESSION[$this->defaultLocale] = (int) $locale;
        }
        return $this;
    }
    public function pr($data) {
      echo '<pre>'; print_r($data); echo '</pre>';
    }
    public function pr_die($data) {
      echo '<pre>'; print_r($data); echo '</pre>'; die;
    }
  public function kitchenPrintFormat($printer, $data, $special_instructions, $newItem = false) {
    // $this->pr($data);
    // $this->pr($data['product_arr']);
    // $this->pr($data['oi_arr']);
    // exit;
    foreach ($data['product_arr'] as $product) {
      foreach ($data['oi_arr'] as $k => $oi) {
        // $this->pr($oi);
        $lineItem = '';
        if ((($newItem && ($oi['cnt'] != $oi['print'])) || (!$newItem && ($oi['print']))) && $oi['foreign_id'] == $product['id']) {
          if ($newItem) {
            $lineItem = ($oi['cnt'] - $oi['print']);
          } else {
            $lineItem = $oi['print'];
          }
          $lineItem .= " x ".strtoupper($product['name'])." ".$oi['size'];
          if ($oi['cancel_or_return_reason']) {
            $lineItem .= ' - CANCELLED';
          }
          // echo $lineItem;
          // echo '<br/>';
          $printer->appendText("$lineItem");
          if ($oi['special_instruction'] != '') {
            $printer->lineFeed();
            $spcl_ins = $oi['special_instruction'];
            $spcl_ins_arr = explode(",", $spcl_ins);
              foreach ($spcl_ins_arr as $ins) {
                foreach ($special_instructions as $instruction) {
                  if ($ins == $instruction['id']) {
                    $printer->appendImage($instruction['image'], 0);
                    // echo $instruction['image'];
                  }
                }
              }
            } 
            if (array_key_exists($oi['hash'], $data['oi_extras'])) { 
              $proExtra = $data['oi_extras'][$oi['hash']]; 
              foreach($proExtra as $extra) {
                // $printer->appendText(" - ".$extra['extra_name'] ." x ".$extra['cnt'] ."\n");
                $printer->appendText(" - ".$extra->extra_name ." x ".$extra->extra_count ."\n");
                // echo " - ".$extra->extra_name ." x ".$extra->extra_count  ."<br>";
              } 
            }
            if ($oi['custom_special_instruction'] != '')
            {
              $customs = json_decode($oi['custom_special_instruction']);
              foreach ($customs as $custom) {
                $printer->appendText(" - \n");
                // $printer->appendText(str_repeat(" ", 4).$oi['custom_special_instruction']."\n");
                $printer->appendText(str_repeat(" ", 4).$custom->cus_ins."\n");
                // echo str_repeat(" ", 4).$custom->cus_ins."<br/>";
              }
            } else {
              $printer->appendText("\n");
            }
        }
      }
    }
  }
    public function getOrderItems($id, $isForPrinting=false, $kPrint=false) {
    if ($id) {
      $korder = pjOrderModel::factory()->select("t1.*")->find($id)->getData();
      $korder['kprint'] = 1;

      $pjOrder = pjOrderModel::factory();
      $pjOrder->where('id', $id)->limit(1)->modifyAll($korder);

      $pjOrderModel = pjOrderModel::factory();

      $arr = $pjOrderModel
    ->join('pjAuthUser', "t2.id=t1.chef_id", 'left')
    ->join('pjTable', "t3.id=t1.table_name", 'left')
      ->select("t1.*, t2.name AS chef_name, t3.name as table_ordered_name,
              AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,
              AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
              AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
              AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
      ->find($id)
    ->getData();
      if (empty($arr)) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex&err=AR08");
      }
     
      $pjProductPriceModel = pjProductPriceModel::factory();
      $oi_arr = array();
      $pjOrderItemObject = pjOrderItemModel::factory()->where('t1.order_id', $arr['id']);
      $_oi_arr = $pjOrderItemObject->orderBy("status ASC, item_order ASC")->findAll()->getData();

      if ($kPrint) {
        foreach ($_oi_arr as $key=>$item) {
          if ($item['print'] != 0) {
            unset ($_oi_arr[$key]);
          }
        }
      }
      if (empty($_oi_arr)) {
        pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminPosOrders&action=pjActionIndex");
      }
      $product_ids = array_column($_oi_arr, 'foreign_id');

      $product_arr = pjProductModel::factory()->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()) , 'left')
        ->select(sprintf("t1.*, t2.content AS name,
                (SELECT GROUP_CONCAT(extra_id SEPARATOR '~:~') FROM `%s` WHERE product_id = t1.id GROUP BY product_id LIMIT 1) AS allowed_extras,
                (SELECT COUNT(*) FROM `%s` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras", pjProductExtraModel::factory()
        ->getTable() , pjProductExtraModel::factory()
        ->getTable()))
        ->orderBy("name ASC")
        ->whereIn('t1.id', $product_ids)
        ->findAll()
        ->toArray('allowed_extras', '~:~')
        ->getData();
      $product_arr = array_combine(array_column($product_arr, 'id'),$product_arr);
      $extra_arr = pjExtraModel::factory()->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '" . $this->getLocaleId() . "' AND t2.field = 'name'", 'left')
          ->select("t1.*, t2.content AS name")
          ->where("t1.id IN (SELECT TPE.extra_id FROM `" . pjProductExtraModel::factory()
          ->getTable() . "` AS TPE)")->orderBy("name ASC")
          ->whereIn('t1.id', $product_ids)
          ->findAll()
          ->getData();
      $extras = array();
      $oi_extras = array();
      foreach ($extra_arr as $extra) {
        $extras[$extra['id']] = $extra;
      }
      foreach ($_oi_arr as $item) {
        $item['is_kitchen'] = 1;
        $item['is_web_orderable'] = 1;
        $item['is_veg'] = 1;
        if ($item['type'] == 'product') {
          $item['price_arr'] = $pjProductPriceModel->reset()
            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()) , 'left')
            ->select("t1.*, t2.content AS price_name")
            ->where('product_id', $item['foreign_id'])->findAll()
            ->getData();
            if ($item['price_id'] != '') {
              foreach ($item['price_arr'] as $price_data) {
                if ($price_data['id'] == $item['price_id']) {
                  $item['size'] = $price_data['price_name'];
                  break;
                }
              }
            } else {
              $item['size'] = '';
            }
            $item['product_name'] = $product_arr[$item['foreign_id']]['name'];
            $item['is_kitchen'] = $product_arr[$item['foreign_id']]['is_kitchen'];
            $item['is_web_orderable'] = $product_arr[$item['foreign_id']]['is_web_orderable'];
            $item['is_veg'] = $product_arr[$item['foreign_id']]['is_veg'];
        }
        if ($item['type'] == 'extra') {
          $extras = json_decode($item['special_instruction']);
          $oi_extras[$item['hash']] = $extras;
        } else {
          $oi_arr[] = $item;
        }
      }
      if ($isForPrinting) {
        return compact('arr', 'product_arr', 'oi_arr', 'oi_extras');
      } else {
        $this->set('arr', $arr);
        $this->set('product_arr', $product_arr);
        $this->set('oi_arr', $oi_arr);
        $this->set('oi_extras', $oi_extras);
      }
    }
  }
  public function getTodayOrderCount() {
    $pjOrderModel = pjOrderModel::factory();
    $todayOrderCnt = $pjOrderModel->where('date(t1.created) >= date(now())')->findCount()->getData();
    return $todayOrderCnt ? $todayOrderCnt + 1: 1;

  }

}
?>