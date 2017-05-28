<?php

function hsv_to_rgb ($H, $S, $V)  // HSV Values:Number 0-1 
{                                 // RGB Results:Number 0-255 
  $RGB = array(); 
  if($S == 0) 
  { 
    $R = $G = $B = $V * 255; 
  } 
  else 
  { 
    $var_H = $H * 6; 
    $var_i = floor( $var_H ); 
    $var_1 = $V * ( 1 - $S ); 
    $var_2 = $V * ( 1 - $S * ( $var_H - $var_i ) ); 
    $var_3 = $V * ( 1 - $S * (1 - ( $var_H - $var_i ) ) ); 
    if       ($var_i == 0) { $var_R = $V     ; $var_G = $var_3  ; $var_B = $var_1 ; } 
    else if  ($var_i == 1) { $var_R = $var_2 ; $var_G = $V      ; $var_B = $var_1 ; } 
    else if  ($var_i == 2) { $var_R = $var_1 ; $var_G = $V      ; $var_B = $var_3 ; } 
    else if  ($var_i == 3) { $var_R = $var_1 ; $var_G = $var_2  ; $var_B = $V     ; } 
    else if  ($var_i == 4) { $var_R = $var_3 ; $var_G = $var_1  ; $var_B = $V     ; } 
    else                   { $var_R = $V     ; $var_G = $var_1  ; $var_B = $var_2 ; } 
    $R = $var_R * 255; 
    $G = $var_G * 255; 
    $B = $var_B * 255; 
  } 
  $RGB['R'] = $R; 
  $RGB['G'] = $G; 
  $RGB['B'] = $B; 
  return $RGB; 
} 