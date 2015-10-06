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



/* CREATE ADMINISTRATOR USER */

function create_administrator_user($username, $password, $email){
  $user_id = wp_create_user($username, $password, $email);
  $wp_user_object = new WP_User($user_id);
  $wp_user_object->set_role('administrator');
}



/* NULL IF FALSY */

function null_if_falsy($value){
  return !empty($value) ? $value : NULL;
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