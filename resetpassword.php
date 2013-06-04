<?php
	require_once 'path.php';
	if (!isset($resetpw)) {
		exit();
	}
	$error = '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Register Page</title>
		<script type="text/javascript" src="../ressources/js/sha512.js"></script>
		<script type="text/javascript" src="../ressources/js/forms.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>

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
			.options {
				width: 200px;
				height: 20px;
				border-radius: 5px;
				margin-bottom: 5px;
			}
		</style>
	</head>

	<body>
		<div id="site">
			<div align="center" id="heading">
				<div id="logo">Scribb'it</div> 
				Reset your accounts password: 
				<br><br>
				<?php
					echo $error;
				?>
				<form action="reset" method="post" name="resetpw_form">
					<div class="discription">New password<br>
						<input class="options" type="password" id="pass">
					</div>
					<div class="discription">Confirm password<br>
						<input class="options" type="password" id="passconfirm">
						<div id="changepwresult"></div>
					</div>
					<input class="update" type="button" value="Update Password" id="updatepassword">
					<br>
					<br>
					<div style="font-size:small">Password must contain at least 8 characters and <br>one uppercase and lowercase letter <br>and at least one number!
					</div>
				</form>
				<br>
			</div>
		</div>
	</body>

</html>

<script type="text/javascript">
	<?php 
		echo "var path = '".path."';".PHP_EOL; 
		echo "var root = '".root."';".PHP_EOL;
		echo "var userid = '".$userid."';".PHP_EOL;
	?>
	// jQuery
	$(document).ready(function() {

		// check password
		$('#passconfirm').blur(function() {
			if (passwordCkeck($('#pass').val(), $('#passconfirm').val()) == 0) {
				$('#passconfirm').css('background-color','#56FF2D');
				$('#pass').css('background-color','#56FF2D');
			} else {
				$('#passconfirm').css('background-color','#FF2B2B');
				$('#pass').css('background-color','#FF2B2B');
			}
		});

		// update pw button
		$('#updatepassword').click(function() {
			updatePassword($('#pass').val(), $('#passconfirm').val());
		});
	});

	function updatePassword(newpw, newpwconf) {
		if (passwordCkeck(newpw, newpwconf) == 0) {
			var xmlhttp;
			
			newpw = hex_sha512(newpw);
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					console.log(xmlhttp.responseText);
					$('#changepwresult').html(xmlhttp.responseText);
					$('#passconfirm').css('background-color','#56FF2D');
					$('#pass').css('background-color','#56FF2D');
					window.location.replace("http://gormed.no-ip.biz"+path+"/login");
				}
			}
			xmlhttp.open("POST",path+"/reset_pw.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("newpw=" + newpw + "&userid=" + userid);
		} else {
			$('#passconfirm').css('background-color','#FF2B2B');
			$('#pass').css('background-color','#FF2B2B');
		}
	}
</script>