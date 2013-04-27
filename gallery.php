<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
 ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/headerSearch.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/gallery.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Gallery</title>
		<script type="text/javascript">

		var scribbles = {};
		var dates = {};
		var users = {};

		function loadScribbles () {
			<?php 

				if (!$loggedIn) {
					exit();
				}
				echo "var path = '".path."';";
				$sql = "SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` ORDER BY `creation` DESC LIMIT 0, 40 ";
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'scribbles['.$row[0]."] = '".$row[1]."';";
					echo 'dates['.$row[0]."] = '".($row[3])."';";

					$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE (id = %d) LIMIT 1", $row[2]);
					$answer = $mysqli->query($sql);
					$user = $answer->fetch_array();
					echo 'users['.$row[0]."] = '".$user[1]."';";
				}
			?>

			var gallery = document.getElementById('content');
			gallery.appendChild(document.createElement('br'));
			var element;
			var temp;
			var img;

			for (var k in scribbles) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (scribbles.hasOwnProperty(k)) {

					element = document.createElement('div');
					element.setAttribute('class','item');
					element.setAttribute('style', 'background-image: url("' + path+'/'+scribbles[k] + '"); background-size: 250px 200px;');
					gallery.appendChild(element);

					temp = document.createElement('div');
					temp.setAttribute('class', 'initem');
					temp.innerHTML = '<span><a href="'+path+'/profile">'+ users[k] +'</a> '+'</span>'+
					'<br><span style="font-size: 0.6em">'+dates[k]+'</span>'+
					'<span style="float:right"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16">'+
					'<img src="'+path+'/ressources/img/ico/star.png" width="16" height="16"></span>';

					element.appendChild(temp);
				}
			}

			gallery.appendChild(document.createElement('br'));
			gallery.appendChild(document.createElement('br'));
		}
		</script>
	</head>

	<body onload="loadScribbles();">
			<div id="site">
			<div id="header">
					<div id="logo">
						<?php
						echo '<a href="'.path.'/"><</a>';
						?>
					</div>
					<div>
							<ul class="topnav">
								<li>
									<span><a href="#">Profile</a></span>
									<ul class="subnav">
										<li><?php echo '<a href="'.path.'/profile">Go to Profile</a>' ?></li>
										<li><a href="#">Freunde</a></li>
										<li><a href="#">Favoriten</a></li>
										<li><?php echo '<a href="'.path.'/logout">Logout</a>' ?></li>
									</ul>
								</li>
								<li><?php echo '<span><a href="'.path.'/gallery">Gallery</a></span>' ?></li>
								<li><?php echo '<span><a href="'.path.'/wall">Wall</a></span>' ?></li>
							</ul>
						</div>	
					<div class="searchbar" >
						<ul>
							<li><input class="searchtext" type="text" name="searchtext"/></li>
							<br>
							<br>
							<li><input name="filter" type="checkbox" value="favorits"/>Favorits&nbsp;</li>
							<li><input name="filter" type="checkbox" value="friends">Friends&nbsp;</li>		
							<li><input name="filter" type="checkbox" value="my"/>Own&nbsp;</li>	
							<li><select name="timefilter">
								<option value="all">all Time</option> 
								<option value="h24">last 24 h</option> 
								<option value="d7">last 7 days</option>
							</select></li>
						</ul>
					</div>
				</div>

				<div id="content">

				</div>
			</div>
	</body>
</html>