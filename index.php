<?php 
include 'header.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta name="keywords" content="scribbit, share, collaborate, paint, scribble">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="cach-control" content="private">
		<meta name="robots" content="all">
		<meta name="description" content="Scribb'it is a collaborative tool 
		for creating drawings and sketches in creative collaboration 
		with friends, acquaintances and even strangers.">
		<title>Scribb'it</title>
		<style type="text/css">

			/* BODY */

			body {
				color: black; background-color: #E0E0E0;
				font-size: 100.01%;
				font-family: Tahoma,sans-serif;
				margin: 0; padding: 0em;
			}

			div#site {
				background-color: #696969;
				text-align: left;    /* Seiteninhalt wieder links ausrichten */
				margin: 0 auto;      /* standardkonforme horizontale Zentrierung */
				width: 640px;
			}

			div#logo {
				color: black;
				font-family: 'Tahoma', sans-serif;
				font-size: 2em; 
				letter-spacing:0.4em; 
				word-spacing:0.4em;
				margin-bottom: 1em;
				padding-top: 2em;
				padding-bottom: 1em;
				padding-left: 2em;
				font-weight: bold;
			}

			/* NAVIGATION */

			div#navigation {
				font-size: 0.91em;
				float: left; width: 25em;
				margin: 0; padding: 0;
				vertical-align: middle;
				background-color: #888;
			}

			div#navigation li {
				list-style: none;
				margin: 0.5em; 
				margin-right: 3em; 
				padding-left: 8.5em;
			}

			div#navigation a {
				display: block;
				padding: 0.3em;
				font-weight: bold;
				text-align: right;
			}

			div#navigation a:link {
				color: black; background-color: #eee;
			}

			div#navigation a:visited {
				color: #222; background-color: #eee;
			}

			div#navigation a:hover {
				color: black; background-color: white;
			}

			div#navigation a:active {
				color: white; background-color: gray;
			}

			/*CONTENT*/

			div#content {
				margin-left: 22em;
				padding: 0 1em;
				min-width: 16em; 
				background-color: #888;
				/* Mindestbreite (der Ueberschrift) verhindert 
				Anzeigefehler in modernen Browsern */
			}

			div#content h1 {
				font-size: 1.5em;
				margin: 0 0 1em;
			}

			div#content h2 {
				font-size: 1.2em;
				margin: 0 0 1em;
			}

			div#content p {
				font-size: 1em;
				margin: 1em 0;
			}
		</style>	
	</head>
<body>
	<div id="site">			
		<div id="logo">Scribb'it</div>

		<div id ="navigation">
			<?php
			if (!$loggedIn) {
				echo '	<ul><li><a href="'.path.'/login">Login</a></li>
						<li><a href="'.path.'/register">Register</a></li></ul>';
			} else {
				echo '	<ul><li><a href="'.path.'/dashboard">Dashboard</a></li>
						<li><a href="'.path.'/logout">Logout</a></li></ul>';
			}
			?>
		</div>
		<div id="content">
			<br>
			<h2>Kreativ, kommunikativ Zeichnen.</h2>

			<p>Scribb'it ist ein kollaboratives Werkzeug zum Erstellen von Zeichnungen und Skizzen 
				in kreativer Zusammenarbeit mit Freunden, Bekannten und auch Unbekannten.</p>
			<div style="font-size:small">
				<ul>
					<li>Ziel</li>
					<ul>
					<li>Kreative Unterhaltung</li>
					</ul>

					<li>Primäre Aktion</li>
					<ul> 
					<li>Kollaboratives Zeichnen</li>
					</ul>

					<li>Soziales Objekt</li>
					<ul>
					<li>"Teilbild"/ Scribble</li>
					</ul>
				</ul>		
				<ul>
					<li>Kernfunktionen</li>
				<ul>
					<li>"Teilbilder" zeichnen</li>
					<li>Bewerten</li>
					<li>Erkunden</li>
				</ul>
					<li>Soziale Funktionen</li>
				<ul>
					<li>Zeichnen mit Freunden und/ oder Fremden</li>
					<li>kreativer Austausch</li>
				</ul>
				</ul>
			</div>
			<p align="center">
				<b>Alleinstellungsmerkmal</b><br>
				gemeinsames Schaffen neuer 
				gross(artig)er Kunstwerke<br><br>

				<b>Warum sollte man das System benutzen?</b><br>
				gemeinsames Skizzieren = Spass 
				Ideenfinden und Praesentieren
			</p>
		</div>
	</div>
</body>
</html>