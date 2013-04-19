<?php
require_once 'db_login.php';
require_once 'functions.php';
require_once 'path.php';

echo 'mail '.isset($_POST['email']).'pw '.isset($_POST['p']);

if(isset($_POST['email'], $_POST['p'])) {
	$email = $_POST['email'];
	$password = $_POST['p']; // The hashed password.
	if ($register) {
		$message='<span style="color: #6f6">Registration successful, please log in!</span>';
	} else {
		if(login($email, $password, $mysqli) == true) {
			// Login success
			//echo 'Success: You have been logged in!';
			header('location:'.path.'/dashboard');

			//include("dashboard.php");
			//exit(1);
		} else {
			// Login failed
			//header(sprintf("location:./login.php?error=1&email=%s", $email));
			$message='<span style="color: #f66">Error logging in!</span>';
		}
	}
} else { 
	// The correct POST variables were not sent to this page.
	// echo 'Invalid Request';
}
?>