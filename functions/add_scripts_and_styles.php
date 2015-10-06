<?php



/* ADD MY SCRIPT */

function add_my_script($name, $arg2 = NULL, $arg3 = NULL){
  if(!isset($GLOBALS['my_scripts'])){
    $GLOBALS['my_scripts'] = array();
  }

  $GLOBALS['my_scripts'][] = get_my_script_or_style_object($name, $arg2, $arg3);
}

function add_my_admin_script($name, $arg2 = NULL, $arg3 = NULL){
  if(!isset($GLOBALS['my_admin_scripts'])){
    $GLOBALS['my_admin_scripts'] = array();
  }

  $GLOBALS['my_admin_scripts'][] = get_my_script_or_style_object($name, $arg2, $arg3);
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_scripts'])){
    foreach($GLOBALS['my_scripts'] as $object){
      wp_enqueue_script($object->id, $object->url, $object->dependencies, $object->version);
    }
  }
});

add_action('admin_enqueue_scripts', function(){
  if(isset($GLOBALS['my_scripts'])){
    foreach($GLOBALS['my_scripts'] as $object){
      wp_register_script($object->id, $object->url, $object->dependencies, $object->version);
    }
  }

  if(isset($GLOBALS['my_admin_scripts'])){
    foreach($GLOBALS['my_admin_scripts'] as $object){
      wp_enqueue_script($object->id, $object->url, $object->dependencies, $object->version);
    }
  }
});



/* ADD MY STYLE */

function add_my_style($name, $arg2 = NULL, $arg3 = NULL){
  if(!isset($GLOBALS['my_styles'])){
    $GLOBALS['my_styles'] = array();
  }

  $GLOBALS['my_styles'][] = get_my_script_or_style_object($name, $arg2, $arg3);
}

function add_my_admin_style($name, $arg2 = NULL, $arg3 = NULL){
  if(!isset($GLOBALS['my_admin_styles'])){
    $GLOBALS['my_admin_styles'] = array();
  }

  $GLOBALS['my_admin_styles'][] = get_my_script_or_style_object($name, $arg2, $arg3);
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $object){
      wp_enqueue_style($object->id, $object->url, NULL, $object->version);
    }
  }
});

add_action('admin_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $object){
      wp_register_style($object->id, $object->url, NULL, $object->version);
    }
  }

  if(isset($GLOBALS['my_admin_styles'])){
    foreach($GLOBALS['my_admin_styles'] as $object){
      wp_enqueue_style($object->id, $object->url, NULL, $object->version);
    }
  }
});



/* GET MY SCRIPT OR STYLE OBJECT */
  
function get_my_script_or_style_object($name, $arg2 = NULL, $arg3 = NULL){
  $is_script = strrpos($name, '.js', 0) === strlen($name) - strlen('.js');
  $is_less = strrpos($name, '.less', 0) === strlen($name) - strlen('.less');
  
  if($arg2 == NULL && $arg3 == NULL){
    $vendor = '';
    $dependencies = '';
  }
  if($arg2 != NULL && $arg3 == NULL){
    if(is_array($arg2)){
      $vendor = '';
      $dependencies = $arg2;
    } else {
      $vendor = $arg2;
      $dependencies = '';
    }
  }
  if($arg2 != NULL && $arg3 != NULL){
    $vendor = $arg2;
    $dependencies = $arg3;
  }

  if(strpos($name, 'http://') === 0 || strpos($name, 'https://') === 0){
    return (object)array(
      'id' => $name,
      'url' => $name,
      'dependencies' => is_array($dependencies) ?
        $dependencies :
        $is_script ?
        array('jquery') :
        NULL,
      'version' => NULL,
    );
  }

  $path = '/';

  if($vendor != ''){
    $path .= 'vendor/';
    $path .= $vendor;
    $path .= '/';
  } else {
    if($is_less){
      $name .= '.css';
      $path .= 'styles/compiled/';
    } else if($is_script) {
      $path .= 'scripts/';
    } else {
      $path .= 'styles/';
    }
  }

  if($is_script && $dependencies == ''){
    $dependencies = array('jquery');
  }

  $path .= $name;

  return (object)array(
    'id' => (empty($vendor) ? '' : $vendor . '/') . $name,
    'url' => get_template_directory_uri() . $path,
    'dependencies' => $dependencies,
    'version' => strpos($_SERVER["HTTP_HOST"], 'localhost') === FALSE ? @filemtime(dirname(__FILE__) . $path) : NULL,
  );
}