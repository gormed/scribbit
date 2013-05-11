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
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/openLayersTheme.css">'; ?>
		.olControlAttribution {
			left: 5px;
			bottom: 5px; 
		}
		</style>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jcanvas.min.js"></script>'; ?>
		<script type="text/javascript">


		var scribbles = {};
		var points=new Object();
		var canvasCount = 10;
		var rowCount = 4;
		var canvasPos ={};
		// var lastX, curX, lastY, curY;

		<?php 

			if (!$loggedIn) {
			exit();
			}
			else {
				echo "var path = '".path."';";
				echo "var root = '".root."/scribbles/l';"; 

				$sql = "SELECT `scribbleid`, `path` FROM `scribbles` LIMIT 0, 40 ";
				$result = $mysqli->query($sql);
				while ($row = $result->fetch_array(MYSQLI_NUM)) {
					echo 'scribbles['.$row[0]."] = '".$row[1]."';";
				}
				// $sql = "SELECT `scribbleid`, `AsText(position)` FROM `map`LIMIT 0, 40  ";
				// $result = $mysqli->query($sql);
				// while ($row = $result->fetch_array(MYSQLI_NUM)) {
				// 	echo 'points['.$row[0]."] = '".$row[1]."';";
				// }


			}
		?>

		function init() {

			storeCoordinate(0, 1, 0, points);
			storeCoordinate(1, 1, 0, points);
			storeCoordinate(2, 0, 1, points);
			storeCoordinate(3, 10, 10, points);
			storeCoordinate(4, -10, 10, points);
			storeCoordinate(5, 10, -10, points);
			storeCoordinate(6, -10, -10, points);
			storeCoordinate(7, -20, 20, points);
			storeCoordinate(8, 2, 2, points);
			storeCoordinate(9, -2, -2, points);
			for(var i =10;i<=21;i++){
					storeCoordinate(i, 0, 0, points);
			}
			
			for (var y=-1; y<=1; y++){
				for (var x=-1; x<=1; x++){
					storeCoordinate(x+""+y, x, y, canvasPos);
					
				}
			}
console.log(canvasPos);
				loadScribbles();

		}



		function storeCoordinate(id, xVal, yVal, array) {
			array[id]={x: xVal, y: yVal};
		}
		

		function loadScribbles () {

			var count=0;
			for (var k in scribbles) {
				var canvasCheck = checkCanvas(count);
				// use hasOwnProperty to filter out keys from the Object.prototype
				if (scribbles.hasOwnProperty(k)) {
					var rx = getRelativXPos(count);
					var ry = getRelativYPos(count);
					$("#canvas"+canvasCheck).addLayer({
						type: "image",
						source: "" + root+"/"+scribbles[k] + "",
						x: 210*rx-105, y: 140*ry+70, 
						width: 210, height: 140
					})
					.drawLayers();
					
				}
				count++;

			}	
			
		}

		function checkCanvas(scribbleid) {
				
				for(var id in canvasPos){
					var cPosX = canvasPos[id].x*9;
					var cPosY = canvasPos[id].y*6;
					
					//North East
					if (points[scribbleid].x>=cPosX && points[scribbleid].x<cPosX+9 && points[scribbleid].y>=cPosY && points[scribbleid].y<cPosY+9){
												
						return id;
						break;
					}
					//North West
					else if (points[scribbleid].x<=cPosX && points[scribbleid].x>cPosX-9 && points[scribbleid].y>=cPosY && points[scribbleid].y<cPosY+9){
						return id;
						console.log(id);
						break;

					}
					//South West
					else if (points[scribbleid].x<=cPosX && points[scribbleid].x>cPosX-9 && points[scribbleid].y<=cPosY && points[scribbleid].y>cPosY-9){
						return id;
						break;
					}
					//South East
					else if (points[scribbleid].x>=cPosX && points[scribbleid].x<cPosX+9 && points[scribbleid].y<=cPosY && points[scribbleid].y>cPosY-9){
						return id;
						break;
					}


				}
				return null;
		}


		function getRelativXPos(scribbleid) {
				var relx =	points[scribbleid].x;	
				return relx%9;
		}
		function getRelativYPos(scribbleid) {		
				var rely =	points[scribbleid].y;
				return rely%6;
		}


					/////////////////////////////
					///////jQueryEvents/////////// 
					/////////////////////////////

		$(window).scroll(function()
			{
				// Scrollbar on Bottom
				if($(window).scrollTop() == $(document).height() - $(window).height())
				{
					$('div#processbar').show();
					$.ajax({
						type: "POST",
						url: "ajaxWallBottom.php",
						data: {currentCanvas: "BOTTOM"},
						success: function(html)
						{
							if(html)
							{
					
								addBottomRow();

							$('div#processbar').hide();
							}else
							{
								$('div#processbar').html('<center>OUT OF SPACE</center>');
							}
						}
					});
				}
			});	


			function addbottomRow(){
			
						var row = document.createElement("div");
						row.setAttribute('class', 'row');
						row.setAttribute('id', 'row'+rowCount+'');
						for(var i=0;i<3;i++){
							var cell = document.createElement("div");
							cell.setAttribute('class', 'cell');
							cell.setAttribute('id', 'cell'+canvasCount+'');

							var canvas =document.createElement("canvas");
							canvas.setAttribute('width','1890');
							canvas.setAttribute('height','840');
							canvas.setAttribute('id', 'canvas'+canvasCount+'');
							cell.appendChild(canvas);
							row.appendChild(cell);
							canvasCount++;

						}
						document.getElementById("table").appendChild(row);
						$("#row"+rowCount).insertBefore("#loadPlaceSouth");
						rowCount++;
						// loadScribbles (canvasCount);
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

<body onload="init()"> 
			<div id="site">
				<div id="header">
					<div id="logo">
						<?php
						echo '<a href="'.path.'/"><</a>';
						?>
						<div id="processbar" style="display:none;"><center><p>LADEN</p></center></div>
					</div>
					<?php include docroot.'/'.path.'/topnav.php'; ?>
					<?php include docroot.'/'.path.'/searchbar.php'; ?>
				</div>	
				<div id="clippingMask"  overflow="scroll">		

					<div class="table" id="table">
						<div id="loadPlaceNorth"></div>
						<div class="row" id="row1">
							<div class="cell" id="cell1"><canvas id="canvas-11" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell2"><canvas id="canvas01" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell3"><canvas id="canvas11" width="1890" height="840"></canvas></div>
						</div>
						<div class="row" id="row2">
							<div class="cell" id="cell4"><canvas id="canvas-10" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell5"><canvas id="canvas00" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell6"><canvas id="canvas10" width="1890" height="840"></canvas></div>
						</div>
						<div class="row" id="row3">
							<div class="cell" id="cell7"><canvas id="canvas-1-1" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell8"><canvas id="canvas0-1" width="1890" height="840"></canvas></div>
							<div class="cell" id="cell9"><canvas id="canvas1-1" width="1890" height="840"></canvas></div>
						</div>
						<div id="loadPlaceSouth"></div>
					</div>

				</div>

			</div>

	</body>
</html>