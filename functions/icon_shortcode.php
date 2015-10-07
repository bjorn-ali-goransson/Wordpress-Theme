<?php



/* ICON SHORTCODE */

add_shortcode('icon', function($attributes, $content){
  return '<i class="fa fa-' . $content . ' my-icon"></i>';
});