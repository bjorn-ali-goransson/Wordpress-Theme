<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY ADMIN SCRIPT */

function add_my_admin_script($name, $dependencies = array()){
  if(!isset($GLOBALS['my_admin_scripts'])){
    $GLOBALS['my_admin_scripts'] = array();
  }

  $GLOBALS['my_admin_scripts'][] = (object)array('name' => $name, 'dependencies' => $dependencies);
}

add_action('admin_enqueue_scripts', function(){
  if(isset($GLOBALS['my_scripts'])){
    foreach($GLOBALS['my_scripts'] as $object){
      wp_register_script($object->name, get_template_directory_uri() . '/' . 'scripts/' . $object->name, $object->dependencies, get_version('scripts/' . $object->name));
    }
  }

  if(isset($GLOBALS['my_admin_scripts'])){
    foreach($GLOBALS['my_admin_scripts'] as $object){
      wp_enqueue_script($object->name, get_template_directory_uri() . '/' . 'scripts/' . $object->name, $object->dependencies, get_version('scripts/' . $object->name));
    }
  }
});