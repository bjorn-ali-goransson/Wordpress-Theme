<?php
  
require_once dirname(__FILE__) . '/load_properties.php';
  


/* GET ALL WHERE */

function get_all_where($post_type, $meta_key, $meta_value, $properties = array()){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'nopaging' => TRUE,
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
  ));
  
  $posts = array();
  
  foreach($post_objects as $post_object){
    $posts[] = load_properties($post_object, $properties);
  }
  
  return $posts;
}
