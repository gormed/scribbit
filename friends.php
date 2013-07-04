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
		var friends = {};
		
		function loadSingleFriend(id, name, date, imgpath) {
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

		function loadFriends() {
			<?php 
				$userid = $_SESSION['user_id'];
				if (!$loggedIn) {
					exit();
				}
				$sql = sprintf("SELECT `friends`.`relationid`, `members`.`username`, `friends`.`friendid`, `friends`.`datetime` FROM `friends`, `members` WHERE `friends`.`userid` = %d AND `friends`.`friendid` = `members`.`id` ORDER BY `datetime` DESC", $userid);

				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'friends['.$row[0]."] = '".$row[1]."';";
					echo 'dates['.$row[0]."] = '".($row[3])."';";

					$sql = sprintf("SELECT `path` FROM `scribbles` WHERE (userid = %d) LIMIT 1", $row[2]);
					$answer = $mysqli->query($sql);
					$scribble = $answer->fetch_array();
					echo 'scribbles['.$row[0]."] = '".$scribble[0]."';";
				}
			?>

			var gallery = document.getElementById('content');
			gallery.appendChild(document.createElement('br'));
			var element;
			var temp;
			var img;

			for (var k in friends) {
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (friends.hasOwnProperty(k)) {

					var element = loadSingleFriend(k, friends[k], dates[k], scribbles[k]);
					gallery.appendChild(element);
				}
			}

			adjustContent();
			
			gallery.appendChild(document.createElement('br'));
			gallery.appendChild(document.createElement('br'));
		}

		function adjustContent() {
			var windowwidth = $(window).width();
			var itemwidth = parseInt($(".item").css("width"));
			var itemmargin = parseInt($(".item").css("margin-left"));
			var items = Math.floor(windowwidth / (itemwidth+itemmargin));
			var itemsize = items * itemwidth + (items) * itemmargin;
			var margin = Math.floor((windowwidth - itemsize) * 0.5);
			$("#content").css({'margin-left': margin+"px"});
		}

		$(window).resize( function() { adjustContent(); } );

		</script>
	</head>

	<body onload="loadFriends();">
		<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>
			</div>

			<div id="content">

			</div>
		</div>
	</body>
</html>