
<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';

	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	

	if(isset($_POST['skipCount'], $_POST['rowCount'])) {
		$skipCount = $_POST['skipCount'];
		$rowCount = $_POST['rowCount'];

		$userid = $_SESSION["user_id"];	
		$temp_scribbles = Array();
		$temp_dates = Array();
		$temp_users = Array();
		$temp_favorites = Array();
		$temp_favCount = Array();
		$temp_commentCount = Array();


		$sql = "SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` ORDER BY `scribbles`.`creation` DESC LIMIT $skipCount, $rowCount ";
		$result = $mysqli->query($sql);
				
		while ($row = $result->fetch_array()) {
					
			$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE (id = %d) LIMIT 1", $row[2]);
			$answer = $mysqli->query($sql);
			$user = $answer->fetch_array();
			
			$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d AND `userid` = %d", $row[0], (int)$userid);
			$isFav = 'false';
			$fav = $mysqli->query($sql);
			if ($fav->num_rows > 0) {
				$isFav = 'true';
			}
			
			$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d ", $row[0]);
			$favcount = $mysqli->query($sql);
			
			$sql = sprintf("SELECT `commentid`, `scribbleid` FROM `comments` WHERE `scribbleid` = %d", $row[0]);
			$commentcount = $mysqli->query($sql)->num_rows;
		
			$favcountnum = $favcount->num_rows;

			$temp_scribbles[$row[0]] = '/scribbles/lm/'.$row[1];
			$temp_dates[$row[0]] = ''.$row[3];
			$temp_users[$row[0]] = ''.$user[1];
			$temp_favorites[$row[0]] = ''.$isFav;
			$temp_favCount[$row[0]] = ''.$favcountnum;
			$temp_commentCount[$row[0]]= ''.$commentcount;
				
		}

		$ret = Array(	
			"temp_scribbles" => $temp_scribbles,
			"temp_dates" => $temp_dates,
			"temp_users" => $temp_users,
			"temp_favorites" => $temp_favorites,
			"temp_favCount" => $temp_favCount,
			"temp_commentCount" => $temp_commentCount
		);
    
		

		echo json_encode($ret); 
	}
?>