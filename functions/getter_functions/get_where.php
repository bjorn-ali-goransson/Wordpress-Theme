<?php
  


/* GET WHERE */

function get_where($post_type, $meta_key, $meta_value, $properties = array()){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'posts_per_page' => 1,
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
  
  if(empty($posts)){
    return NULL;
  } else {
    return $posts[0];
  }
}