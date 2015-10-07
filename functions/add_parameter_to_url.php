<?php



/* ADD PARAMETER TO URL */

function add_parameter_to_url($name, $value, $url = NULL) {
  if(!$url){
    $url = 'http://' . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['REQUEST_URI'];
  }

  if(strpos($url, '?') !== FALSE){
    $separator = '&';
  } else {
    $separator = '?';
  }

  return $url . $separator . $name . '=' . $value;
}