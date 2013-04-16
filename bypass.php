<?php

$pages= array();
$loginRequired = array();

$pages['dashboard']='dashboard.php';
$pages['login']='login.php';
$pages['register']='register.php';
$pages['logout']='logout.php';

$loginRequired['dashboard']=true;
$loginRequired['login']=false;
$loginRequired['register']=false;
$loginRequired['logout']=true;

if (array_key_exists($_GET['start'],$pages) 
	&& array_key_exists($_GET['start'],$loginRequired)) {

	if ($loginRequired[$_GET['start']] == $loggedIn) {
		include($pages[$_GET['start']]);
		exit();
	} else {
		header('location: '.path.'/login');
	}
} else {
	header('location: '.path.'/');
}

?>