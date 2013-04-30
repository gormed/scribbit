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
		<?php 
		echo "var path = '".path."';";
		echo "var root = '".root."';"; 
		?>
		var scribbles = {};
		var dates = {};
		var users = {};
		var favorites = {};
		var favCount = {};
		//var userids = {};

		function favToggle(scribbleid) {
			favorites[scribbleid] = !favorites[scribbleid];
			if (favorites[scribbleid])
				favCount[scribbleid]++;
			else 
				favCount[scribbleid]--;

			if (favorites[scribbleid])
				document.getElementById("fav_"+scribbleid).innerHTML = 
				'<img id="fav_'+scribbleid+'" src="'+path+'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('+scribbleid+');">';
			else
				document.getElementById("fav_"+scribbleid).innerHTML = 
				'<img id="fav_'+scribbleid+'" src="'+path+'/ressources/img/ico/unstar.png" width="16" height="16" onclick="favImage('+scribbleid+');">';

		}

		function favImage(scribbleid) {
			var xmlhttp;

			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					favToggle(scribbleid);
					//src="'+path+'/ressources/img/ico/star.png"
					// if (favorites[scribbleid])
					// 	document.getElementById("fav_"+scribbleid).innerHTML = 
					// 	'<img id="fav_'+scribbleid+'" src="'+path+'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('+scribbleid+');">'+favCount[k];
					// else
					// 	document.getElementById("fav_"+scribbleid).setAttribute('src', "'+path+'/ressources/img/ico/unstar.png");
					//document.getElementById("upload").innerHTML=xmlhttp.response;
				}
			}
			xmlhttp.open("POST","fav_image.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("scribbleid="+scribbleid);

		}

		function loadScribbles () {
			<?php 

				if (!$loggedIn) {
					exit();
				}
				$sql = "SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` ORDER BY `scribbles`.`creation` ASC LIMIT 0, 40 ";
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'scribbles['.$row[0]."] = '".$row[1]."';";
					echo 'dates['.$row[0]."] = '".($row[3])."';";

					$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE (id = %d) LIMIT 1", $row[2]);
					$answer = $mysqli->query($sql);
					$user = $answer->fetch_array();
					echo 'users['.$row[0]."] = '".$user[1]."';";
					$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d AND `userid` = %d", $row[0], (int)$_SESSION['user_id']);
					$isFav = 'false';
					$fav = $mysqli->query($sql);
					if ($fav->num_rows > 0) {
						$isFav = 'true';
					}
					echo 'favorites['.$row[0]."] = ".$isFav.";";

					$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d ", $row[0]);
					$favcount = $mysqli->query($sql);
					echo 'favCount['.$row[0]."] = ".$favcount->num_rows.";";
					// echo 'userids['.$row[0]."] = '".$user[0]."';"; 
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
					element.setAttribute('style', 'background-image: url("' + root+scribbles[k] + '"); background-size: 100% 100%;');
					gallery.appendChild(element);

					temp = document.createElement('div');
					temp.setAttribute('class', 'initem');
					var fav; 
					if (favorites[k]) {
						fav = '<img id="fav_'+k+'" src="'+path+'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('+k+');">';
					} else {
						fav = '<img id="fav_'+k+'" src="'+path+'/ressources/img/ico/unstar.png" width="16" height="16" onclick="favImage('+k+');">';
					}
					temp.innerHTML = '<span><a href="'+path+'/'+users[k]+'">'+ users[k] +'</a> '+'</span>'+
					'<br><span style="font-size: 0.6em">'+dates[k]+'</span>'+
					'<span style="float:right"><a href="'+path+'/'+k+'"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16"></a>'
					+fav+favCount[k]+'</span>';

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