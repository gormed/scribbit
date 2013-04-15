<?php
	$pages= array();
	$pages['dashboard']='dashboard.php';
	$pages['login']='login.php';
	$pages['register']='register.php';
	$pages['logout']='logout.php';
	
	if (array_key_exists($_GET['start'],$pages))
	{
		include($pages[$_GET['start']]);
		exit();
	}
?>