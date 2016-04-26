<?php



/* GET IMAGE FROM META */

function get_image_from_meta($post_id, $meta_key = '_thumbnail_id', $size = 'full'){
  $image_id = get_post_meta($post_id, $meta_key, TRUE);

  return get_image($image_id, $size);
}