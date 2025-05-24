<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontReview extends pjFront {
  public function pjActionCreate() {
  	$this->setAjax(true);
  	if (self::isPost() && $this->_post->toInt('review_create') && $this->_post->toInt('rating')) {
			$post = $this->_post->raw();
			$data = array();
			$data['product_id'] = $post['product_id'];
			$data['rating'] = $post['rating'];
			$data['review'] = $post['review'];
			$data['type'] = $post['type'];
			$data['review_title'] = $post['review_title'];
			$data['user_type'] = $post['user_type'];
			if ($data['user_type'] == 'guest') {
				$data['guest_ip'] = pjUtil::getClientIp();
				$data['guest_email'] = $post['guest_email'];
				$data['guest_un'] = $post['guest_un'];
			} else {
      	$data['user_id'] = $post['user_id'];
			}
			$data['is_accept_terms'] = $this->_post->toBool('terms');
			$rid = pjReviewModel::factory($data)->insert()->getInsertId();
			if ($data['type'] == 'Qr') {
				$redirect_link = APP_WEB_URL."/menu.php#!/loadMainQr";
			} else {
				$redirect_link = APP_WEB_URL."menu.php";
			}
			$_SESSION['msg'] = 'success';
			if ($data['user_type'] == 'client') {
				$pjAuthUserModel = pjAuthUserModel::factory()
				->join('pjClient', 't2.foreign_id = t1.id', 'left')
				->where('t1.id', $data['user_id'])
				->findAll()
				->getData();
				$_SESSION['reviewver'] = $pjAuthUserModel[0]['name'];
			} else {
        $_SESSION['reviewver'] = 'Guest';
			}
			
			if ($rid > 0 ) {
				pjUtil::redirect($redirect_link);
			}
		} else {
			if ($this->isXHR() || $this->_get->check('_escaped_fragment_')) {
				$product = $this->_get->toInt('p_id');
				$review_info = [$this->_get->toString('star'), $this->_get->toString('via'), $this->_get->toString('page')];
			
				$product_arr = pjProductModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->select("t1.*,t2.content AS name, (SELECT COUNT(TO.product_id) FROM `".pjReviewModel::factory()->getTable()."` AS `TO` WHERE `TO`.product_id=t1.id AND `TO`.status = 1) AS cnt_reviews")
				
				->where('t1.id', $product)
				->findAll()
				->getData();
				$this->set('product_arr', $product_arr);
				$this->set('review_info', $review_info);
				$this->set('status', 'OK');
			}
		}
  }
}
?>