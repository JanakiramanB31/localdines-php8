<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjExpenseCategoryModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'expenses_categories';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'sort_order', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'sub_category_ids', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'boolean', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjExpenseCategoryModel($attr);
	}
}
?>