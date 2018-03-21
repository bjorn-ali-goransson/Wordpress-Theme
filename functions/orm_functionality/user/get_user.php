<?php



/* GET USER */

function get_user($id, $properties){
  $wp_user = get_userdata($id);

  $user = new DynamicUser;
  $user->id = $wp_user->ID;
  
  foreach($properties as $property){
    if(strpos($property, 'user_') === 0 || $property == 'display_name'){
      $user->$property = $wp_user->$property;
    } else {
      $user->$property = get_field($property, "user_{$wp_user->ID}");
    }
  }
    
  return $user;
}