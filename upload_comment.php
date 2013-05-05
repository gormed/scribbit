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
	$scribbleid=$_POST["scribbleid"];

	$commentid = time();//mt_rand(0,mt_getrandmax());
	$now = time();
	$mysqldate = date( 'Y-m-d H:i:s', $now );
	//$phpdate = strtotime( $mysqldate );

	// TODO: check if scribble id is already taken! 
	$file = '/comments/'.$userid.$commentid.'.png';//$_SERVER['DOCUMENT_ROOT'].path.'/scribbles/img_'.mt_rand(0,mt_getrandmax()).'.png';
	$handle = fopen($_SERVER['DOCUMENT_ROOT'].root.$file, 'wb') or die("Cannot create new scribble!"); 
	$split = preg_split('[,]', $data);
	$image = base64url_decode($split[1]);

	fwrite($handle, $image);
	fclose($handle);

	$sql = sprintf("INSERT INTO `comments`(`scribbleid`, `userid`, `datetime`, `path`) VALUES (%d, %d, '%s', '%s')", $scribbleid, $userid, $mysqldate, $file);
	$mysqli->query($sql);

	$response = "Done";

?>
