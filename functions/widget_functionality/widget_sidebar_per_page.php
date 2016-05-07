<?php



/* WIDGET SIDEBAR PER PAGE */

class Walker_Widget_Sidebar_Per_Page extends Walker {
	var $tree_type = 'page';
	var $db_fields = array (
		'parent' => 'post_parent', 
		'id' => 'ID'
	);
  function start_el(&$output, $page, $depth = 0, $args = array(), $current_object_id = 0) {
    register_sidebar(array(
      'name'          => str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $depth) . __('Sidebars for page:') . " \"" . $page->post_title . "\"",
      'id'            => "page-" . $page->ID,
      'description'   => '',
      'class'         => '',
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => '<!--',
      'after_title'   => '-->'
    ));
  }
}

function widget_sidebar_per_page(){
  wp_list_pages(array("walker" => new Walker_Widget_Sidebar_Per_Page, "echo" => 0));
}

function page_sidebar(){
  dynamic_sidebar("page-" . get_the_ID());
}