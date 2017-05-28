<?php

require_once dirname(__FILE__) . '/rgb_to_hsv.php';
require_once dirname(__FILE__) . '/hsv_to_rgb.php';

function modify_color($hex, $diff) {
  $rgbhex = str_split(trim($hex, '# '), 2);
  $rgbdec = array_map("hexdec", $rgbhex);
  $hsv = rgb_to_hsv($rgbdec[0], $rgbdec[1], $rgbdec[2]);
  $hsv['V'] = $hsv['V'] + $diff;
  $rgbdark = hsv_to_rgb($hsv['H'], $hsv['S'], $hsv['V']);
  $output = array_map("dechex", $rgbdark);
  $output = array_map(function($num) { return strlen($num) < 2 ? '0' . $num : $num; }, $output);
  return '#'.implode($output);
}