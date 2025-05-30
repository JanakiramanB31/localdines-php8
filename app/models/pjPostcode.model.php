<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPostcodeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'postcodes';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'post_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'int', 'default' => ':1'),
		array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NULL')
		);
	
	public static function factory($attr=array())
	{
		return new pjPostcodeModel($attr);
	}
}
?>