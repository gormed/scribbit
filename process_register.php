<?php
include 'db_login.php';
include 'functions.php';
// The hashed password from the form
$password = $_POST['p']; 
// Create a random salt
$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Create salted password (Careful not to over season)
$password = hash('sha512', $password.$random_salt);
// get mail and username from form
$email = $_POST['email'];
$username = $_POST['username'];
// chekc if username is already taken
$query = sprintf("SELECT * FROM `members` WHERE `username` LIKE '%s' LIMIT 0, 30", $username);
$result = $mysqli->query($query);

echo $email, " ", $username, " ", $result->num_rows;

if($result->num_rows > 0) {
	header(sprintf('location:./register.php?taken=1&email=%s', $email));
} else {
	// Add your insert to database script here. 
	// Make sure you use prepared statements!
	if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
		$insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt); 
		// Execute the prepared query.
		$insert_stmt->execute();
		echo "Registration successful!";
		header(sprintf('location:./login.php?register=1&email=%s',$email));
	} else {
		header(sprintf('location:./register.php?error=1&email=%s', $email));
	}
}

?>
