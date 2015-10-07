<?php



/* DISABLE ALL FEEDS */

function disable_all_feeds(){
  add_action('do_feed', 'disable_single_feed', 1);
  add_action('do_feed_rdf', 'disable_single_feed', 1);
  add_action('do_feed_rss', 'disable_single_feed', 1);
  add_action('do_feed_rss2', 'disable_single_feed', 1);
  add_action('do_feed_atom', 'disable_single_feed', 1);
}

function disable_single_feed() {
	http_response_code(404);
  die;
}