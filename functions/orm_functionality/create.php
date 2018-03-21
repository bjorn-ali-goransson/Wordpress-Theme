<?php



/* CREATE */

function create($post_type, $post_title, $properties = array()){
  $post = (object)array();

  $post->name = $post_title;

  $post->id = wp_insert_post(array(
    'post_type' => $post_type,
    'post_status' => 'publish',
    'post_title' => $post->name,
    'post_content' => array_key_exists('post_content', $properties) ? $properties['post_content'] : '',
    'post_excerpt' => array_key_exists('post_excerpt', $properties) ? $properties['post_excerpt'] : '',
    'post_date' => array_key_exists('post_date', $properties) ? $properties['post_date'] : NULL,
    'post_date_gmt' => array_key_exists('post_date_gmt', $properties) ? $properties['post_date_gmt'] : NULL,
  ));

  foreach($properties as $key => $value){
    if($key == 'post_content'){
      continue;
    }
    if($key == 'post_date'){
      continue;
    }
    if($key == 'post_date_gmt'){
      continue;
    }

    update_post_meta($post->id, $key, $value);
    $post->$key = $value;
  }

  return $post;
}