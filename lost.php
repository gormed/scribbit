<?php 
include 'header.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/headerSearch.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/profile.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribb'it Lost Password</title>
	</head>
	<body>
		<div>
			<input type="text" id="email">
			Email
		</div>
		<div>
			<input type="button" id="requestnewpw" value="Send">
		</div>
	</body>
</html>
<script type="text/javascript">
	$(document).ready(function() {
		$('#requestnewpw').click(function() {
			
		});
	});
</script>