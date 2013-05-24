<?php
	if (isset($_POST['username'])) {
		require_once 'process_register.php';
	}
	$error = '';
	$taken = "<td>What's your name?</td>";
	$email = '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Register Page</title>
		<script type="text/javascript" src="ressources/js/sha512.js"></script>
		<script type="text/javascript" src="ressources/js/forms.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
		<script type="text/javascript">
			// jQuery
			$(document).ready(function() {
				
				// $("#validUsername")
			});

		</script>
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
				padding: 5px;
			}

			div#logo {
				color: black;
				font-family: 'Tahoma', sans-serif;
				font-size: 2em; 
				letter-spacing:0.4em; 
				word-spacing:0.4em;
				padding-bottom: 1em;
				padding-left: 1em;
				font-weight: bold;
			}

			div#heading {
				font-family: 'Tahoma', sans-serif; 
				font-size: 1em;
				padding-top: 2.5em;
			}

			td#text {
				font-style: bold;
				font-family: 'Tahoma', sans-serif; 
				font-size: 1.05em; 
				text-align: right;
			}

			input {
				font-family: 'Tahoma', sans-serif; 
				font-size: 1.1em; 
				font-style: bold;
				text-align: center;
			}
		</style>
	</head>

	<body>
		<div id="site">
			<div align="center" id="heading">
			Register an account for<br>
			<div id="logo">Scribb'it</div><br> 
			<?php
				echo $error;
			?>
				<form action="register" method="post" name="register_form">
					<table>
					<tr>
						<td id="text">Artistname </td>
						<td><input type="text" name="username" id="username" size="16" onblur="checkUsername(this.form)"/></td>
						<td id="validName" >
							<?php if (isset($nameTaken)) { echo $nameTaken; } ?>
						</td>
					</tr>
					<tr>
						<td id="text">Email </td>
						<td>
							<?php
								echo '<input type="text" value="', $email, '" name="email" id="email" size="16" onblur="checkEmail(this.form)"/><br>';
							?>
						</td>
						<td id="validEmail" size="20em">
							<?php if (isset($mailTaken)) { echo $mailTaken; } ?>
						<td>
					</tr>
					<tr>
						<td id="text">Password </td>
						<td><input type="password" name="password" id="password" size="16"/></td>
						<td id="validpw"></td>
					</tr>
					<tr>
						<td id="text">Confirm Password </td>
						<td><input type="password" name="confirm_password" id="confirm_password" size="16" onblur="checkPasswordStrength(this.form)"/></td>
					</tr>
					</table>
					<br>
					<div style="font-size:small">Password must contain at least 8 characters and <br>
						one uppercase and lowercase letter <br>and at least one number!</div>
					<br>
					<input type="button" value="Register" onclick="registerformhash(this.form);" />
					<br>
				</form>
				<br>
			</div>
		</div>
	</body>

</html>