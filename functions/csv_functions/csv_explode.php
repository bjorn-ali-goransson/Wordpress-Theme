<?php
  


/* CSV EXPLODE */

function csv_explode($delim=',', $str, $enclose='"', $preserve=false){ // http://stackoverflow.com/questions/1800675/write-csv-to-file-without-enclosures-in-php
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