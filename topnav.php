
<style type="text/css">

@media screen and (max-width: 700px) {

	div#searchicon:hover {
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
	}

	#friends:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/frieB.png'; ?>);
	}

	#friends {
		background-image: url(<?php echo path.'/ressources/img/ico/frieA.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;
	}

	#favorites:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/favoB.png'; ?>);
	}

	#favorites {
		background-image: url(<?php echo path.'/ressources/img/ico/favoA.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;
	}

	#gallery:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/gallB.png'; ?>);
	}

	#gallery {
		background-image: url(<?php echo path.'/ressources/img/ico/gallA.png'; ?>);
		background-size: 100% 100%;
		height: 22px;
		width: 22px;
		margin-top: 9px;
		margin-bottom: 9px;
	}

	#wall:hover { 
		background-image: url(<?php echo path.'/ressources/img/ico/wallB.png'; ?>);
	}

	#wall { 
		background-image: url(<?php echo path.'/ressources/img/ico/wallA.png'; ?>);
		background-size: 100% 100%;
		height: 22px;
		width: 32px;
		margin-top: 9px;
		margin-bottom: 9px;
	}

	#logout:hover { 
		background-image: url(<?php echo path.'/ressources/img/ico/door_open.png'; ?>);
	}

	#logout { 
		background-image: url(<?php echo path.'/ressources/img/ico/door.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;
	}

	#settings { 
		background-image: url(<?php echo path.'/ressources/img/ico/zahnradA.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;

	}
	#settings:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/zahnradB.png'; ?>);
	}
} 

@media screen and (min-width: 701px) {
	div#searchicon:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/lupeB.png'; ?>);
	}
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
		opacity: 0.0;
	}

	#friends:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/frieB.png'; ?>);
	}

	#friends {
		background-image: url(<?php echo path.'/ressources/img/ico/frieA.png'; ?>);
		background-size: 100% 100%;
		height: 20px;
		width: 20px;
		margin-top: 25px;
		margin-bottom: 15px;
	}

	#favorites:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/favoB.png'; ?>);
	}

	#favorites {
		background-image: url(<?php echo path.'/ressources/img/ico/favoA.png'; ?>);
		background-size: 100% 100%;
		height: 20px;
		width: 20px;
		margin-top: 25px;
		margin-bottom: 15px;
	}

	#gallery:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/gallB.png'; ?>);
	}

	#gallery {
		background-image: url(<?php echo path.'/ressources/img/ico/gallA.png'; ?>);
		background-size: 100% 100%;
		height: 32px;
		width: 32px;
		margin-top: 9px;
		margin-bottom: 9px;
	}

	#wall:hover { 
		background-image: url(<?php echo path.'/ressources/img/ico/wallB.png'; ?>);
	}

	#wall { 
		background-image: url(<?php echo path.'/ressources/img/ico/wallA.png'; ?>);
		background-size: 100% 100%;
		height: 32px;
		width: 44px;
		margin-top: 9px;
		margin-bottom: 9px;
	}

	#logout:hover { 
		background-image: url(<?php echo path.'/ressources/img/ico/door_open.png'; ?>);
	}

	#logout { 
		background-image: url(<?php echo path.'/ressources/img/ico/door.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;
		margin: 5px;
	}

	#settings { 
		background-image: url(<?php echo path.'/ressources/img/ico/zahnradA.png'; ?>);
		background-size: 100% 100%;
		height: 20px;
		width: 20px;
		margin-top: 25px;
		margin-bottom: 15px;
	}
	#settings:hover {
		background-image: url(<?php echo path.'/ressources/img/ico/zahnradB.png'; ?>);
	}
}

</style>
<div id="logo">
	<?php
	echo '<a href="'.path.'/"><</a>';
	?>
</div>
<div class="topnav">

	<div class="main"><?php echo '<span><a href="'.path.'/wall"><div id="wall"></div></a></span>' ?></div>
	<div class="main"><?php echo '<span><a href="'.path.'/gallery"><div id="gallery"></div></a></span>' ?></div>
</div>

<?php include docroot.'/'.path.'/searchbar.php'; ?>

<div id="profilenav">
	<div class="profilelogout"><?php echo '<span><a href="'.path.'/logout"><div id="logout"></div></a></span>'; ?></div>
	<?php 
		echo '<div id="profileline">';
		if ($loggedIn) {
			echo ' Hello <a href="'.path.'/profile">'.$_SESSION['username'].'</a>!';
		} else {

		}
		echo '</div>';
	?>
	
	<div id="profileicons">
	<div class="social"><?php echo '<span><a href="'.path.'/settings"><div id="settings"></div></a></span>' ?></div>
	<div class="social"><?php echo '<span><a href="'.path.'/friends"><div id="friends"></div></a></span>' ?></div>
	<div class="social"><?php echo '<span><a href="'.path.'/favorites"><div id="favorites"></div></a></span>' ?></div>
	</div>

</div>