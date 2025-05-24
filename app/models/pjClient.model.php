<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjClientModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'clients';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'c_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_company', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_address_1', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_address_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_country', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'c_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'c_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_postcode', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'mobile_delivery_info', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'mobile_offer', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'email_receipt', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'email_offer', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'register_type', 'type' => 'enum', 'default' => 'T')
	);
	
	public static function factory($attr=array())
	{
		return new pjClientModel($attr);
	}
}
?>