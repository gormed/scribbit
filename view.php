<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<!-- <?php echo $scribblepath.' '.$fromuser; ?> -->
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/header.css">'; ?>
		<?php echo '<link rel="stylesheet" type="text/css" href="'.path.'/ressources/css/view.css">'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQuery2.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.path.'/ressources/js/jQueryEvents.js"></script>'; ?>
		<title>Scribbit - Wall</title>
		<style type="text/css">
		div#picture {
		<?php echo 'background: url('.root.$scribblepath.'); '; ?>
			box-shadow: 0px 1px 10px #333;
			
			display: table-cell;
			/*border: 1px solid black;*/
			text-align: center;
			vertical-align: top;
			height: 640px; width: 960px;
			background-size: 100% 100%;
		}
		</style>
		<script type="text/javascript">
		
		// 0 topScrib;
		// 1 bottomScrib;
		// 2 leftScrib;
		// 3 rightScrib;
		var neighbours = {};

		function loadNeighbours() {
			
		}
		</script>
	</head>

	<body>
			<div id="site">
				
				<div id="header">
					<div id="logo">
						<?php
						echo '<a href="'.path.'/"><</a>';
						?>
					</div>
					<div>
						<ul class="topnav">
							<li>
								<span><a href="#">Profile</a></span>
								<ul class="subnav">
									<li><?php echo '<a href="'.path.'/profile">Go to Profile</a>'; ?></li>
									<li><a href="#">Freunde</a></li>
									<li><a href="#">Favoriten</a></li>
									<li><?php echo '<a href="'.path.'/logout">Logout</a>'; ?></li>
								</ul>
							</li>
							<li><?php echo '<span><a href="'.path.'/gallery">Gallery</a></span>'; ?></li>
							<li><?php echo '<span><a href="'.path.'/wall">Wall</a></span>'; ?></li>
						</ul>
					</div>	
				</div>
				<center>
					<div class="table" id="content">
						<div class="row">
							<div class="cell"></div>
							<div class="cell" id="top"><?php echo '<a href="'.path.'/view">upper picture <br>(show this)</a>'; ?></div>
							<div class="cell"></div>
						</div>
						<div class="row" id="wrapper">

							<div class="cell" id="leftcolumn">
								<?php echo '<a href="'.path.'/view">left picture <br>(show this)</a>'; ?>
							
							</div>
							
							<div id="picture">
								<?php echo '<div id="from">'.$fromname." | ".$fromdate.'</div>'; ?>
							</div>

							<div class="cell" id="rightcolumn">
								<?php echo '<a href="'.path.'/scribble">right space <br>(paint here)</a>'; ?>
							</div>

						</div>
						<div class="row">
							<div class="cell"></div>
							<div class="cell" id="bottom"><?php echo '<a href="'.path.'/scribble">bottom space <br>(paint here)</a>'; ?></div>
							<div class="cell"></div>
						</div>
					</div>
				</center>

				</div>