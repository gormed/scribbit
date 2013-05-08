<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';
	
	sec_session_start();
	$loggedIn = login_check($mysqli);	
	$msg = "";

	if (!$loggedIn) {
		exit();
	}

	function base64url_decode($base64url)
	{
		$base64 = str_replace(' ','+',$base64url);
		$plainText = base64_decode($base64);
		return ($plainText);
	}

	function resize($cur_dir, $cur_file, $newwidth, $output_dir, $msg)
	{
		$dir_name = $cur_dir;
		//$olddir = getcwd();
		//$dir = opendir($dir_name);
		$filename = $cur_file;
		$format='';
		$filepath=$dir_name.'/'.$filename;
		if(preg_match("/.jpg/i", "$filepath"))
		{
			$format = 'image/jpeg';
		}
		if (preg_match("/.gif/i", "$filepath"))
		{
			$format = 'image/gif';
		}
		if(preg_match("/.png/i", "$filepath"))
		{
			$format = 'image/png';
		}
		if($format!='')
		{
			list($width, $height) = getimagesize($filepath);
			$newheight=$height*$newwidth/$width;
			switch($format)
			{
				case 'image/jpeg':
				$source = imagecreatefromjpeg($filepath);
				break;
				case 'image/gif';
				$source = imagecreatefromgif($filepath);
				break;
				case 'image/png':
				$source = imagecreatefrompng($filepath);
				break;
			}
			$thumb = imagecreate($newwidth,$newheight);
			imagealphablending($thumb, true);
			$source = imagecreatefrompng($filepath);
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			$filename=$output_dir.'/'.$filename;
			imagepng($thumb, $filename);
			$msg = $msg."<br>Written to ".$filename;
		} else {
			$msg = $msg."<br>Error! No format given.";
		}
		return $msg;
	}

	$userid = $_SESSION['user_id'];
	$data=$_POST["data"];
	$scribbleid = time();//mt_rand(0,mt_getrandmax());
	$now = time();
	$mysqldate = date( 'Y-m-d H:i:s', $now );
	//$phpdate = strtotime( $mysqldate );

	// vars for filename and path
	$filename = $userid.$scribbleid.'.png';
	$mainpath = '/scribbles/';
	$subpath = 'h';
	// TODO: check if scribble id is already taken! 
	$file = $mainpath.$subpath.'/'.$filename;//$_SERVER['DOCUMENT_ROOT'].path.'/scribbles/img_'.mt_rand(0,mt_getrandmax()).'.png';
	$filepath = $_SERVER['DOCUMENT_ROOT'].root.$file;

	$handle = fopen($filepath, 'wb') or die("Cannot create new scribble!"); 
	$split = preg_split('[,]', $data);
	$image = base64url_decode($split[1]);

	fwrite($handle, $image);
	fclose($handle);
	// $cur_dir, $cur_file, $newwidth, $output_dir
	$msg = $msg.resize($_SERVER['DOCUMENT_ROOT'].root.$mainpath.$subpath, $filename, "210", $_SERVER['DOCUMENT_ROOT'].root.$mainpath.'l',$msg);
	$msg = $msg.resize($_SERVER['DOCUMENT_ROOT'].root.$mainpath.$subpath, $filename, "300", $_SERVER['DOCUMENT_ROOT'].root.$mainpath.'lm',$msg);
	$msg = $msg.resize($_SERVER['DOCUMENT_ROOT'].root.$mainpath.$subpath, $filename, "600", $_SERVER['DOCUMENT_ROOT'].root.$mainpath.'m',$msg);

	$sql = sprintf("INSERT INTO `scribbles`(`scribbleid`, `path`, `userid`, `creation`) VALUES (%d, '%s',%d, '%s')", 
																			$scribbleid, $filename, $userid, $mysqldate);
	$mysqli->query($sql);

	echo "Done!";

?>
