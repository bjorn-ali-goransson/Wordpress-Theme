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
    
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    
    exit;
  });
}