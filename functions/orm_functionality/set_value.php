<?php



/* SET VALUE */

function set_value($instance, $key, $value){
  if(is_a($instance, 'DynamicUser')){
    update_field($key, $value, 'user_' . $instance->id);
    return;
  }

  update_field($key, $value, $instance->id);
}