<?php



/* USER HAS ROLE */

function user_has_role($role_name){
   return is_user_logged_in() && in_array($role_name, wp_get_current_user()->roles);
}