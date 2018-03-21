<?php



/* GET VALUE */

function get_value($post_id, $meta_key){
  return get_post_meta($post_id, $meta_key, TRUE);
}