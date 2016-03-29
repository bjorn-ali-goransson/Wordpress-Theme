<?php

add_action('widgets_init', function() {
  foreach (get_declared_classes() as $class) {
    if (is_subclass_of($class, "My_Widget"))
      register_widget( $class );
  }
});