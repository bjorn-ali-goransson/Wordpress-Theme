<?php



/* GET PAGE WITH TEMPLATE */

function get_page_with_template($template_name){
  $pages = get_posts(array(
    'post_type' => 'page',
    'numberposts' => 1,
    'meta_key' => '_wp_page_template',
    'meta_value' => "{$template_name}.php",
  ));
  
  if(empty($pages)){
    return null;
  }

  return $pages[0];
}