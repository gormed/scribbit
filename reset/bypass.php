<?php

if (!isset($_GET['freetoken'])) {
	exit();
}
$resetpwtag = $_GET['freetoken'];
$split = preg_split('[-]', $resetpwtag);
$userid = $split[1];
$freetoken = $split[0];

// Using prepared Statements means that SQL injection is not possible. 
if ($stmt = $mysqli->prepare("SELECT id, token, salt, time FROM resetpassword WHERE userid = ? LIMIT 1")) { 
	$stmt->bind_param('s', $userid); // Bind "$email" to parameter.
	$stmt->execute(); // Execute the prepared query.
	$stmt->store_result();
	$stmt->bind_result($id, $db_token, $salt, $time); // get variables from result.
	$stmt->fetch();
	$token = hash('sha512', $freetoken.$salt); // hash the password with the unique salt.
	if ($token == $db_token) {
		$resetpw = true;
		include (docroot.path.'/resetpassword.php');
		exit();
	}
} else {
	header("location: ".path."/");
}

?>