<?php



add_shortcode('brand_primary', function($attributes, $content){
  return '<span class="brand-primary">' . $content . '</span>';
});