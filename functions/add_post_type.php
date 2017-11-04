<?php



/* ADD POST TYPE */

function add_post_type($id, $label, $options = array()){
  if(!isset($GLOBALS['my_post_types'])){
    $GLOBALS['my_post_types'] = array();
  }

  $GLOBALS['my_post_types'][] = get_my_post_type_object($id, $label, $options);
}



/* GET POST TYPE OBJECT */

function get_my_post_type_object($id, $label, $options = array()){
  $supports = array_merge(array('title'), !empty($options['supports']) ? $options['supports'] : array());

  $default_options = array(
    'public' => FALSE,
    'show_ui' => TRUE,
    'show_in_nav_menus' => TRUE,
    'labels' => array(
      'name' => $label,
    ),
    'supports' => $supports,
    //'has_archive' => 'my_slug', // Don't forget to visit Permalinks after changing this
  );

  $options = array_merge($default_options, $options);

  return (object)array(
    'id' => $id,
    'options' => $options,
  );
}



/* OTHER */

add_action('init', function(){
  if(!isset($GLOBALS['my_post_types'])){
    return;
  }

  foreach($GLOBALS['my_post_types'] as $post_type_object){
    register_post_type($post_type_object->id, $post_type_object->options);
  }
});