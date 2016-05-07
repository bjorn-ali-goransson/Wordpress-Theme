<?php



/* UNWRAP ELEMENT FROM ELEMENT */

function unwrap_element_from_element($content, $child, $parent){
  $content = preg_replace('@<' . $parent . '[^>]*>\s*(<' . $child . '[^>]*>)@', '$1', $content);
  $content = preg_replace('@(</' . $child . '>)\s*</' . $parent . '>@', '$1', $content);

  return $content;
}