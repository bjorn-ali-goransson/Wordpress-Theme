<?php

function output_image($image_id, $attributes) {
  $fallback = wp_get_attachment_image_src($image_id, 'screen-xl');
  
  $attributes['src'] = $fallback[0];

  $srcset = array();

  foreach(get_intermediate_image_sizes() as $size) {
    $image = wp_get_attachment_image_src($image_id, $size);

    $srcset[] = $image[0] . ' ' . $image[1] . 'w';
  }

  $attributes['srcset'] = implode(', ', $srcset);

  ?>
    <img <?= join(' ', array_map(function($key) use ($attributes) { return $key.'="'.$attributes[$key].'"'; }, array_keys($attributes))) ?>>
  <?php
}