<?php 
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn || !isset($_POST["oldpw"]) || !isset($_POST["newpw"];)) {
		exit();
	}

	$userid = $_SESSION["user_id"];
	$password = $_POST["oldpw"];
	$newpw = $_POST["newpw"];

	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	// Create salted password (Careful not to over season)
	$newpw = hash('sha512', $newpw.$randomSalt);

	if ($stmt = $mysqli->prepare("SELECT username, password, salt FROM members WHERE id = ? LIMIT 1")) { 
		$stmt->bind_param('s', $userid); // Bind "$email" to parameter.
		$stmt->execute(); // Execute the prepared query.
		$stmt->store_result();
		$stmt->bind_result($username, $db_password, $salt); // get variables from result.
		$stmt->fetch();
		$password = hash('sha512', $password.$salt); // hash the password with the unique salt.
		
		if($stmt->num_rows == 1) { // If the user exists		
			if($db_password == $password) {
				$sql = sprintf("UPDATE `members` SET `password`='%s',`salt`='%s' WHERE `id` = %d", $newpw, $randomSalt, $userid);
				$mysqli->query($sql);
				echo '<div style="color: #2CAC00">Successful!</div>';
				exit();
			} else {
				echo '<div style="color: #CF0000">Incorrect Password!</div>';
				exit();
			}
		} else {
			echo '<div style="color: #CF0000">Error while changing...</div>';
			exit();
		}
	} else {
		echo '<div style="color: #CF0000">Error while changing...</div>';
		exit();
	}
	//echo updatePassword($mysqli, $userid, $password, $newpw, $randomSalt);

?>