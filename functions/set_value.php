<?php



/* SET VALUE */

function set_value($post, $key, $value){
  update_post_meta($post->id, $key, $value);
}