<?php

function load_properties($post_object, $properties){
  $post = (object)array(
    'id' => $post_object->ID,
    'name' => $post_object->post_title,
  );
  
  foreach($properties as $property){
    if(strpos($property, 'post_') === 0){
      $post->$property = $post->$property;
    } else {
      $post->$property = get_field($property, $post_object->ID);
    }
  }
    
  return $post;
}