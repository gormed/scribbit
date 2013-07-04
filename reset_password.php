<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	$email="".$_POST["email"];

	// XSS protection as we might print this value
	//$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); 

	$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE `email` = '%s' LIMIT 0, 1", $email);
	$res = $mysqli->query($sql);
	$user = $res->fetch_array();

	if ($res->num_rows > 0) {
		// create salt for link
		$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$currentTime = time();
		$freetoken = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		// Create salted password (Careful not to over season)
		$token = hash('sha512', $freetoken.$salt);
		$sql = sprintf("SELECT `id`, `time` FROM `resetpassword` WHERE `userid` = %d LIMIT 0, 1", $user[0]);
		$res = $mysqli->query($sql);

		if ($res->num_rows > 0) {
			$sql = sprintf("UPDATE `resetpassword` SET `token`='%s', `salt`='%s' WHERE `userid` = %d", $token, $salt, $user[0]);
		} else {
			$sql = sprintf("INSERT INTO `resetpassword`(`userid`, `token`, `salt`) VALUES (%d, '%s','%s')", $user[0], $token, $salt);	
		}
		$res = $mysqli->query($sql);
		sendResetmail($user[0], $email, $user[1], $freetoken);
		echo '<div class="success">Successful!</div>';
	} else { echo '<div class="error">Email not registered!</div>'; }

	function sendResetmail($userid, $email, $username, $token)
	{
		$to = $email;
		$subject = "Scribb'it Password Reset";

		$message = '
		<html>
		<head>
		  <title>Scribb\'it Password Reset</title>
		</head>
		<body>
			<p>'.'Hello '.$username.'!'.'</p>
			<p>You requested to change your password, if not please change your password immediately!</p>
			<p>Otherwise, click this link to reset your password: <a href="http://gormed.no-ip.biz'.path.'/reset/'.$token.'-'.$userid.'">Link</a></p>
			<br>
			Have a nice day,
			<br>Your scribb\'it-Team!
		</body>
		</html>
		';

		$from = "Scribbit NO REPLY <noreply@scribbit.com>";
		$header = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= "From:" . $from . "\r\n";

		$res = mail($to,$subject,$message,$header);
		//echo "Mail Sent to -".$email.'-'.PHP_EOL.$res.PHP_EOL;
	}
?>
