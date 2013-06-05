<?php 
include 'header.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribb'it Lost Password</title>
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
				font-weight: bold;
			}

			div#heading {
				margin: 0 auto;
				width: 50%;
			}

			.options {
				width: 200px;
				height: 20px;
				border-radius: 5px;
				margin-bottom: 5px;
			}

			.discription {
				vertical-align: middle;
				font-size: small;
				font-weight: bold;
				margin-bottom: 2px;
			}
		</style>
	</head>
	<body>
		<div id="site">
			<div align="center">
				<br>
				<div align="center" id="logo">Scribb'it</div>
				<div align="center" id="heading">
					Reset your accounts password:
				</div>
				<br>
				<form action="reset" method="post" name="resetpw_form">
					<div class="discription">Email<br>
						<input class="options" type="text" id="email">
						<div id="resetpasswordresult"></div>
					</div>
					<input type="button" id="requestnewpw" value="Request new password!">
				</form>
				<br>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	<?php echo "var path = '".path."';".PHP_EOL;  ?>
	$(document).ready(function() {
		$('#requestnewpw').click(function() {
			requestPWToken($('#email').val());
		});
	});

	function requestPWToken(email) {
		var xmlhttp;

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
				$('#resetpasswordresult').html(xmlhttp.responseText);
			}
		}
		xmlhttp.open("POST",path+"/reset_password.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("email=" + email);
	}
</script>