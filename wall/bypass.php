<?php

if (isset($_GET['mapcoord']))
	$mapcoord = $_GET['mapcoord'];
else
	$mapcoord = '0-0-0';

$split = preg_split('[-]', $mapcoord);
$xpos = $split[0];
$ypos = $split[1];
$zoom = $split[2];

$sql = sprintf("SELECT `scribbleid`, `parentid` FROM `map` WHERE X(`position`) = %d AND Y(`position`) = %d LIMIT 0, 1 ", ($xpos), ($ypos));
$result = $mysqli->query($sql);

// $sql = sprintf("SELECT X(`position`), Y(`position`), `parentid` FROM `map` WHERE `scribbleid` = %d LIMIT 0, 1", $scribbleid);
// $result = $mysqli->query($sql);
// $pos = $result->fetch_array();
// $xpos = $pos[0];
// $ypos = $pos[1];

// $sql = sprintf("SELECT `scribbleid`, `userid`, `path`, `creation` FROM `scribbles` WHERE `scribbleid` = %d LIMIT 0, 1", $scribbleid);
// $rqst = $mysqli->query($sql);

if ($result->num_rows > 0 && $loggedIn) {
	$entry = $result->fetch_array();
	$scribbleid = $entry[0];
	$parentid = $entry[1];
	$sql = sprintf("UPDATE `secure_login`.`login_time` SET `views` = %d WHERE `login_time`.`userid` = %d LIMIT 1 ", 
				$_SESSION['views']++, $_SESSION['user_id']);
	$mysqli->query($sql);
	//track($mysqli, "wall/".$scribbleid);
	include (docroot.path.'/wall.php');
	exit();
} elseif ($loggedIn)  {
	$sql = sprintf("UPDATE `secure_login`.`login_time` SET `views` = %d WHERE `login_time`.`userid` = %d LIMIT 1 ", 
				$_SESSION['views']++, $_SESSION['user_id']);
	$mysqli->query($sql);
	//track($mysqli, "wall");
	include (docroot.path.'/wall.php');
	exit();
} else {
	header('location: '.path.'/login');
}

?>