<?php
	require_once 'db_login.php';
	require_once 'functions.php';
	require_once 'path.php';

	sec_session_start();
	$loggedIn = login_check($mysqli);	

	$pages= array();
	$pages['dashboard']='dashboard.php';
	$pages['login']='login.php';
	$pages['register']='register.php';
	$pages['logout']='logout.php';

	//print_r($_GET);
	//print_r($seiten);
	if (array_key_exists($_GET['start'],$pages))
	{

		include($pages[$_GET['start']]);
		exit();
	}
?>