<?php



/* LOAD PROPERTIES */

function load_properties($post_object, $properties){
  $post = (object)array(
    'id' => $post_object->ID,
    'name' => $post_object->post_title,
  );

  if(in_array('permalink', $properties)){
    $post->permalink = get_permalink($post_object->ID);
  }
  
  foreach($properties as $property){
    if(isset($post->$property)){
      continue;
    }

    if(strpos($property, 'post_') === 0){
      $post->$property = $post_object->$property;
    } else {
      $post->$property = get_field($property, $post_object->ID);
    }
  }
    
  return $post;
}