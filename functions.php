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



/* CREATE ADMINISTRATOR USER */

function create_administrator_user($username, $password, $email){
  $user_id = wp_create_user($username, $password, $email);
  $wp_user_object = new WP_User($user_id);
  $wp_user_object->set_role('administrator');
}



/* ICON SHORTCODE */

add_shortcode('icon', function($attributes, $content){
  return '<i class="fa fa-' . $content . ' my-icon"></i>';
});



/* OTHER */

add_theme_support('automatic-feed-links');