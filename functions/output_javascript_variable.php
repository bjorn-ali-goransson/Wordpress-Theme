<?php



/* OUTPUT JAVASCRIPT VARIABLE */

function output_javascript_variable($variable_name, $value){
  echo '<script>' . 'window.' . $variable_name . ' = ' . json_encode($value) . '</script>' . "\n";
}