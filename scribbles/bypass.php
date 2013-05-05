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
	include (docroot.path.'/view.php');
	exit();
}

?>