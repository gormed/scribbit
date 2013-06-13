<?php

$scribbleid = -1;
if (isset($_GET['scribbleid']))
	$scribbleid = $_GET['scribbleid'];

$sql = sprintf("SELECT `scribbleid`, `userid`, `path`, `creation` FROM `scribbles` WHERE `scribbleid` = %d LIMIT 0, 1", $scribbleid);
$rqst = $mysqli->query($sql);

if ($rqst->num_rows > 0 && $loggedIn) {
	$entry = $rqst->fetch_array();
	$fromuser = $entry[1];
	$scribblepath = $entry[2];
	$scribbleid = $entry[0];
	$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE `id` = %d", $fromuser);
	$fromname = $mysqli->query($sql)->fetch_array()[1];
	$fromdate = $entry[3];
	track($mysqli, "scribble/".$scribbleid);
	include (docroot.path.'/view.php');
	$sql = sprintf("UPDATE `secure_login`.`login_time` SET `views` = %d WHERE `login_time`.`userid` = %d LIMIT 1 ", 
					$_SESSION['views']++, $_SESSION['user_id']);
	$mysqli->query($sql);
	exit();
} else if ($rqst->num_rows <= 0 && $loggedIn) {
	include (docroot.path.'/error');
} else {
	header("location: ".path."/");
}

?>