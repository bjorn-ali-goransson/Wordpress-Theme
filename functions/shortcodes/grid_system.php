<?php
  
require_once dirname(__FILE__) . '/../utility_functions/get_html_element.php';
require_once dirname(__FILE__) . '/../utility_functions/unwrap_element_from_element.php';
require_once dirname(__FILE__) . '/../utility_functions/trim_leading_and_trailing_p_tags.php';



/* GRID SYSTEM */

add_shortcode('row', function($attributes, $content){ return get_html_element('div', $attributes, unwrap_element_from_element(do_shortcode(trim_leading_and_trailing_p_tags($content)), 'div', 'p'), 'row'); });
add_shortcode('one_half', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-6'); });
add_shortcode('one_third', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-4'); });
add_shortcode('two_thirds', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-8'); });
add_shortcode('one_fourth', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-3'); });
add_shortcode('three_fourths', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-9'); });
add_shortcode('one_sixth', function($attributes, $content){ return get_html_element('div', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'col-sm-2'); });