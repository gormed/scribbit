<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';

// INPUT
// If accessing directly the profile page there is no input to the page.
// All informations come from the $_SESSION variables, like username and id
//
// If a profile of another user is accessed, the username, the userid come also from $_SESSION vars
// but additionally there come the $userid from the accessed profile page plus the users name in $profile

// SELECT `userid`, `datetime` FROM `login_time`
function findLoginTime($userid, $mysqli) {
	$sql = sprintf("SELECT `userid`, `datetime` FROM `login_time` WHERE `userid` = %d LIMIT 0, 1 ", $userid);
	return $mysqli->query($sql);
}

// SELECT `id` FROM `members`
function findUserId($username, $mysqli) {
	$sql = sprintf("SELECT `id` FROM `members` WHERE `username` = '%s' LIMIT 0, 30 ", $username);
	return $mysqli->query($sql)->fetch_array()[0];
}

// SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles`
function getOwnScribbles($userid, $mysqli)
{
	$sql = sprintf("SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` WHERE (`userid` = %d) ORDER BY `scribbles`.`creation` ASC LIMIT 0, 10 ", $userid);
	$result = $mysqli->query($sql);
	return $result;
}

// SELECT `favid`, `userid`, `scribbleid`, `scribbles`.`datetime`, `path` FROM `favorites`
function getFavScribbles($userid, $mysqli)
{
	$sql = sprintf("SELECT `scribbles`.`scribbleid`, `favorites`.`favid`, `members`.`username`, `scribbles`.`creation`, `scribbles`.`path` FROM `favorites`, `scribbles`, `members` WHERE `scribbles`.`userid` = `members`.`id` AND `favorites`.`userid` = %d AND `scribbles`.`scribbleid` = `favorites`.`scribbleid` ORDER BY `favorites`.`datetime` ASC LIMIT 0, 10 ", $userid);
	$result = $mysqli->query($sql);
	return $result;
}

function getFriendScribbles($userid, $mysqli)
{
	$sql = sprintf("SELECT `scribbles`.`scribbleid`, `scribbles`.`path`, `members`.`username`, `scribbles`.`creation` FROM `friends`, `members`, `scribbles` WHERE `friends`.`userid` = %d AND `scribbles`.`userid` = `friends`.`friendid` AND `members`.`id` = `friends`.`friendid` ORDER BY `scribbles`.`creation` ASC LIMIT 0,10 ", $userid );
	$result = $mysqli->query($sql);
	return $result;
}

function areFriends($userid, $friendid, $mysqli)
{
	$sql = sprintf("SELECT `relationid`, `userid`, `friendid`, `datetime` FROM `friends` WHERE `userid` = %d AND `friendid` = %d LIMIT 0, 1 ", $userid, $friendid);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		return true;
	} else {
		return false;
	}
}

function friendsSince($userid, $friendid, $mysqli)
{
	$sql = sprintf("SELECT `friends`.`datetime` FROM `friends` WHERE `friends`.`userid` = %d AND `friends`.`friendid` = %d LIMIT 0, 1", $userid, $friendid);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		return $result->fetch_array()[0];
	}
	return "ERROR";
}

?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/header.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/profile.css">
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<title>Scribbit - Profile</title>
		<script type="text/javascript">

		var friends = {};
		var favorites = {};

		var ownPath = {};
		var ownDates = {};
		var favPath = {};
		var favDates = {};
		var favNames = {};
		var friendPath = {};
		var friendDates = {};
		var friendNames = {};

		<?php 
		echo "var userid = ".$_SESSION['user_id'].";".PHP_EOL; 
		echo "var path = '".path."';".PHP_EOL; 
		echo "var root = '".root."';".PHP_EOL; 
		?>

		function loadSingleItem (id, username, date, imgpath) {
		
			var profitem = document.createElement('div');
			profitem.setAttribute('class','profitem');
			profitem.setAttribute('style', 'background-image: url(' + root+imgpath + '); background-size: 100% 100%;');


			var temp = document.createElement('div');
			temp.setAttribute('class', 'initem');
			temp.setAttribute('id', 'div_'+id);
			temp.innerHTML = '<span><a href="'+path+'/'+username+'">'+ username +'</a> '+'</span>'+
			'<br><span style="font-size: 0.6em">'+date+'</span>'+
			'<span style="float:right"><a href="'+path+'/scribbles/'+id+
			'"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16"></a>'
			+'</span>';

			profitem.appendChild(temp);
			return profitem;
		}

		function loadFavs (content) {
			content.appendChild(document.createElement('br'));
			var profitem;

			var horizontalbar = document.createElement('div');
			horizontalbar.setAttribute('class', 'horizontalbar');
			content.appendChild(horizontalbar);

			var holder = document.createElement('div');
			holder.setAttribute('class', 'holder');
			horizontalbar.appendChild(holder);
			
			for (var k in favPath) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (favPath.hasOwnProperty(k)) {

					profitem = loadSingleItem(k, favNames[k], favDates[k], favPath[k]);

					holder.appendChild(profitem);
				}
			}
		}

		function loadOwn (content) {

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
					profitem = loadSingleItem(k, '', ownDates[k], ownPath[k]);
					holder.appendChild(profitem);
				}
			}
		}

		function loadFriends (content) {
			content.appendChild(document.createElement('br'));

			var horizontalbar = document.createElement('div');
			horizontalbar.setAttribute('class', 'horizontalbar');
			content.appendChild(horizontalbar);

			var holder = document.createElement('div');
			holder.setAttribute('class', 'holder');
			horizontalbar.appendChild(holder);

			var profitem;
			
			for (var k in friendPath) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (friendPath.hasOwnProperty(k)) {
					profitem = loadSingleItem(k, friendNames[k], friendDates[k], friendPath[k]);
					holder.appendChild(profitem);
				}
			}
		}

		function loadScribbles () {

			<?php 

				function fillFavs ($result) {
					while ($row = $result->fetch_array()) {
						echo 'favPath['.$row[0]."] = '".$row[4]."';";
						echo 'favDates['.$row[0]."] = '".($row[3])."';";
						echo 'favNames['.$row[0]."] = '".$row[2]."';".PHP_EOL;
					}
					echo 'hasFavs = true;'.PHP_EOL;
				}

				function fillFriends ($result) {
					while ($row = $result->fetch_array()) {
						echo 'friendPath['.$row[0]."] = '".$row[1]."';";
						echo 'friendDates['.$row[0]."] = '".($row[3])."';";
						echo 'friendNames['.$row[0]."] = '".($row[2])."';".PHP_EOL;
					}
					echo 'hasFriends = true;'.PHP_EOL;
				}

				if (!$loggedIn) {
					exit();
				}
				echo 'var hasFavs = false; var hasFriends = false;'.PHP_EOL;
				if (isset($viewProfile) && isset($profile)) {
					$result = getOwnScribbles($userid, $mysqli);
				} else {
					$result = getOwnScribbles($_SESSION['user_id'], $mysqli);
				}
				while ($row = $result->fetch_array()) {
					echo 'ownPath['.$row[0]."] = '".$row[1]."';";
					echo 'ownDates['.$row[0]."] = '".($row[3])."';".PHP_EOL;
				}
				if (isset($viewProfile) && $viewProfile && 
					areFriends($_SESSION['user_id'], $userid, $mysqli)) {
					// friends are looking
					$result = getFavScribbles($userid, $mysqli);
					fillFavs($result);
				} else {
					$userid = $_SESSION['user_id'];
					// the user itself is looking
					$result = getFavScribbles($userid, $mysqli);
					fillFavs($result);
				}
				if (isset($viewProfile) && $viewProfile && areFriends($_SESSION['user_id'], $userid, $mysqli)) {
					// friends are looking
					$result = getFriendScribbles($userid, $mysqli);
					fillFriends($result);
				} else {
					$userid = $_SESSION['user_id'];
					// the user itself is looking
					$result = getFriendScribbles($userid, $mysqli);
					fillFriends($result);
				}
				

			?>

			var content = document.getElementById('content');
			if (hasFriends) {
				loadFriends(content);
			}	
			if (hasFavs) {
				loadFavs(content);
			}
			loadOwn(content);

			content.appendChild(document.createElement('br'));
		}

		// Add/Remove Friend
		function addFriend(friendid) {
			var xmlhttp;

			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					//favToggle(scribbleid); 
					//on success
					var friends = document.getElementById('addFriend');
					var parent = document.getElementById('friends');
					parent.removeChild(friends);
					var remove = document.createElement('div');
					//'<div id="removeFriend" onclick="removeFriend('.$userid.');">Remove Friend</div>';
					remove.setAttribute('id', 'removeFriend');
					remove.setAttribute('onclick', 'removeFriend('+friendid+')');
					remove.innerHTML = "Remove Friend";
					parent.appendChild(remove);
					
				}
			}
			xmlhttp.open("POST",path+"/add_remove_friend.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("friendid="+friendid+"&add=1");
		}

		function removeFriend(friendid) {
			var xmlhttp;

			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					//favToggle(scribbleid);
					//on success
					// document.reload();
					var friends = document.getElementById('removeFriend');
					var parent = document.getElementById('friends');
					parent.removeChild(friends);
					var remove = document.createElement('div');
					//'<div id="removeFriend" onclick="removeFriend('.$userid.');">Remove Friend</div>';
					remove.setAttribute('id', 'addFriend');
					remove.setAttribute('onclick', 'addFriend('+friendid+')');
					remove.innerHTML = "Add Friend";
					parent.appendChild(remove);
				}
			}
			xmlhttp.open("POST",path+"/add_remove_friend.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("friendid="+friendid+"&remove=1");
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

				if (isset($viewProfile) && isset($profile)) {
					$row = findLoginTime($userid, $mysqli);
					echo $profile."<br>";
					echo "Last Login: ".$row->fetch_array()[1].'<br><div id="friends">';
					if (areFriends($_SESSION['user_id'], $userid, $mysqli)) {
						echo '<br>friends since '.friendsSince($_SESSION['user_id'], $userid, $mysqli).'<br><br>';
						echo '<div id="removeFriend" onclick="removeFriend('.$userid.');">Remove Friend</div>';
					} else {
						echo '<br>not friends yet<br><br>';
						echo '<div id="addFriend" onclick="addFriend('.$userid.');">Add Friend</div>';
					}
					echo "</div>";
				} else { 
					$row = findLoginTime($_SESSION['user_id'], $mysqli);
					echo $_SESSION['username']."<br>";
					echo "Last Login: ".$row->fetch_array()[1]."<br>";
				}

				?>
			</div>
			<div id="content">

			</div>
		</div>
	</body>
</html>