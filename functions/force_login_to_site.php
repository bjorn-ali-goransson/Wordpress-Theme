<?php

function force_login_to_site($redirect_url = NULL, $whitelist = array()){
  // https://github.com/kevinvess/wp-force-login

  add_action('init', function() {
    if(!is_user_logged_in()) {
      $url  = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
      $url .= '://' . $_SERVER['HTTP_HOST'];
      if(strpos($_SERVER['HTTP_HOST'], ':') === FALSE){
        $url .=  in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':' . $_SERVER['SERVER_PORT'];
      }
      $url .= $_SERVER['REQUEST_URI'];
      
      if(preg_replace('/\?.*/', '', $url) != preg_replace('/\?.*/', '', wp_login_url()) && !in_array($url, $whitelist)) {
        wp_safe_redirect(wp_login_url($redirect_url ? $redirect_url : $url), 302); exit();
      }
    }
  });
}