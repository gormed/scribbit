<?php
	require_once 'db_login.php';
	$mysqli->close();
	require_once 'db_work.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	$userid = $_SESSION['user_id'];
	$scribbleid = (int)$_POST["scribbleid"];

	$now = time();
	$mysqldate = date( 'Y-m-d H:i:s', $now );
	//$phpdate = strtotime( $mysqldate );

	$sql = sprintf("SELECT `favid`, `userid`, `scribbleid`, `datetime` FROM `favorites` WHERE `userid` = %d AND `scribbleid` = %d LIMIT 0, 1 ", $userid, $scribbleid);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		// unfav
		$sql = sprintf("DELETE FROM `favorites` WHERE `favid` = %d", $result->fetch_array()[0]);
		echo $mysqli->query($sql);
	} else {
		// fav
		$sql = sprintf("INSERT INTO `favorites` (`userid`, `scribbleid`, `datetime`) VALUES (%d,%d,'%s')",$userid, $scribbleid, $mysqldate);
		echo $mysqli->query($sql);
	}

?>
