<?php



/* GET CHILDREN OF */

function get_children_of($id){
  $result = array();

  foreach(get_posts(array('numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'page', 'post_parent' => $id, 'suppress_filters' => false)) as $child){
    $result[] = (object)(array(
      'post' => $child,
      'children' => get_children_of($child->ID)
    ));
  }

  return $result;
}