<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReports extends pjAdmin {		
	public function pjActionIndex() {
  	$this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $location_arr = pjLocationModel::factory()
      ->select('t1.*, t2.content as name')
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->orderBy("name ASC")
      ->findAll()
      ->getData();
      
      $date_from = date('Y-m-d', time() - 30 * 86400 );
      $date_to = date('Y-m-d');
      
      $this->set('location_arr', $location_arr);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}
	
	public function pjActionPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    
    $date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
    $date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
    $location_id = $this->_get->toInt('location_id');
    
    if($location_id > 0) {
      $location_arr = pjLocationModel::factory()
      ->select('t1.*, t2.content as name')
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->find($location_id)
      ->getData();
      $this->set('location_arr', $location_arr);
    }
    
    $this->getReportData($location_id, $date_from, $date_to);
	}
	
	public function pjActionGenerate() {
		$this->setAjax(true);
		if (!$this->isXHR()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		if (!self::isPost()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}
		if (!pjAuth::factory()->hasAccess()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		if (!($this->_post->toInt('generate_report') && $this->_post->toString('date_from') && $this->_post->toString('date_to'))) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
		}
		$date_from = pjDateTime::formatDate($this->_post->toString('date_from'), $this->option_arr['o_date_format']);
		$date_to = pjDateTime::formatDate($this->_post->toString('date_to'), $this->option_arr['o_date_format']);
		$location_id = $this->_post->toInt('location_id');
		$this->getReportData($location_id, $date_from, $date_to);
	}
	
	protected function getReportData($location_id, $date_from, $date_to) {
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderModel->where(sprintf("( (DATE(p_dt) BETWEEN '%1\$s' AND '%2\$s') OR (DATE(d_dt) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));
    $location_clause = "";
    if($location_id > 0)
    {
        $pjOrderModel->where('t1.location_id', $location_id);
        $location_clause = sprintf(" AND `TO`.location_id=%u", $location_id);
    }
    $total_orders = $pjOrderModel->findCount()->getData();
    
    $pjOrderModel->where('t1.status', 'confirmed');
    $confirmed_orders = $pjOrderModel->findCount()->getData();
    $confirmed_arr = $pjOrderModel->findAll()->getData();
    $order_id_arr = $pjOrderModel->getDataPair(null, 'id');
    
    $unique_clients = 0;
    $first_time_clients = 0;
    
    $client_id_arr = array();
    
    $total_amount = 0;
    $delivery_fee = 0;
    $packing_fee = 0;
    $tax = 0;
    $discount = 0;
    foreach($confirmed_arr as $v) {
    	$total_amount += $v['total'];
      $delivery_fee += $v['price_delivery'];
      $packing_fee += $v['price_packing'];
      $tax += $v['tax'];
      $discount += $v['discount'];
      $client_id_arr[] = $v['client_id'];
    }
    
    $pjOrderModel->where('t1.type', 'pickup');
    $pickup_orders = $pjOrderModel->findCount()->getData();
    $delivery_orders = $confirmed_orders - $pickup_orders;
    
    $unique_id_arr = array_unique($client_id_arr);
    $unique_clients = count($unique_id_arr);
    $duplicated_clients = array_diff_assoc($client_id_arr, $unique_id_arr);
    $first_time_clients = count(array_diff($client_id_arr, $duplicated_clients));
    $more = 0;
    if(!empty($duplicated_clients)) {
      $pjOrderModel->reset();
      $pjOrderModel->where("( (DATE(p_dt) < '$date_from') OR (DATE(d_dt) < '$date_from') )");
      if((int) $location_id != '' > 0) {
        $pjOrderModel->where('t1.location_id', $location_id);
      }
      $previous_id_arr = $pjOrderModel->whereIn('t1.client_id', array_unique($duplicated_clients))->findAll()->getDataPair(null, 'client_id');
      $more = count(array_unique($duplicated_clients)) - count(array_unique($previous_id_arr));
    }
    $first_time_clients = $first_time_clients + $more;
    
    $total_products = 0;
    if(!empty($order_id_arr)) {
      $product_arr = pjOrderItemModel::factory()->where('t1.type', 'product')->whereIn('t1.order_id', $order_id_arr)->findAll()->getData();
      foreach($product_arr as $v) {
        $total_products += (int)$v['cnt'];
      }
    }
	    
    $order_item_tbl = pjOrderItemModel::factory()->getTable();
    $product_category_tbl = pjProductCategoryModel::factory()->getTable();
	    
    $category_arr = pjCategoryModel::factory()
	    ->select(sprintf("t1.*, t2.content AS name,
							(SELECT SUM(`TOI`.price * `TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%4\$s' AND '%5\$s') OR (DATE(d_dt) BETWEEN '%4\$s' AND '%5\$s'))%6\$s ) AND `TOI`.foreign_id IN (SELECT `TPC`.product_id FROM `%3\$s` AS `TPC` WHERE TPC.category_id=t1.id ) ) AS total_amount,
							(SELECT SUM(`TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%4\$s' AND '%5\$s') OR (DATE(d_dt) BETWEEN '%4\$s' AND '%5\$s'))%6\$s ) AND `TOI`.foreign_id IN (SELECT `TPC`.product_id FROM `%3\$s` AS `TPC` WHERE TPC.category_id=t1.id ) ) AS total_products", $order_item_tbl, $pjOrderModel->getTable(), $product_category_tbl, $date_from, $date_to, $location_clause))
		->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
		->where('t1.status', 'T')
		->orderBy("`order` ASC")
		->findAll()
		->getData();
		
		$product_arr = pjProductModel::factory()
		->select(sprintf("t1.*, t2.content AS name,
    		(SELECT SUM(`TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%3\$s' AND '%4\$s') OR (DATE(d_dt) BETWEEN '%3\$s' AND '%4\$s'))%5\$s ) AND `TOI`.foreign_id=t1.id ) AS total_products,
    		(SELECT SUM(`TOI`.price * `TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%3\$s' AND '%4\$s') OR (DATE(d_dt) BETWEEN '%3\$s' AND '%4\$s'))%5\$s ) AND `TOI`.foreign_id=t1.id ) AS total_amount", $order_item_tbl,$pjOrderModel->getTable(), $date_from, $date_to, $location_clause))
		->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
		->orderBy("total_products DESC")
		->limit(10)
		->findAll()
		->getData();
		
		$this->set('total_orders', $total_orders);
		$this->set('confirmed_orders', $confirmed_orders);
		$this->set('pickup_orders', $pickup_orders);
		$this->set('delivery_orders', $delivery_orders);
		
		$this->set('unique_clients', $unique_clients);
		$this->set('first_time_clients', $first_time_clients);
		
		$this->set('total_products', $total_products);
		$this->set('category_arr', $category_arr);
		$this->set('product_arr', $product_arr);
		
		$this->set('price_info', compact('total_amount', 'delivery_fee', 'tax', 'discount', 'packing_fee'));
	}

	public function pjActionPOSXIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $data = $this->getPOSReportData(0, $date_from, $date_to);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      $this->set('num_of_sales', $data['num_of_sales']); 
      $this->set('report_title', "X Report"); 
      $this->set('sales_report', $data);
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}

	public function pjActionPOSGenerate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setAjax(true);
    if (self::isPost() && $this->_post->toInt('date_from') && $this->_post->toString('date_to')) {
    	$date_from = pjDateTime::formatDate($this->_post->toString('date_from'), $this->option_arr['o_date_format']);
			$date_to = pjDateTime::formatDate($this->_post->toString('date_to'), $this->option_arr['o_date_format']);
      echo $report_type = $this->_post->toString('report_type');
      if ($report_type == 'zReport') {
        $data = $this->getPOSReportData(1, $date_from, $date_to);
      } else {
        $data = $this->getPOSReportData(0, $date_from, $date_to);
      }
	    $this->set('date_from', $date_from);
	    $this->set('date_to', $date_to);
	    $this->set('report_title', "X Report");
	    $this->set('num_of_sales', $data['num_of_sales']);
	    $this->set('sales_report', $data);
  	}
	}

	public function pjActionPOSZIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $data = $this->getPOSReportData(true, $date_from, $date_to);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      $this->set('report_title', "Z Report");
      $this->set('num_of_sales', $data['num_of_sales']);
      $this->set('sales_report', $data);
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}

	public function pjActionPOSXPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    if (self::isGet()) {
    	$date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
			$date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
		} else {
			$date_from = date('Y-m-d');
    	$date_to = date('Y-m-d');
		}
    $data = $this->getPOSReportData(0, $date_from, $date_to);
    $this->set('date_from', $date_from);
    $this->set('date_to', $date_to);
    $this->set('num_of_sales', $data['num_of_sales']);
    $this->set('sales_report', $data);
	}

	public function pjActionPOSZPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    $date_from = date('Y-m-d');
    $date_to = date('Y-m-d');
    $data = $this->getPOSReportData(1, $date_from, $date_to);
    $date_from = $date_to = date('Y-m-d');
    $origin = 'Pos';
    $this->set('date_from', $date_from);
    $this->set('date_to', $date_to);
    $this->set('num_of_sales', $data['num_of_sales']);
    $this->set('sales_report', $data);
	}

	public function pjActionUpdateZViewReport() {
		$this->checkLogin();
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
		$order_ids = $this->_post->toString('order_ids');
    $expense_ids = $this->_post->toString('expense_ids');
    $income_ids = $this->_post->toString('income_ids');
    $return_order_ids = $this->_post->toString('return_order_ids');
		if ($order_ids) {
			$order_ids_arr = explode(',', $order_ids);
			$pjOrderModel = pjOrderModel::factory();
	    $pjOrderModel->whereIn('id', $order_ids_arr);
	    $pjOrderModel->modifyAll(array('is_z_viewed'=>1))->getAffectedRows();
		} 
    if ($expense_ids) {
      $expense_ids = explode(',', $expense_ids);
      $pjExpenseModel = pjExpenseModel::factory();
      $pjExpenseModel->whereIn('id', $expense_ids);
      $pjExpenseModel->modifyAll(array('is_z_viewed'=>1))->getAffectedRows();
    }
    if ($income_ids) {
      $income_ids = explode(',', $income_ids);
      $pjIncomeModel = pjIncomeModel::factory();
      $pjIncomeModel->whereIn('id', $income_ids);
      $pjIncomeModel->modifyAll(array('is_z_viewed'=>1))->getAffectedRows();
    } 
    if ($return_order_ids) {
      $return_order_ids = explode(',', $return_order_ids);
      $pjOrderReturn = pjOrderReturnModel::factory();
      $pjOrderReturn->whereIn('id', $return_order_ids);
      $pjOrderReturn->modifyAll(array('is_z_viewed'=>1))->getAffectedRows();
    } 

   	self::jsonResponse(array(
      'status' => 'OK',
      'code' => 200,
      'text' => 'Order(s) has been updated.'
    ));
	}

	public function getPOSReportData($zReport = 0, $date_from, $date_to) {
    $origin = 'Pos';
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderModel->where(sprintf("( (DATE(p_dt) BETWEEN '%1\$s' AND '%2\$s') OR (DATE(d_dt) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));
    $location_clause = "";
    $pjExpenseModel = pjExpenseModel::factory();
    $pjExpenseModel->where(sprintf("( (DATE(expense_date) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));

    $pjIncomeModel = pjIncomeModel::factory();
    $pjIncomeModel->where(sprintf("( (DATE(income_date) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));

    $pjOrderReturn = pjOrderReturnModel::factory();
    $pjOrderReturn->where(sprintf("( (DATE(created_at) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));

    $pjOrderModel->where('t1.deleted_order', 0);
    if ($zReport) {
    	$pjOrderModel->where('t1.is_z_viewed', 0);
      $pjExpenseModel->where('t1.is_z_viewed', 0);
      $pjIncomeModel->where('t1.is_z_viewed', 0);
      $pjOrderReturn->where('t1.is_z_viewed', 0);
    }
    $pjOrderModel->where('t1.status', 'delivered');
    $num_of_sales = $confirmed_orders = $pjOrderModel->findCount()->getData();
    $confirmed_arr = $pjOrderModel->findAll()->getData();
    $order_id_arr = $pjOrderModel->getDataPair(null, 'id');
    $report_order_ids = implode(',', $order_id_arr);
    $unique_clients = 0;
    $first_time_clients = 0;
    $client_id_arr = array();
    $total_amount = 0;
    $delivery_fee = 0;
    $packing_fee = 0;
    $tax = 0;
    $discount = 0;
    $num_of_table_sales = 0;
    $num_of_direct_sales = 0;
    $total_table_sales = 0;
    $total_direct_sales = 0;
    $num_of_card_sales = 0;
    $num_of_cash_sales = 0;
    $card_sales = 0;
    $cash_sales = 0;
    $num_of_web_sales = 0;
    $num_of_pos_sales = 0;
    $num_of_telephone_sales = 0;
    $num_of_expenses = 0;
    $total_expenses = 0;
    $total_supplier_exp = 0;
    $num_of_incomes = 0;
    $total_incomes = 0;
    $total_return_orders = 0;
    foreach($confirmed_arr as $v) {
      $payment_method = strtolower($v['payment_method']);
      $total_amount += $v['total'];
      if (strtolower($v['table_name']) === 'take away') {
      	$num_of_direct_sales++;
      	$total_direct_sales += $v['total'];
      } else {
      	$num_of_table_sales++;
      	$total_table_sales += $v['total'];
      }
      if (in_array($payment_method, array('card', 'bank'))) {
        $num_of_card_sales++;
        $card_sales += $v['total'];
      } elseif($payment_method === 'split') {
        $num_of_card_sales++;
        $num_of_cash_sales++;
        $card_sales = $card_sales += $v['total'] - $v['cash_amount'];
        $cash_sales += $v['cash_amount'];
      } else {
        $num_of_cash_sales++;
        $cash_sales += $v['total'];
      }
      switch (strtolower($v['origin'])) {
      	case "pos":
      		$num_of_pos_sales++;
      	break;
      	case "telephone":
      		$num_of_telephone_sales++;
      	break;
      	case "web":
      		$num_of_web_sales++;
      	break;
      }
      $delivery_fee += $v['price_delivery'];
      $packing_fee += $v['price_packing'];
      $tax += $v['tax'];
      $discount += $v['discount'];
      $client_id_arr[] = $v['client_id'];
    }

    //Expenses
    
    $num_of_expenses  = $pjExpenseModel->findCount()->getData();
    $total_expenses_arr = $pjExpenseModel->select("id, amount")->findAll()->getData();
    $expense_ids = "";
    if ($total_expenses_arr) {
      $expense_id_arr = array_column($total_expenses_arr,'id');
      $expense_ids = implode(',', $expense_id_arr);
      $total_supplier_exp = array_sum(array_column($total_expenses_arr,'amount'));
    }

    //Incomes
    
    $num_of_incomes  = $pjIncomeModel->findCount()->getData();
    $total_incomes_arr = $pjIncomeModel->select("id, amount")->findAll()->getData();
    $income_ids = "";
    if ($total_incomes_arr) {
      $income_id_arr = array_column($total_incomes_arr,'id');
      $income_ids = implode(',', $income_id_arr);
      $total_incomes = array_sum(array_column($total_incomes_arr,'amount'));
    }

    // Return Order
    $num_of_return_orders = $pjOrderReturn->findCount()->getData();
    $total_return_order_arr = $pjOrderReturn->select("id, amount")->findAll()->getData();
    $return_order_ids = "";
    if($total_return_order_arr) {
      $return_order_id_arr = array_column($total_return_order_arr,'id');
      $return_order_ids = implode(',', $return_order_id_arr);
      $total_return_orders = array_sum(array_column($total_return_order_arr,'amount'));
    }
    
    // Cash in hand
    $cash_in_hand = 0;
    $total_expenses = $total_supplier_exp + $total_return_orders;
    // $cash_in_hand = $total_amount - $total_expenses + $total_incomes;
    $cash_in_hand = $cash_sales - $total_expenses + $total_incomes;

    return compact('total_amount', 'num_of_direct_sales', 'total_direct_sales', 'num_of_table_sales', 'total_table_sales', 'num_of_sales', 'num_of_card_sales', 'num_of_cash_sales', 'card_sales', 'cash_sales', 'report_order_ids', 'num_of_pos_sales', 'num_of_web_sales', 'num_of_telephone_sales' ,'num_of_incomes','total_incomes', 'income_ids', 'num_of_expenses', 'total_supplier_exp', 'total_expenses', 'num_of_return_orders', 'total_return_orders', 'expense_ids', 'return_order_ids', 'cash_in_hand');
	}


  public function pjActionCancelReturnReport() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $today = date('Y-m-d');
    $from = $today . " " . "00:00:00";
    $to = $today . " " . "23:59:59";
    $res = $this->getReturnOrdersTotal($from, $to, '');
    
    $this->set('adminReturnOrderTotal', $res['adminReturnOrderTotal']);
    $this->set('dailyReturnOrderTotal', $res['dailyReturnOrderTotal']);
    $this->set('overAllReturnOrderTotal', $res['overAllReturnOrderTotal']);
    $this->set('loadData', "CancelRetrun");
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionIncomeReport() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $today = date('Y-m-d');
    $from = $today . " " . "00:00:00";
    $to = $today . " " . "23:59:59";
    $res = $this->getIncomeOrders($from, $to, "", 10, 1);

    $this->set('overAllIncomeTotal', $res['overAllIncomeTotal']);
    $this->set('loadData', "Income");
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionGetIncomeReport() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {

        $today = date('Y-m-d');
        $from = $today . " " . "00:00:00";
        $to = $today . " " . "23:59:59";
        
        if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
            $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
            $from = $date_from->format('Y-m-d'). " " . "00:00:00";
            $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
            $to = $date_to->format('Y-m-d'). " " . "23:59:59";
        }
        
        $res = $this->getIncomeOrders($from, $to, $q = $this->_get->toString('q'), $this->_get->toInt('rowCount'), $this->_get->toInt('page'));
        $data = $res['data'];
        $total = $res['total'];
        $pages = $res['pages'];
        $page = $res['page'];
        $rowCount = $res['rowCount'];
        $column = $res['column'];
        $direction = $res['direction'];

        pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function getIncomeCount() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {
      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
        $from = $date_from->format('Y-m-d'). " " . "00:00:00";
        $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
        $to = $date_to->format('Y-m-d'). " " . "23:59:59";
      }
      $res = $this->getIncomeOrders($from, $to, $q = $this->_get->toString('q'), $this->_get->toInt('rowCount'), $this->_get->toInt('page'));

      $overAllIncomeTotal = $res['overAllIncomeTotal'];

      pjAppController::jsonResponse(compact('overAllIncomeTotal'));
    }
  }

  public function pjActionExpenseReport() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $today = date('Y-m-d');
    $from = $today . " " . "00:00:00";
    $to = $today . " " . "23:59:59";
    $res = $this->getExpenseOrders($from, $to, "", 10, 1);

    $this->set('overAllExpenseTotal', $res['overAllExpenseTotal']);
    $this->set('loadData', "Expense");
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionGetExpenseReport() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {

        $today = date('Y-m-d');
        $from = $today . " " . "00:00:00";
        $to = $today . " " . "23:59:59";
        
        if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
            $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
            $from = $date_from->format('Y-m-d'). " " . "00:00:00";
            $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
            $to = $date_to->format('Y-m-d'). " " . "23:59:59";
        }
        
        $res = $this->getExpenseOrders($from, $to, $q = $this->_get->toString('q'), $this->_get->toInt('rowCount'), $this->_get->toInt('page'));
        $data = $res['data'];
        $total = $res['total'];
        $pages = $res['pages'];
        $page = $res['page'];
        $rowCount = $res['rowCount'];
        $column = $res['column'];
        $direction = $res['direction'];

        pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function getExpenseCount() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {
      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
        $from = $date_from->format('Y-m-d'). " " . "00:00:00";
        $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
        $to = $date_to->format('Y-m-d'). " " . "23:59:59";
      }
      $res = $this->getExpenseOrders($from, $to, $q = $this->_get->toString('q'), $this->_get->toInt('rowCount'), $this->_get->toInt('page'));

      $overAllExpenseTotal = $res['overAllExpenseTotal'];

      pjAppController::jsonResponse(compact('overAllExpenseTotal'));
    }
  }

  public function pjActionTopProductsReport() {

    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;

    }
    $date_to = date('Y-m-d');
    $date_from = date('Y-m-d', strtotime('-3 month'));
    $loadData = $this->_get->toString('loadData');

    $categories = pjCategoryModel::factory()
    ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
    ->where('t1.status', 'T')
    ->select(sprintf("t1.*, t2.content AS name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
    ->findAll()
    ->getData();
    $this->set('date_from', $date_from);
    $this->set('date_to', $date_to);  
    $this->set('categories', $categories);
    $this->set('loadData', $loadData);


    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionGetTopProductsReport() {
    $this->setAjax(true);
  
    if ($this->isXHR())
    {
      $date_from = date('Y-m-d 00:00:00', strtotime('-3 month'));
      $date_to = date('Y-m-d 23:59:59');
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        $date_from = date('Y-m-d 00:00:00', strtotime($this->_get->toString('date_from')));
        $date_to = date('Y-m-d 23:59:59', strtotime($this->_get->toString('date_to')));
      }
      
      $pjOrderItemModel = pjOrderItemModel::factory()
        ->where("(t1.order_id IN (SELECT TPC.id FROM `".pjOrderModel::factory()->getTable()."` AS TPC WHERE TPC.status = 'delivered'  AND TPC.created >='".$date_from."' AND TPC.created <='".$date_to."'))")
        ->where('t1.status', null)
        ->where('t1.type', 'product')
        ->join('pjProduct', "t2.id = t1.foreign_id", 'left')
        ->join('pjMultiLang', "t3.foreign_id = t1.foreign_id AND t3.model = 'pjProduct' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'name'", 'left')
        ->groupBy('t1.foreign_id')
        ->orderBy('count DESC');
      
      if ($this->_get->toString('q')) {
        $q = $this->_get->toString('q');
        $pjOrderItemModel->where("(t3.content LIKE '%$q%')");
      }
      if ($category_id = $this->_get->toInt('category_id'))
      {
          $pjOrderItemModel->where("(t2.id IN (SELECT TPC.product_id FROM `".pjProductCategoryModel::factory()->getTable()."` AS TPC WHERE TPC.category_id='".$category_id."'))");
      }

      $column = 'count';
      $direction = 'DESC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
      {
          $column = $this->_get->toString('column');
          $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjOrderItemModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $this->_get->toInt('page') ?: 1;
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages)
      {
        $page = $pages;
      }
      
      $data = $pjOrderItemModel
        ->select('t1.foreign_id, t3.content as name, t2.image, COUNT(*) as count')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData(); 
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetNonProductsReport() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $date_from = date('Y-m-d 00:00:00', strtotime('-3 month'));

      $pjOrderItemModel = pjOrderItemModel::factory()
        ->where("(t1.order_id IN (SELECT TPC.id FROM `".pjOrderModel::factory()->getTable()."` AS TPC WHERE TPC.status = 'delivered'  AND TPC.created >='".$date_from."'))")
        ->where('t1.status', null)
        ->where('t1.type', 'product')
        ->select('t1.foreign_id')
        ->groupBy('t1.foreign_id')
        ->findAll()
        ->getData(); 
      $order_ids = implode(", ",array_column($pjOrderItemModel, 'foreign_id'));
      $pjProductModel = pjProductModel::factory();
      if ($order_ids) {
        $pjProductModel->where("(t1.id NOT IN ($order_ids))");
      }
        
      $pjProductModel->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
      ->groupBy('t1.id');
      
      if ($this->_get->toString('q')) {
        $q = $this->_get->toString('q');
        $pjProductModel->where("(t2.content LIKE '%$q%')");
      }
      if ($category_id = $this->_get->toInt('category_id')) {
        $pjProductModel->where("(t1.id IN (SELECT TPC.product_id FROM `".pjProductCategoryModel::factory()->getTable()."` AS TPC WHERE TPC.category_id='".$category_id."'))");
      }

      $column = 'count';
      $direction = 'DESC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
          $column = $this->_get->toString('column');
          $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjProductModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $this->_get->toInt('page') ?: 1;
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages) {
        $page = $pages;
      }
      
      $data = $pjProductModel
        ->select('t1.id as foreign_id, t2.content as name, t1.image, 0 as count')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData(); 
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetCancelReturnOrders() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {

      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
        $from = $date_from->format('Y-m-d'). " " . "00:00:00";
        $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
        $to = $date_to->format('Y-m-d'). " " . "23:59:59";
      }
      $res = $this->getReturnOrders($from, $to, $this->_get->toString('type'), $q = $this->_get->toString('q'), $this->_get->toInt('rowCount'), $this->_get->toInt('page'));
      $data = $res['data'];
      $total = $res['total'];
      $pages = $res['pages'];
      $page = $res['page'];
      $rowCount = $res['rowCount'];
      $column = $res['column'];
      $direction = $res['direction'];

      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }
  public function getReturnOrdersCount() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {
      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
        $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
        $from = $date_from->format('Y-m-d'). " " . "00:00:00";
        $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
        $to = $date_to->format('Y-m-d'). " " . "23:59:59";
      }
      $res = $this->getReturnOrdersTotal($from, $to, $q = $this->_get->toString('q'));

      $adminReturnOrderTotal = $res['adminReturnOrderTotal'];
      $dailyReturnOrderTotal = $res['dailyReturnOrderTotal'];
      $overAllReturnOrderTotal = $res['overAllReturnOrderTotal'];

      pjAppController::jsonResponse(compact('adminReturnOrderTotal', 'dailyReturnOrderTotal', 'overAllReturnOrderTotal'));
    }
  }

  public function pjActionGetCancelOrderInfo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_get->toInt('id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_get->toInt('id');
        $order_type = strtolower($this->_get->toString('type'));
        $pjOrderModel = pjOrderModel::factory()->where('t1.deleted_order', 0)
          ->join('pjClient', "t2.id=t1.client_id", 'left outer')
          ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
        $order = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
              AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,  
              AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
              AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
              AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
        ->find($id)
        ->getData();
        $this->getOrderItems($id, false);
        $role_id = $this->getRoleId();

        if ($order["surname"] == '' || is_null($order["surname"]) || $order["surname"] === 0) {
          $order["surname"] = $order["surname"] = $order["first_name"];
        }
        if ($role_id != ADMIN_R0LE_ID && $order['status'] == 'delivered') {  
          if ($order["surname"]) {
            $order["surname"] = substr($order["surname"], 0, 2).str_repeat("*", 10); 
          }
          if ($order["sms_email"]) {
            $order["sms_email"] = substr($order["sms_email"], 0, 2).str_repeat("*", (strLen($order['sms_email']) - 2));
          }
          if ($order["phone_no"]) {
            $order["phone_no"] = substr($order["phone_no"], 0, 2).str_repeat("*", (strLen($order['phone_no']) - 2));
          }
        }
        if($order["d_address_2"]) {
          $address = $order["d_address_1"].",<br/>".$order["d_address_2"].",<br/>".$order["d_city"];
        } else { 
          $address = $order["d_address_1"].",<br/>".$order["d_city"];
        }
        $order['sms_sent_time'] = $order['sms_sent_time'] == ''  ?  "-" : date("d-M-Y H:m:s", strtotime($order['sms_sent_time']));
        $order['delivered_time'] = $order['delivered_time'] == ''  ? "-" : date("d-M-Y H:m:s", strtotime($order['delivered_time']));
        if ($order['client_id'] == NULL && $order['origin'] == "web") {
          $order['c_type'] = "guest";
        }
        $order['address'] = $address;
        $this->set('order_details', $order);
        $this->set('order_type', $order_type);
       
      }
    }
  }

  public function pjActionGetAdminReturnOrderInfo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_get->toInt('id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_get->toInt('id');
        $pjOrderReturn = pjOrderReturnModel::factory()
          ->find($id)
          ->getData();

        if($pjOrderReturn['product_id'] != 0) {
          $pjOrderReturn = pjOrderReturnModel::factory()
            ->select("t1.*, t2.content as product_name")
            ->join('pjMultiLang', "t2.model='pjProduct' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='" . $this->getLocaleId() . "'", 'left outer')
            ->find($id)
            ->getData();
          
        }
        $this->set('data', $pjOrderReturn);
      }
    }
  }

  public function pjActionCheckOrderTime() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $today = date( 'y-m-d', time ());
      $today = $today." "."00:00:00";
      $create = pjOrderModel::factory()
        ->select('t1.created , t1.order_id')
        ->where('t1.status', 'pending')
        ->where('t1.deleted_order', '0')
        ->where("(t1.created >= '$today')")
        ->findAll()
        ->getData();
      $ids = [];
      foreach ($create as $k => $created) {
        $new = strtotime($created['created']);
        $current_time = time();
        $time_difference = $current_time - $new;
        array_push($ids, $created['order_id']);
      }
      if(count($ids)) {
        return self::jsonResponse(array('status' => 'true', 'orders' => $ids));
      } else {
        return self::jsonResponse(array('status' => 'false', 'orders' => 'no pending orders'));
      }
    }
  }

  protected function getReturnOrders($date_from, $date_to, $type, $query, $rowCount, $page) {
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderReturn = pjOrderReturnModel::factory();

    if($query) {
      // MEGAMIND
      $pjOrderModel->where("(t1.order_id LIKE '%$query%' OR t1.uuid LIKE '%$query%' OR t1.table_name LIKE '%$query%')");
      $pjOrderReturn->where("(t1.order_id LIKE '%$query%' OR t1.product_name LIKE '%$query%')");
      // !MEGAMIND
    }

    $return_types = implode("','", RETURN_TYPES);
    $pjOrderModel = $pjOrderModel
      ->select("t1.*, 'RO' as type")
      ->where("((t1.p_dt >= '$date_from' AND t1.p_dt <= '$date_to') OR (t1.d_dt >= '$date_from' AND t1.d_dt <= '$date_to'))")
      ->where('t1.deleted_order', 0)
      ->where("t1.id IN (SELECT ORDITEM.order_id FROM `" . pjOrderItemModel::factory()
      ->getTable() . "` AS ORDITEM WHERE ORDITEM.STATUS IN ('".$return_types."'))")->orderBy("name ASC")
      ->where('status', 'delivered');

    $pjOrderReturn = $pjOrderReturn
      ->select("id, order_id, 'Return Order' as table_name, amount as cancel_amount, amount as total, created_at as created, 'delivered' as status, '-' as payment_method, 'AR' as type")
      ->where("created_at >= '$date_from' OR updated_at >= '$date_from'");

    $column = 'id';
    $direction = 'desc';
    
    if($type == "AR") {
      $total = $pjOrderReturn->findCount()->getData();
    } else {
      $total = $pjOrderModel->findCount()->getData();
    }

    $rowCount = $rowCount ?: 10;
    $pages = ceil($total / $rowCount);
    if ($page) {
      $page = $this->_get->toInt('page') ?: 1;
    } else {
      $page = 1;
    }
     
    $offset = ((int) $page - 1) * $rowCount;
    if ($page > $pages) {
      $page = $pages;
    }

    $pjOrderModel = $pjOrderModel
    ->orderBy("$column $direction")
    ->limit($rowCount, $offset)
    ->findAll()
    ->getData();

    $pjOrderReturn = $pjOrderReturn
    ->orderBy("$column $direction")
    ->limit($rowCount, $offset)
    ->findAll()
    ->getData();

    $data = array_merge($pjOrderModel, $pjOrderReturn);

    $order_ids = array_column($pjOrderModel, 'id');
    $pjOrderData = "";
    $pjOrderItems = pjOrderItemModel::factory();
    if ($order_ids) {
      $pjOrderData = $pjOrderItems->whereIn('order_id', $order_ids)
      ->whereIn('status', RETURN_TYPES)
      ->findAll()
      ->getData();
    }
    $groupedOrderItems = array();
    if ($pjOrderData) {
      $groupedOrderItems = array_reduce($pjOrderData, function($carry, $item) {
        if(!isset($carry[$item['order_id']])){
          $carry[$item['order_id']] = ['order_id'=>$item['order_id'],'cancel_amount'=>$item['price'] * $item['cnt']];
        } else {
          $carry[$item['order_id']]['cancel_amount'] += $item['price'] * $item['cnt'];
        }
        return $carry;
      });
    }

    $dailyReturnOrderTotal = 0;
    $adminReturnOrderTotal = 0;
    $table_list = $this->getRestaurantTables();
    
    foreach($data as $k => $v) {
      $data[$k]['order_date'] = date("d-m-Y", strtotime($v['created']));
      $data[$k]['total'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($v['total'])."</strong>";
      if($v['type'] == "AR") {
        $data[$k]['cancel_amount'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($v['cancel_amount'])."</strong>";
        $adminReturnOrderTotal += $v['cancel_amount'];
        $orderType = '';
      } else {
        if ($data[$k]['is_paid'] == 1) {
          $payment_method = strtolower($data[$k]['payment_method']);
          // if (strtolower($data[$k]['payment_method']) == 'bank') {
          if (in_array($payment_method, array('card', 'bank'))) {
            $data[$k]['payment_method'] = 'Card';
          } else {
            $data[$k]['payment_method'] = 'Cash';
          }
        } else {
          $data[$k]['payment_method'] = '-';
        }
        $data[$k]['cancel_amount'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($groupedOrderItems[$v['id']]['cancel_amount'])."</strong>";
        $dailyReturnOrderTotal += $groupedOrderItems[$v['id']]['cancel_amount'];
        if ($v['origin'] == 'Pos') {
          if (array_key_exists($v['table_name'], $table_list)) {
            $orderType = $table_list[$v['table_name']];
          } else {
            $orderType = 'Take Away';
          }
        } else if ($v['origin'] == 'Telephone') {
          $orderType = 'Telephone';
        } else {
          $orderType = 'Web';
        }
      }
      $data[$k]['table_name'] = "<strong class='list-pos-type'>".$orderType."</strong>";
    }
    // $this->pr($data);

    $returnTypeData = array();

    foreach($data as $datum) {
      if($datum['type'] == $type) {
        array_push($returnTypeData, $datum);
      }
    }

    // $this->pr($returnTypeData);
    $overAllReturnOrderTotal = "<strong>".pjCurrency::formatPrice($dailyReturnOrderTotal+$adminReturnOrderTotal)."</strong>";
    $dailyReturnOrderTotal = "<strong class='list-pos-type'>".pjCurrency::formatPrice($dailyReturnOrderTotal)."</strong>";
    $adminReturnOrderTotal = "<strong class='list-pos-type'>".pjCurrency::formatPrice($adminReturnOrderTotal)."</strong>";

    $response = array();
    $response['data'] = $returnTypeData;
    $response['adminReturnOrderTotal'] = $adminReturnOrderTotal;
    $response['dailyReturnOrderTotal'] = $dailyReturnOrderTotal;
    $response['overAllReturnOrderTotal'] = $overAllReturnOrderTotal;
    $response['total'] = $total;
    $response['pages'] = $pages;
    $response['page'] = $page;
    $response['rowCount'] = $rowCount;
    $response['column'] = $column;
    $response['direction'] = $direction;

    return $response;
  }
  protected function getReturnOrdersTotal($date_from, $date_to, $query) {
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderReturn = pjOrderReturnModel::factory();

    if ($query) {
      // MEGAMIND
      $pjOrderModel->where("(t1.order_id LIKE '%$query%' OR t1.uuid LIKE '%$query%' OR t1.table_name LIKE '%$query%')");
      $pjOrderReturn->where("(t1.order_id LIKE '%$query%' OR t1.product_name LIKE '%$query%')");
      // !MEGAMIND
    }

    $return_types = implode("','", RETURN_TYPES);
    $pjOrderModel = $pjOrderModel
      ->select("t1.*, 'RO' as type")
      ->where("((t1.p_dt >= '$date_from' AND t1.p_dt <= '$date_to') OR (t1.d_dt >= '$date_from' AND t1.d_dt <= '$date_to'))")
      ->where('t1.deleted_order', 0)
      ->where("t1.id IN (SELECT ORDITEM.order_id FROM `" . pjOrderItemModel::factory()
      ->getTable() . "` AS ORDITEM WHERE ORDITEM.STATUS IN ('".$return_types."'))")->orderBy("name ASC")
      ->where('status', 'delivered');

    $pjOrderReturn = $pjOrderReturn
      // ->select("id, order_id, 'Return Order' as table_name, amount as cancel_amount, amount as total, created_at as created, 'delivered' as status, '-' as payment_method, 'AR' as type")
      ->select("SUM(amount)as cancel_amount")
      ->where("created_at >= '$date_from' OR updated_at >= '$date_from'");

    $column = 'id';
    $direction = 'desc';

    $pjOrderModel = $pjOrderModel
    ->orderBy("$column $direction")
    ->findAll()
    ->getData();

    $pjOrderReturn = $pjOrderReturn
    ->orderBy("$column $direction")
    ->findAll()
    ->getData();
    
    $order_ids = array_column($pjOrderModel, 'id');
    $pjOrderData = "";
    $pjOrderItems = pjOrderItemModel::factory();
    $dailyReturnOrderTotal = 0;
    if ($order_ids) {
      $pjOrderData = $pjOrderItems->whereIn('order_id', $order_ids)
      ->whereIn('status', RETURN_TYPES)
      ->findAll()
      ->getData();
      $price = array_column($pjOrderData, 'price');
      $quantity = array_column($pjOrderData, 'cnt');
      $dailyReturnOrderTotal = array_map(function($x, $y) { return $x * $y; },
                     $price, $quantity);
      $dailyReturnOrderTotal = array_sum($dailyReturnOrderTotal);
    }

    $adminReturnOrderTotal = 0;
    if ($pjOrderReturn) {
      $adminReturnOrderTotal = $pjOrderReturn[0]['cancel_amount'];
    }

    $overAllReturnOrderTotal = "<strong>".pjCurrency::formatPrice($dailyReturnOrderTotal+$adminReturnOrderTotal)."</strong>";
    $dailyReturnOrderTotal = "<strong class='list-pos-type'>".pjCurrency::formatPrice($dailyReturnOrderTotal)."</strong>";
    $adminReturnOrderTotal = "<strong class='list-pos-type'>".pjCurrency::formatPrice($adminReturnOrderTotal)."</strong>";

    $response = array();
    $response['adminReturnOrderTotal'] = $adminReturnOrderTotal;
    $response['dailyReturnOrderTotal'] = $dailyReturnOrderTotal;
    $response['overAllReturnOrderTotal'] = $overAllReturnOrderTotal;
    return $response;
  }

  protected function getIncomeOrders($date_from, $date_to, $query, $rowCount, $page) {
      $pjIncomeModel = pjIncomeModel::factory();
      $pjMasterModel = pjMasterModel::factory();
      if ($query) {
          $pjIncomeModel->where("(t1.id LIKE '%$query%' OR t1.description LIKE '%$query%' OR t1.amount LIKE '%$query%')");
          $pjMasterModel->where("(t1.id LIKE '%$query%' OR t1.name LIKE '%$query%')");
      }
      $overAllIncomeTotal = 0;
      
      $pjIncomeModel->select("t1.id ,t1.id as income_id, t1.description, t1.income_date, t1.amount, t1.amount as total, t1.created_at as created, t1.master_id")
          ->where("(t1.income_date >= '$date_from' AND t1.income_date <= '$date_to')");

      $column = 't1.id';
      $direction = 'desc';
      $total = $pjIncomeModel->findCount()->getData();
      $rowCount = $rowCount ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $page ? max(1, intval($page)) : 1;
      $offset = ($page - 1) * $rowCount;
      $offset = max(0, $offset);
      $pjIncomeResults = $pjIncomeModel
          ->orderBy("$column $direction")
          ->limit($rowCount, $offset)
          ->findAll()
          ->getData();
      $overAllIncomeTotal = $pjIncomeModel
          ->select("SUM(amount)as totalAmount")
          ->findAll()
          ->getData();
      //$this->pr($overAllIncomeTotal);
      // Fetch the pjMaster data separately
      $pjMasterData = array();
      $totalAmount = 0;
      $serialNumber = ($page - 1) * $rowCount;
      foreach ($pjIncomeResults as $incomeResult) {
          $income_id = $incomeResult['income_id'];
          $pjIncomeModel->find($income_id);
          $incomeData = $pjIncomeModel->getData();
          $incomeResult['income_date'] = date("d-m-Y", strtotime($incomeData['income_date']));
          $amount = $incomeResult['amount'];
          //$totalAmount += $amount;
          $incomeResult['id'] = ++$serialNumber;
          $master_id = $incomeResult['master_id'];
          $pjMasterModel = pjMasterModel::factory();
          $pjMasterModel->find($master_id);
          $masterData = $pjMasterModel->getData();
          $incomeResult['master_name'] = $masterData['name'];
          $pjMasterData[] = $incomeResult;
      }
      if ($overAllIncomeTotal) {
        $totalAmount = $overAllIncomeTotal[0]['totalAmount'];
      } 
      $overAllIncomeTotal = "<strong>" . pjCurrency::formatPrice($totalAmount) . "</strong>";


      $response = array();
      $response['data'] = $pjMasterData;
      $response['overAllIncomeTotal'] = $overAllIncomeTotal;
      $response['total'] = $total;
      $response['pages'] = $pages;
      $response['page'] = $page;
      $response['rowCount'] = $rowCount;
      $response['column'] = $column;
      $response['direction'] = $direction;
      return $response;
  }

  protected function getExpenseOrders($date_from, $date_to, $query, $rowCount, $page) {
      $pjExpenseModel = pjExpenseModel::factory();
      if ($query) {
          $pjExpenseModel->where("(t1.id LIKE '%$query%' OR t1.description LIKE '%$query%' OR t1.amount LIKE '%$query%')");
      }
      $overAllExpenseTotal = 0;
      
      $pjExpenseModel->select("t1.id ,t1.id as expense_id, t1.description, t1.expense_date, t1.amount, t1.amount as total, t1.created_at as created, t1.master_id")
          ->where("(t1.expense_date >= '$date_from' AND t1.expense_date <= '$date_to')");

      $column = 't1.id';
      $direction = 'desc';
      $total = $pjExpenseModel->findCount()->getData();
      $rowCount = $rowCount ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $page ? max(1, intval($page)) : 1;
      $offset = ($page - 1) * $rowCount;
      $offset = max(0, $offset);
      $pjExpenseResults = $pjExpenseModel
          ->orderBy("$column $direction")
          ->limit($rowCount, $offset)
          ->findAll()
          ->getData();
      $overAllExpenseTotal = $pjExpenseModel
          ->select("SUM(amount)as totalAmount")
          ->findAll()
          ->getData();

      $pjMasterData = array();
      $totalAmount = 0;
      $serialNumber = 1;
      $serialNumber = ($page - 1) * $rowCount;
      foreach ($pjExpenseResults as $expenseResult) {
        $expense_id = $expenseResult['expense_id'];
        $pjExpenseModel->find($expense_id);
        $expenseData = $pjExpenseModel->getData();
        $expenseResult['expense_date'] = date("d-m-Y", strtotime($expenseData['expense_date']));
        $amount = $expenseResult['amount'];
        //$totalAmount += $amount;
        $expenseResult['id'] = ++$serialNumber;
        $master_id = $expenseResult['master_id'];
        $pjMasterModel = pjMasterModel::factory();
        $pjMasterModel->find($master_id);
        $masterData = $pjMasterModel->getData();
        $expenseResult['master_name'] = $masterData['name'];
        $pjMasterData[] = $expenseResult;
      }

      if ($overAllExpenseTotal) {
        $totalAmount = $overAllExpenseTotal[0]['totalAmount'];
      } 
      $overAllExpenseTotal = "<strong>" . pjCurrency::formatPrice($totalAmount) . "</strong>";
      $response = array();
      $response['data'] = $pjMasterData;
      $response['overAllExpenseTotal'] = $overAllExpenseTotal;
      $response['total'] = $total;
      $response['pages'] = $pages;
      $response['page'] = $page;
      $response['rowCount'] = $rowCount;
      $response['column'] = $column;
      $response['direction'] = $direction;
      return $response;
  }


}
?>