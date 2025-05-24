<?php
$front_messages = __('front_messages', true, false);
$front_messages[3] = ORDER_SUBMITTED;
if (!empty($tpl['arr']['payment_method'])) {
  if (isset($tpl['params']['plugin']) && !empty($tpl['params']['plugin'])) {
    $payment_messages = __('payment_plugin_messages');
    echo isset($payment_messages[$tpl['arr']['payment_method']]) ? $payment_messages[$tpl['arr']['payment_method']]: $front_messages[1];
    if (pjObject::getPlugin($tpl['params']['plugin']) !== NULL) {
      $controller->requestAction(array('controller' => $tpl['params']['plugin'], 'action' => 'pjActionForm', 'params' => $tpl['params']));
    }
  } else {
    switch ($tpl['arr']['payment_method']) {
      case 'bank':
      case 'creditcard':
      case 'cash':
      default:
        $system_msg = str_replace("[STAG]", "<a href='#' class='btn btn-default alert-link fdStartOver'>", $front_messages[3]);
        $system_msg = str_replace("[ETAG]", "</a>", $system_msg);
        echo $system_msg;
      }
  }
} else {
  $system_msg = str_replace("[STAG]", "<a href='#' class='btn btn-default alert-link fdStartOver'>", $front_messages[3]);
  $system_msg = str_replace("[ETAG]", "</a>", $system_msg);
  echo $system_msg;
}
?>