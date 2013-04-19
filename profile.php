<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/dashboard.css">
		<script type="text/javascript" src="ressources/js/gallery.js"></script>
		<title>Scribbit - Profil</title>
	</head>

	<body>
			<div id="site">
			<div id="header">
				<div id="navigation">
					<?php
					echo '<span><a href="'.path.'/wall">WALL</a></span>
							<span><a href="'.path.'/gallery">GALLERY</a></span>
							<span><a href="'.path.'/profile">PROFILE</a></span>
							<span><a href="'.path.'/logout">LOGOUT</a></span>';
					?>
				</div>

				<div id="logo">
					<?php
					echo '<a href="'.path.'/"><</a>';
					?>
				</div>

			</div>

				<div id="content">
					
					<div>
						<br>
						<br>
						Meine eigenen Scribbles kronologisch sortiert
						<br>
						<br>
					</div>

					<div>
						<br>
						<br>
						Scribbles meiner Freunde kronologisch sortiert
						<br>
						<br>
					</div>

					<div>
						<br>
						<br>
						Scribbles meiner Favoriten kronologisch sortiert
						<br>
						<br>
					</div>
				</div>
			</div>
	</body>
</html>