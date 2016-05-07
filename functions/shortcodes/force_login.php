<?php

require_once dirname(__FILE__) . '/../utility_functions/noop_shortcode.php';


/* FORCE LOGIN SHORTCODE */

noop_shortcode('force_login');

if_post_contains_shortcode('force_login', function(){
  if(!is_user_logged_in()){
    wp_safe_redirect(wp_login_url(get_current_url()), 302); exit();
  }
});