<?php



/* HIDE FOR MOBILE */

add_shortcode('hide_for_mobile', function($attributes, $content){ return get_html_element('hidden-xs hidden-sm', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });