<?php



/* REMOVE DEFAULT WIDGETS */

function remove_default_widgets(){
  add_action('widgets_init', function(){
    global $wp_widget_factory;
  
    foreach($wp_widget_factory->widgets as $widget){
      $reflector = new ReflectionClass(get_class($widget));
    
      if(strpos($reflector->getFileName(), 'wp-includes\widgets') !== FALSE){
        unregister_widget(get_class($widget));
        continue;
      }

      if(strpos($reflector->getFileName(), 'siteorigin-panels') !== FALSE){
        unregister_widget(get_class($widget));
        continue;
      }
    }
  });
}