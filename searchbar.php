<script type="text/javascript">

	$(document).ready(function(){
			$("#mainsearchbar").hide();
			$("#mainsearchbar").hover(
				function() { 
					$("#mainsearchbar").show(); 
				},
				function() {
					$("#mainsearchbar").animate({width:'0px'}, "fast", function() {
						$("#mainsearchbar").hide();
					});
				} );
			$("#searchicon").hover(
				function() { 
					$("#mainsearchbar").show(); 
					$("#mainsearchbar").animate({width:'360px'}, "fast", function() {
						// body...
					});
					// $("#mainsearchbar").show(); 
				},
				function() {
					
					// $("#mainsearchbar").hide(); 
				} );
	});
</script>

<div id="searchicon"></div>
<div class="searchbar" >
	<style type="text/css">
		div#searchicon {
			background-image: url(<?php echo path.'/ressources/img/ico/lupeA.png'; ?>);
			background-size: 100% 100%;
			width: 32px;
			height: 32px;
			margin-left: 15px;
			margin-bottom: 9px;
			margin-top: 9px;
			display: inline;
			float: left;
		}
		div#mainsearchbar {
			width: 0px;
		}
	</style>
	<div id="mainsearchbar">
		<div id="search"><input class="searchtext" type="text" name="searchtext"/></div>
		<br>
		<div id="filterfav"><input name="filter" type="checkbox" value="favorits"/>Favorites&nbsp;</div>
		<div id="filterfriends"><input name="filter" type="checkbox" value="friends">Friends&nbsp;</div>		
		<div id="filtermy"><input name="filter" type="checkbox" value="my"/>Own&nbsp;</div>	
		<div id="filtertime">
			<select name="timefilter">
			<option value="all">all Time</option> 
			<option value="h24">last 24 h</option> 
			<option value="d7">last 7 days</option>
		</select></div>
	</div>
</div>