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
	$newname = $_POST["newname"];

	if ($stmt = $mysqli->prepare("SELECT username FROM members WHERE id = ? LIMIT 1")) { 
		$stmt->bind_param('s', $userid); // Bind "$email" to parameter.
		$stmt->execute(); // Execute the prepared query.
		$stmt->store_result();
		$stmt->bind_result($username); // get variables from result.
		$stmt->fetch();
		
		if($stmt->num_rows == 1 && $stmt = 
			$mysqli->prepare("SELECT id FROM members WHERE username = ? LIMIT 1")) { 
				$stmt->bind_param('s', $newname); // Bind "$email" to parameter.
				$stmt->execute(); // Execute the prepared query.
				$stmt->store_result();
				$stmt->bind_result($existing); // get variables from result.
				$stmt->fetch();
				if ($stmt->num_rows == 0) {
					$sql = sprintf("UPDATE `members` SET `username`='%s' WHERE `id` = %d", $newname, $userid);
					$mysqli->query($sql); 

					echo '<div style="color: #2CAC00">Successful!</div>';
					exit();
				} else {
					echo '<div style="color: #CF0000">Username already taken!</div>';
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