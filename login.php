<?php 
$message = "";
if (!isset($register)) {
	$email = "your@email.com";
}
include("process_login.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<title>Login Please</title>
<script type="text/javascript" src="ressources/js/sha512.js"></script>
<script type="text/javascript" src="ressources/js/forms.js"></script>
<style type="text/css">

	body {
		color: black; background-color: #E0E0E0;
		font-size: 100.00%;
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
		padding-top: 2em;
		padding-bottom: 1em;
		padding-left: 1em;
		font-weight: bold;
	}

</style>

</head>
<body>
	<div id="site">

		<div id="logo" align="center">
			<div style="font-size:medium">Login<br>to<br></div>Scribb'it!
		</div>

		<div align="center">
			<form action="login" method="post" name="login_form">
				<?php

				echo '<input type="text" value="', $email, '" name="email" style="margin:10px; border-radius: 18px;"/><br>';

				?>
				<input type="password" name="password" id="password" style="margin:10px; border-radius: 18px;"/><br><br>
				<input type="button" value="Login" onclick="formhash(this.form);" />
			</form>
			<br>
			<?php 
			echo $message;
			?>
			<br>
			<div style="font-family: 'Tahoma', sans-serif;">Don't have an account? <br>
			<?php
			echo '<a href="'.path.'/register">Create one here</a>!';
			?>
			</div>
			<br>
		</div>
	</div>
</body>
</html>
