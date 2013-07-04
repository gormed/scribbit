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
$pages['favorites']='favorites.php';
$pages['friends']='friends.php';
$pages['settings']='settings.php';
$pages['lost']='lost.php';

$loginRequired['login']=false;
$loginRequired['register']=false;
$loginRequired['dashboard']=true;
$loginRequired['logout']=true;
$loginRequired['wall']=true;
$loginRequired['gallery']=true;
$loginRequired['profile']=true;
$loginRequired['scribble']=true;
$loginRequired['view']=true;
$loginRequired['favorites']=true;
$loginRequired['friends']=true;
$loginRequired['settings']=true;
$loginRequired['lost']=false;

if (isset($_GET['start'])) {
	$folder = $_GET['start'];

	if (array_key_exists($folder,$pages) 
		&& array_key_exists($folder,$loginRequired)) {

		if ($loginRequired[$folder] == $loggedIn) {
			include($pages[$folder]);
			track($mysqli, $folder);
			exit();
		} else if (!$loggedIn) {
			header('location: '.path.'/login');
			exit();
		} else {

			track($mysqli, "wall");
			header('location: '.path.'/wall');
			exit();
		}
	}

	$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE `username` = '%s' LIMIT 0, 1", $folder);
	$rqst = $mysqli->query($sql)->fetch_array();
	$name = $rqst[1];
	$friendid = $rqst[0];

	if (isset($rqst) && $loggedIn) {
		$isFriend = true;

		if ($friendid == $_SESSION['user_id']) {
			track($mysqli, "profile");
			header('location:'.path.'/profile');
			exit();
		} else {
			$viewProfile = true;
		}
		$profile = $name;
		include ('profile.php');
		exit();
	} else {
	}
}

?>