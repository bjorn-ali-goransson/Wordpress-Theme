<?php



/* ADD POST TYPE */

function add_post_type($arg1, $arg2){
  if(!isset($GLOBALS['my_post_types'])){
    $GLOBALS['my_post_types'] = array();
  }

  $GLOBALS['my_post_types'][] = get_my_post_type_object($arg1, $arg2);
}



/* GET POST TYPE OBJECT */

function get_my_post_type_object($arg1, $arg2){
  $id = $arg1;
  $title = $arg2;

  return (object)array(
    'id' => $id,
    'options' => array(
      'public' => FALSE,
      'show_ui' => TRUE,
      'show_in_nav_menus' => TRUE,
      'labels' => array(
        'name' => $title,
      ),
      'supports' => array('title'),
      //'has_archive' => 'my_slug', // Don't forget to visit Permalinks after changing this
    )
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