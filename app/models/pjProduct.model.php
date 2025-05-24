<?php
if (!defined("ROOT_PATH")) {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjProductModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'products';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'parent_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'order_type', 'type' => 'enum', 'default' => 'eatin'),
		array('name' => 'set_different_sizes', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'image', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_featured', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'is_kitchen', 'type' => 'boolean', 'default' => 1),
		array('name' => 'is_web_orderable', 'type' => 'boolean', 'default' => 1),
		array('name' => 'is_veg', 'type' => 'boolean', 'default' => 1),
		array('name' => 'is_vat', 'type' => 'boolean', 'default' => 1),
		array('name' => 'order', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'preparation_time', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'tinyint', 'default' => 1),
		array('name' => 'counter_number', 'type' => 'int', 'default' =>':NULL' ),
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr = array()) {
		return new pjProductModel($attr);
	}
	
	public function getLastOrder() {
    $order = 1;
    $arr = $this->reset()->orderBy("`order` DESC")->limit(1)->findAll()->getData();
    if (!empty($arr)) {
      $order = $arr[0]['order'] + 1;
    }
    return $order;
	}
}
?>