<?php



/* GET YEARS FOR POSTS */

function get_years_for_posts($post_type = 'post') { // https://wordpress.stackexchange.com/a/273627
    global $wpdb;
    
    $result = [];

    foreach($wpdb->get_results($wpdb->prepare("SELECT DISTINCT YEAR(post_date) AS year FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish' ORDER BY year DESC", $post_type), ARRAY_N) as $year) {
      $result[] = $year[0];
    }
      
    return $result;
}
