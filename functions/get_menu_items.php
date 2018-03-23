<?php



/* GET MENU ITEMS */

function get_menu_items($menu_name){
  $locations = get_nav_menu_locations();

  if(!$locations || !isset($locations[$menu_name])){
    return array();
  }

  $menu = wp_get_nav_menu_object($locations[$menu_name]);

  return $menu_items = wp_get_nav_menu_items($menu->term_id);
}