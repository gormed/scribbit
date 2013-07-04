
<?php
require_once 'db_login.php';
require_once 'functions.php';
require_once 'path.php';

sec_session_start();
$loggedIn = login_check($mysqli);	

if (!$loggedIn) {
	exit();
}

if(isset($_POST['scribbleid'])) {
	$scribbleid = $_POST['scribbleid'];

	$userid = $_SESSION["user_id"];	
	$neighbours = Array();
	$freescribbles = Array();

	$sql = sprintf("SELECT X(`position`), Y(`position`), `parentid` FROM `map` WHERE `scribbleid` = %d LIMIT 0, 1", $scribbleid);
	$result = $mysqli->query($sql)->fetch_array();

	$xcurr = $result[0];
	$ycurr = $result[1];
	$parentid = $result[2];

	function hasNeighbour($mysqli, $xcurr, $ycurr, $x, $y)
	{
		$sql = sprintf("SELECT `scribbleid`, `parentid` FROM `map` WHERE X(`position`) = %d AND Y(`position`) = %d LIMIT 0, 1 ", ($xcurr + $x), ($ycurr + $y));
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	function getNeightbour($mysqli, $xcurr, $ycurr)
	{
		$sql = sprintf("SELECT `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username` FROM `map`, `scribbles`, `members` WHERE X(`position`) = %d AND Y(`position`) = %d AND `scribbles`.`scribbleid` = `map`.`scribbleid` AND `scribbles`.`userid` = `members`.`id` LIMIT 0, 1 ", $xcurr, $ycurr);
		$result = $mysqli->query($sql)->fetch_array();
		return $result;
	}
	// top, left, bottom, right
	$freescribbles[0] = hasNeighbour($mysqli, $xcurr, $ycurr, 0, 1);
	if ($freescribbles[0]) $neighbours[0] = getNeightbour($mysqli, $xcurr, $ycurr+1);

	$freescribbles[1] = hasNeighbour($mysqli, $xcurr, $ycurr, -1, 0);
	if ($freescribbles[1]) $neighbours[1] = getNeightbour($mysqli, $xcurr-1, $ycurr);

	$freescribbles[2] = hasNeighbour($mysqli, $xcurr, $ycurr, 0, -1);
	if ($freescribbles[2]) $neighbours[2] = getNeightbour($mysqli, $xcurr, $ycurr-1);

	$freescribbles[3] = hasNeighbour($mysqli, $xcurr, $ycurr, 1, 0);
	if ($freescribbles[3]) $neighbours[3] = getNeightbour($mysqli, $xcurr+1, $ycurr);

	$ret = Array(	"freescribbles" => $freescribbles,
		"neighbours" => $neighbours
		);

	echo json_encode($ret); 
}
?>