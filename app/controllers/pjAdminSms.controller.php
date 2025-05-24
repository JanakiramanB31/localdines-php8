<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminSms extends pjAdmin
{
	public function __construct()
	{
		$requireLogin = false;
		$_get = pjRegistry::getInstance()->get('_get');
		if (in_array($_get->toString('action'), array('pjActionIndex'))) {
			$requireLogin = true;
		}
		parent::__construct($requireLogin);
	}
    public function pjActionIndex()
    {
        $pjAuth = pjAuth::factory();

        if (self::isPost() && $this->_post->toInt('sms_post') == 1)
        {
            $pjBaseOptionModel = pjBaseOptionModel::factory();
            
            if (0 != $pjBaseOptionModel
                ->where('foreign_id', $this->getForeignId())
                ->where('`key`', 'plugin_sms_api_key')
                ->findCount()->getData()
                )
            {
                $pjBaseOptionModel
                ->limit(1)
                ->modifyAll(array(
                    'value' => $this->_post->toString('plugin_sms_api_key')
                ));
            } else {
                $pjBaseOptionModel->setAttributes(array(
                    'foreign_id' => $this->getForeignId(),
                    'key' => 'plugin_sms_api_key',
                    'tab_id' => '99',
                    'value' => $this->_post->toString('plugin_sms_api_key'),
                    'type' => 'string',
                    'is_visible' => 0
                ))->insert();
            }
            
            $pjBaseOptionModel->reset();
            if (0 != $pjBaseOptionModel
                ->where('foreign_id', $this->getForeignId())
                ->where('`key`', 'plugin_sms_country_code')
                ->findCount()->getData()
                )
            {
                $pjBaseOptionModel
                ->limit(1)
                ->modifyAll(array(
                    'value' => $this->_post->toString('plugin_sms_country_code')
                ));
            } else {
                $pjBaseOptionModel->setAttributes(array(
                    'foreign_id' => $this->getForeignId(),
                    'key' => 'plugin_sms_country_code',
                    'tab_id' => '99',
                    'value' => $this->_post->toString('plugin_sms_country_code'),
                    'type' => 'string',
                    'is_visible' => 0
                ))->insert();
            }
            
            $pjBaseOptionModel->reset();
            if (0 != $pjBaseOptionModel
                ->where('foreign_id', $this->getForeignId())
                ->where('`key`', 'plugin_sms_phone_number_length')
                ->findCount()->getData()
                )
            {
                $pjBaseOptionModel
                ->limit(1)
                ->modifyAll(array(
                    'value' => $this->_post->toString('plugin_sms_phone_number_length')
                ));
            } else {
                $pjBaseOptionModel->setAttributes(array(
                    'foreign_id' => $this->getForeignId(),
                    'key' => 'plugin_sms_phone_number_length',
                    'tab_id' => '99',
                    'value' => $this->_post->toString('plugin_sms_phone_number_length'),
                    'type' => 'string',
                    'is_visible' => 0
                ))->insert();
            }
            
            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSms&action=pjActionIndex&err=PSS01");
        }
        if(self::isGet())
        {
            $this->set('has_access_settings', $pjAuth->hasAccess('settings'));
            $this->set('has_access_list', $pjAuth->hasAccess('list'));
            
            $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
            // $this->appendJs('pjBaseSms.js', $this->getConst('PLUGIN_JS_PATH'));
            $this->appendJs('pjAdminCore.js');
            $this->appendJs('pjAdminSms.js');
        }
    }
    
    public function pjActionGetSms()
    {
        $this->setAjax(true);
        
        $this->checkLogin();
        
        $pjBaseSmsModel = pjBaseSmsModel::factory();
        
        if ($q = $this->_get->toString('q'))
        {
            $q = str_replace(array('%', '_'), array('\%', '\_'), $q);
            $pjBaseSmsModel->where("(t1.number LIKE '%$q%' OR t1.text LIKE '%$q%')");
        }
        
        $column = 'created';
        $direction = 'DESC';
        if ($this->_get->toString('direction') && $this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
        {
            $column = $this->_get->toString('column');
            $direction = strtoupper($this->_get->toString('direction'));
        }
        
        $total = $pjBaseSmsModel->findCount()->getData();
        $rowCount = $this->_get->toInt('rowCount') > 0 ? $this->_get->toInt('rowCount') : 10;
        $pages = ceil($total / $rowCount);
        $page = $this->_get->toInt('page') > 0 ? $this->_get->toInt('page') : 1;
        $offset = ((int) $page - 1) * $rowCount;
        if ($page > $pages)
        {
            $page = $pages;
        }
        
        $data = $pjBaseSmsModel->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
        $statuses = __('plugin_base_sms_statuses', true);
        foreach ($data as &$item)
        {
            if (!empty($item['created']))
            {
                $ts = strtotime($item['created']);
                $date = date('Y-m-d', $ts);
                $time = date('H:i:s', $ts);
                if (isset($this->option_arr['o_date_format']) && !empty($this->option_arr['o_date_format']))
                {
                    $date = date($this->option_arr['o_date_format'], $ts);
                }
                if (isset($this->option_arr['o_time_format']) && !empty($this->option_arr['o_time_format']))
                {
                    $time = date($this->option_arr['o_time_format'], $ts);
                }
                $item['created'] = $date . ', ' . $time;
            } else {
                $item['created'] = NULL;
            }
            
            $item['status'] = $item['status']==1 ? '<span class="success-color"><i class="fa fa-check-circle fa-2x" aria-hidden="true"></i></span>' : '<span class="failed-color"><i class="fa fa-times fa-2x" aria-hidden="true"></i></span>';
        }
        $data_old = $data;
        foreach ($data as &$item) {
          $number = $item["number"];
          $masked = preg_replace("/^\d{6}/", "******", $number);
          $item["number"] = $masked;
        }
        self::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
        
        exit;
    }
    
    public function pjActionTestSms()
    {
        $this->setAjax(true);
        
        $this->checkLogin();
        if (!pjAuth::factory('pjAdminSms', 'pjActionIndex_settings')->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
            
        if(!self::isPost())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'title' => __('plugin_base_sms_failed_to_send', true), 'text' => __('plugin_base_sms_test_invalid_method', true)));
        }
        if(!$this->_post->toString('plugin_sms_api_key'))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'title' => __('plugin_base_sms_failed_to_send', true), 'text' => __('plugin_base_sms_test_empty_api_key', true)));
        }
        if(!$this->_post->toString('number'))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'title' => __('plugin_base_sms_failed_to_send', true), 'text' => __('plugin_base_sms_test_empty_number', true)));
        }
        
        $number = $this->_post->toString('number');
        if($this->_post->check('plugin_sms_country_code') && !$this->_post->isEmpty('plugin_sms_country_code'))
        {
            $number = ltrim($number, '0');
            if($this->_post->check('plugin_sms_phone_number_length') && $this->_post->toInt('plugin_sms_phone_number_length') > 0)
            {
                if(strlen($number) < $this->_post->toInt('plugin_sms_phone_number_length'))
                {
                    $number = $this->_post->toString('plugin_sms_country_code') . $number;
                }
            }
        }
        $pjSmsApi = new tlSmsApi();
         $response = $pjSmsApi
        ->setApiKey($this->_post->toString('plugin_sms_api_key'))
        ->setNumber($number)
        ->setText(__('plugin_base_sms_test_message', true))
        ->setSender('TXTLOCAL')
        ->send();
        if($response['status'] == 'success')
        {
            $text = __('plugin_base_sms_test_sms_sent_to', true) . ' ' . $number;
            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'title' => __('plugin_base_sms_sent', true), 'text' => $text));
        }else{
            $statuses = __('plugin_base_sms_statuses', true);
            
            $text = isset($statuses[$response]) ? $statuses[$response] : $response;
            self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'title' => __('plugin_base_sms_failed_to_send', true), 'text' => $text));
        }
        exit;
    }
    
    public function pjActionVerify()
    {
        $this->setAjax(true);
        
        $this->checkLogin();
        if (!pjAuth::factory('pjAdminSms', 'pjActionIndex_settings')->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
        
        if(!self::isPost())
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_sms_key_text_ARRAY_100',true)));
        }
        if(!$this->_post->toString('plugin_sms_api_key'))
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('plugin_base_sms_key_text_ARRAY_101',true)));
        }
        $api_key = $this->_post->toString('plugin_sms_api_key');
        $ch = curl_init('https://api.voodoosms.com/sendsms');

        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $api_key
        ]);
        if ($response == 1) {
            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('plugin_base_sms_key_is_correct', true)));
        }
        else
        {
            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_sms_key_text_ARRAY_' . $response['code'],true)));
        }
    }
    
    public function pjActionSend()
    {
        $this->setAjax(true);
        
        $params = $this->getParams();
        if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT) ||
            !isset($params['number']) || !isset($params['text']) || !isset($this->option_arr['plugin_sms_api_key']))
        {
            return FALSE;
        }
        
        $pjSmsApi = new tlSmsApi();
        
        if (isset($params['type']))
        {
            $pjSmsApi->setType($params['type']);
        }
        
        $sender = DOMAIN;
        if(isset($params['sender']) && !empty($params['sender']))
        {
            $sender = $params['sender'];
        }
        
        $number = $params['number'];
        if(isset($this->option_arr['plugin_sms_country_code']) && !empty($this->option_arr['plugin_sms_country_code']))
        {
            $number = ltrim($number, '0');
            if(isset($this->option_arr['plugin_sms_phone_number_length']) && (int) $this->option_arr['plugin_sms_phone_number_length'] > 0)
            {
                if(strlen($number) < (int) $this->option_arr['plugin_sms_phone_number_length'])
                {
                    $number = $this->option_arr['plugin_sms_country_code'] . $number;
                }
            }
        }
        
        $response = $pjSmsApi
        ->setApiKey($this->option_arr['plugin_sms_api_key'])
        ->setNumber($number)
        ->setText($params['text'])
        ->setSender($sender)
        ->send();
        
        pjBaseSmsModel::factory()->setAttributes(array(
            'number' => $pjSmsApi->getNumber(),
            'text' => $pjSmsApi->getText(),
            'status' => $response
        ))->insert();
        
        return $response;
    }
    
    
}
?>