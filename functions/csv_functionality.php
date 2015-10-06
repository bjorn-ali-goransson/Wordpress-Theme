<?php



/* PARSE CSV */

function my_parse_csv($data){
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

function csv_explode($delim=',', $str, $enclose='"', $preserve=false){ 
  $resArr = array(); 
  $n = 0; 
  $expEncArr = explode($enclose, $str); 
  foreach($expEncArr as $EncItem){ 
    if($n++%2){ 
      array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:'')); 
    }else{ 
      $expDelArr = explode($delim, $EncItem); 
      array_push($resArr, array_pop($resArr) . array_shift($expDelArr)); 
      $resArr = array_merge($resArr, $expDelArr);
    } 
  } 
  return $resArr; 
}



/* MY CSV CELL */

function my_csv_cell($value = NULL, $debug = FALSE){
  if($debug){
    echo '<td>' . $value . '</td>';
    return;
  }

  if($value){
    echo '"' . str_replace('"', '”', iconv('UTF-8', 'Windows-1252', $value)) . '";';
  } else {
    echo '"";';
  }
}