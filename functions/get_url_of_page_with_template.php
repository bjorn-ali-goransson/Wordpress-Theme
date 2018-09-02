<?php



/* GET URL OF PAGE WITH TEMPLATE */

function get_url_of_page_with_template($template_name){
  $page = array_shift(query_posts(array(
    'post_type' =>'page',
    'meta_key'  =>'_wp_page_template',
    'meta_value'=> $template_name
  )));

  if(!$page) {
    return NULL;
  }

  return get_page_link($page->ID);
}