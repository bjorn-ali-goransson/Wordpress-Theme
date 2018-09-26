<?php
  
require_once dirname(__FILE__) . '/load_properties.php';
  


/* GET POST WHERE */

function get_post_where($post_type, $meta_key, $meta_value, $properties = array()){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'posts_per_page' => 1,
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
  ));
  
  $posts = array();
  
  foreach($post_objects as $post_object){
    $posts[] = load_properties($post_object, $properties);
  }
  
  if(empty($posts)){
    return NULL;
  } else {
    return $posts[0];
  }
}