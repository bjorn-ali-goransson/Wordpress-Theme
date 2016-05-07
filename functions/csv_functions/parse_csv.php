<?php



/* PARSE CSV */

function parse_csv($data){
  $data = str_replace("\r", '', $data);

  $lines = csv_explode("\n", $data, "\"");
  
  $csv_lines = array();

  foreach($lines as $line){
    if(trim(trim($line, chr(0xC2).chr(0xA0))) == ''){
      continue;
    }

    $line = csv_explode("\t", $line, "\"");

    $cells = array();

    foreach($line as $cell){
      $cells[] = preg_replace('/[\x00-\x1F]/', '', $cell);
    }

    $csv_lines[] = $cells;
  }

  return $csv_lines;
}