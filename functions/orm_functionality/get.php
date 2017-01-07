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
    
  return $post;
}