<?php



/* ICON SHORTCODE */

add_shortcode('icon', function($args, $content){
  return '<span class="icon"><i class="fa fa-' . array_values($args)[0] . '"></i></span>';
});