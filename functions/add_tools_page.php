<?php



/* ADD TOOLS PAGE */

function add_my_tools_page($title, $slug, $path){
  if(!isset($GLOBALS['my-tools-pages'])){
    $GLOBALS['my-tools-pages'] = array();
  }

  $GLOBALS['my-tools-pages'][] = (object)array(
    'title' => $title,
    'slug' => $slug,
    'path' => $path,
  );
}

add_action('admin_menu', function(){
  if(!isset($GLOBALS['my-tools-pages'])){
    return;
  }
  
  foreach($GLOBALS['my-tools-pages'] as $object){
	  add_management_page($object->title, $object->title, "manage_options", $object->slug, create_function('', 'require "' . addslashes(dirname(__FILE__)) . '/' . $object->path . '";'));
  }
});