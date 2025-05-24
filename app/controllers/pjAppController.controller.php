<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjBaseAppController
{
	public function isEditor()
    {
    	return $this->getRoleId() == 2;
    }
    
    public function isClient()
    {
        return $this->getRoleId() == 3;
    }

    public function pjActionCheckInstall()
    {
        $this->setLayout('pjActionEmpty');

        $result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
        $folders = array('app/web/upload');
        foreach ($folders as $dir)
        {
            if (!is_writable($dir))
            {
                $result['status'] = 'ERR';
                $result['code'] = 101;
                $result['text'] = 'Permission requirement';
                $result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
            }
        }

        return $result;
    }

    /**
     * Sets some predefined role permissions and grants full permissions to Admin.
     */
    public function pjActionAfterInstall()
    {
        $this->setLayout('pjActionEmpty');

        $result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());

        $pjAuthRolePermissionModel = pjAuthRolePermissionModel::factory();
        $pjAuthUserPermissionModel = pjAuthUserPermissionModel::factory();

        $permissions = pjAuthPermissionModel::factory()->findAll()->getDataPair('key', 'id');

        $roles = array(1 => 'admin', 2 => 'editor');
        foreach ($roles as $role_id => $role)
        {
            if (isset($GLOBALS['CONFIG'], $GLOBALS['CONFIG']["role_permissions_{$role}"])
                && is_array($GLOBALS['CONFIG']["role_permissions_{$role}"])
                && !empty($GLOBALS['CONFIG']["role_permissions_{$role}"]))
            {
                $pjAuthRolePermissionModel->reset()->where('role_id', $role_id)->eraseAll();

                foreach ($GLOBALS['CONFIG']["role_permissions_{$role}"] as $role_permission)
                {
                    if($role_permission == '*')
                    {
                        // Grant full permissions for the role
                        foreach($permissions as $key => $permission_id)
                        {
                            $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
                        }
                        break;
                    }
                    else
                    {
                        $hasAsterix = strpos($role_permission, '*') !== false;
                        if($hasAsterix)
                        {
                            $role_permission = str_replace('*', '', $role_permission);
                        }

                        foreach($permissions as $key => $permission_id)
                        {
                            if($role_permission == $key || ($hasAsterix && strpos($key, $role_permission) !== false))
                            {
                                $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
                            }
                        }
                    }
                }
            }
        }
        if (isset($GLOBALS['CONFIG'], $GLOBALS['CONFIG']["listing_actions"])
            && is_array($GLOBALS['CONFIG']["listing_actions"])
            && !empty($GLOBALS['CONFIG']["listing_actions"]))
        {
            $pjAuthPermissionModel = pjAuthPermissionModel::factory();
            foreach($GLOBALS['CONFIG']["listing_actions"] as $parent_key => $get_action)
            {
                $parent_arr = $pjAuthPermissionModel->reset()->where('`key`', $parent_key)->findAll()->getDataIndex(0);
                if(!empty($parent_arr))
                {
                    $data = array('parent_id' => ':NULL', 'key' => $get_action, 'inherit_id' => $parent_arr['id']);
                    $pjAuthPermissionModel->reset()->setAttributes($data)->insert();
                }
            }
        }
        pjAuthRoleModel::factory()->setAttributes(array('id' => 3, 'role' => 'Client', 'is_backend' => 'F', 'T'))->insert();

		// Grant full permissions to Admin
        $user_id = 1; // Admin ID
        $pjAuthUserPermissionModel->reset()->where('user_id', $user_id)->eraseAll();
        foreach($permissions as $key => $permission_id)
        {
            $pjAuthUserPermissionModel->setAttributes(compact('user_id', 'permission_id'))->insert();
        }

        return $result;
    }
    
    public function beforeFilter()
    {
        parent::beforeFilter();

        if(!in_array($this->_get->toString('controller'), array('pjFront')))
        {
            $this->appendJs('pjAdminCore.js?version=123');
            $this->appendCss('admin.css');
        }
        
        return true;
    }

    public function getCoords($str, $option_arr)
    {
        if (!is_array($str))
        {
            $_address = preg_replace('/\s+/', '+', $str);
            $_address = urlencode($_address);
        } else {
            $address = array();
            $address[] = $str['d_zip'];
            $address[] = $str['d_address_1'];
            $address[] = $str['d_city'];
            $address[] = $str['d_state'];
            foreach ($address as $k => $v)
            {
                $tmp = preg_replace('/\s+/', '+', $v);
                $address[$k] = $tmp;
            }
            $_address = join(",+", $address);
        }
        if(!empty($option_arr['o_google_maps_api_key'])){
            $api = sprintf("https://maps.googleapis.com/maps/api/geocode/json?key=%s&address=%s", $option_arr['o_google_maps_api_key'], $_address);
        }else{
            $api = sprintf("https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false", $_address);
        }
        
        $pjHttp = new pjHttp();
        $pjHttp->request($api);
        $response = $pjHttp->getResponse();
        $geoObj = pjAppController::jsonDecode($response);
        
        $data = array();
        if ($geoObj['status'] == 'OK')
        {
            $data['lat'] = $geoObj['results'][0]['geometry']['location']['lat'];
            $data['lng'] = $geoObj['results'][0]['geometry']['location']['lng'];
        } else {
            $data['lat'] = array('NULL');
            $data['lng'] = array('NULL');
        }
        return $data;
    }
    
    public static function getClientTokens($option_arr, $data, $salt, $locale_id)
    {
        $country = NULL;
        $client_id = $data;
        $client = pjClientModel::factory()->find($client_id)->getData();
        $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
        if(isset($client['c_country']) && (int) $client['c_country'] > 0)
        {
            $country_arr = pjBaseCountryModel::factory()
            ->select('t1.id, t2.content AS country_title')
            ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
            ->find($client['c_country'])->getData();
            if (!empty($country_arr))
            {
                $country = $country_arr['country_title'];
            }
        }
        $search = array(
            '{Title}', '{Country}', '{City}', '{State}', '{Notes}',
            '{Zip}', '{Address1}', '{Address2}', '{Company}',
            '{Name}', '{Email}', '{Phone}', '{Password}', '{dCountry}',
            '{dCity}', '{dState}', '{dZip}', '{dAddress1}', '{dAddress2}', '{dNotes}',
            '{PaymentMethod}', '{DateTime}',
            '{Subtotal}', '{Delivery}', '{Discount}','{Tax}',
            '{Total}', '{dNotes}', '{Location}',
            '{OrderID}', '{CancelURL}', '{OrderDetails}');
        $replace = array(
            @$client['c_title'], $country, pjSanitize::clean(@$client['c_city']), pjSanitize::clean(@$client['c_state']), "",
            pjSanitize::clean(@$client['c_zip']), pjSanitize::clean(@$client['c_address_1']), pjSanitize::clean(@$client['c_address_2']), pjSanitize::clean(@$client['c_company']),
            pjSanitize::clean(@$auth_client['name']), pjSanitize::clean(@$auth_client['email']), pjSanitize::clean(@$auth_client['phone']), @$auth_client['password'], "",
            "", "","", "", "","", "", "","", "", "", "","", "", "","", "", "");
        return compact('search', 'replace');
    }
    
    public static function getWorkingTime($date, $location_id, $type)
    {
        $date_arr = pjDateModel::factory()->getWorkingTime($date, $location_id, $type);
        if ($date_arr === false)
        {
            $wt_arr = pjWorkingTimeModel::factory()->getWorkingTime($location_id, $type, $date);
            
            if (count($wt_arr) == 0)
            {
                return false;
            }
            $t_arr = $wt_arr;
        } else {
            if (count($date_arr) == 0)
            {
                return false;
            }
            $t_arr = $date_arr;
        }
        return $t_arr;
    }
    
    public static function getTokens($option_arr, $data, $salt, $locale_id)
    {
        $c_country = NULL;
        $d_country = NULL;
        
        if (isset($data['c_country']) && !empty($data['c_country']))
        {
            $pjCountryModel = pjBaseCountryModel::factory();
            
            $country_arr = pjBaseCountryModel::factory()
            ->reset()
            ->select('t1.id, t2.content AS country_title')
            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $locale_id), 'left outer')
            ->find($data['d_country_id'])
            ->getData();
            if (!empty($country_arr))
            {
                $d_country = $country_arr['country_title'];
            }
        }
        $row = array();
        if (isset($data['product_arr']))
        {
            foreach ($data['product_arr'] as $v)
            {
                $extra = array();
                foreach ($v['extra_arr'] as $e)
                {
                    $extra[] = stripslashes(sprintf("%u x %s", $e['cnt'], $e['name']));
                }
                if(!empty($v['size']))
                {
                    $row[] = stripslashes(sprintf("%u x %s (%s: %s)", $v['cnt'], $v['name'], $v['size'], pjCurrency::formatPrice($v['price']))) . (count($extra) > 0 ? sprintf(" (%s)", join("; ", $extra)) : NULL);
                }else{
                    $row[] = stripslashes(sprintf("%u x %s", $v['cnt'], $v['name'])) . (count($extra) > 0 ? sprintf(" (%s)", join("; ", $extra)) : NULL);
                }
            }
        }
        $order_data = count($row) > 0 ? join("<br/>", $row) : NULL;
        $discount = NULL;
        if (!empty($data['voucher_code']))
        {
            $voucher_arr = pjVoucherModel::factory()
            ->where('t1.code', $data['voucher_code'])
            ->limit(1)
            ->findAll()
            ->getData();
            if (!empty($voucher_arr))
            {
                $voucher_arr = $voucher_arr[0];
                switch ($voucher_arr['type'])
                {
                    case "amount":
                        $discount = pjCurrency::formatPrice($voucher_arr['discount']);
                        break;
                    case "percent":
                        $discount = $voucher_arr['discount'] . '%';
                        break;
                }
            }
        }
        $subtotal = pjCurrency::formatPrice($data['subtotal']);
        $price_packing = pjCurrency::formatPrice($data['price_packing']);
        $price_delivery = pjCurrency::formatPrice($data['price_delivery']);
        $total = pjCurrency::formatPrice($data['total']);
        $tax = pjCurrency::formatPrice($data['tax']);
        
        $cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFrontPublic&action=pjActionCancel&id='.@$data['id'].'&hash='.sha1(@$data['id'].@$data['created'].$salt);
        
        $ts = strtotime(@$data['type'] == 'pickup' ? @$data['p_dt'] : @$data['d_dt']);
        $date_time = date($option_arr['o_date_format'], $ts) . ', ' . date($option_arr['o_time_format'], $ts);
        if(@$data['type'] == 'pickup' &&  @$data['p_asap'] == 'T')
        {
            $date_time = date($option_arr['o_date_format'], $ts) . ', ' . __('front_asap', true);
        }
        if(@$data['type'] == 'delivery' &&  @$data['d_asap'] == 'T')
        {
            $date_time = date($option_arr['o_date_format'], $ts) . ', ' . __('front_asap', true);
        }
        
        $client_id = $data['client_id'];
        $client = pjClientModel::factory()->find($client_id)->getData();
        if($data['origin'] == 'Telephone') {
            $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
        }
        
        if(isset($client['c_country']) && (int) $client['c_country'] > 0)
        {
            $country_arr = pjBaseCountryModel::factory()
            ->select('t1.id, t2.content AS country_title')
            ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
            ->find($client['c_country'])->getData();
            if (!empty($country_arr))
            {
                $c_country = $country_arr['country_title'];
            }
        }
        
        $payment_method = NULL;
        if(pjObject::getPlugin('pjPayments') !== NULL)
        {
            $payment_titles = pjPayments::getPaymentTitles(1, $locale_id);
        }else{
            $payment_titles = __('payment_methods', true);
        }
        $payment_method = $payment_titles[$data['payment_method']];
        
        $search = array(
            '{Country}', '{City}', '{State}', '{Notes}',
            '{Zip}', '{Address1}', '{Address2}',
            '{Name}','{Email}',  '{Password}', '{Phone}', '{Company}', '{dCountry}',
            '{dCity}', '{dState}', '{dZip}', '{dAddress1}', '{dAddress2}', '{dNotes}',
            '{PaymentMethod}', '{DateTime}',
            '{Subtotal}', '{Delivery}', '{PackingFee}', '{Discount}','{Tax}',
            '{Total}', '{dNotes}', '{Location}','{pNotes}',
            '{OrderID}', '{CancelURL}', '{OrderDetails}');
        $replace = array(
            $c_country, pjSanitize::clean(@$client['c_city']), pjSanitize::clean(@$client['c_state']), pjSanitize::clean(@$client['c_notes']),
            pjSanitize::clean(@$client['c_zip']), pjSanitize::clean(@$client['c_address_1']), pjSanitize::clean(@$client['c_address_2']),
            pjSanitize::clean(@$auth_client['name']), pjSanitize::clean(@$auth_client['email']), pjSanitize::clean(@$auth_client['password']), pjSanitize::clean(@$auth_client['phone']), pjSanitize::clean(@$client['c_company']), $d_country,
            pjSanitize::clean(@$data['d_city']), pjSanitize::clean(@$data['d_state']), pjSanitize::clean(@$data['d_zip']), pjSanitize::clean(@$data['d_address_1']), pjSanitize::clean(@$data['d_address_2']), pjSanitize::clean(@$data['d_notes']),
            $payment_method, $date_time,
            $subtotal, $price_delivery, $price_packing, @$discount, @$tax,
            $total, pjSanitize::clean(@$data['d_notes']), @$data['location'], pjSanitize::clean(@$data['p_notes']),
            @$data['uuid'], $cancelURL, $order_data);
        
        return compact('search', 'replace');
    }
    
    public function addOrderDetails(&$arr, $locale_id)
    {
        $l_arr = pjLocationModel::factory()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $locale_id), 'left')
        ->select('t1.*, t2.content as name')
        ->find($arr['location_id'])
        ->getData();
        if (count($l_arr) > 0)
        {
            $arr['location'] = $l_arr['name'];
        }
        
        $pjOrderItemModel = pjOrderItemModel::factory();
        
        $arr['product_arr'] = $pjOrderItemModel
        ->reset()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.foreign_id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $locale_id), 'left')
        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.price_id AND t3.model = 'pjProductPrice' AND t3.locale = '%u' AND t3.field = 'price_name'", $locale_id), 'left')
        ->select('t1.*, t2.content as name, t3.content as size')
        ->where('t1.order_id', $arr['id'])
        ->where('type', 'product')
        ->findAll()
        ->getData();
        
        foreach ($arr['product_arr'] as $k => $product)
        {
            $arr['product_arr'][$k]['extra_arr'] = $pjOrderItemModel
            ->reset()
            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.foreign_id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $locale_id), 'left')
            ->select('t1.*, t2.content as name')
            ->where('t1.order_id', $arr['id'])
            ->where('type', 'extra')
            ->where('hash', $product['hash'])
            ->findAll()
            ->getData();
        }
    }
	
	static public function getDiscount($data, $option_arr)
	{
	    $resp = array();
	    if ((isset($data['vouchercode']) && !empty($data['vouchercode'])) || isset($data['voucher_code']) && !empty($data['voucher_code']))
	    {
            $voucher = !empty($data['vouchercode']) ? $data['vouchercode'] : $data['voucher_code'];
	        $_arr = pjVoucherModel::factory()
	        ->where('code', $voucher)
	        ->findAll()
	        ->getData();

            
	        if(count($_arr) > 0)
	        {
	            $arr = $_arr[0];
	            $dt = null;
	            $date = null;
	            
	            if (strtolower($data['origin']) == 'pos' || isset($data['type']))
	            {
                    if (!empty($data['p_date']) && !empty($data['pickup_time']))
                    {
                        
                        $p_time = $data['pickup_time'];

                        if(count(explode(" ", $p_time)) == 2)
                        {
                            list($_time, $_period) = explode(" ", $p_time);
                            $time = pjDateTime::formatTime($_time . ' ' . $_period, $option_arr['o_time_format']);
                        }else{
                            $time = pjDateTime::formatTime($p_time, $option_arr['o_time_format']);
                        }
                        
                        $date = $data['p_date'];
                        $dt = pjDateTime::formatDate($date,$option_arr['o_date_format']) . ' ' . $time;

                    }
	                
	            } else {
                    if (!empty($data['d_date']) && !empty($data['delivery_time']))
                    {   
                        
                        $d_time = $data['delivery_time'];
                        
                        if(count(explode(" ", $d_time)) == 2)
                        {
                            list($_time, $_period) = explode(" ", $d_time);
                            $time = pjDateTime::formatTime($_time . ' ' . $_period, $option_arr['o_time_format']);
                            
                        }else{
                           
                            $time = pjDateTime::formatTime($d_time, $option_arr['o_time_format']);
                        }
                       
                        $date = $data['d_date']; 
                        $dt = pjDateTime::formatDate($date,$option_arr['o_date_format']) . ' ' . $time;
                        
                    }
	            }
	            if ($dt != null)
	            {
	                $dt = strtotime($dt);
	                $valid = false;
	                switch ($arr['valid'])
	                {
	                    case 'fixed':
	                        $time_from = strtotime($arr['date_from'] . " " . $arr['time_from']);
	                        $time_to = strtotime($arr['date_to'] . " " . $arr['time_to']);
	                        if ($time_from <= $dt && $time_to >= $dt)
	                        {
	                            $valid = true;
	                        }
	                        break;
	                    case 'period':
	                        $t_from = strtotime($arr['date_from'] . " " . $arr['time_from']);
	                        $t_to = strtotime($arr['date_to'] . " " . $arr['time_to']);
	                        if ($t_from <= $dt && $t_to >= $dt)
	                        {
	                            $valid = true;
	                        }
	                        break;
	                    case 'recurring':
	                        $t_from = strtotime($date . " " . $arr['time_from']);
	                        $t_to = strtotime($date . " " . $arr['time_to']);
	                        if ($arr['every'] == strtolower(date("l", $dt)) && $t_from <= $dt && $t_to >= $dt)
	                        {
	                            $valid = true;
	                        }
	                        break;
	                }
	                if ($valid)
	                {
	                    $resp['voucher_code'] = $arr['code'];
	                    $resp['voucher_type'] = $arr['type'];
	                    $resp['voucher_discount'] = $arr['type'] == 'percent' ? (int) $arr['discount'] : $arr['discount'];
	                    $resp['code'] = 200;
	                }else{
	                    $resp['code'] = 102;
	                }
	            }else{
	                $resp['code'] = 103;
	            }
	        }else{
	            $resp['code'] = 101;
	        }
	    }else {
	        $resp['code'] = 100;
	    }
	    return $resp;
	}

	protected static function getAdminEmail()
	{
		$arr = pjAuthUserModel::factory()->select('t1.email')->find(1)->getData();
		
		return $arr ? $arr['email'] : NULL;
	}
	protected static function getAdminPhone()
	{
	    $arr = pjAuthUserModel::factory()->select('t1.phone')->find(1)->getData();
	    
	    return $arr ? $arr['phone'] : NULL;
	}
	/*
     * Returns the ID needed to fetch the Payment Options from pjPayments plugin.
     *
     * Scenario 1:
     *  - The script uses just one set of options, so the method returns NULL to fetch script's default options.
     *
     * Scenario 2:
     *  - The script uses multiple option sets, e.g. Vacation Rental Website.
     *    Then the method should find the related Property ID as each property has different payment options.
     */
    public function getPaymentOptionsForeignId($foreign_id)
    {
        return null;
    }
    
    protected function isAmPm()
    {
    	return strpos($this->option_arr['o_time_format'], 'a') !== false || strpos($this->option_arr['o_time_format'], 'A') !== false;
    }
    
    protected function getAmPmTime()
    {
    	if (!$this->isAmPm())
    	{
    		return 0;
    	}
    	
    	if (strpos($this->option_arr['o_time_format'], 'a') !== false)
    	{
    		return 1;
    	}
    	
    	return 2;
    }
    
    protected function getAmPmFormat()
    {
    	if (strpos($this->option_arr['o_time_format'], 'a') !== false)
    	{
    		return 'a';
    	}

    	return 'A';
    }
}
?>