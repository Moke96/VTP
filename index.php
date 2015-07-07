<?php
	/* PHP vertretungsplan generator
	 * AKA "If you can't fix the problem engineer around it"
	 */
	/*some functions reused in the rest of the program*/
	function daten_to_list($vtp, $day) {
		$a = $vtp["vertretungen"]["tag".$day]["daten"];
		foreach ($a as $d) {
			echo "<li>$d</li>\n";
		}
	}

	function show_fehlend($vtp, $day) {
		echo "<li>Fehlende Lehrer:</li>";
		echo "<li>".$vtp["vertretungen"]["tag".$day]["fehlende_lehrer"]."</li>";
		echo "<li>Fehlende Klassen:</li>";
		echo "<li>".$vtp["vertretungen"]["tag".$day]["fehlende_klassen"]."</li>";
	}

	/*pageinit*/
	$json = file_get_contents("http://localhost/json");
	if (!$json)
		die("couldn't fetch data");
	$vtp = json_decode($json, true);
	if (!$vtp)
		die("couldn't decode data");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Vertretungsplan</title>
		<link rel="icon" type="image/png" href="willms2.png">
		<meta charset=utf-8 />
		<meta name="theme-color" content="#000000">
		<link rel=stylesheet href=index.css type=text/css />
		<link href='http://fonts.googleapis.com/css?family=Roboto|Indie+Flower' rel='stylesheet' type='text/css'>
		<!--jquerymobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="j.mobile/jquery.mobile-1.4.5.min.css">
		<script src="j.mobile/jquery-1.11.2.min.js"></script>
		<script src="j.mobile/jquery.mobile-1.4.5.min.js"></script>
	</head>
	<body>
		<div id=main >
			<div data-role="page" id="pageone" data-theme="b">
				<div data-role="header" data-position="fixed">
					<h1>Vertretungsplan</h1> 	
					<a href="#search" class="ui-btn ui-shadow ui-icon-search ui-btn-icon-left ui-corner-all">Suchen</a>
					<a href="http://www.willms-gymnasium.de/" class="ui-btn ui-btn-icon-left ui-corner-all">Willms</a>
				</div>
				<div data-role="panel" id="search" data-theme="b">
				<h2>Suche:</h2>
				  <paper-ripple fit> </paper-ripple>
				<form>
				<input id="suche" data-type="search" data-theme="a">
				</form>
				</div>
		<div data-role="main" class="ui-content">
			<div data-role="collapsible" data-collapsed="false" class=vtp id=day1 >
				<h2>Montag</h2>
			 	<ul data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="a">
					<?php daten_to_list($vtp, 1); ?>
				</ul>
				<ul data-role="listview" data-inset="true" data-theme="a">
					<?php show_fehlend($vtp, 1); ?>
				</ul>
			</div>
			<div data-role="collapsible" data-collapsed="true" class=vtp id=day2 >
				<h2>Dienstag</h2>
				<ul data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="a">
					<?php daten_to_list($vtp, 2); ?>
				</ul>
				<ul data-role=listview data-inset=true data-theme=a>
					<?php show_fehlend($vtp, 2); ?>
				</ul>
			</div>
			<div data-role="collapsible" data-collapsed="true" class=vtp id=day3 >
				<h2>Mittwoch</h2>
				<ul data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="a">
					<?php daten_to_list($vtp, 3); ?>
				</ul>
				<ul data-role=listview data-inset=true data-theme=a>
					<?php show_fehlend($vtp, 3); ?>
				</ul>
			</div>
		</div>
		<div data-role="footer" data-position="fixed">
			<div data-role="collapsible" data-collapsed="true" id="hinweis">
				<h2>Zusätzliche Informationen</h2>
				<ul data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="b">
					<li><?php echo $vtp["info"]; ?></li>
				</ul>
				<h3>Letze Aktualisierung</h3>
				<ul data-role="listview" data-inset="true" data-theme="b">
					<li><?php echo $vtp["letzte_aktualisierung"]; ?></li>
				</ul>
			</div>
		</div>
	</body>
</html>
