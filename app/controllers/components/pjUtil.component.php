<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUtil extends pjToolkit
{
	public static function getWeekdays(){
		$arr = array();
		$arr[] = 'monday';
		$arr[] = 'tuesday';
		$arr[] = 'wednesday';
		$arr[] = 'thursday';
		$arr[] = 'friday';
		$arr[] = 'saturday';
		$arr[] = 'sunday';
		return $arr;
	}
	public static function toMomemtJS($format)
	{
	    return str_replace(
	        array('Y', 'm', 'n', 'd', 'j'),
	        array('YYYY', 'MM', 'M', 'DD', 'D'),
	        $format);
	}
	public static function toBootstrapDate($format)
	{
	    return str_replace(
	        array('Y', 'm', 'n', 'd', 'j'),
	        array('yyyy', 'mm', 'm', 'dd', 'd'),
	        $format);
	}
	public static function getTitles(){
		$arr = array();
		$arr[] = 'mr';
		$arr[] = 'mrs';
		$arr[] = 'ms';
		$arr[] = 'dr';
		$arr[] = 'prof';
		$arr[] = 'rev';
		$arr[] = 'other';
		return $arr;
	}
	
	public static function getPostMaxSize()
	{
	    $post_max_size = ini_get('post_max_size');
	    switch (substr($post_max_size, -1))
	    {
	        case 'G':
	            $post_max_size = (int) $post_max_size * 1024 * 1024 * 1024;
	            break;
	        case 'M':
	            $post_max_size = (int) $post_max_size * 1024 * 1024;
	            break;
	        case 'K':
	            $post_max_size = (int) $post_max_size * 1024;
	            break;
	    }
	    return $post_max_size;
	}
	public static function sortArrayByArray(Array $array, Array $orderArray)
	{
	    $ordered = array();
	    foreach($orderArray as $key)
	    {
	        if(array_key_exists($key,$array))
	        {
	            $ordered[$key] = $array[$key];
	            unset($array[$key]);
	        }
	    }
	    return $ordered + $array;
	}
	public static function getCurrentTimeSnap15Minutes()
	{
	    $interval = 15 * 60;
	    $ts = time();
	    $last = $ts - $ts % $interval;
	    $next = $last + $interval + 3600;
	    return $next;
	}
}
?>