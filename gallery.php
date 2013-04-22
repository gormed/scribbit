<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/header.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/gallery.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Galery</title>
	</head>

	<body>
			<div id="site">
			<div id="header">
					<div id="logo">
						<?php
						echo '<a href="'.path.'/"><</a>';
						?>
					</div>
					<div class="searchbar" >
						<ul>
							<li><input class="searchtext" type="text" name="searchtext"/></li>
							<br>
							<br>
							<li><input name="filter" type="checkbox" value="favorits"/>Favorits&nbsp;</li>
							<li><input name="filter" type="checkbox" value="friends">Friends&nbsp;</li>		
							<li><input name="filter" type="checkbox" value="my"/>Own&nbsp;</li>	
							<select name="timefilter">
								<option value="all">all Time</option> 
								<option value="h24">last 24 h</option> 
								<option value="d7">last 7 days</option>
							</select>
						</ul>
					</div>
					<div id="navigation">
							<ul class="topnav">
								<li>
									<span><a href="#">Profile</a></span>
									<ul class="subnav">
										<li><?php echo '<a href="'.path.'/profile">Go to Profile</a>' ?></li>
										<li><a href="#">Freunde</a></li>
										<li><?php echo '<a href="'.path.'/logout">Logout</a>' ?></li>
									</ul>
								</li>
								<li><?php echo '<span><a href="'.path.'/gallery">Gallery</a></span>' ?></li>
								<li><?php echo '<span><a href="'.path.'/wall">Wall</a></span>' ?></li>
							</ul>
					</div>
				</div>

				<div id="content">
					<br>
					
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
						<div class="item">bild</div>
					
					
					<br>

				</div>
			</div>
	</body>
</html>