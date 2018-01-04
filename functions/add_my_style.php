<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY STYLE */

function add_my_style($name, $dependencies){
  if(!isset($GLOBALS['my_styles'])){
    $GLOBALS['my_styles'] = array();
  }

  $GLOBALS['my_styles'][] = (object)array('name' => $name, 'dependendencies' => $dependencies);
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $object){
      wp_enqueue_style($object->name, get_template_directory_uri() . '/' . 'styles/' . $object->name, $object->dependencies, get_version('styles/' . $object->name));
    }
  }
});