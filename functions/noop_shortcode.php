<?php



/* NOOP SHORTCODE */

function noop_shortcode($shortcode_name){
  add_shortcode($shortcode_name, function(){});
}