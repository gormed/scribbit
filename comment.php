
<a href="#comments" id="commentslink"><hr width=50% id="bar" size=1><div id="showcomments" onclick="showComments();">↓ Comments ↓</div></a>
<div id="comments" class="hidden">
	<!-- 
	***************************************************************** 
	 Embed the Wacom TabletPlugin object.
	 To avoid plugin selection on page, size and position are adjusted 
	 so as to "tuck it under" canvas. 
	***************************************************************** 
	-->

	<!--[if IE]>

	<object id='wtPlugin' classid='CLSID:092dfa86-5807-5a94-bf3b-5a53ba9e5308' WIDTH=1 HEIGHT=1 style="position:absolute; left:100px; top:100px">
	</object>

	<![endif]--><!--[if !IE]> <-->
	<object id="wtPlugin" type="application/x-wacomtabletplugin" WIDTH=1 HEIGHT=1 style="position:absolute; left:20px; top:20px">
		<!-- <param name="onload" value="pluginLoaded" /> -->
	</object>

	<canvas id="canvas" width="150" height="150" onmousedown="mousedown(event);" onmouseup="mouseup();" onmousemove="mousemove(); " > </canvas>
	<div id="makecomment">
		<a href="#submit"><div tag="submit" id="submitcomment" onclick="saveComment();">Submit <div id="upload"></div></div></a>
		<a href="#clear"><div tag="clear" id="clear" onclick="clearCanvas();">Clear</div></a>
	</div>
	<div id="commentlist">
		<div id="commentholder">
		</div>
	</div>
</div>