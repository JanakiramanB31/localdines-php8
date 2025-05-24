<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOrderModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'orders';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'uuid', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'client_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'logged_user_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'updated_user_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'location_id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'locale_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'total_persons', 'type' => 'int', 'default' => 0),
		array('name' => 'status', 'type' => 'enum', 'default' => 'pending'),
		array('name' => 'payment_method', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'cash_amount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'is_paid', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'processed_on', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'ip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price_packing', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price_delivery', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'discount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'subtotal', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'tax', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'total', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'customer_paid', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'voucher_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'p_dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'p_asap', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'p_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'd_address_1', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_address_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_country_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'd_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'd_dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'd_asap', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'cc_type', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_num', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_exp', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_code', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'call_start', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'call_end', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'post_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'override_postcode', 'type' => 'boolean', 'default' => ':NULL'),
		array('name' => 'phone_no', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'surname', 'type' => 'char', 'default' => ':NULL'),
    array('name' => 'sms_email', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'first_name', 'type' => 'char', 'default' => ':NULL'),
    array('name' => 'kprint', 'type' => 'boolean', 'default' => '0'),
    array('name' => 'order_despatched', 'type' => 'boolean', 'default' => ':NULL'),
    array('name' => 'delivered_customer', 'type' => 'boolean', 'default' => ':NULL'),
    array('name' => 'sms_sent_time', 'type' => 'datetime', 'default' => ':NULL'),
    array('name' => 'delivered_time', 'type' => 'datetime', 'default' => ':NULL'),
    array('name' => 'order_id', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'order_count_of_the_day', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'deleted_order', 'type' => 'boolean', 'default' => ':NULL'),
    array('name' => 'chef_id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'mobile_delivery_info', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'mobile_offer', 'type' => 'tinyint', 'default' => 0),    
    array('name' => 'email_receipt', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'email_offer', 'type' => 'tinyint', 'default' => 0),
    array('name' => 'd_time', 'type' => 'int', 'default' => 0),
    array('name' => 'p_time', 'type' => 'int', 'default' => 0),
    array('name' => 'preparation_time', 'type' => 'int', 'default' => 0),
    array('name' => 'delivery_dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'origin', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'table_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'guest_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_viewed', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'is_z_viewed', 'type' => 'boolean', 'default' => 0),
		array('name' => 'api_result_print', 'type' => 'text', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjOrderModel($attr);
	}
}
?>