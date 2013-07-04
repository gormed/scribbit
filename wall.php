<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<?php 
require_once 'header.php';
?>
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/headerSearch.css">'; ?>
	<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/wall.css">'; ?>
	<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
	<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
	<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/comment.js"></script>'; ?>
	<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/socials.js"></script>'; ?>
	<script type="text/javascript">
	var comments = {};
	var dates = {};
	var usernames = {};

	var scribDates = {};
	var users = {};
	var commentCount = {};


	// var favNames = {};
	var favCount = {};
	var favorites = {};

	var scribbles = {};
	var positionsx = {};
	var positionsy = {};
	var map={};

	var temp_scribbles = {};
	var temp_positionsx = {};
	var temp_positionsy = {};
	var temp_map={};
	var currentScribble;
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
					///////jQueryEvents//////////
					/////////////////////////////

					$(document).ready(function() {
						topY =  (ypos+6);
						bottomY = (ypos-11);
						leftX = (xpos-9);
						rightX = (xpos+17);

				// $('#ithingy').overscroll();
				draginit();
				

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

				$(document).on("mousedown", "#divCanvas", function(event){
					event.preventDefault();
					
					dragstart(document.getElementById("divCanvas"));
				})

				
				
				$(document).on("dblclick", ".scribble", function(event){
					
					event.preventDefault();
					var topOff = $(this).focus().offset().top;
					var leftOff = $(this).focus().offset().left;
					currentScribble = $(this).focus();
					console.log(currentScribble.data());

					$('html, body').stop().animate({
						scrollTop : topOff -(($(window).height()-140) / 2),
						scrollLeft : leftOff -(($(window).width()-210) / 2)				
					}, 300, function() {

							$('#viewOverlay').show().stop().animate({
								opacity : 1
								
							}, 300, function() {
									$("#divCanvas").hide();
								});
						});
					
				});

				$(document).on("dblclick", "#picture", function(event){
					
					event.preventDefault();
					var topOff = $(this).focus().offset().top;
					var leftOff = $(this).focus().offset().left;

					$("#divCanvas").show();
					
					$('html, body').stop().animate({
						scrollTop : topOff -(($(window).height()-140) / 2),
						scrollLeft : leftOff -(($(window).width()-210) / 2)				
					}, 300, function() {

						$('#viewOverlay').show().stop().animate({
									opacity : 0
									
								}, 300, function() {
										$(this).hide();
									});
					
						});
					});
				
				$(document).on("mouseenter", ".scribble", function(event){
					
					event.preventDefault();
					$(this).stop().animate({
						opacity: 1
						
						
					}, 300, function() {
							// Animation complete.
						});
				});

				$(document).on("mouseleave", ".scribble", function(event){
					
					event.preventDefault();

					$(this).stop().animate({
						opacity: 0.75
						
						
					}, 300, function() {
							// Animation complete.
						});
				});


			})

//Das Objekt, das gerade bewegt wird.
var dragobjekt = null;

// Position, an der das Objekt angeklickt wurde.
var dragx = 0;
var dragy = 0;

// Mausposition
var posx = 0;
var posy = 0;


function draginit() {
 // Initialisierung der Überwachung der Events

 document.onmousemove = drag;
 document.onmouseup = dragstop;
}


function dragstart(element) {
   //Wird aufgerufen, wenn ein Objekt bewegt werden soll.

   dragobjekt = element;
   dragx = posx - dragobjekt.offsetLeft;
   dragy = posy - dragobjekt.offsetTop;
}


function dragstop() {
  //Wird aufgerufen, wenn ein Objekt nicht mehr bewegt werden soll.

  dragobjekt=null;

}


function drag(event) {
	posx = document.all ? window.event.clientX : event.pageX;
	posy = document.all ? window.event.clientY : event.pageY;
	if(dragobjekt != null) {
		window.scrollBy((-(posx - dragx)), (-(posy - dragy)));
	}
}

$(window).scroll(function()
{
				// Scrollbar on Bottom
				if($(window).scrollTop() == $(document).height() - $(window).height())
				{
					$('div#bottomProcessBar').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: leftX+' '+(bottomY-7), 
						br: rightX+' '+(bottomY-7),
						tr: rightX+' '+(bottomY-1), 
						tl: leftX+' '+(bottomY-1) 
					},
					success: function(data){
						$('div#bottomProcessBar').hide();
						if($.isEmptyObject(data)){
							
						}
						else{
							var json = $.parseJSON(data);
							if($.isEmptyObject(json.temp_scribbles)){

							}
							else {
								addBottomRow();
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						}
					}
				});
				}

				// Scrollbar on Top
				else if($(window).scrollTop() < 10){
					$('div#topProcessBar').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: leftX+' '+(topY+1), 
						br: rightX+' '+(topY+1),
						tr: rightX+' '+(topY+7), 
						tl: leftX+' '+(topY+7) 
					},
					success: function(data){
						$('div#topProcessBar').hide();
						if($.isEmptyObject(data)){

						}
						else{
							var json = $.parseJSON(data);
							if($.isEmptyObject(json.temp_scribbles)){
								
							}
							else {
								addTopRow();
								$(window).scrollTop($(window).scrollTop()+(6*$(".mapCell").height()));
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						}
					}
				});


				}

				// Scrollbar Left
				else if($(window).scrollLeft() < 10){
					
					$('div#leftProcessBar').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: (leftX-10)+' '+bottomY, 
						br: (leftX-1)+' '+bottomY,
						tr: (leftX-1)+' '+topY, 
						tl: (leftX-10)+' '+topY 
					},
					success: function(data){
						$('div#leftProcessBar').hide();
						if($.isEmptyObject(data)){
							
						}
						else{
							var json = $.parseJSON(data);
							if($.isEmptyObject(json.temp_scribbles)){

							}
							else {
								addLeftColumn();
								$(window).scrollLeft($(window).scrollLeft()+(9*$(".mapCell").width()));
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						}
					}
				});
				}

				// Scrollbar Right
				else if($(window).scrollLeft() == $(document).width() - $(window).width()){

					$('div#rightProcessBar').show();
					$.ajax({
						type: "POST",
						url: "wallAjaxRequest.php",
						data: {	bl: (rightX+1)+' '+bottomY, 
						br: (rightX+10)+' '+bottomY,
						tr: (rightX+10)+' '+topY, 
						tl: (rightX+1)+' '+topY 
					},
					success: function(data){
						$('div#rightProcessBar').hide();
						if($.isEmptyObject(data)){
							
						}
						else{
							var json = $.parseJSON(data);
							if($.isEmptyObject(json.temp_scribbles)){

							}
							else {
								addRightColumn();
								for(var k in json.temp_scribbles){
									if (json.temp_scribbles.hasOwnProperty(k) && scribbles[k] == null){
										scribbles[k] = json.temp_scribbles[k];
										createScribble(json.temp_positionsx[k], json.temp_positionsy[k], k);
									}
								}	
							}
						}
					}
				});
				}
			})


	function isScrolledIntoView(elem)
{
	var docViewTop = $(window).scrollTop();
	var docViewBottom = docViewTop + $(window).height();

	var elemTop = $(elem).offset().top;
	var elemBottom = elemTop + $(elem).height();

	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function scrollToPosition(x,y){

}


function createScribble(x, y, scrid){	
	$('<div/>', {
		id: 'scribble'+x+'_'+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('scribble'
			).data("scribbleid", scrid
			).css({
				'background-image': 'url('+root+scribbles[scrid]+')'
			}).appendTo('#mapCell'+x+'_'+y);				
		}


		function createMapCell(x,y){
			$('<div/>', {
				id: 'mapCell'+x+'_'+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).appendTo('#divCanvas');
			store2DArray('mapCell'+x+'_'+y+'', x, y, mapCells)	
		}


		function createTopMapCell(x,y){
			$('<div/>', {
				id: 'mapCell'+x+'_'+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).prependTo('#divCanvas');
			store2DArray('mapCell'+x+'_'+y+'', x, y, mapCells)	
		}

		function createRightMapCell(x,y){			
			$('<div/>', {
				id: 'mapCell'+x+'_'+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).insertAfter('#mapCell'+(x-1)+'_'+y);
			store2DArray('mapCell'+x+'_'+y+'', x, y, mapCells)
		}

		function createLeftMapCell(x,y){		
			$('<div/>', {
				id: 'mapCell'+x+'_'+y+''
				// title: 'Become a Googler',
				// rel: 'external',
				// text: 'Go to Google!'
			}).addClass('mapCell'
			).insertBefore('#mapCell'+(x+1)+'_'+y);
			store2DArray('mapCell'+x+'_'+y+'', x, y, mapCells)				
		}	


		function addBottomRow(){
			bottomY--;
			$("#divCanvas").height($("#divCanvas").height()+840+'px');
			for(var y = bottomY; y >bottomY-6;y--){
				for(var x = leftX; x<=rightX; x++){
					createMapCell(x,y);
				}	
			}
			bottomY -= 5;
		}

		function addTopRow(){
			topY++;
			$("#divCanvas").height($("#divCanvas").height()+840+'px');
			for(var y = topY; y <topY+6;y++){
				for(var x = rightX; x>=leftX; x--){
					createTopMapCell(x,y);
				}	
			}
			topY += 5;
		}

		function addLeftColumn(x,y){				
			leftX--;
			$("#divCanvas").width($("#divCanvas").width()+1890+'px');
			for(var y = topY; y >=bottomY;y--){	
				for(var x = leftX; x>leftX-9; x--){
					createLeftMapCell(x,y);
				}	
			}
			leftX -= 8;
		}


		function addRightColumn(x,y){
			
			rightX++;

			$("#divCanvas").width($("#divCanvas").width()+1890+'px');
			for(var y = topY; y >=bottomY;y--){	
				for(var x = rightX; x<rightX+9; x++){
					createRightMapCell(x,y);

				}	
			}
			rightX += 8;
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

			<title>Scribbit - Wall</title>
		</head>
		<?php 
			echo '<form action="'.path.'/scribble" method="post" id="postscribble"></form>';
		?>
		<body onload="onLoad()"> 
			<div id="leftProcessBar" class="rotating"></div>
			<div id="rightProcessBar" class="rotating"></div>
			<div id="topProcessBar" class="rotating"></div>
			<div id="bottomProcessBar" class="rotating"></div>
			<div id="site">
				<div id="header">
					<?php include docroot.'/'.path.'/topnav.php'; ?>
				</div>	

				<div id="clippingMask">	

					<div id="divCanvas" class="dragger"></div>
				</div>


				<div id="viewOverlay">
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