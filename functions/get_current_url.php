<?php



/* GET CURRENT URL */

function get_current_url(){
  $url  = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
  $url .= '://' . $_SERVER['HTTP_HOST'];
  if(strpos($_SERVER['HTTP_HOST'], ':') === FALSE){
    $url .=  in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':' . $_SERVER['SERVER_PORT'];
  }
  $url .= $_SERVER['REQUEST_URI'];

  return $url;
}