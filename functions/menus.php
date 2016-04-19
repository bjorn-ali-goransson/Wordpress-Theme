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
  wp_nav_menu(array(
    'theme_location' => $location,
    'depth'    => 1,
    'container'  => false,
    'menu_class'   => $class_names,
    'fallback_cb' => 'echo_menu_error_message',
  ));
}