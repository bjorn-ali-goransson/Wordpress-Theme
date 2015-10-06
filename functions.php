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



/* MENUS */

add_theme_support('menus');
register_nav_menu("top", "Top");



/* THUMBNAILS */

//add_theme_support('post-thumbnails', array( 'post' ));
//set_post_thumbnail_size(200, 200, true);



/* ECHO JAVASCRIPT VARIABLE */

function echo_javascript_variable($variable_name, $value){
  echo '<script>' . 'window.' . $variable_name . ' = ' . json_encode($value) . '</script>' . "\n";
}



/* EDITOR STYLES */

add_action('admin_enqueue_scripts', function(){
  if(is_admin() && in_array($GLOBALS['pagenow'], array("post.php")) && $_GET['action'] == 'edit'){
    $style_object = get_my_script_or_style_object('wp-editor-styles.less');

    add_editor_style($style_object->url);
  }
});



/* RESPONSIVE EMBEDS */

add_filter('embed_oembed_html', function($html, $url, $attr, $post_ID) {
    $return = '<figure class="video-container">'.$html.'</figure>';
    return $return;
}, 10, 4);



/* AJAX URL */

add_action('wp_head', function() {
  ?>
    <script>var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';</script>
  <?php
});



/* ADD PARAMETER TO URL */

function add_parameter_to_url($name, $value, $url = NULL) {
  if(!$url){
    $url = 'http://' . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['REQUEST_URI'];
  }

  if(strpos($url, '?') !== FALSE){
    $separator = '&';
  } else {
    $separator = '?';
  }

  return $url . $separator . $name . '=' . $value;
}



/* CREATE ADMINISTRATOR USER */

function create_administrator_user($username, $password, $email){
  $user_id = wp_create_user($username, $password, $email);
  $wp_user_object = new WP_User($user_id);
  $wp_user_object->set_role('administrator');
}



/* NULL IF FALSY */

function null_if_falsy($value){
  return !empty($value) ? $value : NULL;
}



/* ICON SHORTCODE */

add_shortcode('icon', function($attributes, $content){
  return '<i class="fa fa-' . $content . ' my-icon"></i>';
});



/* TRASH DEFAULT CONTENT */

add_action("after_switch_theme", function(){
  wp_trash_post(1); // Sample post
  wp_trash_post(2); // Sample page
});



/* OTHER */

add_theme_support('automatic-feed-links');