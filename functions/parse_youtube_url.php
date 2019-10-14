<?php



/* PARSE YOUTUBE URL */

function parse_youtube_url($string){ // https://stackoverflow.com/a/6382259
  if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $string, $match)) {
    return $match[1];
  }

  return null;
}