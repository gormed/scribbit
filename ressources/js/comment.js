// **************************************************************************
// *    Scribble app using javascript Canvas object with WebTabletPlugin.
// *
// *    Copyright (c) Wacom Technology, Inc., 2012
// *
// * Notes:
// *    For use on Internet Explorer, Firefox, Chrome.
// *************************************************************************** 

var canvasPos = {x:0.0, y:0.0};
var canvasSize = {width: 150, height: 150};
var width = 150;
var height = 150;
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
function loadCanvas()
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
	var imageData = context.clearRect(0,0,width,height);
}

//************************************************************************
function mousedown(evt)
{
	console.log("MOUSE:DOWN");

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
	console.log("MOUSE:UP");
	var canvas = document.getElementById('canvas');
	canvas.onmousemove=null;
	capturing = false;
}  

//************************************************************************
function mousemove(evt)
{
	if(!capturing)
		return;
	console.log("MOUSE:MOVE");

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
		pressure = 0.1;
		isEraser = false;
	}

//console.log("pressure: " + pressure);
	
	curX = (ev.pageX?ev.pageX : ev.clientX + document.body.scrollLeft) - canvasPos.x;
	curY = (ev.pageY?ev.pageY : ev.clientY + document.body.scrollTop ) - canvasPos.y;

	//capturing = inCanvasBounds(curX, curY);

	if (pressure > 0.0)
	{
		if (canvas.getContext)
		{
				var ctx = canvas.getContext("2d");
				ctx.beginPath();
				// pressure += 0.1;
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
				var tempImg =ctx.getImageData(0,0,width,height);
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