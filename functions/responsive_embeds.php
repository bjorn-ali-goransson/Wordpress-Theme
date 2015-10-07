<?php



/* RESPONSIVE EMBEDS */

add_filter('embed_oembed_html', function($html, $url, $attr, $post_ID) {
    $return = '<figure class="video-container">'.$html.'</figure>';
    return $return;
}, 10, 4);