<?php



/* DYNAMIC SIDEBARS */

//class Walker_Dynamic_Sidebars extends Walker {
//	var $tree_type = 'page';
//	var $db_fields = array (
//		'parent' => 'post_parent', 
//		'id' => 'ID'
//	);
//  function start_el(&$output, $page, $depth=0, $args=array()) {
//    register_sidebar(array(
//      'name'          => str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $depth) . __('Sidebars for page:') . " \"" . $page->post_title . "\"",
//      'id'            => "page-" . $page->ID,
//      'description'   => '',
//      'class'         => '',
//      'before_widget' => '',
//      'after_widget'  => '',
//      'before_title'  => '<!--',
//      'after_title'   => '-->'
//    ));
//  }
//}

//wp_list_pages(array("walker" => new Walker_Dynamic_Sidebars, "echo" => 0));