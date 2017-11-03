<?php



/* ADD COLUMN */

function add_column($post_type, $title, $action){
  if(!isset($GLOBALS['custom-columns'])){
    $GLOBALS['custom-columns'] = array();
  }
  
  if(!isset($GLOBALS['custom-columns'][$post_type])){
    $GLOBALS['custom-columns'][$post_type] = array();
    
    add_filter('manage_' . $post_type . '_posts_columns', function($columns) use ($post_type) {
        foreach($GLOBALS['custom-columns'][$post_type] as $column_number => $column_object){
          $columns['custom_column_' . $column_number] = $column_object->title;
        }
        
        return $columns;
    });
    
    add_action('manage_' . $post_type . '_posts_custom_column', function($column_id, $post_id) use ($post_type) {
      foreach($GLOBALS['custom-columns'][$post_type] as $column_number => $column_object){
        if('custom_column_' . $column_number == $column_id){
          $action = $column_object->action;
          $action($post_id);
        }
      }
    }, 10, 2);
  }
  
  $GLOBALS['custom-columns'][$post_type][] = (object)array(
    'post_type' => $post_type,
    'title' => $title,
    'action' => $action,
  );
}