<?php



/* RESPONSIVE EMBEDS */

add_filter('embed_oembed_html', function($html){ // http://www.lorut.no/responsive-youtube-vimeo-embed-bootstrap-roots-io-wordpress/
  $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
  return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}, 10, 1);