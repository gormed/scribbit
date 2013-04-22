<html>
<head>
<title>Scribb'it - Scribble</title>
<script type="text/javascript" src="ressources/js/scribble.js"></script>
<link rel="stylesheet" type="text/css" href="ressources/css/scribble.css">
<style type="text/css">
	body {
		color: black; background-color: #E0E0E0;
		font-size: 100.00%;
		font-family: 'Tahoma', sans-serif;
		vertical-align: middle;
		text-align: center;
	}

	div#tools {
		font-family: 'Tahoma', sans-serif;
		float: left;
		width: 10%;
		vertical-align: middle;
	}

	div#tools li {
		width: 90%;
		height: 1.5em;
	}

	canvas#scribble {
		width: 960;
		height: 640;
		padding: 10px;
		border: 1px solid;
		border-color: #333;
	}
</style>
</head>

<body>

	<div id="site">
		<div id="tools">
			<ul>
				<li>Brush</li>
				<li>Fill</li>
				<li>Marquee</li>
				<li>Symbol</li>
				<li>...</li>
				<li>Get new tools!</li>
			</ul>
		</div>
		<canvas id="scribble"> </canvas>
	</div>

</body>
</html>