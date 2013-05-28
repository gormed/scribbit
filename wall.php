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

		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/headerSearch.css">'; ?>
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/wall.css">'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jcanvas.min.js"></script>'; ?>
		<script type="text/javascript">


		var scribbles = {};
		var positionsx = {};
		var positionsy = {};
		var map={};
		
		var temp_scribbles = {};
		var temp_positionsx = {};
		var temp_positionsy = {};
		var temp_map={};

		var canvasPos ={};
		var canvases ={};
		var topY, bottomY, leftX, rightX;
		var mapCells = {};
		// var lastX, curX, lastY, curY;

		<?php 

			if (!$loggedIn) {
			exit();
			}
			else {
				echo "var path = '".path."';".PHP_EOL;
				echo "var root = '".root."';".PHP_EOL; 
				echo "var xpos = ".$xpos.";".PHP_EOL;
				echo "var ypos = ".$ypos.";".PHP_EOL;
				echo "var zoom = ".$zoom.";".PHP_EOL;
				// $sql = "SELECT `scribbleid`, `path` FROM `scribbles` LIMIT 0, 40 ";
				// $result = $mysqli->query($sql);
				// while ($row = $result->fetch_array(MYSQLI_NUM)) {
				// 	echo 'scribbles['.$row[0]."] = '".$row[1]."';";
				// }
				// $sql = "SELECT `scribbleid`, `AsText(position)` FROM `map`LIMIT 0, 40  ";
				// $result = $mysqli->query($sql);
				// while ($row = $result->fetch_array(MYSQLI_NUM)) {
				// 	echo 'points['.$row[0]."] = '".$row[1]."';";
				// }
				$bl = ($xpos-9).' '.($ypos-11);
				$tl = ($xpos-9).' '.($ypos+6);
				$tr = ($xpos+17).' '.($ypos+6);
				$br = ($xpos+17).' '.($ypos-11);

				$sql = sprintf("SELECT `scribbles`.`scribbleid`, `scribbles`.`path`, `scribbles`.`userid`, `scribbles`.`creation`, X(`map`.`position`), Y(`map`.`position`) FROM `scribbles`, `map` WHERE `scribbles`.`scribbleid` = `map`.`scribbleid` AND MBRContains(GeomFromText('Polygon((%s, %s, %s, %s, %s))'), `map`.`position`) = 1 LIMIT 0, 486 ", $bl, $br, $tr, $tl, $bl);
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array()) {
					echo 'scribbles['.$row[0]."] = '/scribbles/l/".$row[1]."'; ";
					// echo 'scribDates['.$row[0]."] = '".($row[3])."'; ";
					echo 'positionsx['.$row[0]."] = '".($row[4])."'; ";
					echo 'positionsy['.$row[0]."] = '".($row[5])."'; ";
					echo 'map['.$row[4].''.$row[5]."] = '".$row[0]."'; ".PHP_EOL;
				}

			}
		?>


					/////////////////////////////
					///////jQueryEvents/////////// 
					/////////////////////////////

		$(document).ready(function() {
				topY =  (ypos+6);
				bottomY = (ypos-11);
				leftX = (xpos-9);
				rightX = (xpos+17);

				//TODO FOKUS AUF XPOS, YPOS
				window.scrollTo(($(document).width() - $(window).width())/2, ($(document).height() - $(window).height())/2);
				for(var y =topY ; y>=bottomY; y--){
					for (var x = leftX; x<=rightX; x++){
						createMapCell(x, y);
					}

				}
				for(var k in scribbles){
					if (scribbles.hasOwnProperty(k)) {
						createScribble(positionsx[k], positionsy[k], k);
					}	
				}
		})

		
		$(window).scroll(function()
			{
				// Scrollbar on Bottom
				if($(window).scrollTop() == $(document).height() - $(window).height())
				{
					
					$('div#loadingImage').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: leftX+' '+(bottomY-7), 
								br: rightX+' '+(bottomY-7),
								tr: rightX+' '+(bottomY-1), 
								tl: leftX+' '+(bottomY-1) 
							},
						success: function(data){
								var json = $.parseJSON(data);
								addBottomRow();
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						});
				}

				// Scrollbar on Top
				else if($(window).scrollTop() == 0){
					$('div#loadingImage').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: leftX+' '+(topY+1), 
								br: rightX+' '+(topY+1),
								tr: rightX+' '+(topY+7), 
								tl: leftX+' '+(topY+7) 
							},
						success: function(data){
								var json = $.parseJSON(data);
								addTopRow();
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						});

				}

				// Scrollbar Left
				else if($(window).scrollLeft() == 0){
					console.log("scroll left");
				}

				// Scrollbar Right
				else if($(window).scrollLeft() == $(document).width() - $(window).width()){
					console.log("scroll right");
				}
			})




		function createScribble(x, y, scrid){	

			$('<div/>', {
				id: 'scribble'+x+''+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('scribble'
			).css({
				'background-image': 'url('+root+scribbles[scrid]+')'
			}).appendTo('#mapCell'+x+''+y);
							
						
		}


		function createMapCell(x,y){

			$('<div/>', {
				id: 'mapCell'+x+''+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).appendTo('#divCanvas');

			store2DArray('mapCell'+x+''+y+'', x, y, mapCells)

			if(x==rightX){
				$('#mapCell'+x+''+y+'').removeClass('mapCell').addClass('rightMapCell');
			}
				
		}

		function createTopMapCell(x,y){

			$('<div/>', {
				id: 'mapCell'+x+''+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).prependTo('#divCanvas');

			store2DArray('mapCell'+x+''+y+'', x, y, mapCells)
			console.log("'mapCell'+x+''+y+''");
			if(x==rightX){
				$('#mapCell'+x+''+y+'').removeClass('mapCell').addClass('rightMapCell');
			}
				
		}
		
		function addBottomRow(){
			bottomY--;
			$("#divCanvas").height($("#divCanvas").height()+840+'px');
			for(var y = bottomY; y >=bottomY-6;y--){
				for(var x = leftX; x<=rightX; x++){
					createMapCell(x,y);
				}	
			}
			bottomY -= 6;
		}

		function addTopRow(){
			topY++;
			$("#divCanvas").height($("#divCanvas").height()+840+'px');
			for(var y = topY; y <=topY+6;y++){
				for(var x = rightX; x>=leftX; x--){
					createTopMapCell(x,y);
				}	
			}
			topY += 6;
		}

		function addLeftColumn(x,y){
			//$("p").prepend("<b>Hello </b>");

		}

		function addRightColumn(x,y){
			

		}


	
		function store2DArray(id, xVal, yVal, array){
			if (typeof array[xVal] == "undefined"){
				array[xVal] = new Array();
			}
			array[xVal][yVal] = id;
		}

		function fDiv(n1, n2)
		{
		// Simulation einer ganzahligen Division
		// erst dividieren, dann auf Ganzzahl abrunden. 
		if ( n1*n2 > 0 ) return Math.floor( n1/n2 );
		else return Math.ceil ( n1/n2 );
		}
		







			

			// //************************************************************************
			// function mousedown(evt)
			// {
			// //console.log("MOUSE:DOWN");

			//	// Non-IE browsers will use evt
			//	var ev = evt || window.event;

			//	var canvas = document.getElementById('canvas');
			//	canvas.onmousemove=mousemove;

			//	lastX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
			//	lastY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;

			//	capturing = inCanvasBounds(lastX, lastY);

			//	// Register click immediately
			//	mousemove(evt);
			// }


			// //************************************************************************
			// // posX and posY are assumed relative to canvas boundaries.
			// // Returns true if posX and posY are contained within canvas boundaries.
			// function inCanvasBounds( posX, posY )
			// {
			//	var left = 0;
			// var top = 0;
			//	var right = canvasSize.width;
			// var bottom = canvasSize.height;

			// return ( posX >= left && posX <= right && 
			//	posY >= top && posY <= bottom);
			// }

			// function mousemove(evt) {

			//		// Non-IE browsers will use evt
			//		var ev = evt || window.event;

			//		var penAPI = plugin().penAPI;	
			//		var canvas = document.getElementById('canvas');
			//		var pressure = 0.0;

			//		if (penAPI){
			//			pressure = penAPI.pressure;
			//		}
			//		else {
			//			pressure = 1.0;
			//		}

			//		//console.log("pressure: " + pressure);
						
			//		curX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
			//		curY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;

			//		capturing = inCanvasBounds(curX, curY);

			//		if (capturing && pressure > 0.0)
			//		{
			//			if (canvas.getContext)
			//			{
			//				var ctx = canvas.getContext("2d");
			//				ctx.beginPath();
			//				ctx.moveTo(lastX, lastY);
			//				ctx.lineTo(curX, curY);
			//				ctx.lineWidth = 25.0 * pressure;
			//				ctx.strokeStyle = "rgba(128, 0, 0, 1.0)";
			//				$("canvas").translateCanvas({
			//					translateX: curX, translateY: curY
			//				})
			//				// MOVE ON CANVAS !!!
			//			}

			//			ctx.stroke();
			//		}
			//		lastX = curX;
			//		lastY = curY;
			// }

		</script>

		<title>Scribbit - Wall</title>
	</head>

	<body> 
		<div id="site">
			<div id="header">
				<?php include docroot.'/'.path.'/topnav.php'; ?>
			</div>	
			<div id="clippingMask">	
				<div id="loadingImage">Loading</div>
				<div id="divCanvas"></div>
			</div>
		</div>
	</body>
</html>