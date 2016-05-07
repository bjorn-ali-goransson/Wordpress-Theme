<?php



/* SET VALUE */

function set_value($instance, $key, $value){
  if(is_a($instance, 'DynamicUser')){
    update_user_meta($instance->id, $key, $value);
    return;
  }

  update_post_meta($instance->id, $key, $value);
}