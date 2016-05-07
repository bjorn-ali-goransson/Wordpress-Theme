<?php
  


/* CLASSES */

function classes($classes){
  $class_names = [];

  foreach($classes as $class_name => $value){
    if($value){
      $class_names[] = $class_name;
    }
  }

  return implode(' ', $class_names);
}