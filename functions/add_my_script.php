<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY SCRIPT */

function add_my_script($name, $dependencies = array()){
  if(!isset($GLOBALS['my_scripts'])){
    $GLOBALS['my_scripts'] = array();
  }

  $GLOBALS['my_scripts'][] = (object)array('name' => $name, 'dependencies' => $dependencies);
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_scripts'])){
    foreach($GLOBALS['my_scripts'] as $object){
      $path = $object->name;

      if(strpos($path, '/') === 0){
        $path = substr($path, 1);
      } else {
        $path = 'scripts/' . $path;
      }

      wp_enqueue_script($object->name, get_template_directory_uri() . '/' . $path, $object->dependencies, get_version($path));
    }
  }
});