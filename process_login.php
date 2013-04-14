<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php

if(isset($_POST['email'], $_POST['p'])) {
	$email = $_POST['email'];
	$password = $_POST['p']; // The hashed password.
	if(login($email, $password, $mysqli) == true) {
		// Login success
		//echo 'Success: You have been logged in!';
		header('location:./dashboard/');

		//include("dashboard.php");
		//exit(1);
	} else {
		// Login failed
		//header(sprintf("location:./login.php?error=1&email=%s", $email));
		$errorstring="Error logging in!";
	}
} else { 
	// The correct POST variables were not sent to this page.
	// echo 'Invalid Request';
}
?>