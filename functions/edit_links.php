<?php



/* MY EDIT POST LINK */

function my_edit_post_link($post_id = 0, $classes = ''){
  my_edit_link(get_edit_post_link($post_id), __('Edit post'), "my-edit-post-link $classes");
}



/* MY EDIT MENU LINK */

function my_edit_menu_link($location = 'top', $classes = ''){
  $menu_obj = get_menu_by_location($location);

  if($menu_obj){
    my_edit_link("/wp-admin/nav-menus.php?action=edit&menu={$menu_obj->term_id}", __('Edit menu'), "my-edit-menu-link my-edit-menu-link-$location $classes");
  } else {
    my_edit_link("/wp-admin/nav-menus.php?action=edit&menu=0&use-location=$location", __('Add menu'), "my-edit-menu-link my-edit-menu-link-$location $classes", '<i class="fa fa-plus-square"></i>');
  }
}



/* MY EDIT LINK */

function my_edit_link($url, $title, $classes = '', $icon = '<i class="fa fa-pencil-square"></i>'){
  if(is_user_logged_in() && current_user_can('edit_posts')){
    if(strpos($url, 'http://') !== 0){
      $url = get_bloginfo("url") . $url;
    }
    
    echo " <a href=\"{$url}\" class=\"my-edit-link {$classes}\" title=\"{$title}\">$icon</a>";
  }
}