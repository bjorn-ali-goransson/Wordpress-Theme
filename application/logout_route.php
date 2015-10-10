<?php



/* LOGOUT ROUTE */

add_route('/logout', function(){
  wp_clear_auth_cookie();
  redirect_to_page_containing_shortcode('logged_out');
});

noop_shortcode('logged_out'); // shortcode shouldn't output anything --- only serving the purpose of tagging the page for the above redirect