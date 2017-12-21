<?php



/* REDIRECT SHORTCODE */

add_shortcode('redirect', function($attributes, $content){});

add_action('template_redirect', function(){
  global $post;

  if(strpos($post->post_content, '[redirect]') === FALSE){
    return;
  }

  preg_match('@\[redirect\]([^[]+)\[/redirect\]@', $post->post_content, $matches);

  wp_redirect($matches[1]);

  die;
});