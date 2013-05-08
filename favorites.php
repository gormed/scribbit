<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
 ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/headerSearch.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/friends.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Friends</title>
		<script type="text/javascript">
		<?php 
		echo "var path = '".path."';";
		echo "var root = '".root."';"; 
		?>
		var scribbles = {};
		var dates = {};
		var favorites = {};
		
		function loadSingleFav(id, name, date, imgpath) {
			var element = document.createElement('div');
			element.setAttribute('class','item');
			element.setAttribute('style', 'background-image: url("' + root+'/scribbles/l/'+imgpath + '"); background-size: 100% 100%;');

			var temp = document.createElement('div');
			temp.setAttribute('class', 'initem');
			temp.setAttribute('id', 'div_'+id);
			
			temp.innerHTML = '<span><a href="'+path+'/'+name+'">'+ name +'</a> '+'</span>'+
			'<br><span style="font-size: 0.6em">'+date+'</span>';

			element.appendChild(temp);
			return element;
		}

		function loadFavorites() {
			<?php 
				$userid = $_SESSION['user_id'];
				if (!$loggedIn) {
					exit();
				}

				// SELECT `favid`, `userid`, `scribbleid`, `scribbles`.`datetime`, `path` FROM `favorites`
				function getFavScribbles($userid, $mysqli)
				{
					$sql = sprintf("SELECT `favorites`.`favid`, `members`.`username`, `scribbles`.`creation`, `scribbles`.`path` FROM `favorites`, `scribbles`, `members` WHERE `scribbles`.`userid` = `members`.`id` AND `favorites`.`userid` = %d AND `scribbles`.`scribbleid` = `favorites`.`scribbleid` ORDER BY `favorites`.`datetime` ASC LIMIT 0, 40 ", $userid);
					$result = $mysqli->query($sql);
					return $result;
				}

				$result = getFavScribbles($userid, $mysqli);
				while ($row = $result->fetch_array()) {
					echo 'favorites['.$row[0]."] = '".$row[1]."';";
					echo 'dates['.$row[0]."] = '".($row[2])."';";
					echo 'scribbles['.$row[0]."] = '".$row[3]."';";
				}
			?>

			var gallery = document.getElementById('content');
			gallery.appendChild(document.createElement('br'));
			var element;
			var temp;
			var img;

			for (var k in favorites) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (favorites.hasOwnProperty(k)) {
					var element = loadSingleFav(k, favorites[k], dates[k], scribbles[k]);
					gallery.appendChild(element);
				}
			}

			gallery.appendChild(document.createElement('br'));
			gallery.appendChild(document.createElement('br'));
		}
		</script>
	</head>

	<body onload="loadFavorites();">
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