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
		echo "var path = '".path."';".PHP_EOL;
		echo "var root = '".root."';".PHP_EOL; 
		?>
		var scribbles = {};
		var dates = {};
		var users = {};
		var favorites = {};
		var favCount = {};

		var canvasPos = {x:0.0, y:0.0};
		var canvasSize = {width:960, height:640};
		var lastX = 0.0;
		var lastY = 0.0;
		var capturing = false;

		function favToggle(scribbleid) {
			favorites[scribbleid] = !favorites[scribbleid];
			if (favorites[scribbleid])
				favCount[scribbleid]++;
			else 
				favCount[scribbleid]--;

			var temp = document.getElementById('fav_'+scribbleid);
			if (favorites[scribbleid])
				temp.setAttribute('src', path+'/ressources/img/ico/star.png');
			else
				temp.setAttribute('src', path+'/ressources/img/ico/unstar.png');

			var temp = document.getElementById('count_'+scribbleid);
			temp.innerHTML = favCount[scribbleid];
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
					favToggle(scribbleid); //on success
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
				$sql = "SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` ORDER BY `scribbles`.`creation` DESC LIMIT 0, 40 ";
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'scribbles['.$row[0]."] = '/scribbles/lm/".$row[1]."'; ";
					echo 'dates['.$row[0]."] = '".($row[3])."'; ";

					$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE (id = %d) LIMIT 1", $row[2]);
					$answer = $mysqli->query($sql);
					$user = $answer->fetch_array();
					echo 'users['.$row[0]."] = '".$user[1]."'; ";
					$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d AND `userid` = %d", $row[0], (int)$_SESSION['user_id']);
					$isFav = 'false';
					$fav = $mysqli->query($sql);
					if ($fav->num_rows > 0) {
						$isFav = 'true';
					}
					echo 'favorites['.$row[0]."] = ".$isFav."; ";

					$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d ", $row[0]);
					$favcount = $mysqli->query($sql);
					echo 'favCount['.$row[0]."] = ".$favcount->num_rows.";".PHP_EOL;
				}
			?>

			var gallery = document.getElementById('content');
			gallery.appendChild(document.createElement('br'));
			var element;
			var temp;
			var img;
			var link;

			for (var k in scribbles) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (scribbles.hasOwnProperty(k)) {
					link = document.createElement('a');
					link.setAttribute('href', path+'/scribbles/'+k);

					element = document.createElement('div');
					element.setAttribute('class','item');
					element.setAttribute('style', 'background-image: url("' + root+scribbles[k] + '"); background-size: 100% 100%;');
					link.appendChild(element);


					temp = document.createElement('div');
					temp.setAttribute('class', 'initem');
					temp.setAttribute('id', 'div_'+k);
					var fav; 
					if (favorites[k]) {
						fav = '<a href="#unfav"><img id="fav_'+k+'" src="'+path+'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('+k+');"></a>';
					} else {
						fav = '<a href="#fav"><img id="fav_'+k+'" src="'+path+'/ressources/img/ico/unstar.png" width="16" height="16" onclick="favImage('+k+');"></a>';
					}
					temp.innerHTML = '<span><a href="'+path+'/'+users[k]+'">'+ users[k] +'</a> '+'</span>'+
					'<br><span style="font-size: 0.6em">'+dates[k]+'</span>'+
					'<span style="float:right"><a href="'+path+'/scribbles/'+k+'#comments"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16"></a>'
					+fav+'<span id="count_'+k+'">'+favCount[k]+'</span></span>';

					element.appendChild(temp);
					gallery.appendChild(link);
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
					<?php include docroot.'/'.path.'/topnav.php'; ?>
					<?php include docroot.'/'.path.'/searchbar.php'; ?>
				</div>

				<div id="content">

				</div>
			</div>
	</body>
</html>