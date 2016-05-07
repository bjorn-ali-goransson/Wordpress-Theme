<?php
  
require_once dirname(__FILE__) . '/../utility_functions/get_html_element.php';



/* HR SHORTCODE */

add_shortcode('brand_primary', function($attributes, $content){ return get_html_element('span', $attributes, $content, 'brand-primary'); });