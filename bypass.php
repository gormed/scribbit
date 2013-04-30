<?php

$pages= array();
$loginRequired = array();

$pages['dashboard']='dashboard.php';
$pages['login']='login.php';
$pages['register']='register.php';
$pages['logout']='logout.php';
$pages['wall']='wall.php';
$pages['gallery']='gallery.php';
$pages['profile']='profile.php';
$pages['scribble']='scribble.php';
$pages['view']='view.php';


$loginRequired['login']=false;
$loginRequired['register']=false;
$loginRequired['dashboard']=true;
$loginRequired['logout']=true;
$loginRequired['wall']=true;
$loginRequired['gallery']=true;
$loginRequired['profile']=true;
$loginRequired['scribble']=true;
$loginRequired['view']=true;

$folder = $_GET['start'];

$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE `username` = '%s' LIMIT 0, 1", $folder);
$rqst = $mysqli->query($sql);
$name = $rqst->fetch_array()[1];

if (array_key_exists($folder,$pages) 
	&& array_key_exists($folder,$loginRequired)) {

	if ($loginRequired[$folder] == $loggedIn) {
		include($pages[$folder]);
		exit();
	} else {
		header('location: '.path.'/login');
	}
} else if ($rqst->num_rows > 0 && $loggedIn) {
	//$isFriend = false;
	$viewProfile = true;
	//$ownProfile = false;
	$profile = $name;
	include ('profile.php');
	exit();
}

?>