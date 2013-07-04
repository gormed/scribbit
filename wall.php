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

	<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/main.css">'; ?>
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
	var ajaxRight = true;
	var ajaxLeft = true;
	var ajaxTop = true;
	var ajaxBottom = true;

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
				echo 'scribbles['.$row[0]."] = '".$row[1]."'; ";
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
					loadPictures();

					$('html, body').stop().animate({
						scrollTop : topOff -(($(window).height()-140) / 2),
						scrollLeft : leftOff -(($(window).width()-210) / 2)				
					}, 300, function() {

							$('#viewOverlay').show().stop().animate({
								opacity : 1
								
							}, 300, function() {
									
								});
						});
					
				});

				$(document).on("dblclick", "#picture", function(event){
					
					event.preventDefault();

					
					
				
						$('#viewOverlay').show().stop().animate({
									opacity : 0
									
								}, 300, function() {
										$(this).hide();
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
					
					if(ajaxBottom){
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
								ajaxBottom = false;
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
				}

				// Scrollbar on Top
				else if($(window).scrollTop() == 0){
					
					if(ajaxTop){
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
								ajaxTop =false;
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

				}

				// Scrollbar Left
				else if($(window).scrollLeft() == 0){
					if(ajaxLeft){
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
								ajaxLeft=false;
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
				}

				// Scrollbar Right
				else if($(window).scrollLeft() == $(document).width() - $(window).width()){

					if(ajaxRight){
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
								ajaxRight = false;
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
				'background-image': 'url('+root+'/scribbles/l/'+scribbles[scrid]+')'
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


			function submitWhere(where) {

				var input = document.getElementById('where'); 
				input.value = where;
				var form = document.getElementById('postscribble'); 
				form.submit();
				
			}

			function wallSelectRequest(){
				$.ajax({
							type: "POST",
							url: "../wallSelectRequest.php",
							data: {	scribbleid: currentScribble.data("scribbleid")
							
						},
						success: function(data){
						if($.isEmptyObject(data)){
								
							}
							else{
								var json = $.parseJSON(data);
								
							
								console.log(i+" "+json.neighbours[1]);
								for(var i = 0; i < json.neighbours.size;i++){
										
											neighbours[i] = json.neighbours[1];
											console.log(i+" "+neighbours[i]);
										
								}	
							}
						}
					});
			}
			function loadPictures(){
				// wallSelectRequest();
				// $('#topneightbour').css("background-image", "url(" + root + '/scribbles/h/' + neighbours[2]+")");
				
				$('#picture').data("scribbleid", currentScribble.data("scribbleid")
					).css("background-image", "url(" + path  +'/scribbles/h/'+ scribbles[currentScribble.data("scribbleid")]+")");
			}

			function onLoad () {

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
							
							<div class="cell">

								<div id="painthorz" onclick="submitWhere(0)">(paint here)</div>

							</div>
							
						</div>
						<div class="row" id="wrapper">
							
							<div class="cell">
								
								<div id="paintvert" onclick="submitWhere(3)">(paint here)</div>
								
								
							</div>

							<div id="picture">
								
							</div>

							<div class="cell">
								<div id="paintvert" onclick="submitWhere(1)">(paint here)</div>
								
							</div>

						</div>
						<div class="row">
							
							<div class="cell">
								

								<div id="painthorz" onclick="submitWhere(2)">(paint here)</div>
								
							</div>
							
						</div>
					</div>
					
					<?php include docroot.'/'.path.'/comment.php'; ?>
				</div>
			</div>
		</body>
		</html>