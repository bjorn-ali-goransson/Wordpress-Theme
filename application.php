<?php



/* SCRIPTS */

add_my_script('angular.min.js', 'angular');
add_my_script('js/tab.js', 'bootstrap');
add_my_script('lodash.min.js', 'lodash', array('underscore')); // always override underscore
add_my_script('main.js', '', array('lodash/lodash.min.js', 'jquery', 'jquery-ui-autocomplete'));

add_action('admin_enqueue_scripts', function(){
  //wp_enqueue_script('my_admin_script_1', get_template_directory_uri() . '/scripts/admin.js', array('jquery', 'underscore'));
});



/* STYLES */

add_my_style('main.less');



/* CUSTOM POST TYPES */

add_action('init', function(){
  register_post_type('agreement', array(
    'public' => FALSE,
    'show_ui' => TRUE,
    'show_in_nav_menus' => TRUE,
    'labels' => array(
      'name' => 'Avtal',
    ),
    'supports' => array('title'),
    //'has_archive' => 'my_slug', // Don't forget to visit Permalinks after changing this
  ));
});



/* APPLICATION FUNCTIONS */

require 'application-functions.php';



/* CUSTOM OPTIONS PAGE */

require 'application-options-page.php';



/* SIDEBARS */

//register_sidebar(array(
//  'name' => '',
//  'id' => '',
//  'before_widget' => '<section id="%1$s" class="widget %2$s">',
//  'after_widget' => '</section>',
//));



/* REMOVE ITEMS FROM UI */

remove_item_from_admin_menu('index.php');
remove_item_from_admin_menu('edit.php');
remove_item_from_admin_menu('upload.php');
remove_item_from_admin_menu('edit.php?post_type=page');
remove_item_from_admin_menu('edit-comments.php');

remove_item_from_admin_bar('new-content');
remove_item_from_admin_bar('comments');
remove_item_from_admin_bar('search');
remove_item_from_admin_bar('wp-logo');



/* OTHER */

