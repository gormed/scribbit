<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">

<style type="text/css">
	#friendstag{
		background-image: url(<?php echo path.'/ressources/img/friends.png'; ?>);
		background-size: 100% 100%;
}

	#myowntag{
		background-image: url(<?php echo path.'/ressources/img/myown.png'; ?>);
		background-size: 100% 100%;
}

	#favouritestag{
		background-image: url(<?php echo path.'/ressources/img/favourites.png'; ?>);
		background-size: 100% 100%;
}

</style>


<?php 
	require_once 'header.php';

	// INPUT
	// If accessing directly the profile page there is no input to the page.
	// All informations come from the $_SESSION variables, like username and id
	//
	// If a profile of another user is accessed, the username, the userid come also from $_SESSION vars
	// but additionally there come the $friendid from the accessed profile page plus the users name in $profile

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
		$sql = sprintf("SELECT `scribbleid`, `path`, `userid`, `creation` FROM `scribbles` WHERE (`userid` = %d) ORDER BY `scribbles`.`creation` DESC LIMIT 0, 10 ", $userid);
		$result = $mysqli->query($sql);
		return $result;
	}

	// SELECT `favid`, `userid`, `scribbleid`, `scribbles`.`datetime`, `path` FROM `favorites`
	function getFavScribbles($userid, $mysqli)
	{
		$sql = sprintf("SELECT `scribbles`.`scribbleid`, `favorites`.`favid`, `members`.`username`, `scribbles`.`creation`, `scribbles`.`path` FROM `favorites`, `scribbles`, `members` WHERE `scribbles`.`userid` = `members`.`id` AND `favorites`.`userid` = %d AND `scribbles`.`scribbleid` = `favorites`.`scribbleid` ORDER BY `favorites`.`datetime` DESC LIMIT 0, 10 ", $userid);
		$result = $mysqli->query($sql);
		return $result;
	}

	function getFriendScribbles($userid, $mysqli)
	{
		$sql = sprintf("SELECT `scribbles`.`scribbleid`, `scribbles`.`path`, `members`.`username`, `scribbles`.`creation` FROM `friends`, `members`, `scribbles` WHERE `friends`.`userid` = %d AND `scribbles`.`userid` = `friends`.`friendid` AND `members`.`id` = `friends`.`friendid` ORDER BY `scribbles`.`creation` DESC LIMIT 0,10 ", $userid );
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

	function daysSince($date)
	{
		$now = time(); // or your date as well
		$your_date = strtotime($date);
		$datediff = abs($now - $your_date);
		return floor($datediff/(60*60*24));
	}

	function getPublicProfile($mysqli, $userid)
	{
		$sql = sprintf("SELECT `userid`, `name`, `email`, `location`, `url` FROM `public_profile` WHERE `userid` = %d LIMIT 0, 1", $userid);
		$res = $mysqli->query($sql);
		return $profile = $res->fetch_array();
	}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="ressources/css/main.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/profile.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
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
				var link = document.createElement('a');
				link.setAttribute('href', path+'/scribbles/'+id);

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
				link.appendChild(profitem);
				profitem.appendChild(temp);
				return link;
			}

			function sort(array, iteratefunc) {
				var arraykeys=[];
				for(var k in array) {
					if (array.hasOwnProperty(k)) { 
						arraykeys.push(k);
					}
				}
				arraykeys.sort();
				arraykeys.reverse();
				for(var i=0; i<arraykeys.length; i++) {
					iteratefunc(arraykeys[i]);
				}
			}

			function loadFavs (content) {
				content.appendChild(document.createElement('br'));
				var profitem;

				var horizontalbar = document.createElement('div');
				horizontalbar.setAttribute('class', 'horizontalbar');
				content.appendChild(horizontalbar);

				var holder = document.createElement('div');
				holder.setAttribute('class', 'holder');
				holder.setAttribute('id', 'favsholder');
				horizontalbar.appendChild(holder);

				sort(favPath, function(k) {
					var holder = document.getElementById('favsholder');
					var profitem = loadSingleItem(k, favNames[k], favDates[k], favPath[k]);
					holder.appendChild(profitem);
				});
			}

			function loadOwn (content) {

				content.appendChild(document.createElement('br'));

				var horizontalbar = document.createElement('div');
				horizontalbar.setAttribute('class', 'horizontalbar');
				content.appendChild(horizontalbar);

				var holder = document.createElement('div');
				holder.setAttribute('class', 'holder');
				holder.setAttribute('id', 'ownholder');
				horizontalbar.appendChild(holder);

				sort(ownPath, function(k) {
					var holder = document.getElementById('ownholder');
					var profitem = loadSingleItem(k, '', ownDates[k], ownPath[k]);
					holder.appendChild(profitem);
				});
			}

			function loadFriends (content) {
				content.appendChild(document.createElement('br'));

				var horizontalbar = document.createElement('div');
				horizontalbar.setAttribute('class', 'horizontalbar');
				content.appendChild(horizontalbar);

				var holder = document.createElement('div');
				holder.setAttribute('class', 'holder');
				holder.setAttribute('id', 'friendsholder');
				horizontalbar.appendChild(holder);

				sort(friendPath, function(k) {
					var holder = document.getElementById('friendsholder');
					var profitem = loadSingleItem(k, friendNames[k], friendDates[k], friendPath[k]);
					holder.appendChild(profitem);
				});
			}

			function loadScribbles () {

				<?php 
					$userid = $_SESSION['user_id'];
					if (!$loggedIn) {
						exit();
					}

					function fillFavs ($result) {
						while ($row = $result->fetch_array()) {
							echo 'favPath['.$row[0]."] = '/scribbles/l/".$row[4]."';";
							echo 'favDates['.$row[0]."] = '".($row[3])."';";
							echo 'favNames['.$row[0]."] = '".$row[2]."';".PHP_EOL;
						}
						echo 'hasFavs = true;'.PHP_EOL;
					}

					function fillFriends ($result) {
						while ($row = $result->fetch_array()) {
							echo 'friendPath['.$row[0]."] = '/scribbles/l/".$row[1]."';";
							echo 'friendDates['.$row[0]."] = '".($row[3])."';";
							echo 'friendNames['.$row[0]."] = '".($row[2])."';".PHP_EOL;
						}
						echo 'hasFriends = true;'.PHP_EOL;
					}

					echo 'var hasFavs = false; var hasFriends = false;'.PHP_EOL;
					if (isset($viewProfile)) {
						$result = getOwnScribbles($friendid, $mysqli);
					} else {
						$result = getOwnScribbles($_SESSION['user_id'], $mysqli);
					}
					while ($row = $result->fetch_array()) {
						echo 'ownPath['.$row[0]."] = '/scribbles/l/".$row[1]."';";
						echo 'ownDates['.$row[0]."] = '".($row[3])."';".PHP_EOL;
					}
					if (isset($viewProfile) && $viewProfile && 
						areFriends($userid, $friendid, $mysqli)) {
						// friends are looking
						$result = getFavScribbles($friendid, $mysqli);
						fillFavs($result);
					} else if (!isset($viewProfile)) {
						// the user itself is looking
						$result = getFavScribbles($userid, $mysqli);
						fillFavs($result);
					}
					if (isset($viewProfile) && $viewProfile && 
						areFriends($userid, $friendid, $mysqli)) {
						// friends are looking
						$result = getFriendScribbles($friendid, $mysqli);
						fillFriends($result);
					} else if (!isset($viewProfile)) {
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

				adjustContent();

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
						var parent = document.getElementById('friendssection');
						parent.removeChild(friends);
						var remove = document.createElement('div');
						//'<div id="removeFriend" onclick="removeFriend('.$friendid.');">Remove Friend</div>';
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
						var parent = document.getElementById('friendssection');
						parent.removeChild(friends);
						var remove = document.createElement('div');
						//'<div id="removeFriend" onclick="removeFriend('.$friendid.');">Remove Friend</div>';
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

			function adjustContent() {
				var windowwidth = $(window).width();
				var profinfo = parseInt($("#profile").css("width")) + 20;
				var profinfomargin = parseInt($("#profile").css("margin-left"));
				var width = Math.floor(windowwidth - (profinfo + profinfomargin));
				$("#content").css({'width': width+"px"});
			}

			$(window).resize( function() { adjustContent(); } );

		</script>
	</head>

	<body onload="loadScribbles();">
		<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>
			</div>

			<div id="friendstag"></div>
			<div id="myowntag"></div>
			<div id="favouritestag"></div>

			<div id="profile">
				<br>
				<div id="profilepic"></div>
				<div id="profilecontent">
				<?php 

				function createPublicProfile($mysqli, $userid)
				{
					$public = getPublicProfile($mysqli, $userid);
					echo '<div id="ppublic">';
					
					if (isset($public[1]) && $public[1] != "")
						echo $public[1].'<br>';
					if (isset($public[2]) && $public[2] != "")
						echo $public[2].'<br>';
					if (isset($public[3]) && $public[3] != "")
						echo $public[3].'<br>';
					if (isset($public[4]) && $public[4] != "") 
						echo '<a href="'.$public[4].'">'.$public[4];
					
					echo '</div><br>';
				}

				if (isset($viewProfile) && isset($profile)) {
					$row = findLoginTime($friendid, $mysqli);
					echo '<div id="pname">'.$profile."</div>";
					echo '<div id="plogin">Last login was ';
					$days = daysSince($row->fetch_array()[1]);
					if ($days > 0)
						echo $days.' days ago';
					else
						echo 'today';
					echo '</div><br>';
					createPublicProfile($mysqli, $friendid);
					echo '<div id="friendssection">';
					if (areFriends($_SESSION['user_id'], $friendid, $mysqli)) {
						echo '<br>friends since:<br> '.friendsSince($userid, $friendid, $mysqli).'<br><br>';
						echo '<div id="removeFriend" onclick="removeFriend('.$friendid.');">Remove Friend</div>';
					} else {
						echo '<br>not friends yet<br><br>';
						echo '<div id="addFriend" onclick="addFriend('.$friendid.');">Add Friend</div>';
					}
					echo "</div>";
				} else { 
					echo '<div id="pname">'.$_SESSION['username'].'</div><br>';
					
					createPublicProfile($mysqli, $_SESSION['user_id']);

				}

				?>
				</div>
			</div>
			<div id="content">

			</div>
		</div>
	</body>
</html>