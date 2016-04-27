<?php


/* ADD SIDEBAR */

function add_sidebar($id, $title){
  register_sidebar(array(
    'name'          => $title,
    'id'            => $id,
    'description'   => '',
    'class'         => '',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<!--',
    'after_title'   => '-->'
  ));
}