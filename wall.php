<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
 ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link rel="stylesheet" type="text/css" href="ressources/css/headerSearch.css">		
		<link rel="stylesheet" type="text/css" href="ressources/css/wall.css">
		<link rel="stylesheet" type="text/css" href="ressources/css/openLayersTheme.css">
		<style type="text/css">
		.olControlAttribution {
			left: 5px;
			bottom: 5px; 
		}
		</style>
		<script type="text/javascript" src="ressources/js/jQuery2.js"></script>
		<script type="text/javascript" src="ressources/js/jQueryEvents.js"></script>
		<script type="text/javascript" src="ressources/js/OpenLayers.js"></script>
		<script type="text/javascript">


		// var scribbles = {};

		// function addElement(ni) {
		// 	var numi = document.getElementById('theValue');
		// 	var num = (document.getElementById('theValue').value -1)+ 2;

		// 	numi.value = num;
		// 	var newdiv = document.createElement('div');
		// 	var divIdName = 'wall'+num+'Div';

		// 	newdiv.setAttribute('id',divIdName);
		// 	newdiv.innerHTML = 'Element Number '+num+' has been added! <a href=\'#\' onclick=\'removeElement('+divIdName+')\'>Remove the div "'+divIdName+'"</a>';
		// 	ni.appendChild(newdiv);
		// 	return newdiv;
		// }

		// function removeElement(divNum, d) {
		// 	var olddiv = document.getElementById(divNum);
		// 	d.removeChild(olddiv);
		// }

		// function loadScribbles () {
		// <?php 

		// 		function loadScribbles () {
		// <?php 

		// 	if (!$loggedIn) {
		// 		exit();
		// 	}
		// 	echo "var path = '".path."';";
		// 	$sql = "SELECT `scribbleid`, `path` FROM `scribbles` LIMIT 0, 40 ";
		// 	$result = $mysqli->query($sql);
		// 	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		// 		echo 'scribbles['.$row[0]."] = '".$row[1]."';";
		// 	}
		// ?>
		// 	var wall = document.getElementById('wall');
		// 	var row = document.createElement('div');
		// 	row.setAttribute('class','row');
		// 	wall.appendChild(row);
		// 	var temp;

		// 	for (var k in scribbles) {
		// 		// use hasOwnProperty to filter out keys from the Object.prototype
		// 		if (scribbles.hasOwnProperty(k)) {
		// 			temp = document.createElement('div');
		// 			temp.setAttribute('class', 'cell');
		// 			temp.innerHTML = '<a href="'+path+'/view"><img src="'+path+'/'+scribbles[k]+'"></a><br>';
		// 			row.appendChild(temp);
		// 			//<?php echo '<div class="cell"><a href="'.path.'/view"><img src="ressources/img/template.gif"></a></div>' ?>
		// 			//alert('key is: ' + k + ', value is: ' + scribbles[k]);
		// 		}
		// 	}
		// }


		function loadMap() {
			var points = new OpenLayers.Layer.PointGrid({
				isBaseLayer: true, dx: 15, dy: 15
			});

			var map = new OpenLayers.Map({
				div: "map",
				layers: [points],
				center: new OpenLayers.LonLat(0, 0),
				zoom: 2
			});

			var rotation = document.getElementById("rotation");
			rotation.value = String(points.rotation);
			rotation.onchange = function() {
				points.setRotation(Number(rotation.value));
			};

			var dx = document.getElementById("dx");
			var dy = document.getElementById("dy");
			dx.value = String(points.dx);
			dy.value = String(points.dy);
			dx.onchange = function() {
				points.setSpacing(Number(dx.value), Number(dy.value));
			};
			dy.onchange = function() {
				points.setSpacing(Number(dx.value), Number(dy.value));
			};

			var max = document.getElementById("max");
			max.value = String(points.maxFeatures);
			max.onchange = function() {
				points.setMaxFeatures(Number(max.value));
			};
		}
		</script>

		<title>Scribbit - Wall</title>
	</head>

	<!-- <body onload="loadScribbles();"> -->
	<body onload="loadMap();">
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
					<div class="searchbar" >
						<ul>
							<li><input class="searchtext" type="text" name="searchtext"/></li>
							<br>
							<br>
							<li><input name="filter" type="checkbox" value="favorits"/>Favorits&nbsp;</li>
							<li><input name="filter" type="checkbox" value="friends">Friends&nbsp;</li>		
							<li><input name="filter" type="checkbox" value="my"/>Own&nbsp;</li>	
							<li><select name="timefilter">
								<option value="all">all Time</option> 
								<option value="h24">last 24 h</option> 
								<option value="d7">last 7 days</option>
							</select></li>
						</ul>
					</div>
				</div>
				<div id="content">
					<div id="map" class="smallmap"></div>
				</div>
				

			</div>
				
	</body>
</html>