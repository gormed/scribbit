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


		var scribbles = {};
		var scribbleLayers = {};

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

		function loadScribbles () {

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
		// 	// var wall = document.getElementById('wall');
		// 	// var row = document.createElement('div');
		// 	// row.setAttribute('class','row');
		// 	// wall.appendChild(row);
		// 	// var temp;

		// 	for (var k in scribbles) {
		// 		// use hasOwnProperty to filter out keys from the Object.prototype
		// 		if (scribbles.hasOwnProperty(k)) {
		// 			// temp = document.createElement('div');
		// 			// temp.setAttribute('class', 'cell');
		// 			// temp.innerHTML = '<a href="'+path+'/view"><img src="'+path+'/'+scribbles[k]+'"></a><br>';
		// 			// row.appendChild(temp);
		// 			//<?php echo '<div class="cell"><a href="'.path.'/view"><img src="ressources/img/template.gif"></a></div>' ?>
		// 			//alert('key is: ' + k + ', value is: ' + scribbles[k]);

		// 			var graphic = new OpenLayers.Layer.Image(
		// 			'scribble_'+k+'',
		// 			''+path+'/'+scribbles[k]+'',
		// 			new OpenLayers.Bounds(-200, -150, 200, 150),
		// 			new OpenLayers.Size(400, 300),
		// 			{numZoomLevels: 3}
		// 			);
		// 			var scribbleLayers[k] = graphic;
		// 		}
		// 	}



		// 	graphic.events.on({
		// 		loadstart: function() {
		// 			OpenLayers.Console.log("loadstart");
		// 		},
		// 		loadend: function() {
		// 			OpenLayers.Console.log("loadend");
		// 		}
		// 	});



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

<body onload="loadScribbles();"> 
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
					<div id="map" class="smallmap"></div>
				</div>
				

			</div>
				
	</body>
</html>