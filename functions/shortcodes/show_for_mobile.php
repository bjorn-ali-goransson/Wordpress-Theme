<?php
  


/* SHOW FOR MOBILE */

add_shortcode('show_for_mobile', function($attributes, $content){ return get_html_element('hidden-md hidden-lg hidden-xl', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });