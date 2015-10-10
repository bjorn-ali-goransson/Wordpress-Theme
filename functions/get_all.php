<?php
  


/* GET ALL */

function get_all($post_type, $properties = array()){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'nopaging' => TRUE,
    'orderby' => 'post_title',
    'order' => 'ASC',
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