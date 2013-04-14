<?php
$loggedIn = login_check($mysqli);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">


		<title>Dashboard</title>
	</head>

	<body>
		<h1>Dashboard</h1>
		<div>
<?php
if($loggedIn) {
	echo 'Welcome to the Dashboard! <br/>';
} else {
	echo 'You are not authorized to access this page, please login. <br/>';
}
?>
		</div>
	</body>
</html>