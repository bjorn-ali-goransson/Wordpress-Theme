<?php

require_once dirname(__FILE__) . '/dynamic_user.php';



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