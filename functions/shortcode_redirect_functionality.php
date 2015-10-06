<?php



/* REDIRECT TO PAGE CONTAINING SHORTCODE */

function redirect_to_page_containing_shortcode($shortcode_name, $query_string = ''){
  $url = get_url_of_page_containing_shortcode($shortcode_name);

  if($url){
    wp_redirect($url . $query_string);
    die;
  }

  echo 'Error: No [' . $shortcode_name . '] page found, copy this URL and contact administrators: ' . $query_string;
  die;
}



/* GET URL OF PAGE CONTAINING SHORTCODE */

function get_url_of_page_containing_shortcode($shortcode_name){
  $page = get_custom_post_containing_shortcode('page', $shortcode_name);

  if($page != NULL){
    return get_permalink($page->ID);
  }

  return NULL;
}



/* GET CUSTOM POST CONTAINING SHORTCODE */

function get_custom_post_containing_shortcode($post_type, $shortcode_name){
  foreach(get_posts(array('numberposts' => -1, 'post_type' => $post_type)) as $post){
    if(strpos($post->post_content, '[' . $shortcode_name . ']') !== FALSE){
      return $post;
    }
  }

  return NULL;
}