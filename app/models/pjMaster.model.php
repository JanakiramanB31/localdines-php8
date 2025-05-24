<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjMasterModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'masters';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'master_type_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'address', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'contact_person', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'is_active', 'type' => 'boolean', 'default' => ':1'),
    array('name' => 'postal_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'contact_number', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjMasterModel($attr);
	}
}
?>