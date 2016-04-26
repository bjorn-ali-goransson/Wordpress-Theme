<?php



/* GET IMAGE */

function get_image($image_id, $size = 'full'){
  $image = wp_get_attachment_image_src($image_id, $size);

  if(empty($image)){
    return NULL;
  }

  return (object)array(
    'url' => $image[0],
    'width' => $image[1],
    'height' => $image[2],
  );
}