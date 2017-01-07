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
      $int_value = FALSE;

      if(substr($property, 0, 1) == '+'){
        $property = substr($property, 1);
        $int_value = TRUE;
      }

      $post->$property = $post_object->$property;

      if($post->$property === ''){
        $post->$property = NULL;
      } else {
        if($int_value){
          $post->$property = +$post->$property;
        }
      }
    }
    
    $posts[] = $post;
  }
  
  return $posts;
}
