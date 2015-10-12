<?php
  

/* DISABLE SEARCH */

function disable_search(){
  add_action('parse_query', function($query, $error = true){
    if(is_search()){
      $query->is_search = false;
      $query->query_vars['s'] = false;
      $query->query['s'] = false;
      
      $query->is_404 = true;
    }
  });
}