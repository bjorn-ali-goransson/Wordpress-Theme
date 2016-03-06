<?php



/* MENUS */

function get_menu_by_location( $location ) {
  if( empty($location) ) return false;

  $locations = get_nav_menu_locations();
  if( ! isset( $locations[$location] ) ) return false;

  $menu_obj = get_term( $locations[$location], 'nav_menu' );

  return $menu_obj;
}

function echo_menu_error_message(){
  ?>
    <ul class="<?php echo $GLOBALS["my_menu_class_names"]; ?>">
      <li><a href="<?php echo admin_url('nav-menus.php'); ?>?action=edit&menu=0&location=<?php echo $GLOBALS["my_menu_location"] ?>"><?php _e("Click here to create a menu!") ?></a></li>
    </ul>
  <?php
}

function no_menu_error_message(){}

function my_menu($location = "top", $class_names = "nav"){
  $GLOBALS["my_menu_location"] = $location;
  $GLOBALS["my_menu_class_names"] = $class_names;
  $GLOBALS["my_menu_dropdowns"] = 1;
  wp_nav_menu(array(
    'theme_location' => $location,
    'depth'    => 1,
    'container'  => false,
    'menu_class'   => $class_names,
    'fallback_cb' => 'echo_menu_error_message',
    'walker'   => new Bootstrap_Walker_Nav_Menu()
  ));
  unset($GLOBALS["my_menu_location"]);
  unset($GLOBALS["my_menu_class_names"]);
  unset($GLOBALS["my_menu_dropdowns"]);
}

class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat( "\t", $depth );
    
    $classes = array();

    if($GLOBALS["my_menu_dropdowns"] == 1 && $depth == 0){
      $classes[] = 'dropdown-menu';
    }
    
    $output	.= "\n$indent<ul class=\"" . join(' ', $classes) . "\">\n";
  }

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>";
	}

  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    
    if($GLOBALS["my_menu_dropdowns"] == 1 && $depth == 0){
      $classes[] = ($args->has_children) ? 'dropdown' : '';
    }
    
    $classes[] = ($item->current) ? 'active' : '';
    $classes[] = ($item->current_item_ancestor) ? 'child-is-active' : '';
    $classes[] = 'menu-item-' . $item->ID;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = ' class="' . esc_attr( $class_names ) . '"';

    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

    $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    
    if($GLOBALS["my_menu_dropdowns"] == 1 && $depth == 0){
      $attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    
    if($GLOBALS["my_menu_dropdowns"] == 1 && $depth == 0 && $args->has_children){
      $item_output .= ' <i class="icon-caret-down"></i>';
    }
    
    $item_output .= '</a>';
    
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
	  $output .= "</li>";
	}
}