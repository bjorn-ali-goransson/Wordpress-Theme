<?php

require_once dirname(__FILE__) . '/get_version.php';



/* ADD MY EDITOR STYLE */

function add_my_editor_style($name){
  add_action('admin_enqueue_scripts', function() use ($name){
    add_editor_style(get_template_directory_uri() . '/' . 'styles/' . $name . '?v=' . get_version('styles/' . $name));
  });
}