<?php



/* LOAD FUNCTION MODULES */

foreach(glob(dirname(__FILE__) . '/functions/*.php') as $filename){
  require_once $filename;
}



/* APPLICATION LOGIC */

require DIRNAME(__FILE__) . '/application.php';



/* LOAD APPLICATION FUNCTIONS */

foreach(glob(dirname(__FILE__) . '/application/functions/*.php') as $filename){
  require_once $filename;
}



/* LOAD APPLICATION MODULES */

foreach(glob(dirname(__FILE__) . '/application/*.php') as $filename){
  require_once $filename;
}