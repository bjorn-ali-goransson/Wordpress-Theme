<?php
  
require_once dirname(__FILE__) . '/../utility_functions/get_html_element.php';



/* HR SHORTCODE */

add_shortcode('hr', function($attributes, $content){ return get_html_element('div', $attributes, $content); });