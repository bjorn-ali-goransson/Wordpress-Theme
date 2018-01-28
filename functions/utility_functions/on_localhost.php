<?php



/* ON LOCALHOST */

function on_localhost(){
  return strpos($_SERVER["HTTP_HOST"], 'localhost') !== FALSE;
}