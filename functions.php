<?php
//
// Nach einem Tutorial auf http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
// Hans Ferchland
//
function sec_session_start() {
	$session_name = 'sec_session_id'; // Set a custom session name
	$secure = false; // Set to true if using https.
	$httponly = true; // This stops javascript being able to access the session id. 

	ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
	$cookieParams = session_get_cookie_params(); // Gets current cookies params.
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	session_name($session_name); // Sets the session name to the one set above.
	session_start(); // Start the php session
	session_regenerate_id(true); // regenerated the session, delete the old one.     
}

function login($email, $password, $mysqli) {
   // Using prepared Statements means that SQL injection is not possible. 
   if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1")) { 
	  $stmt->bind_param('s', $email); // Bind "$email" to parameter.
	  $stmt->execute(); // Execute the prepared query.
	  $stmt->store_result();
	  $stmt->bind_result($user_id, $username, $db_password, $salt); // get variables from result.
	  $stmt->fetch();
	  $password = hash('sha512', $password.$salt); // hash the password with the unique salt.
 
	  if($stmt->num_rows == 1) { // If the user exists
		 // We check if the account is locked from too many login attempts
		 if(checkbrute($user_id, $mysqli) == true) { 
			// Account is locked
			// Send an email to user saying their account is locked
			return false;
		 } else {
		 if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
			// Password is correct!
			$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

			$user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
			$_SESSION['user_id'] = $user_id; 
			$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
			$_SESSION['username'] = $username;
			$_SESSION['login_string'] = hash('sha512', $password.$user_browser);
			$_SESSION['views'] = 1;
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			// log last login time
			$now = time();
			$mysqldate = date( 'Y-m-d H:i:s', $now );
			$sql = sprintf("SELECT `userid`, `datetime` FROM `login_time` WHERE `userid` = %d LIMIT 0, 30 ", $user_id);
			$result = $mysqli->query($sql);
			if ($result->num_rows == 0) {
				$sql = sprintf("INSERT INTO `login_time`(`userid`, `datetime`) VALUES (%d,'%s')",$user_id, $mysqldate);
			} else {
				$sql = sprintf("UPDATE `secure_login`.`login_time` SET `datetime` = '%s' WHERE `login_time`.`userid` = %d LIMIT 1 ", $mysqldate, $user_id);
			}
			
			$mysqli->query($sql);

			// Login successful.
			return true;    
		 } else {
			// Password is not correct
			// We record this attempt in the database
			$now = time();
			$mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
			return false;
		 }
	  }
	  } else {
		 // No user exists. 
		 return false;
	  }
   }
}

function checkbrute($user_id, $mysqli) {
	// Get timestamp of current time
	$now = time();
	// All login attempts are counted from the past 2 hours. 
	$valid_attempts = $now - (2 * 60 * 60); 

	if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) { 
		$stmt->bind_param('i', $user_id); 
		// Execute the prepared query.
		$stmt->execute();
		$stmt->store_result();
		// If there has been more than 5 failed logins
		if($stmt->num_rows > 5) {
			return true;
		} else {
			return false;
		}
	}
}

// Memcash fuer session id?
function login_check($mysqli) {
   // Check if all session variables are set
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
	 $user_id = $_SESSION['user_id'];
	 $login_string = $_SESSION['login_string'];
	 $username = $_SESSION['username'];
 
	 $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
	 if ($stmt = $mysqli->prepare("SELECT password FROM members WHERE id = ? LIMIT 1")) { 
		$stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
		$stmt->execute(); // Execute the prepared query.
		$stmt->store_result();
 
		if($stmt->num_rows == 1) { // If the user exists
		   $stmt->bind_result($password); // get variables from result.
		   $stmt->fetch();
		   $login_check = hash('sha512', $password.$user_browser);
		   if($login_check == $login_string) {
			  // Logged In!!!!
			  return true;
		   } else {
			  // Not logged in
			  return false;
		   }
		} else {
			// Not logged in
			return false;
		}
	 } else {
		// Not logged in
		return false;
	 }
   } else {
	 // Not logged in
	 return false;
   }
}

function track($mysqli, $folder)
{
	if (login_check($mysqli)) {
		$lastpage = path."/".$folder;
		$sql = sprintf("UPDATE `secure_login`.`login_time` SET `views` = %d, `lastpage` = '%s' WHERE `login_time`.`userid` = %d LIMIT 1 ", 
					$_SESSION['views']++, $lastpage, $_SESSION['user_id']);
		$mysqli->query($sql);
	}
}

?>
