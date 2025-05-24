<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjReviewModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'products_review';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'product_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'rating', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'review', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'table_number', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'tinyint', 'default' => '0'),
		array('name' => 'review_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'user_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'user_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'guest_ip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'guest_un', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'guest_email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_accept_terms', 'type' => 'tinyint', 'default' => '1'),
		array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjReviewModel($attr);
	}
}
?>