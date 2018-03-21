<?php

require_once dirname(__FILE__) . '/load_properties.php';



/* GET ALL POSTS */

function get_all_posts($post_type, $properties = array()){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'nopaging' => TRUE,
    'orderby' => 'post_title',
    'order' => 'ASC',
  ));

  $posts = array();

  foreach($post_objects as $post_object){
    $posts[] = load_properties($post_object, $properties);
  }

  return $posts;
}