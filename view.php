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
					<div>
							<ul class="topnav">
								<li>
									<span><a href="#">Profile</a></span>
									<ul class="subnav">
										<li><?php echo '<a href="'.path.'/profile">Go to Profile</a>' ?></li>
										<li><a href="#">Freunde</a></li>
										<li><a href="#">Favoriten</a></li>
										<li><?php echo '<a href="'.path.'/logout">Logout</a>' ?></li>
									</ul>
								</li>
								<li><?php echo '<span><a href="'.path.'/gallery">Gallery</a></span>' ?></li>
								<li><?php echo '<span><a href="'.path.'/wall">Wall</a></span>' ?></li>
							</ul>
						</div>	
					
				</div>

					<div class="table" id="content">
						<div class="row">
							<div class="cell"></div>
							<div class="cell" id="top"><?php echo '<a href="'.path.'/view">upper picture <br>(show this)</a>' ?></div>
							<div class="cell"></div>
						</div>
						<div class="row" id="wrapper">

							<div class="cell" id="leftcolumn">
								<?php echo '<a href="'.path.'/view">left picture <br>(show this)</a>' ?>
							
							</div>
							<div class="cell" id="picture">
								
							</div>

							<div class="cell" id="rightcolumn">
														<?php echo '<a href="'.path.'/scribble">right space <br>(paint here)</a>' ?>
								
							</div>

						</div>
						<div class="row">
							<div class="cell"></div>
							<div class="cell" id="bottom"><?php echo '<a href="'.path.'/scribble">bottom space <br>(paint here)</a>' ?></div>
							<div class="cell"></div>
						</div>
					</div>


				</div>