<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
 ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/header.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/profile.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Profil</title>
		<script type="text/javascript">

		

		var friends = {};
		var favorites = {};

		var ownPath = {};
		var ownDates = {};

		<?php 
		echo "var userid = ".$_SESSION['user_id'].";"; 
		echo "var path = '".path."';"; 
		?>

		function loadScribbles () {

			<?php 

				if (!$loggedIn) {
					exit();
				}
				
				$sql = sprintf("SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` WHERE (`userid` = %d) ORDER BY `scribbles`.`creation` ASC LIMIT 0, 10 ", $_SESSION['user_id']);
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'ownPath['.$row[0]."] = '".$row[1]."';";
					echo 'ownDates['.$row[0]."] = '".($row[3])."';";
				}
			?>

			// <?php 

			// 	if (!$loggedIn) {
			// 		exit();
			// 	}
			// 	echo "var path = '".path."';";
			// 	$sql = "SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` ORDER BY `scribbles`.`creation` DESC LIMIT 0, 40 ";
			// 	$result = $mysqli->query($sql);
			// 	while ($row = $result->fetch_array()) {
			// 		echo 'scribbles['.$row[0]."] = '".$row[1]."';";
			// 		echo 'dates['.$row[0]."] = '".($row[3])."';";

			// 		$sql = sprintf("SELECT `id`, `username` FROM `members` WHERE (id = %d) LIMIT 1", $row[2]);
			// 		$answer = $mysqli->query($sql);
			// 		$user = $answer->fetch_array();
			// 		echo 'users['.$row[0]."] = '".$user[1]."';";
			// 	}
			// ?>

			var content = document.getElementById('content');
			content.appendChild(document.createElement('br'));

			var horizontalbar = document.createElement('div');
			horizontalbar.setAttribute('class', 'horizontalbar');
			content.appendChild(horizontalbar);

			var holder = document.createElement('div');
			holder.setAttribute('class', 'holder');
			horizontalbar.appendChild(holder);

			var profitem;

			for (var k in ownPath) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (ownPath.hasOwnProperty(k)) {

					profitem = document.createElement('div');
					profitem.setAttribute('class','profitem');
					profitem.setAttribute('style', 'background-image: url("' + path+'/'+ownPath[k] + '"); background-size: 100% 100%;');
					holder.appendChild(profitem);

					// temp = document.createElement('div');
					// temp.setAttribute('class', 'initem');
					// temp.innerHTML = '<span><a href="'+path+'/profile">'+ users[k] +'</a> '+'</span>'+
					// '<br><span style="font-size: 0.6em">'+dates[k]+'</span>'+
					// '<span style="float:right"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16">'+
					// '<img src="'+path+'/ressources/img/ico/star.png" width="16" height="16"></span>';

					// element.appendChild(temp);
				}
			}
			content.appendChild(document.createElement('br'));
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
			</div>
			<div style="float: left; margin-top: 100px;" id="profile">
				<br>
				<?php 
				$sql = sprintf("SELECT `userid`, `datetime` FROM `login_time` WHERE `userid` = %d LIMIT 0, 1 ", $_SESSION['user_id']);
				$row = $mysqli->query($sql);
				echo $_SESSION['username']."<br>";
				echo "Last Login: ".$row->fetch_array()[1]."<br>";
				?>
			</div>
			<div id="content">
				<div class="horizontalbar">
						<div class="holder">
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
						<div class="profitem">Scribbles meiner Freunde</div>
				 	</div>
				</div>
				<br>
				<div class="horizontalbar">	
					<div class="holder">					
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
						<div class="profitem">Scribbles meiner Favoriten</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>