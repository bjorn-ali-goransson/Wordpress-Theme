<?php
  


/* GET ALL */

function get_all($type, $properties = array()){
  if($type == 'user'){
    return get_all_users($properties);
  }

  return get_all_posts($type, $properties);
}



/* GET ALL USERS */

function get_all_users($properties = array()){
  $wp_users_columns = array('ID');
  $meta_properties = array();

  foreach($properties as $property){
    if($property == 'ID'){
      continue;
    }

    if(in_array($property, array('user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name'))){
      $wp_users_columns[] = $property;
      continue;
    }

    $meta_properties[] = $property;
  }

  $users = array();

  foreach(get_users(array('fields' => $wp_users_columns)) as $wp_user){
    $wp_user = (array)$wp_user;
    $user_id = $wp_user['ID'];
    unset($wp_user['ID']);

    $user = new DynamicUser;

    $user->id = $user_id;

    foreach($wp_user as $key => $value){
      $user->$key = $value;
    }

    foreach($meta_properties as $meta_key){
      $user->$meta_key = get_user_meta($user->id, $meta_key, TRUE);
    }

    $users[] = $user;
  }

  return $users;
}

class DynamicUser extends stdClass { }



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
    $post = (object)array(
      'id' => $post_object->ID,
      'name' => $post_object->post_title,
    );
    
    foreach($properties as $property){
      $post->$property = $post_object->$property;
    }

    $posts[] = $post;
  }

  return $posts;
}