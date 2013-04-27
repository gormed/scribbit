<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	

	if (!$loggedIn) {
		exit();
	}

	function base64url_decode($base64url)
	{
		$base64 = str_replace(' ','+',$base64url);
		$plainText = base64_decode($base64);
		return ($plainText);
	}

	$userid = $_SESSION['user_id'];
	$data=$_POST["data"];
	$scribbleid = mt_rand(0,mt_getrandmax());
	$now = time();
	$mysqldate = date( 'Y-m-d H:i:s', $now );
	//$phpdate = strtotime( $mysqldate );

	// TODO: check if scribble id is already taken! 

	$file = 'scribbles/'.$userid.'_'.$scribbleid.'.png';//$_SERVER['DOCUMENT_ROOT'].path.'/scribbles/img_'.mt_rand(0,mt_getrandmax()).'.png';
	$handle = fopen($file, 'wb'); 
	$split = preg_split('[,]', $data);
	$image = base64url_decode($split[1]);

	fwrite($handle, $image);
	fclose($handle);

	$sql = sprintf("INSERT INTO `scribbles`(`scribbleid`, `path`, `userid`, `creation`) VALUES (%d, '%s',%d, '%s')", $scribbleid, $file, $userid, $mysqldate);
	$mysqli->query($sql);

	$response = "Done";

?>
