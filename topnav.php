
<style type="text/css">

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
		width: 17px;
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
		position: absolute;
		right: -19px;
		top: 2px;
	}

	#settings { 
		background-image: url(<?php echo path.'/ressources/img/ico/wrench_orange.png'; ?>);
		background-size: 100% 100%;
		height: 16px;
		width: 16px;
		margin-top: 25px;
		margin-bottom: 15px;
	}
</style>

<div class="topnav">

	<div class="main"><?php echo '<span><a href="'.path.'/wall"><div id="wall"></div></a></span>' ?></div>
	<div class="main"><?php echo '<span><a href="'.path.'/gallery"><div id="gallery"></div></a></span>' ?></div>
</div>

<?php include docroot.'/'.path.'/searchbar.php'; ?>

<div class="topnav">
	<div id="profilenav">
		
		<div class="social"><?php echo '<span><a href="'.path.'/settings"><div id="settings"></div></a></span>' ?></div>
		<div class="social"><?php echo '<span><a href="'.path.'/friends"><div id="friends"></div></a></span>' ?></div>
		<div class="social"><?php echo '<span><a href="'.path.'/favorites"><div id="favorites"></div></a></span>' ?></div>
		<?php 
			echo '<div id="profileline">';
			if ($loggedIn) {
				echo 'Hello <a href="'.path.'/profile">'.$_SESSION['username'].'</a>! <a href="'.path.'/logout"><div id="logout"></div></a>';
			} else {

			}
			echo '</div>';
		?>
	</div>
</div>