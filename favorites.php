<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
 ?>
<html>
	<head>

		<?php require_once 'intern_header.php'; ?>
		<link rel="stylesheet" type="text/css" href="ressources/css/gallery.css">
		<title>Scribbit - Friends</title>
		
	</head>

	<body onload="loadFavorites();">
			<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>
				</div>

				<div id="content">

				</div>
			</div>
	</body>
</html>

<?php require_once 'intern_scripts.php'; ?>
<script type="text/javascript">
		<?php 
		echo "var path = '".path."';";
		echo "var root = '".root."';"; 
		?>
		var scribbles = {};
		var dates = {};
		var favorites = {};

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

		function loadSingleFav(id, name, date, imgpath) {
			var element = document.createElement('div');
			element.setAttribute('class','item');
			element.setAttribute('style', 'background-image: url("' + root+'/scribbles/lm/'+imgpath + '"); background-size: 100% 100%;');

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

			sort(favorites, function(k) {
				var gallery = document.getElementById('content');
				var element = loadSingleFav(k, favorites[k], dates[k], scribbles[k]);
				gallery.appendChild(element);
			})

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