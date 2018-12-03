<?php



/* GET MENU ITEMS */

function get_menu_items($menu_name){
  $locations = get_nav_menu_locations();

  if(!$locations || !isset($locations[$menu_name])){
    return array();
  }

  $menu = wp_get_nav_menu_object($locations[$menu_name]);

  $menu_items = wp_get_nav_menu_items($menu->term_id);

  $children = array();

  foreach($menu_items as $menu_item){
    $menu_item->children = array();

    $children[$menu_item->ID] = &$menu_item->children;
  }

  foreach($menu_items as $menu_item){
    if(!$menu_item->menu_item_parent){
      continue;
    }

    $children[$menu_item->menu_item_parent][] = $menu_item;
  }

  foreach($menu_items as $i => $menu_item){
    if(!$menu_item->menu_item_parent){
      continue;
    }

    unset($menu_items[$i]);
  }

  return $menu_items;
}