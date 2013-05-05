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




	
			function addElement(ni) {
				var numi = document.getElementById('theValue');
				var num = (document.getElementById('theValue').value -1)+ 2;

				numi.value = num;
				var newdiv = document.createElement('div');
				var divIdName = 'wall'+num+'Div';

				newdiv.setAttribute('id',divIdName);
				newdiv.innerHTML = 'Element Number '+num+' has been added! <a href=\'#\' onclick=\'removeElement('+divIdName+')\'>Remove the div "'+divIdName+'"</a>';
				ni.appendChild(newdiv);
				return newdiv;
			}

			function removeElement(divNum, d) {
				var olddiv = document.getElementById(divNum);
				d.removeChild(olddiv);
			}

		// function loadScribbles () {




			// for (var k in scribbles) {
			// 	// use hasOwnProperty to filter out keys from the Object.prototype
			// 	if (scribbles.hasOwnProperty(k)) {
			// 		// temp = document.createElement('div');
			// 		// temp.setAttribute('class', 'cell');
			// 		// temp.innerHTML = '<a href="'+path+'/view"><img src="'+path+'/'+scribbles[k]+'"></a><br>';
			// 		// row.appendChild(temp);
			// 		//<?php echo '<div class="cell"><a href="'.path.'/view"><img src="ressources/img/template.gif"></a></div>' ?>
			// 		//alert('key is: ' + k + ', value is: ' + scribbles[k]);

			// 		var graphic = new OpenLayers.Layer.Image(
			// 		'scribble_'+k+'',
			// 		''+path+'/'+scribbles[k]+'',
			// 		new OpenLayers.Bounds(-200, -150, 200, 150),
			// 		new OpenLayers.Size(40, 30),
			// 		{   numZoomLevels: 3,      
			// 			isBaseLayer: false,
			// 			opacity: 0.3,
			// 			displayOutsideMaxExtent: true
			// 		 });
			// 		var scribbleLayers[k] = graphic;
			// 	}
			// }



		//  graphic.events.on({
		//      loadstart: function() {
		//          OpenLayers.Console.log("loadstart");
		//      },
		//      loadend: function() {
		//          OpenLayers.Console.log("loadend");
		//      }
		//  });


			// var points = new OpenLayers.Layer.PointGrid({
			// 	isBaseLayer: false, dx: 15, dy: 15
			// });


			// var graphic = new OpenLayers.Layer.Image( 
			// 'City Lights', 
			// ''+path+'/'+scribbles[1367270694]+'', 
			// new OpenLayers.Bounds(-200, -150, 200, 150), 
			// new OpenLayers.Size(40, 30), isBaseLayer: false
			// ); 

			// var graphic2 = new OpenLayers.Layer.Image( 
			// 'City Lights2', 
			// ''+path+'/'+scribbles[1367270694]+'', 
			// new OpenLayers.Bounds(-100, -150, 200, 150), 
			// new OpenLayers.Size(400, 300), isBaseLayer: false
			// ); 

			// for (var k in scribbles) {
			// 	// use hasOwnProperty to filter out keys from the Object.prototype
			// 	if (scribbles.hasOwnProperty(k)) {
					
					// var graphic = new OpenLayers.Layer.Image(
					// 'scribble_',
					// ''+path+'/'+scribbles[1367270694]+'',
					// new OpenLayers.Bounds(-200, -150, 200, 150),
					// new OpenLayers.Size(40, 30),
					// {isBaseLayer: false}
					// );
					// scribbleLayers[k] = graphic;
			// 	}
			// }		
			// var layer = new OpenLayers.Layer.TMS("Name", "http://example.com/", { 'type':'png', 'getURL':get_my_url });
			
			// var map = new OpenLayers.Map({
			// 	div: "map",
			// 	layers: [graphic],
			// 	center: new OpenLayers.LonLat(0, 0),
			// 	zoom: 1
			// });

			// extent = new OpenLayers.Bounds(1197980.141, 8101143.032, 1244615.311, 8198123.317); 
			// map.zoomToExtent(extent); 

			


			// maxExtent: new OpenLayers.Bounds(-20037508.3427892,-20037508.3427892,20037508.3427892,20037508.3427892), 
			// numZoomLevels:18, 
			// maxResolution:156543.0339, 
			// units:'m', 
			// projection: "EPSG:900913",
			// displayProjection: new OpenLayers.Projection("EPSG:4326")

			// var lonLat = new OpenLayers.LonLat(-100, 40) ;
			// lonLat.transform(map.displayProjection,map.getProjectionObject());
			// map.setCenter(lonLat, 5);
 
			// }
			

	// function get_my_url (bounds) {
	// 	var res = this.map.getResolution();
	// 	var x = Math.round ((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
	// 	var y = Math.round ((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
	// 	var z = this.map.getZoom();

	// 	<?php
	// 			$sql = "SELECT `tileid`, `AsText(position)` FROM `map`LIMIT 0, 40  ";
	// 			$result = $mysqli->query($sql);
	// 			while ($row = $result->fetch_array(MYSQLI_NUM)) {
	// 			echo 'points['.$row[0]."] = '".$row[1]."';";
	// 		}
	// 	?>

	// 	var path = z + "/" + x + "/" + y + "." + this.type; 
	// 	var url = this.url;
	// 	if (url instanceof Array) {
	// 		url = this.selectUrl(path, url);
	// 	}
	// 	return url + path;

	// }


function map_init() {

	<?php 

	if (!$loggedIn) {
	exit();
	}
		echo "var path = '".root."';";
		$sql = "SELECT `scribbleid`, `path` FROM `scribbles` LIMIT 0, 40 ";
		$result = $mysqli->query($sql);
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo 'scribbles['.$row[0]."] = '".$row[1]."';";
	}
	?>
	var points = new OpenLayers.Layer.PointGrid({
		isBaseLayer: true, dx: 15, dy: 15
	});
	var graphic = new OpenLayers.Layer.Image(
									'scribble_',
									''+path+'/'+scribbles[1367270694]+'',
									new OpenLayers.Bounds(-20, -15, 20, 15),
									new OpenLayers.Size(40, 30),
									{isBaseLayer: false}
									);

	var map = new OpenLayers.Map({
		div: "map",
		layers:[points, graphic],
		center: new OpenLayers.LonLat(0, 0),
		zoom: 1
	});
}
	


		</script>

		<title>Scribbit - Wall</title>
	</head>

<body onload="map_init()"> 
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