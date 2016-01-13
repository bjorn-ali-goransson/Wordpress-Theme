<?php
  
/* DISABLE WELCOME PANEL */

function disable_welcome_panel(){
  remove_action('welcome_panel', 'wp_welcome_panel');
}