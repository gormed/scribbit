<?php 
include 'header.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php include 'extern_styles.php'; ?>
	<?php include 'extern_meta.php'; ?>
	<title>scribbit, welcome stranger!</title>
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
				echo '	<ul><li><a href="'.path.'/wall">Explore</a></li>
				<li><a href="'.path.'/logout">Logout</a></li></ul>';
			}
			?>
		</div>
		<div id="content">
			<br>
			<h1>Creative, communicative drawing.</h1>
			<p>Scribb'it is a <b>collaborative tool</b> to create drawings and 
				sketches in <b>creative</b> collaboration with <b>friends</b>, 
				<b>acquaintances</b> and <b>even strangers</b>.</p>
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