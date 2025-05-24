<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminQrCode extends pjAdmin {
	public function pjActionFrontEnd() {
		$this->checkLogin();
		if (!pjAuth::factory()->hasAccess()) {
    	$this->sendForbidden();
	    return;
		}
		$arr = pjOptionModel::factory()
		->where('t1.foreign_id', 2)
		->orderBy('t1.order ASC')
		->findAll()
		->getData();

		$this->set('arr', $arr);

		$this->appendJs('pjAdminOptions.js');
		$this->appendCss('jasny-bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'jasny/');
		$this->appendJs('jasny-bootstrap.min.js',  PJ_THIRD_PARTY_PATH . 'jasny/');
  }
	 
	public function pjActionUpdateFrontOption() {

		$this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
	    
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid request.'));
    }
        
		$val = $this->_post->toString('value');
		$affected = pjOptionModel::factory()
					->reset()
					->where('foreign_id', 2)
					->where('`key`', 'front_qr')
					->modifyAll(array('value' => $val))
					->getAffectedRows();
		if ($affected == 1) {
			self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Front Option Updated'));
		} else {
			self::jsonResponse(array('status' => 'ERR', 'code' => 500, 'text' => 'Unexpected Error'));
		}
    exit;
	}

	public function pjActionUpdate() {
		$this->setAjax(true);
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    if (!$this->_post->toInt('qr_settings_update')) {
        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
    }
		$post = $this->_post->raw();
		$pjOptionsModel = pjOptionModel::factory();
	    $is_enable = isset($post['front_qr']) && $post['front_qr'] == 'on' ? '1' : '0';
		pjOptionModel::factory()->where('`key`', 'front_qr')->modifyAll(array('value'=>$is_enable));
		pjOptionModel::factory()->where('`key`', 'front_qr_url')->modifyAll(array('value'=>$post['qr_url']));
		if (isset($_FILES['qr_image'])) {
			if ($_FILES['qr_image']['error'] == 0) {
				if (getimagesize($_FILES['qr_image']["tmp_name"]) != false) {
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200) {
						$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
						if ($Image->load($_FILES['qr_image'])) {
							$resp = $Image->isConvertPossible();
							if ($resp['status'] === true) {
								$hash = md5(uniqid(rand(), true));
								$image_path = PJ_UPLOAD_PATH . 'qrcode/' . "QrCodeScanImage" .'.' . $Image->getExtension();
								$Image->loadImage($_FILES['qr_image']["tmp_name"]);
								$Image->resizeSmart(270, 270);
								$Image->saveImage($image_path);
								$pjOptionsModel->reset()->where('`key`', 'front_qr_img')->modifyAll(array('value'=>$image_path));
							}
						}
					}
				} else{
					$err = 'AP09';
				}
			}else if($_FILES['qr_image']['error'] != 4){
				$err = 'AP09';
			}
		}
    pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminQrCode&action=pjActionFrontEnd");
	}

}
?>