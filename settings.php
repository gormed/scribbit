<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/headerSearch.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/settings.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<script type="text/javascript" src="ressources/js/forms.js"></script>
		<script type="text/javascript" src="ressources/js/sha512.js"></script>
		<title>Scribbit - Profile</title>
	</head>
	<body>
		<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>
			</div>
			<div id="content">
				<div id="profile">
					<div class="heading">Public Profile</div>
					<div class="discription">Name<br>
						<input class="options" type="text" id="name">
					</div>
					<div class="discription">Email<br>
						<input class="options" type="text" id="email">
					</div>
					<div class="discription">Location<br>
						<input class="options" type="text" id="location">
					</div>
					<div class="discription">URL<br>
						<input class="options" type="text" id="url">
						<div id="changeprofileresult"></div>
					</div>
					<input class="update" type="button" value="Update Profile" id="updateprofile">
				</div>
				<div class="horizontal"></div>
				<div id="account">
					<br>
					<div class="heading">Account</div>
					<div id="changepassword">
						<div class="subheading">Change password</div>
						<div class="discription">Old password<br>
							<input class="options" type="password" id="oldpass">
							<div id="changepwresult"></div>
						</div><br>
						<div class="discription">New password<br>
							<input class="options" type="password" id="pass">
						</div>
						<div class="discription">Confirm password<br>
							<input class="options" type="password" id="passconfirm">
						</div>
						<input class="update" type="button" value="Update Password" id="updatepassword">

					</div>
					<div class="horizontal"></div>
					<div id="changeusername">
						<div class="subheading">Change username</div>
						<div class="discription">New username<br>
							<input class="options" type="text" id="newusername">
							<div id="changeusernameresult"></div>
						</div>
						<input class="update" type="button" value="Update Username" id="updatename">
					</div>
					<div class="horizontal"></div>
					<div id="deleteaccount">
						<div class="subheading">Delete Account</div><br>
						<div class="discription">Are you really sure? :(<br>
							<input class="options" type="text" name="sure">
							<br>
							<input id="delete" type="button" name="yes" value="Confirm Delete">
						</div><br>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript">
	<?php 
		echo "var userid = ".$_SESSION['user_id'].";".PHP_EOL; 
		echo "var path = '".path."';".PHP_EOL; 
		echo "var root = '".root."';".PHP_EOL; 
		
		$userid = $_SESSION['user_id'];

		$sql = sprintf("SELECT `name`, `email`, `location`, `url` FROM `public_profile` WHERE `userid` = %d LIMIT 0, 1", $userid);
		$res = $mysqli->query($sql)->fetch_array();


	?>
	$(document).ready(function() {
		// fill public profile inputs
		<?php 
			echo '$("#name").val("'.$res[0].'");'.PHP_EOL;
			echo '$("#email").val("'.$res[1].'");'.PHP_EOL;
			echo '$("#location").val("'.$res[2].'");'.PHP_EOL;
			echo '$("#url").val("'.$res[3].'");'.PHP_EOL;
		?>
		// check pw on confirm blur
		$('#passconfirm').blur(function() {
			if (passwordCkeck($('#pass').val(), $('#passconfirm').val()) == 0) {
				$('#passconfirm').css('background-color','#56FF2D');
				$('#pass').css('background-color','#56FF2D');
			} else {
				$('#passconfirm').css('background-color','#FF2B2B');
				$('#pass').css('background-color','#FF2B2B');
			}
		});
		// update username blur
		$('#newusername').blur(function() {
			if (checkValidUsername($('#newusername').val())) {
				$('#newusername').css('background-color','#56FF2D');
			} else {
				$('#newusername').css('background-color','#FF2B2B');
				$('#changeusernameresult').html('<div style="color: #f66">Only A-Z, a-z, 0-9 and -_ are allowed</div>');
			}
		});
		// update profile button
		$('#updateprofile').click(function() {
			updateProfile($('#name').val(),$('#email').val(),$('#location').val(),$('#url').val());
		});
		// update pw button
		$('#updatepassword').click(function() {
			updatePassword($('#oldpass').val(), $('#pass').val(), $('#passconfirm').val());
		});
		// update username button
		$('#updatename').click(function() {
			if (checkValidUsername($('#newusername').val())) {
				updateUsername();
				$('#newusername').css('background-color','#56FF2D');
			} else {
				$('#newusername').css('background-color','#FF2B2B');
				$('#changeusernameresult').html('<div style="color: #f66">Only A-Z, a-z, 0-9<br>and -_ are allowed</div>');
			}
		});
		// delete acc button
		$('#delete').click(function() {

		});
	});

	function updateProfile(name, email, location, url) {
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
				$('#changeprofileresult').html(xmlhttp.responseText);
			}
		}
		xmlhttp.open("POST",path+"/update_profile.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("name=" + name + "&email=" + email + "&location=" + location + "&url=" + url);
	}

	function updatePassword(oldpw, newpw, newpwconf) {
		if (passwordCkeck(newpw, newpwconf) == 0) {
			var xmlhttp;
			
			oldpw = hex_sha512(oldpw);
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
				}
			}
			xmlhttp.open("POST",path+"/update_pw.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("oldpw=" + oldpw + "&newpw=" + newpw);
		} else {
			$('#passconfirm').css('background-color','#FF2B2B');
			$('#pass').css('background-color','#FF2B2B');
		}
	}

	function updateUsername (newname) {
		if (newname.length > 2) {
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
					//location.reload();
					$('#changeusernameresult').html(xmlhttp.responseText);
					$('#newusername').css('background-color','#56FF2D');
					//document.getElementById("upload").innerHTML="Sent!" + "\n" + xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST",path+"/update_name.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("newname=" + newname);
		} else {
			$('#newusername').css('background-color','#FF2B2B');
			$('#changeusernameresult').html('<div style="color: #FF2B2B">Please give us some more characters...</div>');
		}
	}

</script>