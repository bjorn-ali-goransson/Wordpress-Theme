<?php



/* GET HTML ELEMENT */

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