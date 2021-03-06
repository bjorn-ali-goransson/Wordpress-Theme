<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY STYLE */

function add_my_style($name, $dependencies = array()){
  if(!isset($GLOBALS['my_styles'])){
    $GLOBALS['my_styles'] = array();
  }

  $GLOBALS['my_styles'][] = (object)array('name' => $name, 'dependencies' => $dependencies);
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $object){
      $path = $object->name;

      if(strpos($path, '/') === 0){
        $path = substr($path, 1);
      } else {
        $path = 'styles/' . $path;
      }

      wp_enqueue_style($object->name, get_template_directory_uri() . '/' . $path, $object->dependencies, get_version($path));
    }
  }
});