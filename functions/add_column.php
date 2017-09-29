<?php



/* ADD COLUMN */

function add_column($post_type, $title, $action){
  if(!isset($GLOBALS['custom-column-count'])){
    $GLOBALS['custom-column-count'] = 0;
  }
  
  $GLOBALS['custom-column-count']++;
  
  add_filter('manage_' . $post_type . '_posts_columns',function( $columns ) use ($title) {
      $columns['custom_column_' . $GLOBALS['custom-column-count']] = $title;
      return $columns;
  });
  
  add_action( 'manage_' . $post_type . '_posts_custom_column', function ( $column_id, $post_id ) use ($action) {
      if($column_id != 'custom_column_' . $GLOBALS['custom-column-count']){
          return;
      }
      
      $action($post_id);
  }, 10, 2 );
}