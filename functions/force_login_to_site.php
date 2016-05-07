<?php

require_once dirname(__FILE__) . '/utility_functions/get_current_url.php';
  


/* FORCE LOGIN TO SITE */

function force_login_to_site($redirect_url = NULL, $whitelist = array()){ // https://github.com/kevinvess/wp-force-login
  add_action('init', function()use($redirect_url, $whitelist) {
    if(!is_user_logged_in()) {
      $url = get_current_url();
      
      if(preg_replace('/\?.*/', '', $url) != preg_replace('/\?.*/', '', wp_login_url()) && !in_array($url, $whitelist)) {
        wp_safe_redirect(wp_login_url($redirect_url ? $redirect_url : $url), 302); exit();
      }
    }
  });
}