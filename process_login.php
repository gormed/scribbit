<?php
require_once 'db_login.php';
require_once 'functions.php';
require_once 'path.php';

if(isset($_POST['username'], $_POST['p'])) {
	$email = $_POST['username'];
	$password = $_POST['p']; // The hashed password.
	if (isset($register) && $register) {
		$message='<div class="success">Registration successful, please log in!</div>';
	} else {
		if(login($email, $password, $mysqli) == true) {
			// Login success
			//echo 'Success: You have been logged in!';
			header('location:'.path.'/wall');

			//include("dashboard.php");
			//exit(1);
		} else {
			// Login failed
			//header(sprintf("location:./login.php?error=1&email=%s", $email));
			$message='<span class="error">Error logging in!</span>';
		}
	}
} else { 
	// The correct POST variables were not sent to this page.
	// echo 'Invalid Request';
}
?>