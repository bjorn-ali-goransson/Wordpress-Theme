<?php // Please note! This file is just an example of project-specific logic in your theme, and it will make sure that the application/ directory is created by git. By using the checkout command as in README.md, this file will be deleted automatically.



/* LOGOUT ROUTE */

add_route('/logout', function(){
  wp_clear_auth_cookie();
  redirect_to_page_containing_shortcode('logged_out');
});

noop_shortcode('logged_out');