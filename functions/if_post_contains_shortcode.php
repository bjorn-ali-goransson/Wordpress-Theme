<?php
  

/* IF POST CONTAINS SHORTCODE */

function if_post_contains_shortcode($shortcode_name, $callback){
  add_action('the_posts', function($posts) use ($shortcode_name, $callback){
    if(empty($posts)){
      return $posts;
    }

    $post_content = $posts[0]->post_content;

    if(strpos($post_content, '[' . $shortcode_name . ']') !== FALSE){
      $callback();
    }

    return $posts;
  });
}