<?php
  
require_once dirname(__FILE__) . '/user/get_all_users.php';
require_once dirname(__FILE__) . '/post/get_all_posts.php';
  


/* GET ALL */

function get_all($type, $properties = array()){
  if($type == 'user'){
    return get_all_users($properties);
  }

  return get_all_posts($type, $properties);
}