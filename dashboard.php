<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

		<link rel="stylesheet" type="text/css" href="dashboard.css">
		<title>Dashboard</title>
	</head>

	<body>
		<div id="back">
			<?php
			echo '<a href="'.path.'/"><</a>';
			?>
		</div>

		<div id="logo">
			Dashboard
		</div>

		<div id="navigation">
			<?php
			echo '<li><a href="'.path.'/wall">WALL</a></li>';
			echo '<li><a href="'.path.'/gallery">GALLERY</a></li>';
			echo '<li><a href="'.path.'/profile">PROFILE</a></li>';
			echo '<li><a href="'.path.'/logout">LOGOUT</a></li>';
			?>
		</div>

		<div id="content">
			<br>
			<table border="0">
				<tr>
					<td>1</td>
					<td>2</td>
					<td>3</td>
				</tr>
			</table>
			<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
			</p>
			<br>
		</div>
	</body>
</html>