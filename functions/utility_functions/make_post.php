<?php
  
/* MAKE POST */

function make_post($url, $parameters){
  $response = wp_remote_post( $url, array('body' => $parameters));

  if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    throw new Exception("Something went wrong: $error_message");
  } else {
    return $response['body'];
  }
}