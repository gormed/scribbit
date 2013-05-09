<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<!-- <?php echo $scribblepath.' '.$fromuser; ?> -->
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/header.css">'; ?>
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/view.css">'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/comment.js"></script>'; ?>
		<title>Scribbit - View</title>
		<style type="text/css">
			div#picture {
			<?php echo 'background: url('.root.'/scribbles/h/'.$scribblepath.'); '; ?>
				box-shadow: 0px 1px 10px #333;
				
				display: table-cell;
				/*border: 1px solid black;*/
				text-align: center;
				vertical-align: top;
				height: 640px; width: 960px;
				background-size: 100% 100%;
				background-color: white;
			}
		</style>
		
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

			function showComments() {
				var comments = document.getElementById('comments');
				var content = document.getElementById('content');

				if(comments.className == "hidden") {
					comments.className = "visible";

					// var canvas = document.getElementById('canvas');
					// canvas.setAttribute('id', 'canvasbug');
					//content.className = "contentspace";
				} else {
					comments.className = "hidden";
					// var canvas = document.getElementById('canvasbug');
					// canvas.setAttribute('id', 'canvas');
					//canvas.setAttribute('class', '');
					//content.className = "contentnospace";
				}
			}
			//************************************************************************
			function saveComment () {
				
				var xmlhttp;
				var canvas = document.getElementById('canvas');
				var img = canvas.toDataURL("image/png");
				document.getElementById("upload").innerHTML="Sending...";

				if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} else {// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
						location.reload();
						//document.getElementById("upload").innerHTML="Sent!" + "\n" + xmlhttp.responseText;
					}
				}
				document.getElementById("upload").innerHTML="Sending...";
				xmlhttp.open("POST",path+"/upload_comment.php",true);
				xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xmlhttp.send("data=" + img + "&scribbleid=" + scribbleid);
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

	?>
	<body onload="onLoad();">
		<div id="site">
			<div id="header">
				<div id="logo">
					<?php
					echo '<a href="'.path.'/"><</a>';
					?>
				</div>
				<?php include docroot.'/'.path.'/topnav.php'; ?>	
			</div>
			<div id="content">
				<div class="table" >
					<div class="row">
						<div class="cell"></div>
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 0, 1)) {
								$where = 0;
								echo '<div class="paint" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr, $ycurr+1);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="topneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>
						<div class="cell"></div>
					</div>
					<div class="row" id="wrapper">
						
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, -1, 0)) {
								$where = 3;
								echo '<div class="paint" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr-1, $ycurr);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="leftneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						
						</div>
						
						<div id="picture">
							<?php echo '<div id="from">'.$fromname." | ".$fromdate.'</div>'; ?>
						</div>

						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 1, 0)) {
								$where = 1;
								echo '<div class="paint" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr+1, $ycurr);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="rightneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>

					</div>
					<div class="row">
						<div class="cell"></div>
						<div class="cell">
							<?php
							if(!hasNeighbour($mysqli, $xcurr, $ycurr, 0, -1)) {
								$where = 2;
								echo '<div class="paint" onclick="submitWhere('.$where.');">(paint here)</div>';
							} else {
								// `map`.`scribbleid`, `scribbles`.`path`, `scribbles`.`creation`, `members`.`username`
								$neighbour = getNeightbour($mysqli, $xcurr, $ycurr-1);
								echo '<a href="'.path.'/scribbles/'.$neighbour[0].'"><div id="bottomneightbour" onclick="gotoNeighbour('.$neighbour[0].');" style="background-image: url('.root.'/scribbles/h/'.$neighbour[1].')"></div></a>';

							}
							?>
						</div>
						<div class="cell"></div>
					</div>
				</div>
				<a href="#comments" id="commentslink"><hr width=50% id="bar" size=1><div id="showcomments" onclick="showComments();">↓ Comments ↓</div></a>
				<div id="comments" class="hidden">
					<!-- 
					***************************************************************** 
					 Embed the Wacom TabletPlugin object.
					 To avoid plugin selection on page, size and position are adjusted 
					 so as to "tuck it under" canvas. 
					***************************************************************** 
					-->

					<!--[if IE]>

					<object id='wtPlugin' classid='CLSID:092dfa86-5807-5a94-bf3b-5a53ba9e5308' WIDTH=1 HEIGHT=1 style="position:absolute; left:100px; top:100px">
					</object>

					<![endif]--><!--[if !IE]> <-->
					<object id="wtPlugin" type="application/x-wacomtabletplugin" WIDTH=1 HEIGHT=1 style="position:absolute; left:20px; top:20px">
						<!-- <param name="onload" value="pluginLoaded" /> -->
					</object>
				
					<canvas id="canvas" width="150" height="150" onmousedown="mousedown(event);" onmouseup="mouseup();" onmousemove="mousemove(); " > </canvas>
					<div id="makecomment">
						<a href="#submit"><div tag="submit" id="submitcomment" onclick="saveComment();">Submit <div id="upload"></div></div></a>
						<a href="#clear"><div tag="clear" id="clear" onclick="clearCanvas();">Clear</div></a>
					</div>
					<div id="commentlist">
						<div id="commentholder">
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</body>
</html>