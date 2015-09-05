<?php



/* APPLICATION LOGIC */

require DIRNAME(__FILE__) . '/application.php';



/* WIDGET BASE CLASS */

require dirname(__FILE__) . "/widget.class.php";

add_action('admin_head', function() {
  ?>
  <script>
    (function ($) {
      $(document).on("click", 'input.choose-image', function (event) {
        var button = $(this);

        wp.media.editor.send.attachment = function (props, attachment) {
          button.parent().siblings("input[type=\"hidden\"]").val(attachment.id);
          button.parent().siblings("span.my-image").html('<img src="' + attachment.sizes.thumbnail.url + '" />');
        }

        wp.media.editor.open(this);
        event.preventDefault();
      });

      // $('div.widgets-sortables').bind('sortstop', activate);

    })(jQuery);
  </script>
  <?php
});

add_action('admin_enqueue_scripts', function($hook) {
  if($hook == "widgets.php"){
    wp_enqueue_media();
  }
});

add_action('widgets_init', function() {
  foreach (get_declared_classes() as $class) {
    if (is_subclass_of($class, "My_Widget"))
      register_widget( $class );
  }
});



/* ADD ROUTE */

function add_my_route($url, $callback){
  if(!isset($GLOBALS['my-routes'])){
    $GLOBALS['my-routes'] = array();
  }

  $GLOBALS['my-routes'][] = (object)array(
    'url' => $url,
    'callback' => $callback,
  );
}

add_action('init', function(){
  if(!isset($GLOBALS['my-routes'])){
    return;
  }

  foreach($GLOBALS['my-routes'] as $route){
    if(strpos($_SERVER['REQUEST_URI'], $route->url) === FALSE){
      continue;
    }

    if(!is_user_logged_in()){
      continue;
    }

    $callback = $route->callback;

    $callback();
    die;
  }
});



/* ECHO HEADER VARIABLE */

function echo_header_variable($variable_name, $value){
  echo '<script>' . 'window.' . $variable_name . ' = ' . json_encode($value) . '</script>' . "\n";
}



/* ADD MY SCRIPT */

function add_my_script($name, $vendor = '', $dependencies = array('jquery')){
  $my_script_object = get_my_script_object($name, $vendor, $dependencies);

  if(!isset($GLOBALS['my_scripts'])){
    $GLOBALS['my_scripts'] = array();
  }

  $GLOBALS['my_scripts'][] = $my_script_object;
}


  
function get_my_script_object($name, $vendor = '', $dependencies = array('jquery')){
  $path = '/';

  if($vendor != ''){
    $path .= 'vendor/';
    $path .= $vendor;
    $path .= '/';
  } else {
    $path .= 'scripts/';
  }

  $path .= $name;

  return (object)array(
    'id' => (empty($vendor) ? '' : $vendor . '/') . $name,
    'path' => get_template_directory_uri() . $path,
    'dependencies' => $dependencies,
    'version' => @filemtime(dirname(__FILE__) . $path),
  );
}



add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_scripts'])){
    foreach($GLOBALS['my_scripts'] as $script){
      $url = $script->path;

      wp_enqueue_script($script->id, $url, $script->dependencies, $script->version);
    }
  }
});



/* ADD MY STYLE */

function add_my_style($name, $vendor = ''){
  $path = '/';

  if($vendor != ''){
    $path .= 'vendor/';
    $path .= $vendor;
    $path .= '/';
  } else {
    if(strpos($name, '.less') == strlen($name) - strlen('.less')){
      $name .= '.css';
      $path .= 'styles/compiled/';
    } else {
      $path .= 'styles/';
    }
  }

  $path .= $name;

  if(!isset($GLOBALS['my_styles'])){
    $GLOBALS['my_styles'] = array();
  }

  $GLOBALS['my_styles'][] = (object)array(
    'path' => $path,
    'version' => @filemtime(dirname(__FILE__) . $path),
  );
}

add_action('wp_enqueue_scripts', function(){
  if(isset($GLOBALS['my_styles'])){
    foreach($GLOBALS['my_styles'] as $style){
      wp_enqueue_style(preg_replace('@[^a-z]+@', '-', $style->path), get_template_directory_uri() . $style->path, NULL, $style->version);
    }
  }
});



/* MENUS */

add_theme_support('menus');
register_nav_menu("top", "Top");



/* THUMBNAILS */

//add_theme_support('post-thumbnails', array( 'post' ));
//set_post_thumbnail_size(200, 200, true);



/* REMOVE ITEMS FROM ADMIN MENU */

function remove_item_from_admin_menu($slug){
  if(!isset($GLOBALS['remove_items_from_admin_menu'])){
    $GLOBALS['remove_items_from_admin_menu'] = array();
  }

  $GLOBALS['remove_items_from_admin_menu'][] = $slug;
}

add_action('admin_menu', function() {
  if(isset($GLOBALS['remove_items_from_admin_menu'])){
    foreach($GLOBALS['remove_items_from_admin_menu'] as $slug){
	    remove_menu_page($slug);
    }
  }
});



/* REMOVE ITEMS FROM ADMIN BAR */

function remove_item_from_admin_bar($slug){
  if(!isset($GLOBALS['remove_items_from_admin_bar'])){
    $GLOBALS['remove_items_from_admin_bar'] = array();
  }

  $GLOBALS['remove_items_from_admin_bar'][] = $slug;
}

add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
  global $wp_admin_bar;

  if(isset($GLOBALS['remove_items_from_admin_bar'])){
    foreach($GLOBALS['remove_items_from_admin_bar'] as $slug){
	    $wp_admin_bar->remove_node($slug);
    }
  }
}, 999);



/* EDITOR STYLES */

add_action('admin_enqueue_scripts', function(){
  if(is_admin() && in_array($GLOBALS['pagenow'], array("post.php")) && $_GET['action'] == 'edit'){
    $path = '/styles/wp-editor-styles.less';
    $src = get_template_directory_uri() . $path;

    $handle = preg_replace('@[^a-z]+@', '-', $path);
    
    //

    $compilation_units = get_option('pm-less.js compilation_units');

    if($compilation_units == FALSE){
      $compilation_units = new stdClass;
    }

    $compilation_units->$src = "{$handle}.css";

    update_option('pm-less.js compilation_units', $compilation_units);

    //
    
  	$upload_dir = wp_upload_dir();

		$dir = apply_filters( 'pm_less_js_cache_url', path_join( $upload_dir[ 'baseurl' ], 'pm-less.js-cache' ) );
    
    add_editor_style("$dir/{$handle}.css");
  }
});



/* RESPONSIVE EMBEDS */

add_filter('embed_oembed_html', function($html, $url, $attr, $post_ID) {
    $return = '<figure class="video-container">'.$html.'</figure>';
    return $return;
}, 10, 4);



/* PAGINATION */

function my_pagination(){
  global $wp_query;

  $big = 999999999; // need an unlikely integer

  $links = paginate_links( array(
	  'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	  'format' => '?paged=%#%',
    'type' => 'array',
	  'current' => max( 1, get_query_var('paged') ),
	  'total' => $wp_query->max_num_pages,
	  'prev_text' => '&laquo;',
	  'next_text' => '&raquo;',
  ));

  if(empty($links)){
    return;
  }

  $links_html = '<ul class="pagination"><li>';
  $links_html .= join('</li><li>', $links);
  $links_html .= '</ul>';

  $links_html = str_replace('<li><span class=\'page-numbers current\'>1</span>', '<li class="disabled"><span>&laquo;</span></li><li><span class=\'page-numbers current\'>1</span>', $links_html);
  $links_html = str_replace('<li><span class="page-numbers dots">', '<li class="disabled"><span class=\'page-numbers dots\'>', $links_html);
  $links_html = str_replace('<li><span class=\'page-numbers current\'>', '<li class="active"><span class=\'page-numbers current\'>', $links_html);

  echo $links_html;
}



/* AJAX URL */

add_action('wp_head', function() {
  ?>
    <script>var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';</script>
  <?php
});



/* INCLUDE GRAVATARS IN FEED */

add_action("do_feed_rss","my_rss_img_do_feed",5,1);
add_action("do_feed_rss2","my_rss_img_do_feed",5,1); 

function my_rss_img_do_feed($for_comments){
  if(!$for_comments){
    add_action('rss2_ns', 'my_rss_img_adding_yahoo_media_tag');
    add_action('rss_item', 'my_rss_img_include');
    add_action('rss2_item', 'my_rss_img_include');
  }
}

function my_rss_img_include (){
  global $post;

  $user = get_userdata($post->post_author);
  $image_url = get_gravatar_url_for_user($user);
  
  echo '<media:content url="'.$image_url.'" width="80" height="80" medium="image" type="image/jpeg" />';
}

function my_rss_img_adding_yahoo_media_tag(){
  echo 'xmlns:media="http://search.yahoo.com/mrss/"';
}



/* INCLUDE IMAGES IN FEED */

function my_post_thumbnail_feeds($content) {
    global $post;
    if(has_post_thumbnail($post->ID)) {
        $content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
    }
    return $content;
}

add_filter('the_excerpt_rss', 'my_post_thumbnail_feeds');
add_filter('the_content_feed', 'my_post_thumbnail_feeds');



/* GET GRAVATAR */

add_action('parse_request', 'get_gravatar_for_my_user');

function get_gravatar_for_my_user() {
  if( isset($_GET['get_gravatar_for_my_user']) ) {
    $username = $_GET['get_gravatar_for_my_user'];

    $url = get_gravatar_url_for_username($username);

    header("Location: $url");
    
    exit();
  }
}

function get_gravatar_url_for_username($username){
  $user = get_user_by("slug", $username);
  
  return get_gravatar_url_for_user($user);
}

function get_gravatar_url_for_user($user){
  return get_gravatar_url_for_email($user->user_email);
}

function get_gravatar_url_for_email($email){
  $hash = md5(strtolower(trim($email)));
  return "http://gravatar.com/avatar/$hash";
}



/* UNREGISTER DEFAULT WIDGETS */

add_action('widgets_init', function(){
  global $wp_widget_factory;
  
  foreach($wp_widget_factory->widgets as $widget){
    $reflector = new ReflectionClass(get_class($widget));

    if(strpos($reflector->getFileName(), 'default-widgets.php') !== FALSE){
      unregister_widget(get_class($widget));
      continue;
    }

    if(strpos($reflector->getFileName(), 'siteorigin-panels') !== FALSE){
      unregister_widget(get_class($widget));
      continue;
    }
  }
});



/* MY EDIT POST LINK */

function my_edit_post_link($post_id = 0, $classes = ''){
  my_edit_link(get_edit_post_link($post_id), __('Edit post'), "my-edit-post-link $classes");
}



/* MY EDIT MENU LINK */

function my_edit_menu_link($location = 'top', $classes = ''){
  $menu_obj = get_menu_by_location($location);

  if($menu_obj){
    my_edit_link("/wp-admin/nav-menus.php?action=edit&menu={$menu_obj->term_id}", __('Edit menu'), "my-edit-menu-link my-edit-menu-link-$location $classes");
  } else {
    my_edit_link("/wp-admin/nav-menus.php?action=edit&menu=0&use-location=$location", __('Add menu'), "my-edit-menu-link my-edit-menu-link-$location $classes", '<i class="fa fa-plus-square"></i>');
  }
}



/* MY EDIT LINK */

function my_edit_link($url, $title, $classes = '', $icon = '<i class="fa fa-pencil-square"></i>'){
  if(is_user_logged_in() && current_user_can('edit_posts')){
    if(strpos($url, 'http://') !== 0){
      $url = get_bloginfo("url") . $url;
    }
    
    echo " <a href=\"{$url}\" class=\"my-edit-link {$classes}\" title=\"{$title}\">$icon</a>";
  }
}



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
    'depth'    => 2,
    'container'  => false,
    'menu_class'   => $class_names,
    'fallback_cb' => 'echo_menu_error_message',
    'walker'   => new Bootstrap_Walker_Nav_Menu()
  ));
  unset($GLOBALS["my_menu_location"]);
  unset($GLOBALS["my_menu_class_names"]);
  unset($GLOBALS["my_menu_dropdowns"]);
}

function my_sub_menu($location = "top", $class_names = "nav"){
  $GLOBALS["my_menu_location"] = $location;
  $GLOBALS["my_menu_sub_menu"] = 1;
  $GLOBALS["my_menu_sub_menu_walking_status"] = 'nothing found yet';
  $GLOBALS["my_menu_class_names"] = $class_names;
  $GLOBALS["my_menu_dropdowns"] = 0;
  wp_nav_menu(array(
    'theme_location' => $location,
    'depth'    => 2,
    'container'  => false,
    'menu_class'   => $class_names,
    'fallback_cb' => 'echo_menu_error_message',
    'walker'   => new Bootstrap_Walker_Nav_Menu()
  ));
  unset($GLOBALS["my_menu_location"]);
  unset($GLOBALS["my_menu_sub_menu"]);
  unset($GLOBALS["my_menu_sub_menu_walking_status"]);
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
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

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
    $item_output .= $item->post_excerpt;
    $item_output .= " ";
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    
    if($GLOBALS["my_menu_dropdowns"] == 1 && $depth == 0 && $args->has_children){
      $item_output .= ' <i class="icon-caret-down"></i>';
    }
    
    $item_output .= '</a>';
    
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
    if ( !$element )
      return;

    if(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1){
      if($GLOBALS["my_menu_sub_menu_walking_status"] == 'already found'){
        return;
      }

      if($GLOBALS["my_menu_sub_menu_walking_status"] == 'nothing found yet'){
        if($depth == 0){
          if($element->current || $element->current_item_ancestor){
            $GLOBALS["my_menu_sub_menu_walking_status"] = 'now showing selected tree';
          } else {
            return;
          }
        }
      }
    }
    
    $id_field = $this->db_fields['id'];

    //display this element
    if ( is_array( $args[0] ) ) 
      $args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
    else if ( is_object( $args[0] ) ) 
      $args[0]->has_children = ! empty( $children_elements[$element->$id_field] ); 
    $cb_args = array_merge( array(&$output, $element, $depth), $args);

    if(!(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1 && $depth == 0)){
      call_user_func_array(array(&$this, 'start_el'), $cb_args);
    }

    $id = $element->$id_field;

    // descend only when the depth is right and there are childrens for this element
    if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

      foreach( $children_elements[ $id ] as $child ){

        if ( !isset($newlevel) ) {
          $newlevel = true;
          //start the child delimiter
          $cb_args = array_merge( array(&$output, $depth), $args);
          
          if(!(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1 && $depth == 0)){
            call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
          }
        }
        $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
      }
        unset( $children_elements[ $id ] );
    }

    if ( isset($newlevel) && $newlevel ){
      //end the child delimiter
      $cb_args = array_merge( array(&$output, $depth), $args);
      
      if(!(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1 && $depth == 0)){
        call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
      }
    }

    //end this element
    $cb_args = array_merge( array(&$output, $element, $depth), $args);
    
    if(!(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1 && $depth == 0)){
      call_user_func_array(array(&$this, 'end_el'), $cb_args);
    }

    if(isset($GLOBALS["my_menu_sub_menu"]) && $GLOBALS["my_menu_sub_menu"] == 1){
      if($depth == 0 && $GLOBALS["my_menu_sub_menu_walking_status"] == 'now showing selected tree'){
        $GLOBALS["my_menu_sub_menu_walking_status"] = 'already found';
      }
    }
  }
}



/* COMMENTS */

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li>
    <article <?php comment_class('media'); ?> id="comment-<?php comment_ID(); ?>">
      <a class="pull-left" href="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>">
        <?php echo get_avatar($comment, $size='64'); ?>
      </a>
      <div class="media-body">
        <header class="comment-author vcard">
            
          <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
          <time><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></time> 
          <span class="sharing-tools">
            <a href="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-link"></i></a>
            <span class='st_facebook_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-facebook-sign"></i></span>
            <span class='st_twitter_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-twitter-sign"></i></span>
            <span class='st_linkedin_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-linkedin-sign"></i></span>
            <span class='st_email_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-envelope"></i></span>   
          </span>
          <?php my_edit_link(get_edit_comment_link(), __('Edit comment')) ?>
        </header>
        <?php if ($comment->comment_approved == '0') : ?>
          <em><?php _e('Your comment is awaiting moderation.') ?></em>
          <br />
        <?php endif; ?>

        <?php comment_text() ?>

        <nav>
          <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'Reply <i class="icon-reply"></i>'))) ?>
        </nav>
      </div>
    </article>
  <!-- </li> is added by wordpress automatically -->
<?php
}



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



/* CREATE ADMINISTRATOR USER */

function create_administrator_user($username, $password, $email){
  $user_id = wp_create_user($username, $password, $email);
  $wp_user_object = new WP_User($user_id);
  $wp_user_object->set_role('administrator');
}



/* SEARCH FOR AUTHORS POSTS */

add_filter( 'posts_search', 'db_filter_authors_search' ); // by danielbachhuber
function db_filter_authors_search( $posts_search ) {
 
    // Don't modify the query at all if we're not on the search template
    // or if the LIKE is empty
    if ( !is_search() || empty( $posts_search ) )
        return $posts_search;
 
    global $wpdb;
    // Get all of the users of the blog and see if the search query matches either
    // the display name or the user login
    add_filter( 'pre_user_query', 'db_filter_user_query' );
    $search = sanitize_text_field( get_query_var( 's' ) );
    $args = array(
        'count_total' => false,
        'search' => sprintf( '*%s*', $search ),
        'search_fields' => array(
            'display_name',
            'user_login',
        ),
        'fields' => 'ID',
    );
    $matching_users = get_users( $args );
    remove_filter( 'pre_user_query', 'db_filter_user_query' );
    // Don't modify the query if there aren't any matching users
    if ( empty( $matching_users ) )
        return $posts_search;
    // Take a slightly different approach than core where we want all of the posts from these authors
    $posts_search = str_replace( ')))', ")) OR ( {$wpdb->posts}.post_author IN (" . implode( ',', array_map( 'absint', $matching_users ) ) . ")))", $posts_search );
    error_log( $posts_search );
    return $posts_search;
}
function db_filter_user_query( &$user_query ) {
 
    if ( is_object( $user_query ) )
        $user_query->query_where = str_replace( "user_nicename LIKE", "display_name LIKE", $user_query->query_where );
    return $user_query;
}



/* NULL IF FALSY */

function null_if_falsy($value){
  return !empty($value) ? $value : NULL;
}



/* ON IIS ? */

function on_iis() {
    $sSoftware = strtolower( $_SERVER["SERVER_SOFTWARE"] );
    if ( strpos($sSoftware, "microsoft-iis") !== false )
        return true;
    else
        return false;
}



/* REDIRECT TO PAGE CONTAINING SHORTCODE */

function redirect_to_page_containing_shortcode($shortcode_name, $query_string = ''){
  $url = get_url_of_page_containing_shortcode($shortcode_name);

  if($url){
    wp_redirect($url . $query_string);
    die;
  }

  echo 'Error: No [' . $shortcode_name . '] page found, copy this URL and contact administrators: ' . $query_string;
  die;
}



/* GET URL OF PAGE CONTAINING SHORTCODE */

function get_url_of_page_containing_shortcode($shortcode_name){
  $page = get_custom_post_containing_shortcode('page', $shortcode_name);

  if($page != NULL){
    return get_permalink($page->ID);
  }

  return NULL;
}



/* GET CUSTOM POST CONTAINING SHORTCODE */

function get_custom_post_containing_shortcode($post_type, $shortcode_name){
  foreach(get_posts(array('numberposts' => -1, 'post_type' => $post_type)) as $post){
    if(strpos($post->post_content, '[' . $shortcode_name . ']') !== FALSE){
      return $post;
    }
  }

  return NULL;
}



/* PARSE CSV */

function my_parse_csv($data){
  $data = str_replace("\r", '', $data);

  $lines = csv_explode("\n", $data, "\"");
  
  $csv_lines = array();

  foreach($lines as $line){
    if(trim(trim($line, chr(0xC2).chr(0xA0))) == ''){
      continue;
    }

    $line = csv_explode("\t", $line, "\"");

    $cells = array();

    foreach($line as $cell){
      $cells[] = preg_replace('/[\x00-\x1F]/', '', $cell);
    }

    $csv_lines[] = $cells;
  }

  return $csv_lines;
}

function csv_explode($delim=',', $str, $enclose='"', $preserve=false){ 
  $resArr = array(); 
  $n = 0; 
  $expEncArr = explode($enclose, $str); 
  foreach($expEncArr as $EncItem){ 
    if($n++%2){ 
      array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:'')); 
    }else{ 
      $expDelArr = explode($delim, $EncItem); 
      array_push($resArr, array_pop($resArr) . array_shift($expDelArr)); 
      $resArr = array_merge($resArr, $expDelArr);
    } 
  } 
  return $resArr; 
}



/* MY CSV CELL */

function my_csv_cell($value = NULL, $debug = FALSE){
  if($debug){
    echo '<td>' . $value . '</td>';
    return;
  }

  if($value){
    echo '"' . str_replace('"', '”', iconv('UTF-8', 'Windows-1252', $value)) . '";';
  } else {
    echo '"";';
  }
}



/* GRID SYSTEM */

function get_html_element($class_name, $attributes, $content, $tag_name = 'div'){
  if(isset($attributes['class'])){
    $attributes['class'] .= ' ' . $class_name;
  } else {
    $attributes['class'] = $class_name;
  }

  $attributes_output = '';

  foreach($attributes as $key => $value){
    $attributes_output .= ' ';
    $attributes_output .= $key.'="'.$value.'"';
  }

  return '<' . $tag_name . $attributes_output . '>' . $content . '</' . $tag_name . '>';
}

add_shortcode('row', function($attributes, $content){ return get_html_element('row', $attributes, unwrap_element_from_element(do_shortcode(trim_leading_and_trailing_p_tags($content)), 'div', 'p')); });
add_shortcode('one_half', function($attributes, $content){ return get_html_element('col-sm-6', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_third', function($attributes, $content){ return get_html_element('col-sm-4', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('two_thirds', function($attributes, $content){ return get_html_element('col-sm-8', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_fourth', function($attributes, $content){ return get_html_element('col-sm-3', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('three_fourths', function($attributes, $content){ return get_html_element('col-sm-9', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('one_sixth', function($attributes, $content){ return get_html_element('col-sm-2', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });



/* RESPONSIVE COLUMNS */

add_shortcode('responsive_column_1', 'responsive_column_shortcode');
add_shortcode('responsive_column_2', 'responsive_column_shortcode');

function responsive_column_shortcode($attributes, $content, $shortcode_name){
  $attributes_output = '';

  foreach($attributes as $key => $value){
    $attributes_output .= ' ';
    $attributes_output .= $key.'="'.$value.'"';
  }

  $column_content = do_shortcode(trim_leading_and_trailing_p_tags($content));

  if(strpos($column_content, '<form') !== FALSE){
    $column_content = unwrap_element_from_element($column_content, 'form', 'p');
  }
  
  $column_content = unwrap_element_from_element($column_content, 'p', 'p');

  $output = '';

  if($shortcode_name == 'responsive_column_1'){
    $output .= '<div class="responsive-columns">';
  }
  
  $output .= '<div class="responsive-column' . $responsive_class . '" ' . $attributes_output . '>' . $column_content . '</div>';

  if($shortcode_name == 'responsive_column_2'){
    $output .= '</div>';
  }

  return $output;
}



/* ICON SHORTCODE */

add_shortcode('icon', function($attributes, $content){
  return '<i class="fa fa-' . $content . ' my-icon"></i>';
});



/* GET OPTION */

function my_option($key){
  $options = get_option('my_settings');

  if(!array_key_exists($key, $options)){
    return NULL;
  }

  return $options[$key];
}



/* TRASH DEFAULT CONTENT */

add_action("after_switch_theme", function(){
  wp_trash_post(1); // Sample post
  wp_trash_post(2); // Sample page
});



/* GET IMAGE FROM META */

function get_image_from_meta($post_id, $meta_key, $size = 'full'){
  $image_id = get_post_meta($post_id, $meta_key, TRUE);
  $image = wp_get_attachment_image_src($image_id, $size);

  return (object)array(
    'url' => $image[0],
    'width' => $image[1],
    'height' => $image[2],
  );
}



/* FORCE LOGIN TO SITE */

function force_login_to_site(){
  add_action('template_redirect', function(){
   if(!is_user_logged_in()){
     auth_redirect();
   }
  });
}



/* OTHER */

add_theme_support('automatic-feed-links');

add_shortcode('link_button', function($attributes, $content){ return get_html_element('btn btn-primary', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'a'); });

add_shortcode('show_for_mobile', function($attributes, $content){ return get_html_element('hidden-md hidden-lg hidden-xl', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('hide_for_mobile', function($attributes, $content){ return get_html_element('hidden-xs hidden-sm', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });