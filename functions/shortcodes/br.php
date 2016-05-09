<?php
  
require_once dirname(__FILE__) . '/../utility_functions/get_html_element.php';



/* BR SHORTCODE */

add_shortcode('br', function($attributes, $content){ return get_html_element('br', $attributes, $content); });