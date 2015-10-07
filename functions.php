<?php



/* LOAD FUNCTION MODULES */

foreach(glob(dirname(__FILE__) . '/functions/*.php') as $filename){
  require_once $filename;
}



/* APPLICATION LOGIC */

require DIRNAME(__FILE__) . '/application.php';



/* LOAD APPLICATION MODULES */

foreach(glob(dirname(__FILE__) . '/application/*.php') as $filename){
  require_once $filename;
}



/* RESPONSIVE EMBEDS */

add_filter('embed_oembed_html', function($html, $url, $attr, $post_ID) {
    $return = '<figure class="video-container">'.$html.'</figure>';
    return $return;
}, 10, 4);



/* OTHER */

add_theme_support('automatic-feed-links');