<?php
  
require_once dirname(__FILE__) . '/load_properties.php';



/* GET */

function get($id, $properties = array()){
  $post_object = get_post($id);
  
  if($post_object == NULL){
    return NULL;
  }

  return load_properties($post_object, $properties);
}