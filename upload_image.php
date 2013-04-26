<?php
require_once 'path.php';

function base64url_decode($base64url)
{
	$base64 = str_replace(' ','+',$base64url);
	$plainText = base64_decode($base64);
	return ($plainText);
}

$data=$_POST["data"];
$file = 'img_'.mt_rand(0,mt_getrandmax()).'.png';//$_SERVER['DOCUMENT_ROOT'].path.'/scribbles/img_'.mt_rand(0,mt_getrandmax()).'.png';
$handle = fopen($file, 'wb'); 
$split = preg_split('[,]', $data);
$image = base64url_decode($split[1]);

fwrite($handle, $image);
fclose($handle);

$response = "Done";

?>
