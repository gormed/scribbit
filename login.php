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
	<?php include 'extern_styles.php'; ?>
	<?php include 'extern_meta.php'; ?>
	<title>scribbit - Login Please</title>
	<?php include 'extern_scripts.php'; ?>
</head>
<body>
	<div id="site">

		<div id="logo">
			Login to<br>Scribb'it!
		</div>
		<div align="center">
			<div class="assist">
				<?php
				echo "Don't have an account? ".'<a href="'.path.'/register">Create one</a>!';
				echo '<br>Or lost password? <a href="'.path.'/lost">Reset it</a>!';
				?>
			</div>
			<br>
			<form action="login" method="post" name="login_form" id="login_form">
				<div class="descriptive">Email</div>
				<?php
				echo '<input type="text" value="', $email, '" name="username" id="username"/><br>';
				?>
				<div class="descriptive">Password</div>
				<input type="password" name="password" id="password"/><br>
				<input type="button" value="Login" onclick="formhash(this.form);" />
			</form>
			<?php 
			echo '<br>'.$message;
			?>			

			<br>
			<div class="terms">
				By using our service you accept<br> 
				that we store cookies on your computer. <br>
				Read why!
			</div>
			<br>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">

$(document).ready(function() {
	
});

function onEnter(event) {
	if (event.keyCode == 13) {
		var email = document.getElementById('username');
		var pw = document.getElementById('password');
		if (email.value != "" && pw.value != "") {
			formhash(document.getElementById('login_form'));
		}
	}
}

document.onkeypress = onEnter;

</script>