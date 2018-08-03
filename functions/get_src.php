<?php



/* GET SRC */

function get_src($image_id, $size = NULL) {
  if(!$image_id){
    return NULL;
  }

  $image = wp_get_attachment_image_src($image_id, $size);
  return $image[0];
}