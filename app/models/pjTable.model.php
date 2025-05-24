<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjTableModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'tables';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'boolean', 'default' => '1'),
	);
	
	public static function factory($attr=array())
	{
		return new pjTableModel($attr);
	}
}
?>