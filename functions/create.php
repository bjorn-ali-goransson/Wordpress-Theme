<?php



/* CREATE */

function create($post_type, $post_title, $properties = array()){
  $post = (object)array();

  $post->name = $post_title;

  $post->id = wp_insert_post(array(
    'post_type' => $post_type,
    'post_status' => 'publish',
    'post_title' => $post->name,
  ));

  foreach($properties as $key => $value){
    update_post_meta($post->id, $key, $value);
    $post->$key = $value;
  }

  return $post;
}