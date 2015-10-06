<?php



/* REMOVE ITEMS FROM ADMIN MENU */

function remove_item_from_admin_menu($slug){
  if(!isset($GLOBALS['remove_items_from_admin_menu'])){
    $GLOBALS['remove_items_from_admin_menu'] = array();
  }

  $GLOBALS['remove_items_from_admin_menu'][] = $slug;
}

add_action('admin_menu', function() {
  if(isset($GLOBALS['remove_items_from_admin_menu'])){
    foreach($GLOBALS['remove_items_from_admin_menu'] as $slug){
	    remove_menu_page($slug);
    }
  }
});



/* REMOVE ITEMS FROM ADMIN BAR */

function remove_item_from_admin_bar($slug){
  if(!isset($GLOBALS['remove_items_from_admin_bar'])){
    $GLOBALS['remove_items_from_admin_bar'] = array();
  }

  $GLOBALS['remove_items_from_admin_bar'][] = $slug;
}

add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
  global $wp_admin_bar;

  if(isset($GLOBALS['remove_items_from_admin_bar'])){
    foreach($GLOBALS['remove_items_from_admin_bar'] as $slug){
	    $wp_admin_bar->remove_node($slug);
    }
  }
}, 999);