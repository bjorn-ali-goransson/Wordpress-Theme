<?php
  


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
    $post = (object)array(
      'id' => $post_object->ID,
      'name' => $post_object->post_title,
    );
    
    foreach($properties as $property){
      $post->$property = get_post_meta($post->id, $property, TRUE);
    }
    
    $posts[] = $post;
  }
  
  return $posts;
}
