<?php

require_once dirname(__FILE__) . '/post/_get_post.php';
require_once dirname(__FILE__) . '/user/get_user.php';



/* GET */

function get($type, $id, $properties = array()){
  if($type == 'user'){
    return get_user($id, $properties);
  }
  
  return _get_post($id, $properties);
}