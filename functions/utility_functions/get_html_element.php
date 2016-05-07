<?php



/* GET HTML ELEMENT */

function get_html_element($tag_name, $attributes, $content, $class_name = NULL){
  if($class_name){
    if(isset($attributes['class'])){
      $attributes['class'] .= ' ' . $class_name;
    } else {
      $attributes['class'] = $class_name;
    }
  }

  $attributes_output = '';

  foreach($attributes as $key => $value){
    $attributes_output .= ' ';
    $attributes_output .= $key.'="'.$value.'"';
  }

  return '<' . $tag_name . $attributes_output . '>' . $content . '</' . $tag_name . '>';
}