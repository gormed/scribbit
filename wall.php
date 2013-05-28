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
		var points=new Object();
		var canvasCount = 10;
		var rowCount = 9;
		var canvasPos ={};
		var canvases ={};
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

		function init() {


				initDivCanvas(fDiv(xpos,9),fDiv(ypos,6));
				for(var k in scribbles){
					if (scribbles.hasOwnProperty(k)) {
						createMapCell(positionsx[k], positionsy[k], k);
					}	
				}
				addBottomRow();
		}

		function createMapCell(x,y, scrid){

							var canvasCheck = checkCanvas(scrid);
							if (canvasCheck!=null){
								var mapCell  =document.createElement("div");
								var carDir = getCardinalDirection(scrid);
								var rely = 140 * getRelativYPos(scrid, carDir);
								var relx = 210 * getRelativXPos(scrid, carDir);
								mapCell.setAttribute('class','mapCell');
								mapCell.setAttribute('id','mapCell'+x+''+y+'');						
								mapCell.style.backgroundImage = "url("+root+scribbles[scrid]+")"; 
								var divCanvas = document.getElementById(canvasCheck);
								divCanvas.appendChild(mapCell);
								console.log(scrid + " @ " + x + " " + y + " witch relcoord " + relx + " " + rely+ " on "+divCanvas.id );
								$("#mapCell"+x+""+y).css({	'top' : rely + "px",
															'left': relx + "px" });
		
							}
						
		}


		function initDivCanvas(x,y){
			document.getElementById("divCanvas-11").setAttribute("id","divCanvas"+(x-1)+""+(y+1));
			storeCanvas("divCanvas"+(x-1)+""+(y+1), (x-1), (y+1), canvases);
			document.getElementById("divCanvas01").setAttribute("id","divCanvas"+x+""+(y+1));
			storeCanvas("divCanvas"+x+""+(y+1), x, (y+1), canvases);
			document.getElementById("divCanvas11").setAttribute("id","divCanvas"+(x+1)+""+(y+1));
			storeCanvas("divCanvas"+(x+1)+""+(y+1), (x+1), (y+1), canvases);

			document.getElementById("divCanvas-10").setAttribute("id","divCanvas"+(x-1)+""+y);
			storeCanvas("divCanvas"+(x-1)+""+y, (x-1), y, canvases);
			document.getElementById("divCanvas00").setAttribute("id","divCanvas"+x+""+y);
			storeCanvas("divCanvas"+x+""+y, x, y, canvases);
			document.getElementById("divCanvas10").setAttribute("id","divCanvas"+(x+1)+""+y);
			storeCanvas("divCanvas"+(x+1)+""+y, (x+1), y, canvases);

			document.getElementById("divCanvas-1-1").setAttribute("id","divCanvas"+(x-1)+""+(y-1));
			storeCanvas("divCanvas"+(x-1)+""+(y-1), (x-1), (y-1), canvases);
			document.getElementById("divCanvas0-1").setAttribute("id","divCanvas"+x+""+(y-1));
			storeCanvas("divCanvas"+x+""+(y-1), x, (y-1), canvases);
			document.getElementById("divCanvas1-1").setAttribute("id","divCanvas"+(x+1)+""+(y-1));
			storeCanvas("divCanvas"+(x+1)+""+(y-1), (x+1), (y-1), canvases);
		}

		function storeCanvas(id, xVal, yVal, array){
			if (typeof array[xVal] == "undefined"){
				array[xVal] = new Array();
			}
			array[xVal][yVal] = id;
		}

		function storeCoordinate(id, xVal, yVal, array) {
			array[id]={x: xVal, y: yVal};
		}

		function fDiv(n1, n2)
		{
		// Simulation einer ganzahligen Division
		// erst dividieren, dann auf Ganzzahl abrunden. 
		if ( n1*n2 > 0 ) return Math.floor( n1/n2 );
		else return Math.ceil ( n1/n2 );
		}
		

		// function loadScribbles () {

		//	for (var k in scribbles) {
				
		//		// use hasOwnProperty to filter out keys from the Object.prototype
		//		if (scribbles.hasOwnProperty(k)) {
		//			var canvasCheck = checkCanvas(k);
		//			var rx = getRelativXPos(k);
		//			var ry = getRelativYPos(k);
		//			$("#"+canvasCheck).addLayer({
		// 				type: "image",
		// 				source: "" + root+"/"+scribbles[k] + "",
		// 				x: 210*rx+105, y: 140*ry+70, 
		// 				width: 210, height: 140
		// 			})
		// 			.drawLayers();
					
		// 		}

		// 	}	
			
		// }

		function getCardinalDirection(scribbleid){
			if(positionsx[scribbleid]>=0 && positionsy[scribbleid]<=0){
				return "se";
			}
			else if(positionsx[scribbleid]>=0 && positionsy[scribbleid]>0){
				return "ne";
			}
			else if(positionsx[scribbleid]<0 && positionsy[scribbleid]<=0){
				return "sw";
			}
			else if(positionsx[scribbleid]<0 && positionsy[scribbleid]>0){
				return "nw";
			}
		}

		function checkCanvas(scribbleid) {
				
				if(positionsx[scribbleid]>=0 && positionsy[scribbleid]<=0){
					//SOUTHEAST
					for(var x in canvases){
						if (canvases.hasOwnProperty(x)) {
							var cPosX = x*9;
							for (var y in canvases[x]){
								if (canvases[x].hasOwnProperty(y)) {
									var cPosY = y*6;
									if (positionsx[scribbleid]>=cPosX && positionsx[scribbleid]<(cPosX+9) && positionsy[scribbleid]<=cPosY && positionsy[scribbleid]>(cPosY-6)){
										return canvases[x][y];
										break;
									}
								}
							}	
						}	
					}
				}

				else if(positionsx[scribbleid]>=0 && positionsy[scribbleid]>0){
					//NORTHEAST
					for(var x in canvases){
						if (canvases.hasOwnProperty(x)) {
							var cPosX = x*9;
							for (var y in canvases[x]){
								if (canvases[x].hasOwnProperty(y)) {
									var cPosY = y*6;
									if (positionsx[scribbleid]>=cPosX && positionsx[scribbleid]<(cPosX+9) && positionsy[scribbleid]>(cPosY-6) && positionsy[scribbleid]<=cPosY){
										return canvases[x][y];
										break;
									}
								}
							}
						}
					}
				}

				else if(positionsx[scribbleid]<0 && positionsy[scribbleid]>0){
					//NORTHWEST
					for(var x in canvases){
						if (canvases.hasOwnProperty(x)) {
							var cPosX = x*9;
							for (var y in canvases[x]){
								if (canvases[x].hasOwnProperty(y)) {
									var cPosY = y*6;
									if (positionsx[scribbleid]>=cPosX && positionsx[scribbleid]<(cPosX+9) && positionsy[scribbleid]>(cPosY-6) && positionsy[scribbleid]<=cPosY){
										return canvases[x][y];
										break;
									}
								}
							}
						}
					}
				}

				else if(positionsx[scribbleid]<0 && positionsy[scribbleid]<=0){
					//SOUTHWEST
					for(var x in canvases){
						if (canvases.hasOwnProperty(x)) {
							var cPosX = x*9;
							for (var y in canvases[x]){
								if (canvases[x].hasOwnProperty(y)) {
									var cPosY = y*6;
									if (positionsx[scribbleid]>=cPosX && positionsx[scribbleid]<(cPosX+9) && positionsy[scribbleid]<=cPosY && positionsy[scribbleid]>(cPosY-6)){
										return canvases[x][y];
										break;
									}
								}
							}
						}
					}
				}
				return null;
		}


		function getRelativXPos(scribbleid, carDir) {
			switch (carDir) {
				case "nw":
					var relx =	positionsx[scribbleid]* -1;
					relx = relx%9;
					if(relx != 0) relx = 9-relx;
					return relx;
					break;
				case "ne":
					var relx = positionsx[scribbleid];	
					return relx%9;
					break;
				case "sw":
					var relx =	positionsx[scribbleid]* -1;
					relx = relx%9;
					if(relx != 0) relx = 9-relx;
					return relx; 
					break;
				case "se":
					var relx =	positionsx[scribbleid];	
					return relx%9;
					break;
				default:
					console.log("getRelativCoord failed!");
					break;
			}		
		}
		function getRelativYPos(scribbleid, carDir) {		
			switch (carDir) {
				case "nw":
					var rely =	positionsy[scribbleid];
					rely = rely%6;
					if(rely != 0) rely = 6-rely;
					return rely; 
					break;
				case "ne":
					var rely =	positionsy[scribbleid];
					rely = rely%6;
					if(rely != 0) rely = 6-rely;
					return rely; 
					break;
				case "sw":
					var rely =	positionsy[scribbleid]* -1;
					return rely%6;
					break;
				case "se":
					var rely =	positionsy[scribbleid]* -1;
					return rely%6;
					break;
				default:
					return 0;
					console.log("getRelativCoord failed!");
					break;
			}

				
		}


					/////////////////////////////
					///////jQueryEvents/////////// 
					/////////////////////////////

		$(window).scroll(function()
			{
				// Scrollbar on Bottom
				if($(window).scrollTop() == $(document).height() - $(window).height())
				{
					console.log("scrolldown");
					// $('div#processbar').show();
					// $.ajax({
					// 	type: "POST",
					// 	url: "ajaxWallBottom.php",
					// 	data: {currentCanvas: "BOTTOM"},
					// 	success: function(html)
					// 	{
					// 		if(html)
					// 		{
					
								addBottomRow();

					// 		$('div#processbar').hide();
					// 		}else
					// 		{
					// 			$('div#processbar').html('<center>OUT OF SPACE</center>');
					// 		}
					}
					});
				



			function addBottomRow(){
				
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
					<?php include docroot.'/'.path.'/topnav.php'; ?>
				</div>	
				<div id="clippingMask">	
					<span class="table" id="table">
						<div id="loadPlaceNorth"></div>
						<div class="row" id="row1">
							<div class="cell" id="cell1"><div id="divCanvas-11">&nbsp;</div></div>
							<div class="cell" id="cell2"><div id="divCanvas01">&nbsp;</div></div>
							<div class="cell" id="cell3"><div id="divCanvas11">&nbsp;</div></div>
						</div>
						<div class="row" id="row2">
							<div class="cell" id="cell4"><div id="divCanvas-10">&nbsp;</div></div>
							<div class="cell" id="cell5"><div id="divCanvas00">&nbsp;</div></div>
							<div class="cell" id="cell6"><div id="divCanvas10">&nbsp;</div></div>
						</div>
						<div class="row" id="row3">
							<div class="cell" id="cell7"><div id="divCanvas-1-1">&nbsp;</div></div>
							<div class="cell" id="cell8"><div id="divCanvas0-1">&nbsp;</div></div>
							<div class="cell" id="cell9"><div id="divCanvas1-1">&nbsp;</div></div>
						</div>
						<div id="loadPlaceSouth"></div>
					</span>

				</div>

			</div>

	</body>
</html>