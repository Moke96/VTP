<?php
//Original von Jochen Hillenstedt 10.12.2014
//Aenderungen von Jan Tatje

// Hilfsmodul "simple html dom" einbinden (Auswerten des DOM)
include('./inc/simple_html_dom.php');

$VTP = Array( 
		"vertretungen" => Array(
			"tag1" => Array(
				"datum" => "",
				"daten" => Array()
			),
			"tag2" => Array(
				"datum" => "",
				"daten" => Array()
			),
			"tag3" => Array(
				"datum" => "",
				"daten" => Array()
			)
		),
		"info" => ""
);

//encoding
$src_charset = "Windows-1252";
//other
$days = 3;

//Username und Password 
$username = 'schueler@willms';
$password = 'SpiegleinGmbH';

// Daten einlesen mit cURL
// direktes Einlesen ist auf diesem Server nicht möglich
$curl = curl_init();
//general options
curl_setopt($curl, CURLOPT_USERPWD, $username.':'.$password); //Daten aus .htaccess
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
// get vtp, convert to utf-8 immediately
curl_setopt($curl, CURLOPT_URL, "http://willms-gymnasium.selfhost.bz:88/svplan.html");
$vtp_str = iconv($src_charset, "UTF-8", curl_exec($curl));
// get info
curl_setopt($curl, CURLOPT_URL, 'http://willms-gymnasium.selfhost.bz:88/info.html');  
$info_str = iconv($src_charset, "UTF-8", curl_exec($curl));
curl_close($curl);

$html = str_get_html($vtp_str);
//Tabelle durcharbeiten mit simple html dom und in Listen umwandeln
$i = 0;
foreach($html->find('tr') as $zeile) {
	if ($i == 2) {
		$VTP["vertretungen"]["tag1"]["datum"] =
		str_replace('&nbsp','',$zeile->find('td',0)->plaintext);
		$VTP["vertretungen"]["tag2"]["datum"] =
		str_replace('&nbsp','',$zeile->find('td',1)->plaintext);
		$VTP["vertretungen"]["tag3"]["datum"] =
		str_replace('&nbsp','',$zeile->find('td',2)->plaintext);
	}
	if ($i++ <= 2) continue;
	for ($j = 0; $j < $days; $j++) {
		$temp = $zeile->find('td',$j);
		if (trim($temp->plaintext) <> '.') 
			array_push($VTP["vertretungen"]["tag".($j+1)]["daten"], str_replace('&nbsp','',$temp->plaintext));
	}
}

//Inhalt des Infofensters auslesen
$html = str_get_html($info_str);
$memotext = $html->find('html',0)->innertext;
$memotext = str_replace("\r\n", "", $memotext);
$memotext = str_replace(chr(34), chr(39), $memotext); // what does this do???
$memotext = trim($memotext);

$VTP["info"] = strip_tags($memotext);

//CORS header setzen
header("Access-Control-Allow-Origin: *");

//Daten-Ausgabe
$json = json_encode($VTP, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
if (!$json)
	$json = json_encode(
		Array(
			"error" => "encode_json() failed"
		),
		JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE
	);
echo $json;
?>

