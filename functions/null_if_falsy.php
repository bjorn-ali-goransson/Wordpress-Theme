<?php



/* NULL IF FALSY */

function null_if_falsy($value){
  return !empty($value) ? $value : NULL;
}