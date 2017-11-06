<?php



/* ADD META FIELD TO SEARCH QUERY */

function add_meta_field_to_search_query($field){
  if(isset($GLOBALS['added_meta_field_to_search_query'])){
    $GLOBALS['added_meta_field_to_search_query'][] = '\'' . $field . '\'';
  
    return;
  }
  
  $GLOBALS['added_meta_field_to_search_query'] = array();
  $GLOBALS['added_meta_field_to_search_query'][] = '\'' . $field . '\'';
  
  add_filter('posts_join', function($join){
      global $wpdb;

      if (is_search()){    
          $join .= " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
      }

      return $join;
  });

  add_filter('posts_groupby', function($groupby){
      global $wpdb;

      if (is_search()) {    
          $groupby = "$wpdb->posts.ID";
      }

      return $groupby;
  });

  add_filter('posts_search', function($search_sql){
      global $wpdb;

      $search_terms = get_query_var('search_terms');
      
      if(!empty($search_terms)){
        foreach ($search_terms as $search_term){
            $old_or = "OR ({$wpdb->posts}.post_content LIKE '{$wpdb->placeholder_escape()}{$search_term}{$wpdb->placeholder_escape()}')";
            $new_or = $old_or . " OR ({$wpdb->postmeta}.meta_value LIKE '{$wpdb->placeholder_escape()}{$search_term}{$wpdb->placeholder_escape()}' AND {$wpdb->postmeta}.meta_key IN (" . implode(', ', $GLOBALS['added_meta_field_to_search_query']) . "))";
            $search_sql = str_replace($old_or, $new_or, $search_sql);
        }
      }
      
      $search_sql = str_replace( " ORDER BY ", " GROUP BY $wpdb->posts.ID ORDER BY ", $search_sql );
      
      return $search_sql;
  });
}