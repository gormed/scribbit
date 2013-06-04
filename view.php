<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<!-- <?php echo $scribblepath.' '.$fromuser; ?> -->
<?php 
	$sql = sprintf("SELECT X(`position`), Y(`position`), `parentid` FROM `map` WHERE `scribbleid` = %d LIMIT 0, 1", $scribbleid);
	$result = $mysqli->query($sql)->fetch_array();

	$xcurr = $result[0];
	$ycurr = $result[1];
	$parentid = $result[2];

	function hasNeighbour($mysqli, $xcurr, $ycurr, $x, $y)
	{
		$sql = sprintf("SELECT `scribbleid`, `parentid` FROM `map` WHERE X(`position`) = %d AND Y(`position`) = %d LIMIT 0, 1 ", ($xcurr + $x), ($ycurr + $y));
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	function getNeightbour($mysqli, $xcurr, $ycurr)
	{
		$sql = sprintf("SELECT `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username` FROM `map`, `scribbles`, `members` WHERE X(`position`) = %d AND Y(`position`) = %d AND `scribbles`.`scribbleid` = `map`.`scribbleid` AND `scribbles`.`userid` = `members`.`id` LIMIT 0, 1 ", $xcurr, $ycurr);
		$result = $mysqli->query($sql)->fetch_array();
		return $result;
	}

	function getFavoriteCount($mysqli, $scribbleid)
	{
		$sql = sprintf("SELECT `favid`, `userid`, `scribbleid` FROM `favorites` WHERE `scribbleid` = %d ", $scribbleid);
		return $mysqli->query($sql)->num_rows;
	}

	function getCommentCount($mysqli, $scribbleid)
	{
		$sql = sprintf("SELECT `commentid`, `scribbleid` FROM `comments` WHERE `scribbleid` = %d", $scribbleid);
		return $mysqli->query($sql)->num_rows;
	}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/headerSearch.css">'; ?>
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/view.css">'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/comment.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/socials.js"></script>'; ?>
		<title>Scribbit - View</title>

		
		<!-- SCRIPT -->

		<script type="text/javascript">

			<?php 
			echo "var scribbleid = ".$scribbleid.";".PHP_EOL; 		
			echo "var path = '".path."';".PHP_EOL; 
			echo "var root = '".root."';".PHP_EOL; 
			?>

			var comments = {};
			var dates = {};
			var usernames = {};
			var scribbles = {};
			var scribDates = {};
			var users = {};
			var commentCount = {};
			var positionsx = {};
			var positionsy = {};
			var map = {};

			// var favNames = {};
			var favCount = {};
			var favorites = {};

			function loadScribbles (argument) {
				<?php
				$bl = ($xcurr-1.5).' '.($ycurr-1.5);
				$tl = ($xcurr-1.5).' '.($ycurr+1.5);
				$tr = ($xcurr+1.5).' '.($ycurr+1.5);
				$br = ($xcurr+1.5).' '.($ycurr-1.5);

				$sql = sprintf("SELECT `scribbles`.`scribbleid`, `scribbles`.`path`, `scribbles`.`userid`, `scribbles`.`creation`, X(`map`.`position`), Y(`map`.`position`) FROM `scribbles`, `map` WHERE `scribbles`.`scribbleid` = `map`.`scribbleid` AND MBRContains(GeomFromText('Polygon((%s, %s, %s, %s, %s))'), `map`.`position`) = 1 LIMIT 0, 9 ", $bl, $tl, $tr, $br, $bl);
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'scribbles['.$row[0]."] = '/scribbles/h/".$row[1]."'; ";
					echo 'scribDates['.$row[0]."] = '".($row[3])."'; ";
					echo 'positionsx['.$row[0]."] = '".($row[4])."'; ";
					echo 'positionsy['.$row[0]."] = '".($row[5])."'; ";
					echo 'map['.$row[4].$row[5]."] = '".$row[0]."'; ";

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
				
			}

			function loadComments() {
				<?php 

				$sql = sprintf("SELECT `comments`.`commentid`, `members`.`username`, `comments`.`datetime`, `comments`.`path` FROM `comments`, `members` WHERE `comments`.`scribbleid` = %d AND `comments`.`userid` = `members`.`id` ORDER BY `datetime` DESC LIMIT 0, 40", $scribbleid);
				$result = $mysqli->query($sql);
				echo 'var commentCount = '.$result->num_rows.';'.PHP_EOL;
				while ($row = $result->fetch_array()) {
					echo 'comments['.$row[0]."] = '".$row[3]."';";
					echo 'dates['.$row[0]."] = '".$row[2]."';";
					echo 'usernames['.$row[0]."] = '".$row[1]."';".PHP_EOL;
				}
				
				?>
				var commentlist = document.getElementById('commentholder');
				commentlist.setAttribute('style', 'width: '+((180*commentCount)+40)+'px;')
				commentlist.appendChild(document.createElement('br'));
				var element;

				for (var k in comments) {
					// use hasOwnProperty to filter out keys from the Object.prototype
					if (comments.hasOwnProperty(k)) {
						element = document.createElement('div');
						element.setAttribute('class','item');
						element.setAttribute('style', 'background-image: url("' + root+comments[k] + '"); background-size: 100% 100%;');
						
						temp = document.createElement('div');
						temp.setAttribute('class', 'initem');
						temp.setAttribute('id', 'div_'+k);
						temp.innerHTML = '<span><a href="'+path+'/'+usernames[k]+'">'+ usernames[k] +'</a> '+'</span>'+
						'<br><span style="font-size: 0.6em">'+dates[k]+'</span>';

						element.appendChild(temp); 
						commentlist.appendChild(element);
					}
				}
			}

			// 0 topScrib;
			// 1 bottomScrib;
			// 2 leftScrib;
			// 3 rightScrib;
			var neighbours = {};

			function loadNeighbours() {
				
			}

			function submitWhere(where) {

				var input = document.getElementById('where'); 
				input.value = where;
				var form = document.getElementById('postscribble'); 
				form.submit();
			}

			function onLoad () {
				loadCanvas();
				loadComments();
				loadScribbles();

				var form = document.getElementById('postscribble'); 
				var p = document.createElement("input");

				// Add the new element to our form.
				form.appendChild(p);
				p.id = "where";
				p.name ="where";
				p.type = "hidden"
				p.value = "0";
				
				p = document.createElement("input");

				// Add the new element to our form.
				form.appendChild(p);
				p.id = "parentid";
				p.type = "hidden";
				p.name = "parentid";
				<?php echo 'p.value = "'.$scribbleid.'";'; ?>
			}
		</script>
	</head>

	<?php 
		echo '<form action="'.path.'/scribble" method="post" id="postscribble"></form>';
	?>
	<body onload="onLoad();">
		<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>	
			</div>
			<div id="content">
				<div class="table" >
					<div class="row">
						<div class="corner"></div>
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 0, 1)) {
								$where = 0;
								echo '<div id="painthorz" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr, $ycurr+1);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="topneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>
						<div class="corner"></div>
					</div>
					<div class="row" id="wrapper">
						
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, -1, 0)) {
								$where = 3;
								echo '<div id="paintvert" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr-1, $ycurr);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="leftneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						
						</div>
						<style type="text/css">
							div#picture {
							<?php echo 'background: url('.root.'/scribbles/h/'.$scribblepath.'); '; ?>
								box-shadow: 0px 0px 10px #333;
								float: left;
								/*border: 1px solid black;*/
								height: 640px; width: 960px;
								background-size: 100% 100%;
								background-color: white;
								z-index: 100; 
							}
						</style>
						<div id="picture">
							<?php 
								$viewFavCount = getFavoriteCount($mysqli, $scribbleid);
								$viewCmtCount = getCommentCount($mysqli, $scribbleid);
								$userlink = '<a href="'.path.'/'.$fromname.'">'.$fromname.'</a> <br> ';
								$imgdate = $fromdate.'<br>';
								$favs = '<a href="#unfav"><img id="fav_'.$scribbleid.'" src="'.path.
								'/ressources/img/ico/star.png" width="16" height="16" onclick="favImage('.$scribbleid.');">';

								$icon = '<span style="float: right; margin-right: 40px;"><a href="#comments" onclick="showComments();">'.
								'<img src="'.path.'/ressources/img/ico/comment.png" width="16" height="16">'.$viewCmtCount.'</a>'.$favs.
								'<span id="count_'.$scribbleid.'">'.$viewFavCount.'</a></span>';
								echo '<div id="from">'.$userlink.$imgdate.$icon.'</div>'; 
							?>
						</div>

						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 1, 0)) {
								$where = 1;
								echo '<div id="paintvert" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr+1, $ycurr);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="rightneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>

					</div>
					<div class="row">
						<div class="corner"></div>
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 0, -1)) {
								$where = 2;
								echo '<div id="painthorz" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr, $ycurr-1);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="bottomneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>
						<div class="corner"></div>
					</div>
				</div>
				
				<?php include docroot.'/'.path.'/comment.php'; ?>
			</div>
		</div>
	</body>
</html>