<?php
	require_once 'db_work.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	$userid = $_SESSION['user_id'];
	$friendid = (int) $_POST['friendid'];

	$now = time();
	$mysqldate = date( 'Y-m-d H:i:s', $now );

	$sql = sprintf("SELECT `relationid`, `userid`, `friendid` FROM `friends` WHERE `userid` = %d AND `friendid` = %d LIMIT 0, 1 ", $userid, $friendid);
	$result = $mysqli->query($sql);
	// Thats for adding a friend
	if (isset($_POST['add']) && $result->num_rows == 0) {
		$sql = sprintf("INSERT INTO `friends`(`userid`, `friendid`, `datetime`) VALUES (%d, %d,'%s')",$userid, $friendid, $mysqldate);
		$mysqli->query($sql);
		// thats for the "first friendship" so both are automatically friends from beginning
		// both can cancel their friendship, but not automatically cancelling both sides
		// so you can be friend with someone who isnt yours :P like the real world
		$sql = sprintf("SELECT `relationid`, `userid`, `friendid` FROM `friends` WHERE `userid` = %d AND `friendid` = %d LIMIT 0, 1 ", $friendid, $userid);
		$result = $mysqli->query($sql);
		if ($result->num_rows == 0) {
			$sql = sprintf("INSERT INTO `friends`(`userid`, `friendid`, `datetime`) VALUES (%d, %d,'%s')", $friendid, $userid, $mysqldate);
			$mysqli->query($sql);
		}

	} elseif (isset($_POST['remove']) && $result->num_rows > 0) {
		$sql = sprintf("DELETE FROM `friends` WHERE `relationid` = %d", $result->fetch_array()[0]);
		$mysqli->query($sql);
	}
	//$phpdate = strtotime( $mysqldate );

?>
