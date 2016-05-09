<?php
  
require_once dirname(__FILE__) . '/../utility_functions/get_html_element.php';



/* BRAND PRIMARY SHORTCODE */

add_shortcode('brand_primary', function($attributes, $content){ return get_html_element('span', $attributes, do_shortcode($content), 'brand-primary'); });