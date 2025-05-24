<?php
// $d_hour = date('H')+1;
// $d_minute = date('i')+5;
// echo $d_hour.":".$d_minute;
$type_err = __('type_err', true, false);
$ts = time();
// if (empty($tpl['wt_arr']) && empty($tpl['option']))
// {
if (empty($tpl['wt_arr']))
{
	echo $type_err[1];
	exit;
}
// if ($controller->_get->toInt('location_id') <= 0 || (!isset($tpl['wt_arr']['end_ts']) && !isset($tpl['option'])))
// {
if ($controller->_get->toInt('location_id') <= 0 || (!isset($tpl['wt_arr']['end_ts']) && !isset($tpl['option'])))
{
	exit;
}
$index = $controller->_get->toString('index');
$STORAGE = &$_SESSION[$controller->defaultStore];
switch ($controller->_get->toString('type'))
{
	case 'pickup':
		$time_key = 'p_time';
		break;
	case 'delivery':
		$time_key = 'd_time';
		break;
}

$time = isset($STORAGE) && isset($STORAGE[$time_key]) ? $STORAGE[$time_key] : NULL;

$start_time = strtotime(date($tpl['date'] . ' 00:00:00'));
$end_time = strtotime(date($tpl['date'] . ' 23:45:00'));

if (isset($tpl['wt_arr']))
{
	$start_time = strtotime($tpl['date'] . ' ' . $tpl['wt_arr']['start_hour'] . ':' . $tpl['wt_arr']['start_minutes'] . ':00');
	$end_time = strtotime($tpl['date'] . ' ' . $tpl['wt_arr']['end_hour'] . ':' . $tpl['wt_arr']['end_minutes'] . ':00');
}
if($end_time < $start_time)
{
	$end_time += 86400;
}
if ($end_time < $ts)
{
	echo $type_err[3];
	exit;
}

$midnight = strtotime($tpl['date'] . ' 23:59:59');

$interval = 1800;
$next = pjUtil::getCurrentTimeSnap15Minutes();
if($start_time < $next)
{
	$start_time = $next;
}
?>
<select id="<?php echo $time_key . '_' . $index;?>" name="<?php echo $time_key;?>" class="form-control fdSelect">
	<?php
	
	// if (isset($tpl['option']) && $tpl['option'] == 'asap') {
		
	// 	?>
	 		<!-- <option value="asap"><?php //__('front_asap');?></option> -->
	 	<?php
	// } else {
        if($ts >= $tpl['wt_arr']['start_ts'] && $ts <= $tpl['wt_arr']['end_ts'])
		{ 
			?>
			<option value="asap"><?php __('front_asap');?></option>
			<?php
		}
	
	
		for($i = $start_time; $i <= $end_time; $i += $interval)
		{
			$iso_time = date($tpl['option_arr']['o_time_format'], $i);
			$time_text = $iso_time;
			if($i > $midnight )
			{
				$time_text .= ' (' . __('front_next_day', true) . ')';
			}
			?><option value="<?php echo $iso_time;?>"<?php echo $iso_time == $time ? ' selected="selected"' : null; ?>><?php echo $time_text;?></option><?php	
		} 
    //}
	?>
</select>