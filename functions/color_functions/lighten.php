<?php

require_once dirname(__FILE__) . '/modify_color.php';

function lighten($hex, $diff) {
  return modify_color($hex, $diff);
}