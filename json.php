<?php
// Vetretungsplandaten in JSON String umwandeln
//Jochen Hillenstedt
//10.12.2014
//JSON:
//{"vertretungen":[{"datum":<Datum>,
//                 "daten":[<eintrag1>,
//                          <eintrag2> ...]}
//                ]}

// Hilfsmodul "simple html dom" einbinden (Auswerten des DOM)
include('./inc/simple_html_dom.php');
//Spalten der html Datei einzeln speichern
$spalte1 = '{"datum":';
$spalte2 = '{"datum":';
$spalte3 = '{"datum":';
$URL = ''; //Adresse des Vertretungsplans
$zaehler = 0; //Ab welcher Zeile soll der Quelltext verarbeitet werden

//Username und Password 
$username = 'schueler@willms';
$password = 'SpiegleinGmbH';
$userpw = $username.':'.$password;

if ($username == 'lehrer@willms') {
	$URL = 'http://willms-gymnasium.selfhost.bz:88/l/lvplan.html';
	$zaehler = 2;
	} else {
	$URL = 'http://willms-gymnasium.selfhost.bz:88/svplan.html';
	$zaehler = 1;
}	

$i = 0;
$Info = '';

// Daten einlesen mit cURL
// direktes Einlesen ist auf diesem Server nicht möglich
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $URL);  
curl_setopt($curl, CURLOPT_USERPWD, $userpw); //Daten aus .htaccess
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  

$str = curl_exec($curl);  
curl_close($curl);  

//Daten auch curl übernehmen
$html= str_get_html($str); 


//Tabelle durcharbeiten mit simple html dom und in Listen umwandeln
foreach($html->find('tr') as $zeile) {
	if ($i == $zaehler + 2) {
		$spalte1 .= '"daten":[';
		$spalte2 .= '"daten":[';
		$spalte3 .= '"daten":[';
	}
	$temp = $zeile->find('td',0);
	if ($i > $zaehler && trim($temp->plaintext) <> '.') $spalte1 .= '"'.str_replace('&nbsp','',$temp->plaintext).'",';
	$temp = $zeile->find('td',1);
	if ($i > $zaehler && trim($temp->plaintext) <> '.') $spalte2 .= '"'.str_replace('&nbsp','',$temp->plaintext).'",';
	$temp = $zeile->find('td',2);
	if ($i > $zaehler && trim($temp->plaintext) <> '.') $spalte3 .= '"'.str_replace('&nbsp','',$temp->plaintext).'",';
	$i ++;
}

//Liste der Spalten abschließen
//Komma nach dem letzten Eintrag entfernen
$spalte1 = substr($spalte1,0,-1).']},';
$spalte2 = substr($spalte2,0,-1).']},';
$spalte3 = substr($spalte3,0,-1).']},';

//Info-Fenster
// Daten einlesen mit cURL
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, 'http://willms-gymnasium.selfhost.bz:88/info.html');  
curl_setopt($curl, CURLOPT_USERPWD, $userpw);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  

$str = curl_exec($curl);  
curl_close($curl);  

//Inhalt des Infofensters auslesen
$html = str_get_html($str); 
$temp = $html->find('html',0);
$memotext = $temp->innertext;
$memotext = str_replace("\r\n", "", $memotext);
$memotext = str_replace(chr(34), chr(39), $memotext);
$memotext = trim($memotext);
$info = '{"Info":"'.strip_tags($memotext).'"}';

//CORS header setzen
header("Access-Control-Allow-Origin: *");

//Daten-Ausgabe
echo '{"vertretungen":['.$spalte1.$spalte2.$spalte3.$info."]}";
//ENDE
?>
