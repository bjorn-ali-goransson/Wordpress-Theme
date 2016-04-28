<?php



/* TRIM LEADING AND TRAILING P TAGS */

function trim_leading_and_trailing_p_tags($content){
  $content = trim($content);

  if(strpos($content, '</p>') === 0){
    $content = substr($content, strlen('</p>'));
  }

  if(strrpos($content, '<p>') == strlen($content) - strlen('<p>')){
    $content = substr($content, 0, strlen($content) - strlen('<p>'));
  }

  return trim($content);
}