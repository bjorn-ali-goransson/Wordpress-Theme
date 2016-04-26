<?php
  


/* ARRAY PLUCK */

function array_pluck($key, $input){
  if(is_array($key) || !is_array($input)){
    return array();
  }
  
  $array = array();
  
  foreach($input as $v){
    if(array_key_exists($key, $v)) $array[]=$v[$key];
  }
  
  return $array;
}