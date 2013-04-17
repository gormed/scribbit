<?php
require_once 'db_login.php';
// The hashed password from the form
$password = $_POST['p']; 
// Create a random salt
$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Create salted password (Careful not to over season)
$password = hash('sha512', $password.$randomSalt);
// get mail and username from form
$email = $_POST['email'];
$username = $_POST['username'];
// chekc if username is already taken
$query = sprintf("SELECT * FROM `members` WHERE `username` LIKE '%s' LIMIT 0, 1", $username);
$usernames = $mysqli->query($query);
// chekc if mail is already taken
$query = sprintf("SELECT * FROM `members` WHERE `email` LIKE '%s' LIMIT 0, 1", $email);
$emails = $mysqli->query($query);

//echo $email, " ", $username, " ", $result->num_rows;
if (isset($username) && isset($password) && isset($email) && isset($randomSalt)) {
	if($usernames->num_rows > 0) {
		$nameTaken = '<td style="color: #f66">Username already taken!</td>';
		// header(sprintf('location:./register.php?taken=1&email=%s', $email));
	} else if($emails->num_rows > 0) {
		$mailTaken = '<td style="color: #f66">Email already registered!</td>';
		// header(sprintf('location:./register.php?taken=1&email=%s', $email));
	} else {
		// Add your insert to database script here. 
		// Make sure you use prepared statements!
		if ($insertStmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
			$insertStmt->bind_param('ssss', $username, $email, $password, $randomSalt); 
			// Execute the prepared query.
			$insertStmt->execute();
			$register = 1;
			include 'login.php';
			exit();
		} 
	}
} else {
	$error = '<center style="color: #f66">There was an error in the registration process. <br>We are sorry for any inconvienience!</center>';
}

?>
