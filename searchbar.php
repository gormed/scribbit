<script type="text/javascript">

	$(document).ready(function(){
			$("#mainsearchbar").hide();
			$("#mainsearchbar").hover(
				function() { 
					$("#mainsearchbar").show(); 
				},
				function() {
					$("#mainsearchbar").animate({opacity:'0.0'}, "fast", function() {
						$("#mainsearchbar").hide();
					});
				} );
			$("#searchicon").hover(
				function() { 
					$("#mainsearchbar").show(); 
					$("#mainsearchbar").animate({opacity:'1.0'}, "fast", function() {
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