<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPaymentResponseModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'api_payment_responses';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int','default' => 'none'),
		array('name' => 'order_id', 'type' => 'varchar','default' => 'none'),
		array('name' => 'response', 'type' => 'text','default' => 'none'),
		array('name' => 'method', 'type' => 'varchar','default' => 'null'),
		array('name' => 'payment_vendor', 'type' => 'varchar','default' => 'null'),
    array('name' => 'is_active', 'type' => 'boolean','default' => 'none'),
	);

	public static function factory($attr=array()) {
		return new pjPaymentResponseModel($attr);
	}
}
?>