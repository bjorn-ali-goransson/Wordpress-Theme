<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY ADMIN STYLE */

function add_my_admin_style($name, $dependencies = array()){
  if(!isset($GLOBALS['my_admin_styles'])){
    $GLOBALS['my_admin_styles'] = array();
  }
  
  $GLOBALS['my_admin_styles'][] = (object)array('name' => $name, 'dependencies' => $dependencies);
}

add_action('admin_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $object){
      wp_register_style($object->name, get_template_directory_uri() . '/' . 'styles/' . $object->name, $object->dependencies, get_version('styles/' . $object->name));
    }
  }
  
  if(isset($GLOBALS['my_admin_styles'])){
    foreach($GLOBALS['my_admin_styles'] as $object){
      wp_enqueue_style($object->name, get_template_directory_uri() . '/' . 'styles/' . $object->name, $object->dependencies, get_version('styles/' . $object->name));
    }
  }
});