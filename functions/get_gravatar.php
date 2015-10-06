<?php



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