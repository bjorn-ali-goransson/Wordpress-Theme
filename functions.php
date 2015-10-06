<?php
  


/* LOAD MODULES */

foreach(glob(dirname(__FILE__) . '/functions/*.php') as $filename){
  require_once $filename;
}



/* APPLICATION LOGIC */

require DIRNAME(__FILE__) . '/application.php';



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
    return;
    
    add_editor_style(" ... ");
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



/* TRASH DEFAULT CONTENT */

add_action("after_switch_theme", function(){
  wp_trash_post(1); // Sample post
  wp_trash_post(2); // Sample page
});



/* GET IMAGE FROM META */

function get_image_from_meta($post_id, $meta_key, $size = 'full'){
  $image_id = get_post_meta($post_id, $meta_key, TRUE);

  return get_image($image_id, $size);
}



/* GET IMAGE */

function get_image($image_id, $size = 'full'){
  $image = wp_get_attachment_image_src($image_id, $size);

  return (object)array(
    'url' => $image[0],
    'width' => $image[1],
    'height' => $image[2],
  );
}



/* OTHER */

add_theme_support('automatic-feed-links');

add_shortcode('link_button', function($attributes, $content){ return get_html_element('btn btn-primary', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content)), 'a'); });

add_shortcode('show_for_mobile', function($attributes, $content){ return get_html_element('hidden-md hidden-lg hidden-xl', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });
add_shortcode('hide_for_mobile', function($attributes, $content){ return get_html_element('hidden-xs hidden-sm', $attributes, do_shortcode(trim_leading_and_trailing_p_tags($content))); });