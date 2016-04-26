<?php
  


/* PLUCK */

function pluck($key, $array){
  $result = array();
  
  foreach($array as $element){
    if(is_array($element)){
      if(array_key_exists($key, $v)){
        $result[] = $element[$key];
      }
    }
    if(is_object($element)){
      if(property_exists($element, $key)){
        $result[] = $element->$key;
      }
    }
  }
  
  return $result;
}