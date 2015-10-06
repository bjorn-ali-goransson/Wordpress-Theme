<?php



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