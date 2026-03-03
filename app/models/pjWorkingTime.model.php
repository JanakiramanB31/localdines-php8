<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjWorkingTimeModel extends pjAppModel
{
  protected $primaryKey = 'location_id';

  protected $table = 'working_times';

  protected $schema = array(
    array('name' => 'location_id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'p_monday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_monday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_monday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_tuesday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_tuesday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_tuesday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_wednesday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_wednesday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_wednesday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_thursday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_thursday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_thursday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_friday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_friday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_friday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_saturday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_saturday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_saturday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'p_sunday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_sunday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'p_sunday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_monday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_monday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_monday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_tuesday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_tuesday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_tuesday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_wednesday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_wednesday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_wednesday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_thursday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_thursday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_thursday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_friday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_friday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_friday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_saturday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_saturday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_saturday_dayoff', 'type' => 'enum', 'default' => 'F'),
    array('name' => 'd_sunday_from', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_sunday_to', 'type' => 'time', 'default' => ':NULL'),
    array('name' => 'd_sunday_dayoff', 'type' => 'enum', 'default' => 'F')
  );

  public static function factory($attr = array())
  {
    return new pjWorkingTimeModel($attr);
  }

  public function init($location_id)
  {
    $data = array();
    $data['location_id']      = $location_id;
    $data['p_monday_from']    = '08:00:00';
    $data['p_monday_to']      = '22:00:00';
    $data['p_tuesday_from']   = '08:00:00';
    $data['p_tuesday_to']     = '22:00:00';
    $data['p_wednesday_from'] = '08:00:00';
    $data['p_wednesday_to']   = '22:00:00';
    $data['p_thursday_from']  = '08:00:00';
    $data['p_thursday_to']    = '22:00:00';
    $data['p_friday_from']    = '08:00:00';
    $data['p_friday_to']      = '22:00:00';
    $data['p_saturday_from']  = '08:00:00';
    $data['p_saturday_to']    = '22:00:00';
    $data['p_sunday_from']    = '08:00:00';
    $data['p_sunday_to']      = '22:00:00';
    $data['d_monday_from']    = '08:00:00';
    $data['d_monday_to']      = '22:00:00';
    $data['d_tuesday_from']   = '08:00:00';
    $data['d_tuesday_to']     = '22:00:00';
    $data['d_wednesday_from'] = '08:00:00';
    $data['d_wednesday_to']   = '22:00:00';
    $data['d_thursday_from']  = '08:00:00';
    $data['d_thursday_to']    = '22:00:00';
    $data['d_friday_from']    = '08:00:00';
    $data['d_friday_to']      = '22:00:00';
    $data['d_saturday_from']  = '08:00:00';
    $data['d_saturday_to']    = '22:00:00';
    $data['d_sunday_from']    = '08:00:00';
    $data['d_sunday_to']      = '22:00:00';
    return $this->reset()->setAttributes($data)->insert()->getInsertId();
  }

  public function getWorkingTime($id, $type, $date)
  {
    $prefix = ($type == 'pickup') ? 'p_' : 'd_';
    $day = strtolower(date("l", strtotime($date)));
    $arr = $this->reset()->find($id)->getData();

    if (count($arr) == 0) {
      return false;
    }

    if ($arr[$prefix . $day . '_dayoff'] == 'T') {
      return array();
    }
    $wt = array();
    $start = $arr[$prefix . $day . '_from'];
    $end = $arr[$prefix . $day . '_to'];
    if (!is_null($start)) {
      $d = getdate(strtotime($start));
      $wt['start_hour'] = $d['hours'];
      $wt['start_minutes'] = $d['minutes'];
      $wt['start_ts'] = strtotime($date . " " . $start);
    }
    if (!is_null($end)) {
      $d = getdate(strtotime($end));
      $wt['end_hour'] = $d['hours'];
      $wt['end_minutes'] = $d['minutes'];
      $wt['end_ts'] = strtotime($date . " " . $end);
    }
    if ($wt['end_ts'] < $wt['start_ts']) {
      $wt['end_ts'] = $wt['end_ts'] + 86400;
    }
    return $wt;
  }

  function isServiceAvailable($service,)
  {
    $location_id = 1;
    $day = strtolower(date('l'));
    $wt_arr = $this->reset()->find($location_id)->getData();
    date_default_timezone_set('Europe/London');
    // Get current date/time in UK
    $current_time = date('H:i');
    if ($service == 'pickup') {
      $schedule = array(
        'from' => $wt_arr["p_{$day}_from"] ?? '',
        'to' =>  $wt_arr["p_{$day}_to"] ?? '',
        'dayoff' =>  $wt_arr["p_{$day}_dayoff"] == 'T' ? 1 : 0
      );
    } else {
      $schedule = array(
        'from' => $wt_arr["d_{$day}_from"] ?? '',
        'to' =>  $wt_arr["d_{$day}_to"] ?? '',
        'dayoff' =>  $wt_arr["d_{$day}_dayoff"] == 'T' ? 1 : 0
      );
    }
    // Check if it's a day off
    if ($schedule['dayoff'] == 1) {
      return [
        'available' => false,
        'message' => ucfirst($service) . " is not available today (Day Off)",
        'next_available' => 'Tomorrow'
      ];
    }

    // Check if within operating hours
    if ($current_time < $schedule['from']) {
      return [
        'available' => false,
        'message' => ucfirst($service) . " will be available from " . substr($schedule['from'], 0, 5),
        'next_available' => $schedule['from']
      ];
    }

    if ($current_time >= $schedule['to']) {
      return [
        'available' => false,
        'message' => ucfirst($service) . " service has ended for today",
        'next_available' => 'Tomorrow ' . substr($schedule['from'], 0, 5)
      ];
    }

    // Available now
    return [
      'available' => true,
      'message' => ucfirst($service) . " is available now",
      'until' => substr($schedule['to'], 0, 5)
    ];
  }
}
