<?php



/* GRID SYSTEM */

function get_html_element($class_name, $attributes, $content, $tag_name = 'div'){
  if(isset($attributes['class'])){
    $attributes['class'] .= ' ' . $class_name;
  } else {
    $attributes['class'] = $class_name;
  }

  $attributes_output = '';

  foreach($attributes as $key => $value){
    $attributes_output .= ' ';
    $attributes_output .= $key.'="'.$value.'"';
  }

  return '<' . $tag_name . $attributes_output . '>' . $content . '</' . $tag_name . '>';
}

add_shortcode('row', function($attributes, $content){ return get_html_element('row', $attributes, unwrap_element_from_element(do_shortcode(trim_leading_and_trailing_p_tags($content)), 'div', 'p')); });
add_shortcode('one_half', function($attributes, $content){ return get_html_element('col-sm-6', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_third', function($attributes, $content){ return get_html_element('col-sm-4', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('two_thirds', function($attributes, $content){ return get_html_element('col-sm-8', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_fourth', function($attributes, $content){ return get_html_element('col-sm-3', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('three_fourths', function($attributes, $content){ return get_html_element('col-sm-9', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_sixth', function($attributes, $content){ return get_html_element('col-sm-2', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });



/* RESPONSIVE COLUMNS */

add_shortcode('responsive_column_1', 'responsive_column_shortcode');
add_shortcode('responsive_column_2', 'responsive_column_shortcode');

function responsive_column_shortcode($attributes, $content, $shortcode_name){
  $attributes_output = '';

  foreach($attributes as $key => $value){
    $attributes_output .= ' ';
    $attributes_output .= $key.'="'.$value.'"';
  }

  $column_content = do_shortcode(trim_leading_and_trailing_p_tags($content));

  if(strpos($column_content, '<form') !== FALSE){
    $column_content = unwrap_element_from_element($column_content, 'form', 'p');
  }
  
  $column_content = unwrap_element_from_element($column_content, 'p', 'p');

  $output = '';

  if($shortcode_name == 'responsive_column_1'){
    $output .= '<div class="responsive-columns">';
  }
  
  $output .= '<div class="responsive-column' . $responsive_class . '" ' . $attributes_output . '>' . $column_content . '</div>';

  if($shortcode_name == 'responsive_column_2'){
    $output .= '</div>';
  }

  return $output;
}