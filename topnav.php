
<style type="text/css">



/*	div#searchicon:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/lupeB.png'; ?>);
	}
	div#searchicon {
		background-image: url(<?php echo path.'/ressources/img/ico/lupeA.png'; ?>);
		background-size: 100% 100%;
		width: 22px;
		height: 22px;
		margin-left: 15px;
		margin-bottom: 9px;
		margin-top: 9px;
		display: inline;
		float: left;
	}
	div#mainsearchbar {
		opacity: 0.0;
	}*/

	#friends:hover {
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/friendshover.png'; ?>);
	}

	#friends {
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/friends.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;
	}

	#favorites:hover {
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/favshover.png'; ?>);
	}

	#favorites {
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/favs.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;
	}

	#gallery:hover {
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/galleryhover.png'; ?>);
	}

	#gallery {
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/gallery.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;

	}

	#wall:hover { 
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/wallhover.png'; ?>);
	}

	#wall { 
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/wall.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;
	
	}

	#logout:hover { 
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/tuerzuhover.png'; ?>);
	}

	#logout { 
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/tuerzu.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;
	}

	#settings { 
		background-image: url(<?php echo path.'/ressources/img/scribbitIcon/einstellungen.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 60px;
		margin-top: 10px;

	}
	#settings:hover {
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/einstellungenhover.png'; ?>);
	}

	.homelogo { 
		background-image: url(<?php echo path.'/ressources/img/schriftzug.png'; ?>);
		background-size: 100% 100%;
		height: 60px;
		width: 170px;
		margin-top: 10px;

	}
	.homelogo:hover {
		background-image: url(<?php echo path.'/ressources/img/schriftzughover.png'; ?>);
	}

	#profilepic{
			background-image: url(<?php echo path.'/ressources/img/scribbitIcon/profilepic.png'; ?>);
			width:60px; 
			height:60px;
			margin-top: 10px;
			float: left;
		}

	#profilepic:hover {
		background-image: url(<?php echo path.'/ressources/img/scribbitIconhover/profilepichover.png'; ?>);
	}


 </style>


<div id="logo" class="homelogo">
	<?php
	echo '<a href="'.path.'/"></a>';
	?>
</div>
<div class="topnav">
	<div class="main"><?php echo '<span><a href="'.path.'/wall"><div id="wall"></div></a></span>' ?></div>
	<div class="main"><?php echo '<span><a href="'.path.'/gallery"><div id="gallery"></div></a></span>' ?></div>
	<div id="profilenav">
	
	<?php 
		echo '<div id="profileline">';
		if ($loggedIn) {
			echo ' Hello<br> <a href="'.path.'/profile">'.$_SESSION['username'].'</a>!';
		} else {

		}
		echo '</div>';
	?>
	

</div>

<?php 
//include docroot.'/'.path.'/searchbar.php'; 
?>


	<div class="profilelogout">
		<?php echo '<span><a href="'.path.'/logout"><div id="logout"></div></a></span>'; ?>
	</div>
	<div id="profileicons">
	<div class="social"><?php echo '<span><a href="'.path.'/settings"><div id="settings"></div></a></span>' ?></div>
		<div class="social"><?php echo '<span><a href="'.path.'/favorites"><div id="favorites"></div></a></span>' ?></div>
	<div class="social"><?php echo '<span><a href="'.path.'/friends"><div id="friends"></div></a></span>' ?></div>
	<div id="profilepic"></div>


	</div>
	

</div>