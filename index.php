﻿<?php
	/* PHP vertretungsplan generator
	 * AKA "If you can't fix the problem engineer around it"
	 */

	/*some functions reused in the rest of the program*/
	function daten_to_list($vtp, $day) {
		$a = $vtp["vertretungen"]["tag".$day]["daten"];
		foreach ($a as $d) {
			echo "<li>$d</li>\n";
		}
		if (true) { //disable this if you do not care about all days having the
					//same height
			$longest = 0;
			for ($i = 1; $i <= 3; $i++)
				if ($longest < count($vtp["vertretungen"]["tag".$i]["daten"]))
					$longest = count($vtp["vertretungen"]["tag".$i]["daten"]);
			for ($i = 0; count($vtp["vertretungen"]["tag".$day]["daten"])+$i <
				$longest; $i++ )
				echo "<li> </li>\n";
		}
	}


	function show_fehlend($vtp, $day) {
		echo "<h4>Fehlende Lehrer:</h4>";
		echo "<li>".$vtp["vertretungen"]["tag".$day]["fehlende_lehrer"]."</li>";
		echo "<h5>Fehlende Klassen:</h5>";
		echo "<li>".$vtp["vertretungen"]["tag".$day]["fehlende_klassen"]."</li>";
	}

	/*pageinit*/
	include_once "./vtp.php";
	$vtp = get_vtp();
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
				<form>
				<input id="suche" data-type="search" data-theme="a">
				</form>
				</div>
		<div data-role="main" class="ui-content">
			<div data-role="collapsible" data-collapsed="false" class=vtp id=day1 >
				<h2><?php echo $vtp["vertretungen"]["tag1"]["datum"]; ?></h2>
			 	<ul  data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="a">
					<?php daten_to_list($vtp, 1); ?>
				</ul>
				<ul data-role="listview" data-inset="true" data-theme="a">
					<?php show_fehlend($vtp, 1); ?>
				</ul>
			</div>
			<div data-role="collapsible" data-collapsed="true" class=vtp id=day2 >
				<h2><?php echo $vtp["vertretungen"]["tag2"]["datum"]; ?></h2>
				<ul data-role="listview" data-filter="true" data-input="#suche" data-inset="true" data-theme="a">
					<?php daten_to_list($vtp, 2); ?>
				</ul>
				<ul data-role=listview data-inset=true data-theme=a>
					<?php show_fehlend($vtp, 2); ?>
				</ul>
			</div>
			<div data-role="collapsible" data-collapsed="true" class=vtp id=day3 >
				<h2><?php echo $vtp["vertretungen"]["tag3"]["datum"]; ?></h2>
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
