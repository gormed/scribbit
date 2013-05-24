<?php 
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	$userid = $_SESSION["user_id"];
	$password = $_POST["oldpw"];
	$newpw = $_POST["newpw"];

	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	// Create salted password (Careful not to over season)
	$newpw = hash('sha512', $newpw.$randomSalt);

	echo updatePassword($mysqli, $userid, $password, $newpw, $randomSalt);

?>