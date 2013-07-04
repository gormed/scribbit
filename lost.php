<?php 
include 'header.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<?php include 'extern_styles.php'; ?>
		<?php include 'extern_meta.php'; ?>
		<title>scribbit - Lost Password</title>
		<?php include 'extern_scripts.php'; ?>
	</head>
	<body>
		<div id="site">
			<div align="center">
				<div align="center" id="logo">Rescue your<br>Scribb'it</div>
				<div align="center">
					Request to reset your accounts password!
				</div>
				<br>
				<form action="reset" method="post" name="resetpw_form">
					<div class="descriptive">Email<br>
						<input class="options" type="text" id="email" placeholder="your@email.com">
						<div id="resetpasswordresult"></div>
					</div>
					<div class="assist">
						You will get a mail with a <br>
						link to a new page where you can<br>
						reset your password. This link is only valid unitl<br>
						30 minutes after the submit!
					</div>
					<br>
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