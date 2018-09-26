<?php
  
require_once dirname(__FILE__) . '/post/get_post_where.php';
  


/* GET ALL WHERE */

function get_where($post_type, $meta_key, $meta_value, $properties = array()){
  return get_post_where($post_type, $meta_key, $meta_value, $properties);
}