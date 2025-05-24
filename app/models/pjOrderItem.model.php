<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOrderItemModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'orders_items';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'order_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'custom_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'hash', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'parent_hash', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'item_order', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'cancel_or_return_reason', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'cnt', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'special_instruction', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'custom_special_instruction', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'print', 'type' => 'int', 'default' => '0')
	);
	
	public static function factory($attr=array())
	{
		return new pjOrderItemModel($attr);
	}
}
?>