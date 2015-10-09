<?php



/* STRING BEGINS WITH */

function string_begins_with($haystack, $needle) { // http://stackoverflow.com/a/10473026/3638471
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}



/* STRING ENDS WITH */

function string_ends_with($haystack, $needle) { // http://stackoverflow.com/a/10473026/3638471
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}



/* TRIM OFF END OF STRING */

function trim_off_end_of_string($string, $number_of_characters){ // http://stackoverflow.com/a/4915787/3638471
  return substr($string, 0, -$number_of_characters);
}