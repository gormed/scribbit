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

if (array_key_exists($_GET['start'],$pages) 
	&& array_key_exists($_GET['start'],$loginRequired)) {

	if ($loginRequired[$_GET['start']] == $loggedIn) {
		include($pages[$_GET['start']]);
		exit();
	} else {
		header('location: '.path.'/login');
	}
}

?>