<?php



/* GET */

function get($id, $properties = array()){
  $post_object = get_post($id);
  
  if($post_object == NULL){
    return NULL;
  }

  $post = (object)array(
    'id' => $post_object->ID,
    'name' => $post_object->post_title,
  );
    
  foreach($properties as $property){
    $post->$property = get_post_meta($post->id, $property, TRUE);
  }
    
  return $post;
}