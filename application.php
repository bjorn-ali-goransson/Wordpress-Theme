<?php

force_login_to_site();



/* SCRIPTS */

add_my_script('angular.min.js', 'angular');
add_my_script('js/tab.js', 'bootstrap');
add_my_script('lodash.min.js', 'lodash', array('underscore')); // always override underscore
add_my_script('main.js', array('lodash/lodash.min.js', 'jquery', 'jquery-ui-autocomplete'));

add_my_admin_script('admin.js', array('jquery', 'lodash/lodash.min.js'));



/* STYLES */

add_my_style('main.less');
add_my_admin_style('admin.less');



/* CUSTOM POST TYPES */

//add_post_type('agreement', 'Agreements');



/* IMAGE SIZES */

// add_image_size('lg', 1700, 9999);
// add_image_size('md', 1200, 9999);
// add_image_size('sm', 900, 9999);



/* CUSTOM OPTIONS */

//add_long_text_field_to_settings('footer_text', 'Footer text');



/* PROFILE OPTIONS */

//add_text_field_to_profile('postal_code', 'Postal code');



/* SIDEBARS */

//register_sidebar(array(
//  'name' => '',
//  'id' => '',
//  'before_widget' => '<section id="%1$s" class="widget %2$s">',
//  'after_widget' => '</section>',
//));



/* MENUS */

//add_theme_support('menus');
//register_nav_menu("top", "Top");



/* THUMBNAILS */

//add_theme_support('post-thumbnails', array( 'post' ));
//set_post_thumbnail_size(200, 200, true);



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

add_theme_support('automatic-feed-links');