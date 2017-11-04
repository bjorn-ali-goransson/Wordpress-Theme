<?php

function only_enable_singular_requests(){
  add_action('template_redirect', function($query){
    if(is_admin()) {
      return;
    }
    
    if(is_front_page()){
      return;
    }
    
    if(is_singular()){
      return;
    }
    
    wp_redirect(home_url());
    exit;
  });
}