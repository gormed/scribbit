<?php 
require_once 'path.php'; require_once 'header.php';
if (isset($_POST['parentid']) && isset($_POST['where'])) {
	$where = $_POST['where'];
	$parentid = $_POST['parentid'];
} else {
	header("location: ".path."/gallery");
}

?>

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



<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/scribble.js"></script>'; ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
<script type="text/javascript" src="https://raw.github.com/caleb531/jcanvas/master/jcanvas.min.js"></script>
<script type="text/javascript">
<?php 
	echo 'var parentid = '.$parentid.';';
	echo 'var where = '.$where.';';
?>

</script>
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

    <script>
    
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
	function clearCanvas() {
		var context = document.getElementById('canvas').getContext("2d");
		var imageData = context.clearRect(0,0,960,640);
	}


	//************************************************************************
	function saveImage () {
		
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
			pressure = 0.25;
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
				this.ctx = canvas.getContext("2d");
				this.ctx.beginPath();
				this.ctx.moveTo(lastX, lastY);
				this.ctx.lineTo(curX, curY);
				
				//console.log("mousemove: cur: " + curX + "," + curY);

				this.ctx.lineCap = 'round';

				//this.ctx.shadowBlur = 3;
  				//this.ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
  				//this.ctx.shadowOffsetX = 3;
  				//this.ctx.shadowOffsetY = 6;

				

				
				//var farbe= [];
				//farbe[0]="green";
				//farbe[1]="blue";
				//farbe[2]="red";
				//farbe[3]="grey";
				//farbe[4]="black";

				//rot="red";
				
				
				
				

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

		//console.log("ctx.lineWidth: " + ctx.lineWidth);
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

<script type="text/javascript">
	var x;
	var imgData;

	function copy () {
		var canvas = document.getElementById('canvas');
		 this.ctx=canvas.getContext("2d");
		 imgData=this.ctx.getImageData(0,0,960,640);
		 
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


function rubber() {
	console.log("jau");

	var canvas = document.getElementById('canvas');
	var farbe="#FFFFFF";
	this.ctx.strokeStyle=farbe;

}

 function showValueDicke(val){
 			
          dicke = document.getElementById('resultDicke').value= val;

}function showValueTrans(val){
 			
          trans = document.getElementById('resultTrans').value= val;

}


$('#canvas').disableSelection();



</script>






</head>

<body onselectstart="return false" onload="onLoad();"><!-->markieren verhindern<!-->

	<button onclick="copy()">Copy</button>
	<button onclick="fill()">fill</button>
	

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
	<div id="site">
		
		<div id="tools" class="hidden">
			<div>
				<div id="clear" title="Clear" onclick="clearCanvas();">clear</div><br>
				
					<div id="stepback" title="back" onclick="...();">back</div>
					<div id="stepforward" title="forward" onclick="...();">for</div><br><br>
					<div id="rubber" title="rubber" onclick="rubber();">rubber</div><br>
				
					<div>
						<div id="color1">
								<div id="cbox3" onclick="setColor3();"> </div>
								<div id="cbox" onclick="setColor();"> </div>
								<div id="cbox1" onclick="setColor1();"></div>
								<div id="cbox2" onclick="setColor2();"></div>
						</div>

						<div id="brush" onclick="...();">brush</div>
					</div>

				
				<div id="bar">
				<input title="width"  type="range" min="1" max="150" value="50" step="1" onChange="showValueDicke(this.value);" />
						<input type ="text" id="resultDicke" value="" />
				<input  title="opacity"type="range" min="0.1" max="1" value="1" step="0.1" onChange="showValueTrans(this.value);" />
						<input type ="text" id="resultTrans" value="" />
				</div>
				<br>

				
				
				
				


				<input type="button" value="Publish" id="publish" title="Publish" onclick="saveImage();"></input>

			</div>
		</div>

		


		<div id="content" class="contentnospace">
			<div id="show" onclick="show();">Show/Hide</div>
			<div id="share">
				Share: <input type="text" size="30" value="http://scribbit.com/7ezebU6">
			</div>
			<div id="upload"></div>
			<canvas id="canvas" width="960" height="640" onmousedown="mousedown(event);" onmouseup="mouseup();" onmousemove="mousemove();"> </canvas>
		</div>
	</div>



</body>
</html>