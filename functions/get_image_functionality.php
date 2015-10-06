<?php



/* GET IMAGE FROM META */

function get_image_from_meta($post_id, $meta_key, $size = 'full'){
  $image_id = get_post_meta($post_id, $meta_key, TRUE);

  return get_image($image_id, $size);
}



/* GET IMAGE */

function get_image($image_id, $size = 'full'){
  $image = wp_get_attachment_image_src($image_id, $size);

  return (object)array(
    'url' => $image[0],
    'width' => $image[1],
    'height' => $image[2],
  );
}