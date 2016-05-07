<?php


/* GET JSON PAYLOAD */

function get_json_payload(){
  return json_decode(file_get_contents("php://input"));
}