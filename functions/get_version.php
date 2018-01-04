<?php



/* GET VERSION */

function get_version($path){
  if(strpos($_SERVER["HTTP_HOST"], 'localhost') !== FALSE){
    return NULL;
  }

  $version = @filemtime(get_template_directory() . '/' . $path);

  if(!$version){
    return NULL;
  }

  return substr(dechex(substr($version . '', 5)), -3);
}