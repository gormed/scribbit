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
	<?php include 'extern_styles.php'; ?>
	<?php include 'extern_meta.php'; ?>
	<title>scribbit - Register Page</title>
	<?php include 'extern_scripts.php'; ?>

</head>

<body>
	<div id="site">
		<div align="center">
			<div id="logo">
				Register for<br>Scribb'it
			</div>
			<div class="assist">
				<?php
				echo "Already got an account? ".'<a href="'.path.'/login">Log In</a>!';
				echo '<br>Or lost password? <a href="'.path.'/lost">Reset it</a>!';
				?>
			</div>
			<?php
			echo $error;
			?>
			<br>
			<form action="register" method="post" name="register_form">
				<table>
					<tr>
						<td></td>
						<td><div class="descriptive">Artistname</div><input type="text" name="artistname" id="artistname" size="22" onblur="checkUsername(this.form.artistname)"/></td>
						<td id="validName" >
							<?php if (isset($nameTaken)) { echo $nameTaken; } ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><div class="descriptive">Email</div>
							<?php
							echo '<input type="text" value="', $email, '" name="email" id="email" size="22" onblur="checkEmail(this.form)"/><br>';
							?>
						</td>
						<td id="validEmail" size="20em">
							<?php if (isset($mailTaken)) { echo $mailTaken; } ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><div class="descriptive">Password</div><input type="password" name="password" id="password" size="22"/></td>
						<td id="validpw"></td>
					</tr>
					<tr>
						<td></td>
						<td><div class="descriptive">Confirm Password</div><input type="password" name="confirm_password" id="confirm_password" size="22" onblur="checkPasswordStrength(this.form)"/></td>
					</tr>
				</table>
				<input type="button" value="Register" onclick="registerformhash(this.form);" />
				<br>
				<div class="terms">
					Password must contain at least 8 characters and 
					<br>one uppercase and lowercase letter 
					<br>and at least one number!
				</div>
			</form>
			<br>
		</div>
	</div>
</body>

</html>