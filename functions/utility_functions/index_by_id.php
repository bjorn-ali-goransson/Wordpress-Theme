<?php
  


/* INDEX BY ID */

function index_by_id($array){
  $result = array();

  foreach($array as $object){
    $result[$object->id] = $object;
  }

  return $result;
}