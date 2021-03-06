
<?php 

$mysqli->close();
require_once 'db_work.php';
$loggedIn = login_check($mysqli);	

require_once 'bypass.php';

function timeTillNow($oldTime)
{
		$now = time(); // or your date as well
		$your_date = strtotime($oldTime);
		$datediff = abs($now - $your_date);
		return floor($datediff/(60)); // minutes
	}

	function isReserved($mysqli, $xpos, $ypos)
	{
		$sql = sprintf("SELECT `id`, `userid`, `time` FROM `reserved_map` WHERE X(`position`) = %d AND Y(`position`) = %d LIMIT 0, 1", $xpos, $ypos);
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
		if (isset($result->num_rows) && $result->num_rows > 0) {
			$time = $row[2];
			$userid = $row[1]; //echo "time ".timeTillNow($time).PHP_EOL;
			if (timeTillNow($time) > 60 || $userid == $_SESSION['user_id']) { // time since entry bigger than 59 min
				return false;
			} else { 
				return true;
			}
		}
		return false;
	}

	if (isset($_POST['parentid']) && isset($_POST['where'])) {
		$where = $_POST['where'];
		$parentid = $_POST['parentid'];
		$userid = $_SESSION['user_id'];

		$sql = sprintf("SELECT X(`position`), Y(`position`), `parentid` FROM `map` WHERE `scribbleid` = %d LIMIT 0, 1", $parentid);
		$result = $mysqli->query($sql)->fetch_array();

		$xparent = $result[0];
		$yparent = $result[1];

		switch ($where) {
					//top
			case '0':
			$xpos = $xparent;
			$ypos = $yparent + 1;
			break;
					//right
			case '1':
			$ypos = $yparent;
			$xpos = $xparent + 1;
			break;
					//bottom
			case '2':
			$xpos = $xparent;
			$ypos = $yparent - 1;
			break;
					//left
			case '3':
			$ypos = $yparent;
			$xpos = $xparent - 1;
			break;

			default:

			break;
		}

		$reserved = isReserved($mysqli, $xpos, $ypos);
		if (!$reserved) {
			$sql = sprintf("DELETE FROM `reserved_map` WHERE `userid` = %d", $userid); // have you the right to access
			$res1 = $mysqli->query($sql);
			$sql = sprintf("INSERT INTO `reserved_map`(`position`, `userid`) VALUES (GEOMFROMTEXT('POINT(%d %d)', 0 ), %d)", $xpos, $ypos, $userid);
			$res2 = $mysqli->query($sql);
		} else {
			header("location: ".path."/scribbles/".$parentid);
		}
	} else {
		header("location: ".path."/gallery");
	}

	?>

	<html>
	<head>
		<?php require_once 'intern_header.php'; ?>
		<title>Scribb'it - Scribble</title>
		<!--<script type="text/javascript" src="ressources/js/scribble.js"></script>-->
		<link rel="stylesheet" type="text/css" href="ressources/css/scribble.css">
		<style type="text/css">
		#round {
			background-image: url(<?php echo path.'/ressources/img/brush/brushkreis.png'; ?>);
			background-size: 100% 100%;
		}


		#round:hover {
			background-image: url(<?php echo path.'/ressources/img/brush/brushkreishover.png'; ?>);
		}


		#block{
			background-image: url(<?php echo path.'/ressources/img/brush/brushviereck.png'; ?>);
			background-size: 100% 100%;
		}


		#block:hover {
			background-image: url(<?php echo path.'/ressources/img/brush/brushviereckhover.png'; ?>);
		}


		#split{
			background-image: url(<?php echo path.'/ressources/img/brush/brushsplit.png'; ?>);
			background-size: 100% 100%;
		}


		#split:hover {
			background-image: url(<?php echo path.'/ressources/img/brush/brushsplithover.png'; ?>);
		}


		#shadow{
			background-image: url(<?php echo path.'/ressources/img/brush/brushshadow.png'; ?>);
			background-size: 100% 100%;
		}


		#shadow:hover {
			background-image: url(<?php echo path.'/ressources/img/brush/brushshadowhover.png'; ?>);
		}

		div#cut1 {
			float: left;
			margin: 3px;
			margin-top: 5px;
			background-image: url(<?php echo '"'.path.'/ressources/img/cuts.png"'; ?>);
			width: 24px; height: 24px;
			border: 1px solid;
			border-color: #666;
		}
		</style>

	</head>

	<body onselectstart="return false" onload="onLoad();">

	<!-- 
	***************************************************************** 
	 Embed the WacomTabletPlugin object.
	 To avoid plugin selection on page, size and position are adjusted 
	 so as to "tuck it under" canvas. 
	***************************************************************** 
-->

	<!--[if IE]>

	<object id='wtPlugin' classid='CLSID:092dfa86-5807-5a94-bf3b-5a53ba9e5308' WIDTH=1 HEIGHT=1 style="position:absolute; left:100px; top:100px">
	</object>

	<![endif]--><!--[if !IE]> <-->

	<object id="wtPlugin" type="application/x-wacomtabletplugin" WIDTH=1 HEIGHT=1 style="position:absolute; left:0px; top:0px">
		<!-- <param name="onload" value="pluginLoaded" /> -->
	</object>

	
	<!--> <![endif]-->

	<div id="content" class="contentnospace">
		<div id="closescribble" onclick="closescribble();"> x </div>
		<div id="show" onclick="show();"> - </div>
		<div id="upload"></div>
	</div>

	<div id="site">
		<canvas id="canvas" width="960" height="640" onmousedown="mousedown(event);" onmouseup="mouseup();" onmousemove="mousemove();"> </canvas>
		

		<div id="tools">
			<div  class="visible">



				<div id="blackbox">
					<div id="clear" title="Clear" onclick="clearCanvas();">clear</div>
					<div id="copy" title="copy" onclick="copy();">copy</div>
					<div id="paste" title="paste" onclick="fill();">paste</div>
					<div id="rubber" title="rubber" onclick="rubber();">rubber</div>
				</div>

				<div id="color">
					<div id="cbox3" onclick="setColor3();"></div>
					<div id="cbox" onclick="setColor();"></div>
					<div id="cbox1" onclick="setColor1();"></div>
					<div id="cbox2" onclick="setColor2();"></div>
					<div id="cbox4" onclick="setColor4();"></div>
					<div id="cbox5" onclick="setColor5();"></div>
				</div>

				<div id="brushes">
					<div id="shadow" onclick= "setShadow();"></div>
					<div id="round" onclick= "setRound();"></div>
					<div id="block" onclick= "setBlock();"></div>
					<div id="split" onclick= "setSplit();"></div>
				</div>	

				<div id="bar">
					<div>Größe</div>
					<input title="width"  type="range" min="1" max="250" value="50" step="1" onChange="showValueDicke(this.value);" />
					<input type ="text" id="resultDicke" value="" />
					<div>Transparenz</div>
					<input  title="opacity"type="range" min="0.1" max="1" value="1" step="0.1" onChange="showValueTrans(this.value);" />
					<input type ="text" id="resultTrans" value="" />
				</div><br>
				
				<input type="button" value="Publish" id="publish" title="Publish" onclick="saveImage();"></input>

			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript" src="https://raw.github.com/caleb531/jcanvas/master/jcanvas.min.js"></script>

<!-- 
**************************************************************************
*    Scribble app using javascript Canvas object with WebTabletPlugin.
*
*    Copyright (c) Wacom Technology, Inc., 2012
*
* Notes:
*    For use on Internet Explorer, Firefox, Chrome.
*************************************************************************** 
-->

<script type="text/javascript">

<?php 
echo 'var parentid = '.$parentid.';';
echo 'var where = '.$where.';';
echo 'var path = "'.path.'";';

?>

var canvasPos = {x:0.0, y:0.0};
var canvasSize = {width:960, height:640};
var lastX = 0.0;
var lastY = 0.0;
	var capturing = false;	// tracks in/out of canvas context
	var ctx ;
	//************************************************************************
	function plugin()
	{
		return document.getElementById('wtPlugin');
	}

	//************************************************************************
	function findPos(obj) 
	{
		var curleft = curtop = 0;
		if (obj.offsetParent) 
		{
			curleft = obj.offsetLeft
			curtop = obj.offsetTop
			while (obj = obj.offsetParent) 
			{
				curleft += obj.offsetLeft
				curtop += obj.offsetTop
			}
		}
		return {x:curleft, y:curtop};
	}

	//************************************************************************
	function onLoad()
	{
		var canvas = document.getElementById('canvas');        
		canvasPos = findPos(canvas);
		canvas.addEventListener("mousemove", mousemove, true);
		canvas.addEventListener("mouseup", mouseup, true);
		canvas.addEventListener("mousedown", mousedown, true); 

			// Show plugin version
		//_docPluginVersion           = document.getElementById('docPluginVersion');
		//_docPluginVersion.innerHTML = "Plugin Version: " + plugin().version;
	}



	//************************************************************************
	function pluginLoaded()
	{
		alert("PluginLoaded");
	}

	//************************************************************************
	function clearCanvas() 
	{
		var context = document.getElementById('canvas').getContext("2d");
		var imageData = context.clearRect(0,0,960,640);
	}


	//************************************************************************
	function saveImage () 
	{
		
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
				document.getElementById("upload").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST","upload_image.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("data=" + img + "&where=" + where +"&parentid="+parentid );
	}

	function closescribble() 
	{
		window.location.href = path + "/wall";
	}

	//************************************************************************
	function mousedown(evt)
	{
	//console.log("MOUSE:DOWN");

		// Non-IE browsers will use evt
		var ev = evt || window.event;

		var canvas = document.getElementById('canvas');
		canvas.onmousemove=mousemove;

		lastX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
		lastY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;

		capturing = inCanvasBounds(lastX, lastY);

		// Register click immediately
		mousemove(evt);
	}



	//************************************************************************
	function mouseup()
	{
	//console.log("MOUSE:UP");
	var canvas = document.getElementById('canvas');
	canvas.onmousemove=null;
	capturing = false; 
}


	//************************************************************************
	function mousemove(evt)
	{
		if (!capturing)
			return;
		// Non-IE browsers will use evt
		var ev = evt || window.event;

		var penAPI = plugin().penAPI;
		var canvas = document.getElementById('canvas');
		
		var pressure = 0.0;
		var isEraser;

		if (penAPI)
		{
			pressure = penAPI.pressure;
			isEraser = penAPI.isEraser;
		}
		else
		{
			pressure = 0.25;
			isEraser = false;
		}
		curX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
		curY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;


		capturing = inCanvasBounds(curX, curY);

		if (capturing && pressure > 0.0)
		{
			if (canvas.getContext)
			{		
				this.ctx = canvas.getContext("2d");
				this.ctx.beginPath();
				this.ctx.moveTo(lastX, lastY);
				this.ctx.lineTo(curX, curY);

				var farbe="black";
				var brush="round";			

				if (isEraser == true) 
				{
					this.ctx.lineWidth = 25.0 * pressure;
					this.ctx.strokeStyle = "rgba(255, 255, 255, 1.0)";

				}
				else 
				{	
					this.ctx.lineWidth =dicke * pressure;
					this.ctx.globalAlpha =trans;
				}
				this.ctx.stroke();
				var tempImg =this.ctx.getImageData(0,0,960,640);
			}
		}

		lastX = curX;
		lastY = curY;

	}

	//************************************************************************
	// posX and posY are assumed relative to canvas boundaries.
	// Returns true if posX and posY are contained within canvas boundaries.
	function inCanvasBounds( posX, posY )
	{
		var left = 0;
		var top = 0;
		var right = canvasSize.width;
		var bottom = canvasSize.height;

		return ( posX >= left && posX <= right && 
			posY >= top && posY <= bottom);
	}
	//************************************************************************

	function show () {
		var tools = document.getElementById('tools');
		var content = document.getElementById('content');
		if(tools.className == "hidden") {
			tools.className = "visible";
			content.className = "contentspace";
		} else {
			tools.className = "hidden";
			content.className = "contentnospace";
		}
	}




	var x;
	var imgData;

	var history = [];	
	




	function copy () {
		var canvas = document.getElementById('canvas');
		this.ctx=canvas.getContext("2d");
		imgData=this.ctx.getImageData(0,0,960,640);
		history.push(imgData);

	}

	

	function fill () {
		var canvas = document.getElementById('canvas');
		this.ctx=canvas.getContext("2d");

		this.ctx.putImageData(imgData,0,0);
	}

	function setColor() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#ff0000";
		this.ctx.strokeStyle=farbe;

	}

	function setColor1() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#009900";
		this.ctx.strokeStyle=farbe;

	}

	function setColor2() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#0033CC";
		this.ctx.strokeStyle=farbe;

	}



	function setColor3() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#000000";
		this.ctx.strokeStyle=farbe;

	}

	function setColor4() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#FFFF00";
		this.ctx.strokeStyle=farbe;

	}

	function setColor5() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#FF00FF";
		this.ctx.strokeStyle=farbe;

	}


	function rubber() {
		console.log("jau");

		var canvas = document.getElementById('canvas');
		var farbe="#FFFFFF";
		this.ctx.strokeStyle="#FFFFFF";

	}



	var dicke=20;
	var trans=1;


	function setShadow(){
		var canvas = document.getElementById('canvas');
		this.ctx.shadowBlur = 3;
		this.ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
		this.ctx.shadowOffsetX = 3;
		this.ctx.shadowOffsetY = 6;

	}

	function setRound(){
		var canvas = document.getElementById('canvas');
		var brush = "round"
		this.ctx.lineCap = brush;

	}

	function setBlock(){
		var canvas = document.getElementById('canvas');
		var brush ="square"
		this.ctx.lineCap = brush;

	}

	function setSplit(){
		var canvas = document.getElementById('canvas');
		var brush ="butt"
		this.ctx.lineCap = brush;

	}


	function showValueDicke(val){

		dicke = document.getElementById('resultDicke').value= val;

	}function showValueTrans(val){

		trans = document.getElementById('resultTrans').value= val;

	}


	$('#canvas').disableSelection();



	</script>