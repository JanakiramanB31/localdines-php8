<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjClientMessagesModel extends pjAppModel {
	protected $primaryKey = 'id';
	protected $table = 'client_messages';
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_show', 'type' => 'boolean', 'default' => '1')
	);
	
	public static function factory($attr=array()) {
		return new pjClientMessagesModel($attr);
	}
}
?>