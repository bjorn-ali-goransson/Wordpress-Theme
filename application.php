<?php

force_login_to_site();



/* SCRIPTS */

add_my_script('main.js', array('jquery'));
add_my_admin_script('admin.js');



/* STYLES */

add_my_style('wp-required-styles.css');
add_my_style('main.css');
add_my_admin_style('admin.css');
add_my_editor_style('wp-editor-styles.css');



/* CUSTOM POST TYPES */

//add_post_type('agreement', 'Agreements');



/* IMAGE SIZES */

// add_image_size('lg', 1700, 9999);
// add_image_size('md', 1200, 9999);
// add_image_size('sm', 900, 9999);



/* CUSTOM OPTIONS PAGES */

//if(function_exists('acf_add_options_sub_page')){
//  acf_add_options_sub_page(array(
//    'page_title' => 'Footer settings',
//    'menu_title' => 'Footer settings',
//    'menu_slug' => 'settings-footer',
//    'capability' => 'edit_posts',
//    'redirect' => false,
//    'parent_slug' => 'options-general.php',
//  ));
//}



/* SIDEBARS */

//add_sidebar('id', 'title');

remove_default_widgets();



/* MENUS */

add_theme_support('menus');
register_nav_menu("main", "Main menu");



/* THUMBNAILS */

//add_theme_support('post-thumbnails', array( 'post' ));
//set_post_thumbnail_size(200, 200, true);



/* REMOVE ITEMS FROM UI */

remove_item_from_admin_menu('index.php'); // Dashboard
remove_item_from_admin_menu('edit.php'); // Posts
remove_item_from_admin_menu('upload.php'); // Media library
remove_item_from_admin_menu('edit.php?post_type=page'); // Pages
remove_item_from_admin_menu('edit-comments.php'); // Comments

remove_item_from_admin_bar('customize');
remove_item_from_admin_bar('new-content');
remove_item_from_admin_bar('comments');
remove_item_from_admin_bar('search');
remove_item_from_admin_bar('wp-logo');



/* OTHER */

add_theme_support('automatic-feed-links');