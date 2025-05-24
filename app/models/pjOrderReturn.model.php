<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOrderReturnModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'order_returns';

	protected $schema = array(
    array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'order_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'product_id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'product_name', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'reason', 'type' => 'text', 'default' => ':NULL'),
    array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
    array('name' => 'size', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'qty', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'amount', 'type' => 'decimal', 'default' => ':NULL'),
    array('name' => 'purchase_date', 'type' => 'date', 'default' => ':NULL'),
    array('name' => 'return_date', 'type' => 'date', 'default' => ':NULL'),
    array('name' => 'is_z_viewed', 'type' => 'tinyint', 'default' => '0'),
    array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NULL'),
    array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjOrderReturnModel($attr);
	}
}
?>