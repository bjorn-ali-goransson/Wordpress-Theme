<?php
  


/* GET ALL */

function get_all($post_type){
  $post_objects = get_posts(array(
    'post_type' => $post_type,
    'nopaging' => TRUE,
    'orderby' => 'post_title',
    'order' => 'ASC',
  ));

  $posts = array();

  foreach($post_objects as $post_object){
    $posts[] = (object)array(
      'id' => $post_object->ID,
      'name' => $post_object->post_title,
    );
  }

  return $posts;
}