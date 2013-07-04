<?php
	require_once '../db_login.php';
	require_once '../functions.php';
	require_once '../path.php';

	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	function hasNeighbour($mysqli, $xcurr, $ycurr, $x, $y)
	{
		$sql = sprintf("SELECT `scribbleid`, `parentid` FROM `map` WHERE X(`position`) = %d AND Y(`position`) = %d LIMIT 0, 1 ", ($xcurr + $x), ($ycurr + $y));
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		// $user_browser = $_SERVER['REMOTE_ADDR']; // Get the user-agent string of the user.

		return false;
	}

	function checkAllNeighbours($mysqli, $xcurr, $ycurr, $posx, $posy, $counter) {
			// above
		if (!hasNeighbour($mysqli, $xcurr, $ycurr, 0, 1)) {
			$counter++;
			$posx[$counter] = ''.$xcurr;
			$posy[$counter] = ''.($ycurr + 1);
			// below
		} else if (!hasNeighbour($mysqli, $xcurr, $ycurr, 0, -1)) {
			$counter++;
			$posx[$counter] = ''.$xcurr;
			$posy[$counter] = ''.($ycurr - 1);
			// right
		} else if (!hasNeighbour($mysqli, $xcurr, $ycurr, 1, 0)) {
			$counter++;
			$posx[$counter] = ''.($xcurr +1);
			$posy[$counter] = ''.$ycurr;
			// left
		} else if (!hasNeighbour($mysqli, $xcurr, $ycurr, -1, 0)) {
			$counter++;
			$posx[$counter] = ''.$xcurr;
			$posy[$counter] = ''.($ycurr - 1);
		}
	}

	if(isset($_POST['bl'], $_POST['br'],$_POST['tr'],$_POST['tl'])) {
		$bl = $_POST['bl'];
		$br = $_POST['br'];
		$tr = $_POST['tr'];
		$tl = $_POST['tl'];

		$sql = sprintf("SELECT `scribbles`.`scribbleid`, `scribbles`.`path`, `scribbles`.`userid`, `scribbles`.`creation`, X(`map`.`position`), Y(`map`.`position`) FROM `scribbles`, `map` WHERE `scribbles`.`scribbleid` = `map`.`scribbleid` AND MBRContains(GeomFromText('Polygon((%s, %s, %s, %s, %s))'), `map`.`position`) = 1 LIMIT 0, 486 ", $bl, $br, $tr, $tl, $bl);
		$result = $mysqli->query($sql);
		$temp_scribbles = Array();
		$temp_positionsx = Array();
		$temp_positionsy = Array();
		$temp_map = Array();
		$temp_empty = Array();
		$temp_commentCount = Array();
		$temp_favorites = Array();
		$temp_favoriteCount = Array();
		$counter = 0;

		while ($row = $result->fetch_array()) {

			$temp_scribbles[$row[0]] = '/scribbles/l/'.$row[1];
			$temp_positionsx[$row[0]] = ''.$row[4];
			$temp_positionsy[$row[0]] = ''.$row[5];
			$temp_map[$row[4].''.$row[5]] = ''.$row[0];

			// get comments for this scribble (could be more performant if JOIN for the comments table)
			$sql = sprintf("SELECT `comments`.`commentid`, `members`.`username`, `comments`.`datetime`, `comments`.`path` FROM `comments`, `members` WHERE `comments`.`scribbleid` = %d AND `comments`.`userid` = `members`.`id` ORDER BY `datetime` DESC LIMIT 0, 40", $row[0]);
<<<<<<< HEAD
			$temp_commentCount[$row[0]] = ''.$mysqli->query($sql)->num_rows;
=======
			$result = $mysqli->query($sql);
			$commentCount[$row[0]] = $result->num_rows;
>>>>>>> 67c0c0d... added track functionality for page views and current site;
			// get if the scribble is your favorite and the whole fav count (same as above)
			$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d AND `userid` = %d", $row[0], (int)$_SESSION['user_id']);
			$isFav = 'false';
			$fav = $mysqli->query($sql);
			if ($fav->num_rows > 0)
				$isFav = 'true';
			$temp_favorites[$row[0]] = ''.$isFav;

			$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d ", $row[0]);
			$temp_favoriteCount[$row[0]] = ''.$mysqli->query($sql)->num_rows;

			checkAllNeighbours($mysqli, $row[4], $row[5], $temp_positionsx, $temp_positionsy, $counter);
		}

		$ret = Array(	"temp_scribbles" => $temp_scribbles,
						"temp_positionsx" => $temp_positionsx,
						"temp_positionsy" => $temp_positionsy,
						"temp_map" => $temp_map,
						"temp_counter" => $counter,
						"temp_favorites" => $temp_favorites,
						"temp_favoriteCount" => $temp_favoriteCount,
						"temp_commentCount" => $temp_commentCount
				);
    
		

		echo json_encode($ret); 
	}
?>