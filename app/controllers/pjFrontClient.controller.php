<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontClient extends pjAuth
{
    public $defaultClient = 'pjFoodDelivery_Client';
    
    public $defaultTmpUser = 'pjFoodDelivery_Temp_User';
    
    public function getClientByEmail()
    {
        $params = $this->getParams();
        $client = pjAuthUserModel::factory()->where('email', $params['email'])->where('role_id', 3)->findAll()->getDataIndex(0);
        if(!empty($client))
        {
            return $client;
        }else{
            return FALSE;
        }
    }
    public function getClientByPhoneNumber()
    {
        $params = $this->getParams();
        $client = pjAuthUserModel::factory()->where('phone', $params['phone'])->where('role_id', 3)->findAll()->getDataIndex(0);
        if(!empty($client))
        {
            return $client;
        }else{
            return FALSE;
        }
    }
    
    public function createClient()
    {
        $params = $this->getParams();

        $u_data = array();
        $u_data['is_active'] = 'T';
        $u_data['role_id'] = 3;
        $u_data['email'] = isset($params['c_email']) && $params['c_email']  ? $params['c_email'] : ":NULL";
        $u_data['password'] = isset($params['c_password']) ? $params['c_password'] : pjAuth::generatePassword($this->option_arr);
        $u_data['name'] = isset($params['c_name']) && $params['c_name']  ? $params['c_name'] : ":NULL";
        $u_data['u_surname'] = isset($params['surname']) && $params['surname'] ? $params['surname'] : ":NULL";
        $u_data['phone'] = isset($params['c_phone']) ? $params['c_phone'] : ":NULL";
        $u_data['status'] = isset($params['status']) ? $params['status'] : ":NULL";
        $u_data['ip'] = pjUtil::getClientIp();
        $u_data['is_active'] = 'T';
        
        $id = pjAuthUserModel::factory($u_data)->insert()->getInsertId();

        if ($id !== false && (int) $id > 0)
        {
            $client = pjFrontClient::init($u_data)->getClientByPhoneNumber();
            $clientByMail = pjFrontClient::init($u_data)->getClientByEmail();
            
            if($client != FALSE || $clientByMail != FALSE)
            {
                $c_data = array();
                $c_data['foreign_id'] = $id;
                $c_data['c_title'] = isset($params['c_title']) ? $params['c_title'] : ":NULL";
                $c_data['c_company'] = isset($params['c_company']) ? $params['c_company'] : ":NULL";
                $c_data['c_notes'] = isset($params['c_notes']) ? $params['c_notes'] : ":NULL";
                $c_data['c_address_1'] = isset($params['d_address_1']) ? (!empty($params['d_address_1'])  ? $params['d_address_1'] : ':NULL') : (isset($params['c_address_1']) ? (!empty($params['c_address_1'])  ? $params['c_address_1'] : ':NULL') : ":NULL");
                $c_data['c_address_2'] = isset($params['d_address_2']) ? (!empty($params['d_address_2'])  ? $params['d_address_2'] : ':NULL') : (isset($params['c_address_2']) ? (!empty($params['c_address_2'])  ? $params['c_address_2'] : ':NULL') : ":NULL");
                $c_data['c_country'] = isset($params['d_country_id']) ? (!empty($params['d_country_id'])  ? $params['d_country_id'] : ':NULL') : (isset($params['c_country']) ? (!empty($params['c_country'])  ? $params['c_country'] : ':NULL') : ":NULL");
                $c_data['c_state'] = isset($params['d_state']) ? (!empty($params['d_state'])  ? $params['d_state'] : ':NULL') : (isset($params['c_state']) ? (!empty($params['c_state'])  ? $params['c_state'] : ':NULL') : ":NULL");
                $c_data['c_city'] = isset($params['d_city']) ? (!empty($params['d_city'])  ? $params['d_city'] : ':NULL') : (isset($params['c_city']) ? (!empty($params['c_city'])  ? $params['c_city'] : ':NULL') : ":NULL");
                $c_data['c_postcode'] = isset($params['post_code']) ? (!empty($params['post_code'])  ? $params['post_code'] : ':NULL') : (isset($params['post_code']) ? (!empty($params['post_code'])  ? $params['post_code'] : ':NULL') : ":NULL");
                $c_data['c_type'] = isset($params['c_type']) ? $params['c_type'] : ":NULL";
                $c_data['mobile_delivery_info'] = isset($params['mobile_delivery_info'])? $params['mobile_delivery_info']: ":NULL";
                $c_data['mobile_offer'] = isset($params['mobile_offer'])?$params['mobile_offer']: ":NULL";
                $c_data['email_receipt'] = isset($params['email_receipt'])?$params['email_receipt']: ":NULL"; 
                $c_data['email_offer'] = isset($params['email_offer'])?$params['email_offer']: ":NULL";
                $c_data['register_type'] = isset($params['register_type']) ? $params['register_type'] : "T";
                $client_id = pjClientModel::factory()->setAttributes($c_data)->insert()->getInsertId();
                if ($client_id !== false && (int) $client_id > 0)
                {
                    pjFrontEnd::pjActionConfirmSend($this->option_arr, $client_id, PJ_SALT, 'account', $params['locale_id']);
                    return array('status' => "OK", 'code' => 200, 'client_id' => $client_id);
                }
                return array('status' => "OK", 'code' => 200);
            }
        }else{
            return array('status' => "ERR", 'code' => 100);
        }
    }
    
    public function setClientSession()
    {
        $params = $this->getParams();
        $client = pjClientModel::factory()->where('foreign_id', $params['id'])->findAll()->getDataIndex(0);
        if(!empty($client))
        {
            $user = pjAuth::init($params)->getUser();
            $client['client_id'] = $client['id'];
            unset($client['id']);
            $client = array_merge($user, $client);
            $this->session->unsetData($this->defaultClient);
            $this->session->setData($this->defaultClient, $client);
        }
    }
    
    public function doClientLogin()
    {
        $params = $this->getParams();
        
        if($this->session->has($this->defaultUser))
        {
            $this->session->setData($this->defaultTmpUser, $this->session->getData($this->defaultUser));
        }
        $params['is_backend'] = 'F';
        $response = pjAuth::init($params)->doLogin();
        if($response['status'] == 'OK')
        {
            if($this->isClient())
            {
                pjFrontClient::init(array('id' => $this->getUserId()))->setClientSession();
            }else{
                $response = array('status' => 'ERR', 'code' => '6');
            }
        }
        if($this->session->has($this->defaultTmpUser))
        {
            $this->session->setData($this->defaultUser, $this->session->getData($this->defaultTmpUser));
            $this->session->unsetData($this->defaultTmpUser);
        }
        return $response;
    }
    
    public function doSendPassword()
    {
        $params = $this->getParams();
        $user = pjFrontClient::init($params)->getClientByEmail();
        $response = array();
        if ($user == FALSE)
        {
            $response = array('status' => 'ERR', 'code' => 100);
        }else{
            $client = pjClientModel::factory()->where('foreign_id', $user['id'])->findAll()->getDataIndex(0);
            if ($user['status'] != 'T')
            {
                $response = array('status' => 'ERR', 'code' => 101);
            }else{
                $locale_id = $this->getLocaleId();
                if(isset($params['locale_id']) && (int) $params['locale_id'] > 0)
                {
                    $locale_id = $params['locale_id'];
                }
                pjFrontEnd::pjActionConfirmSend($this->option_arr, $client['id'], PJ_SALT, 'forgot', $locale_id);
                $response = array('status' => 'OK', 'code' => 200);
            }
        }
        return $response;
    }
}
?>