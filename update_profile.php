<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	$userid = $_SESSION['user_id'];
	$name="".$_POST["name"];
	$email="".$_POST["email"];
	$location="".$_POST["location"];
	$url="".$_POST["url"];

	// XSS protection as we might print this value
	//$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); 

	$sql = sprintf("SELECT `id`, `userid` FROM `public_profile` WHERE `userid` = %d LIMIT 0, 1", $_SESSION['user_id']);
	$res = $mysqli->query($sql);
	$pid = $res->fetch_array()[0];
	if ($res->num_rows > 0) {
		$sql = sprintf("UPDATE `secure_login`.`public_profile` SET `name`='%s', `email`='%s', `location`='%s', `url`='%s' WHERE `public_profile`.`id` = %d", $name, $email, $location, $url, $pid);
		
	} else {
		$sql = sprintf("INSERT INTO `public_profile`(`userid`, `name`, `email`, `location`, `url`) VALUES (%d, '%s','%s','%s','%s')", $userid, $name, $email, $location, $url);	
	}
	$res = $mysqli->query($sql);

	echo '<div style="color: #2CAC00">Successful!</div>';
?>
