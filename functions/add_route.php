<?php



/* ADD PUBLIC ROUTE */

function add_public_route($url, $arg2, $options = array()){
  $options['public'] = TRUE;

  add_route($url, $arg2, $options);
}



/* ADD ROUTE */

function add_route($url, $arg2, $options = array()){
  if(!isset($GLOBALS['my-routes'])){
    $GLOBALS['my-routes'] = array();
  }

  if(is_callable($arg2)){
    $callback = $arg2;
    $path = NULL;
  } else {
    $callback = NULL;
    $path = dirname(__FILE__) . '/../' . $arg2;
  }

  $GLOBALS['my-routes'][] = (object)array(
    'url' => $url,
    'callback' => $callback,
    'path' => $path,
    'options' => $options,
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
  $uri = on_iis() ? utf8_encode($_SERVER['REQUEST_URI']) : urldecode($_SERVER['REQUEST_URI']);
  
  foreach($GLOBALS['my-routes'] as $route){
    $route_segments = explode('/', $route->url);
    $uri_segments = explode('/', $uri);
    $route_values = array();

    if(strpos($route->url, ':') !== FALSE){
      if(count($route_segments) != count($uri_segments)){
        continue;
      }

      $route_match = TRUE;

      foreach($route_segments as $i => $route_segment){
        $uri_segment = $uri_segments[$i];

        if(strpos($route_segment, ':') !== FALSE) {
          continue;
        }

        if($route_segment === $uri_segment){
          continue;
        }
        
        $route_match = FALSE;
      }

      if(!$route_match){
        echo 'no';
        continue;
      }

      foreach($route_segments as $i => $route_segment){
        $uri_segment = $uri_segments[$i];

        if(strpos($route_segment, ':') !== 0) {
          continue;
        }

        $route_values[substr($route_segment, 1)] = $uri_segment;
      }

      $new_route_values = array();
      
      $reflection = new ReflectionFunction($route->callback);

      foreach ($reflection->getParameters() as $parameter) {
        $new_route_values[] = $route_values[$parameter->name];
      }

      $route_values = $new_route_values;
    }

    if(empty($route_values) && strpos($uri, $site_url . $route->url) !== 0){
      continue;
    }
    
    if(!isset($route->options['public']) && !is_user_logged_in()){
      continue;
    }

    if($route->callback){
      $callback = $route->callback;
      $result = call_user_func_array($callback, $route_values);

      if($result){
        if(is_string($result)){
          echo $result;
        } else {
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($result);
        }
      }
    }

    if($route->path){
      require $route->path;
    }

    die;
  }
});