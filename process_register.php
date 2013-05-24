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
// XSS protection as we might print this value
$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); 

// chekc if username is already taken
$query = sprintf("SELECT * FROM `members` WHERE `username` LIKE '%s' LIMIT 0, 1", $username);
$usernames = $mysqli->query($query);
// chekc if mail is already taken
$query = sprintf("SELECT * FROM `members` WHERE `email` LIKE '%s' LIMIT 0, 1", $email);
$emails = $mysqli->query($query);

//echo $email, " ", $username, " ", $result->num_rows;
if (isset($username) && isset($password) && isset($email) && isset($randomSalt)) {
	if($usernames->num_rows > 0) {
		$nameTaken = '<div style="color: #f66">Username already taken!</div>';
	} else if($emails->num_rows > 0) {
		$mailTaken = '<div style="color: #f66">Email already registered!</div>';
	} else {
		// Add your insert to database script here. 
		// Make sure you use prepared statements!
		if ($insertStmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
			$insertStmt->bind_param('ssss', $username, $email, $password, $randomSalt); 

			// Execute the prepared query.
			$insertStmt->execute();
			$register = 1;

			// $sql = sprintf("SELECT `id` FROM `members` WHERE `username` = '%s' LIMIT 0, 30 ", $username);
			// $user_id = $mysqli->query($sql)->fetch_array()[0];
			$file = $_SERVER['DOCUMENT_ROOT'].root.'/users/'.$username.'/';
			if (!file_exists($file)) {
				mkdir($file) or die("Cannot create new user!");
			}

			include 'login.php';
			exit();
		} 
	}
} else {
	$error = '<center style="color: #f66">There was an error in the registration process. <br>We are sorry for any inconvienience!</center>';
}

?>
