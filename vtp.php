<?php
//Original von Jochen Hillenstedt 10.12.2014
//Aenderungen von Jan Tatje

function get_vtp() {
	// grabs the vertretungsplan from the server, parses the html response and 
	// returns an array with the vertretungsplan
	include('./login');
	
	// Hilfsmodul "simple html dom" einbinden (Auswerten des DOM)
	include('./inc/simple_html_dom.php');
	
	$VTP = Array( 
			"vertretungen" => Array(
				"tag1" => Array(
					"datum" => "",
					"fehlende_lehrer" => "",
					"fehlende_klassen" => "",
					"daten" => Array()
				),
				"tag2" => Array(
					"datum" => "",
					"fehlende_lehrer" => "",
					"fehlende_klassen" => "",
					"daten" => Array()
				),
				"tag3" => Array(
					"datum" => "",
					"fehlende_lehrer" => "",
					"fehlende_klassen" => "",
					"daten" => Array()
				)
			),
			"info" => "",
			"letzte_aktualisierung" => ""
	);
	
	//encoding
	$src_charset = "Windows-1252";
	//other
	$days = 3;
	
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
	foreach($html->find('tr') as $line) {
		//Third line is date (counts from 0)
		if ($i == 2)
			for ($j = 0; $j < $days; $j++) {
				$VTP["vertretungen"]["tag".(1+$j)]["datum"] =
					str_replace('&nbsp','',$line->find('td',$j)->plaintext);
			}
		//second line is missing teachers
		if ($i == 1)
			for ($j = 0; $j < $days; $j++) {
				$VTP["vertretungen"]["tag".(1+$j)]["fehlende_lehrer"] =
					str_replace('&nbsp','',$line->find('td',$j)->plaintext);
			}
		//do not add these first lines to the array, they have their own
		if ($i++ <= 2)
			continue;
		
		//add every non empty cell to the corresponding array
		for ($j = 0; $j < $days; $j++) {
			$tmp = $line->find('td',$j);
			if (trim($tmp->plaintext) <> '.' && !empty(trim($tmp->plaintext)))
				array_push($VTP["vertretungen"]["tag".($j+1)]["daten"],
					str_replace('&nbsp','',$tmp->plaintext));
		}
	}
	
	//
	for ($j = 0; $j < $days; $j++) {
			$tmp = array_pop($VTP["vertretungen"]["tag".($j+1)]["daten"]);
			if($tmp == "fehlende klassen:") {
				$VTP["vertretungen"]["tag".($j+1)]["fehlende_klassen"] = "Keine";
				continue;
			} else {
				$VTP["vertretungen"]["tag".($j+1)]["fehlende_klassen"] = $tmp;
				array_pop($VTP["vertretungen"]["tag".($j+1)]["daten"]);
			}
	}
	
	//letzte aktualisierung
	$tmp = $html->plaintext;
	$matches = Array();
	if (preg_match("/Letzte\s.+M/", $tmp, $matches)){
		$VTP["letzte_aktualisierung"] = mb_substr($matches[0],16);
	} else {
		$VTP["letzte_aktualisierung"] = "unbekannt";
	}
	
	//Inhalt des Infofensters auslesen
	$html = str_get_html($info_str);
	$memotext = $html->find('html',0)->innertext;
	$memotext = str_replace("\r\n", "", $memotext);
	$memotext = str_replace(chr(34), chr(39), $memotext);
	$memotext = trim($memotext);
	
	$VTP["info"] = strip_tags($memotext);
	
	return $VTP;
}

?>
