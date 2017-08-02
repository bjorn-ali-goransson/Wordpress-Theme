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
  
function get_my_script_or_style_object($name, $dependencies = ''){
  $is_script = substr($name, -strlen('.js')) == '.js';
  $is_css = substr($name, -strlen('.css')) == '.css';
  $is_scss = substr($name, -strlen('.scss')) == '.scss';
  
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

  if($is_scss){
    $name .= '.css';
    $path .= 'styles/compiled/';
  }
  
  if($is_script) {
    if(strpos($name, '/') === 0){
      $path = '';
    } else {
      $path .= 'scripts/compiled/';
    }
  }
  
  if($is_css) {
    if(strpos($name, '/') === 0){
      $path = '';
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
    'version' => strpos($_SERVER["HTTP_HOST"], 'localhost') === FALSE ? @filemtime(dirname(__FILE__) . '/..' . $path) : NULL,
  );
}



/* EDITOR STYLES */

add_action('admin_enqueue_scripts', function(){
  if(is_admin()){
    $style_object = get_my_script_or_style_object('wp-editor-styles.scss');

    add_editor_style($style_object->url);
  }
});