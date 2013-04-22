<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/header.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/view.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Wall</title>
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

					<div id="content">
						
						<div class="top">
							up
						</div>
						<div class="wrapper">


							<div class="leftcolumn">
								left
							</div>

							<div class="picture">
								<img src="ressources/img/view_template.gif">
							</div>

							<div class="rightcolumn">
								right
							</div>

						</div>
						<div class="bottom">
							bottom
						</div>
					</div>


				</div>