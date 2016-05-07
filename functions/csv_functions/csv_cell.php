<?php



/* CSV CELL */

function csv_cell($value = NULL, $debug = FALSE){
  if($value){
    echo '"' . str_replace('"', '”', iconv('UTF-8', 'Windows-1252', $value)) . '";';
  } else {
    echo '"";';
  }
}