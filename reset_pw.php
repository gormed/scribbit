<?php 
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	if (!isset($_POST["newpw"])) {
		exit();
	}

	$userid = $_POST["userid"];
	$newpw = $_POST["newpw"];

	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	// Create salted password (Careful not to over season)
	$newpw = hash('sha512', $newpw.$randomSalt);

	if ($stmt = $mysqli->prepare("SELECT username FROM members WHERE id = ? LIMIT 1")) { 
		$stmt->bind_param('s', $userid); // Bind "$email" to parameter.
		$stmt->execute(); // Execute the prepared query.
		$stmt->store_result();
		$stmt->bind_result($username); // get variables from result.
		$stmt->fetch();
		
		if($stmt->num_rows == 1) { // If the user exists
			$sql = sprintf("UPDATE `members` SET `password`='%s',`salt`='%s' WHERE `id` = %d", $newpw, $randomSalt, $userid);
			$mysqli->query($sql);
			$sql = sprintf("DELETE FROM `resetpassword` WHERE `userid` = %d", $userid);
			echo '<div style="color: #2CAC00">Successful!</div>';
			exit();
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