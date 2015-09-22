<?php



/* ADD (PRIVATE) ROUTE */

function add_my_route($url, $arg2){
  if(!isset($GLOBALS['my-routes'])){
    $GLOBALS['my-routes'] = array();
  }

  if(is_callable($arg2)){
    $callback = $arg2;
    $path = NULL;
  } else {
    $callback = NULL;
    $path = dirname(__FILE__) . '/' . $arg2;
  }

  $GLOBALS['my-routes'][] = (object)array(
    'url' => $url,
    'callback' => $callback,
    'path' => $path,
  );
}



/* OTHER */

add_action('init', function(){
  if(!isset($GLOBALS['my-routes'])){
    return;
  }

  $site_url = get_option('home');
  $slash_position = strpos($site_url, '/', strlen('https://'));
  $site_url = $slash_position !== FALSE ? substr($site_url, $slash_position) : '';
  
  foreach($GLOBALS['my-routes'] as $route){
    if(strpos($_SERVER['REQUEST_URI'], $site_url . $route->url) !== 0){
      continue;
    }

    if(!is_user_logged_in()){
      continue;
    }

    if($route->callback){
      $callback = $route->callback;
      $callback();
    }

    if($route->path){
      require $route->path;
    }

    die;
  }
});