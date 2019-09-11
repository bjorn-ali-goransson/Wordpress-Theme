<?php



/* GET SRCSET */

function get_srcset($image_id, $sizes = NULL) {
  if(!$image_id){
    return NULL;
  }
  
  $srcset = array();

  foreach($sizes ? $sizes : get_intermediate_image_sizes() as $size) {
    $image = wp_get_attachment_image_src($image_id, $size);

    if(!empty($image)){
      $srcset[] = $image[0] . ' ' . $image[1] . 'w';
    }
  }

  return implode(', ', $srcset);
}