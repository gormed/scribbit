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
		<script type="text/javascript" src="ressources/js/socials.js"></script>
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
		var commentCount = {};

		var canvasPos = {x:0.0, y:0.0};
		var canvasSize = {width:960, height:640};
		var lastX = 0.0;
		var lastY = 0.0;
		var capturing = false;
		var scribbleCount = 0;

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

		function init () {
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

					$sql = sprintf("SELECT `commentid`, `scribbleid` FROM `comments` WHERE `scribbleid` = %d", $row[0]);
					$commentcount = $mysqli->query($sql)->num_rows;
					echo 'commentCount['.$row[0]."] = ".$commentcount.";".PHP_EOL;
				}
			?>

			sort(scribbles, function(k) {
				createScribbleDiv(k);
			});
			adjustContent();
			//sorted = sortArrayByKeys(scribbles);
			//scribbles.reverse();

			// for (var k in sorted) {
			// 	// use hasOwnProperty to filter out keys from the Object.prototype
			// 	if (sorted.hasOwnProperty(k)) {
			// 		createScribbleDiv(k);
			// 	}
			// }
		}



		function createScribbleDiv(k){
				var gallery = document.getElementById('content');
				//gallery.appendChild(document.createElement('br'));
				var element;
				var temp;
				var img;
				var link;

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
				if (parseInt(favorites[k]) == 1) {
					fav = '<a href="#unfav"><img id="fav_'+k+'" src="'+path+'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('+k+');">';
				} else {
					fav = '<a href="#fav"><img id="fav_'+k+'" src="'+path+'/ressources/img/ico/unstar.png" width="16" height="16" onclick="favImage('+k+');">';
				}
				temp.innerHTML = '<span><a href="'+path+'/'+users[k]+'">'+ users[k] +'</a> '+'</span>'+
				'<br><span style="font-size: 0.6em">'+dates[k]+'</span>'+
				'<span style="float:right"><a href="'+path+'/scribbles/'+k+'#comments"><img src="'+path+'/ressources/img/ico/comment.png" width="16" height="16">'+commentCount[k]+'</a>'
				+fav+'<span id="count_'+k+'">'+favCount[k]+'</a></span></span>';

				element.appendChild(temp);
				gallery.appendChild(link);
				scribbleCount++;

				// gallery.appendChild(document.createElement('br'));
				// gallery.appendChild(document.createElement('br'));
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

		$(window).scroll(function()
		{
			if($(window).scrollTop() == $(document).height() - $(window).height())
			{
				//$('div#loadingImage').show();
				$.ajax({
					type: "POST",
					url: "galleryAjaxRequest.php",
					data: {	skipCount: scribbleCount, 
							rowCount: 40
						},
					success: function(data){

							var json = $.parseJSON(data);
							console.log(json);
							if($.isEmptyObject(json.temp_scribbles)){
								// show end 
							}
							else {
								sort(json.temp_scribbles, function(k) {
									scribbles[k] = json.temp_scribbles[k];
									dates[k] =json.temp_dates[k];
									users[k] = json.temp_users[k];
									favorites[k] = json.temp_favorites[k];
									favCount[k] = json.temp_favCount[k];
									commentCount[k] = json.temp_commentCount[k];
									createScribbleDiv(k);
								});
							}
						}
					});
			}
		})
		</script>
	</head>

	<body onload="init()">
			<div id="site">
				
				<div id="header">
					<?php include docroot.'/'.path.'/topnav.php'; ?>
				</div>

				<div id="content">

				</div>
			</div>
	</body>
</html>