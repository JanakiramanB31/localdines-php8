<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjMasterTypeModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'master_types';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'int', 'default' => ':1')

	);
	
	public static function factory($attr=array()) {
		return new pjMasterTypeModel($attr);
	}
}
?>