

<?php
	require_once '../db_login.php';
	require_once '../functions.php';
	require_once '../path.php';


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
		while ($row = $result->fetch_array()) {

			$temp_scribbles[$row[0]] = '/scribbles/l/'.$row[1];
			$temp_positionsx[$row[0]] = ''.$row[4];
			$temp_positionsy[$row[0]] = ''.$row[5];
			$temp_map[$row[4].''.$row[5]] = ''.$row[0];
		}
			$ret = Array(	"temp_scribbles" => $temp_scribbles,
							"temp_positionsx" => $temp_positionsx,
							"temp_positionsy" => $temp_positionsy,
							"temp_map" => $temp_map
					);
    
		

		echo json_encode($ret); 
	}
?>