<?php
// This really is utf8 json. Tell the browser.
header("Content-Type: application/json; charset=utf-8");
//CORS header
header("Access-Control-Allow-Origin: *");

include_once("./vtp.php");

$json = json_encode(get_vtp(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
if (!$json)
	$json = json_encode(
		Array(
			"error" => "encode_json() failed"
		),
		JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE
	);
echo $json;

?>
