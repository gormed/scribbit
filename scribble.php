<?php require_once 'path.php'; require_once 'header.php';?>

<html>
<head>
<title>Scribb'it - Scribble</title>
<!--<script type="text/javascript" src="ressources/js/scribble.js"></script>-->
<link rel="stylesheet" type="text/css" href="ressources/css/scribble.css">
<style type="text/css">
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

	var canvasPos = {x:0.0, y:0.0};
	var canvasSize = {width:960, height:640};
	var lastX = 0.0;
	var lastY = 0.0;
	var capturing = false;	// tracks in/out of canvas context

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
	  
	function clearCanvas() {
		var context = document.getElementById('canvas').getContext("2d");
		var imageData = context.clearRect(0,0,960,640);
	}

	function saveImage () {

		var xmlhttp;
		var canvas = document.getElementById('canvas');
		var img = canvas.toDataURL("image/png");

		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST","upload_image.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("data=" + img);
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
	//console.log("MOUSE:MOVE");

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
			pressure = 1.0;
			isEraser = false;
		}

	//console.log("pressure: " + pressure);
		
	curX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
	curY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;

	capturing = inCanvasBounds(curX, curY);

	if (capturing && pressure > 0.0)
	{
		if (canvas.getContext)
		{
				var ctx = canvas.getContext("2d");
				ctx.beginPath();
				ctx.moveTo(lastX, lastY);
				ctx.lineTo(curX, curY);
				
				//console.log("mousemove: cur: " + curX + "," + curY);

				ctx.lineCap = 'round';
				if (isEraser == true) 
				{
					ctx.lineWidth = 25.0 * pressure;
					ctx.strokeStyle = "rgba(255, 255, 255, 1.0)";

				}
				else 
				{
					ctx.lineWidth = 25.0 * pressure;
					ctx.strokeStyle = "rgba(128, 0, 0, 1.0)";
				}

		//console.log("ctx.lineWidth: " + ctx.lineWidth);
				ctx.stroke();
				var tempImg =ctx.getImageData(0,0,640,480);
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
	</script>
<script type="text/javascript">
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
</script>
</head>

<body onload="onLoad();">

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

	<object id="wtPlugin" type="application/x-wacomtabletplugin" WIDTH=1 HEIGHT=1 style="position:absolute; left:100px; top:100px">
		<!-- <param name="onload" value="pluginLoaded" /> -->
	</object>

	
	<!--> <![endif]-->
	<div id="site">
		
		<div id="tools" class="hidden">
			<div>
				<ul>
					<a href=""><li>Brush</li></a>
					<a href=""><li>Fill</li></a>
					<a href=""><li>Marquee</li></a>
					<a href=""><li>Symbol</li></a>
					<a href=""><li>...</li></a>
					<a href=""><li>Get new tools!</li></a>
				</ul>

				<div id="cut1" title="Back"></div>
				<div id="cut1" title="Forward"></div>
				<div id="cut1" title="Clear"></div>
				<div id="cut1" title="Save"></div>
				<div id="cut1" title="Back"></div>
				<div id="cut1" title="Forward"></div>
				<div id="cut1" title="Clear"></div>
				<div id="cut1" title="Save"></div>
				<div id="cut1" title="Back"></div>
				<div id="cut1" title="Forward"></div>
				<div id="cut1" title="Clear" onclick="clearCanvas()"></div>
				<div id="cut1" title="Save"></div>
				<a href="">
				<div id="publish" onclick="saveImage()">
					Publish
				</div>
				</a>
			</div>
		</div>
		<div id="content" class="contentnospace">
			<div id="show" onclick="show()">Show/Hide</div>
			<div id="share">
				Share: <input type="text" size="30" value="http://scribbit.com/7ezebU6">
			</div>
			<canvas id="canvas" onmousedown="mousedown(event);" onmouseup="mouseup();" onmousemove="mousemove();"> </canvas>
		</div>
	</div>
</body>
</html>