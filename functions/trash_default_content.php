<?php



/* TRASH DEFAULT CONTENT */

add_action("after_switch_theme", function(){
  wp_trash_post(1); // Sample post
  wp_trash_post(2); // Sample page
});