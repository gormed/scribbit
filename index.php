<?php 
include 'header.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<<<<<<< HEAD
<head>
	<?php include 'extern_styles.php'; ?>
	<?php include 'extern_meta.php'; ?>
	<title>scribbit, welcome stranger!</title>
</style>	
</head>
=======
	<head>
		<meta name="keywords" content="scribbit, share, collaborate, paint, scribble">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="cache-control" content="private">
		<meta name="robots" content="all">
		<meta name="description" content="Scribb'it is a collaborative tool 
		for creating drawings and sketches in creative collaboration 
		with friends, acquaintances and even strangers.">
		<title>scribbit</title>
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
>>>>>>> 67c0c0d... added track functionality for page views and current site;
<body>
	<div id="site">			
		<div id="logo">Scribb'it</div>

		<div id="navigation">
			<?php
			if (!$loggedIn) {
				echo '	<div><a href="'.path.'/login">Login</a></div>
				<div><a href="'.path.'/register">Register</a></div>';
			} else {
				echo '	<div><a href="'.path.'/wall">Explore</a></div>
				<div><a href="'.path.'/logout">Logout</a></div>';
			}
			?>
		</div>
		<div id="content">
			<br>
			<h1>Creative, communicative drawing.</h1>
			<p>Scribb'it is a <b>collaborative tool</b> to create drawings and 
				sketches in <b>creative</b> collaboration with <b>friends</b>, 
				<b>acquaintances</b> and <b>even strangers</b>.
			</p>
			<div class="assist">
				<b>Objective:</b> Creative Entertainment<br>
				<b>Primary action:</b> Collaborative Drawing<br>
				<b>Social Object:</b>  partial image / Scribble<br>
				<b>Core functions:</b>  subimages draw, evaluate, explore<br>
				<b>Social functions:</b>  Drawing with friends and/or strangers, creative exchange!<br>
				<b>USP:</b>  joint creation of new great) he works of art<br>
			</div>
			<br>
			<div class="assist">
				<b>Why use the system?</b><br>
				common sketching = Fun finding Ideas and present
			</div>
			<br>
		</div>
	</div>
</body>
</html>