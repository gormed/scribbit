<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">

		<title>Dashboard</title>

		<link rel="stylesheet" type="text/css" href="ressources/css/main.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>

	</head>

	<body>
			<div id="site">
			<div id="header">

				<div id="logo">
					<?php
					echo '<a href="'.path.'/"><</a>';
					?>
				</div>

				<div id="navigation">
					<ul class="topnav">
						<li><?php echo '<span><a href="'.path.'/wall">WALL</a></span>' ?></li>
						<li><?php echo '<span><a href="'.path.'/gallery">Gallery</a></span>' ?></li>
						<li>
							<span><a href="#"><img src="#" width="20px" heigth="20px">&nbsp;Profile</a></span>
							<ul class="subnav">
								<li><?php echo '<a href="'.path.'/profile">Go to Profile</a>' ?></li>
								<li><?php echo '<a href="'.path.'/logout">Logout</a>' ?></li>
							</ul>
						</li>
					</ul>
				</div>
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
	
				</div>
			</div>
	</body>
</html>