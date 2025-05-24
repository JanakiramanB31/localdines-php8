<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjConfigModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'configs';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'key', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'value', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'boolean', 'default' => ':0'),
		array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjConfigModel($attr);
	}
}
?>