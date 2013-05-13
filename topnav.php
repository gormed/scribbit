
<style type="text/css">
	#friends {
		background-image: url(<?php echo path.'/ressources/img/ico/frieA.png'; ?>);
		background-size: 100% 100%;
		height: 20px;
		width: 20px;
		margin-top: 15px;
		margin-bottom: 15px;
	}
	#favorites {
		background-image: url(<?php echo path.'/ressources/img/ico/favA.png'; ?>);
		background-size: 100% 100%;
		height: 20px;
		width: 17px;
		margin-top: 15px;
		margin-bottom: 15px;
	}
	#gallery {
		background-image: url(<?php echo path.'/ressources/img/ico/gallA.png'; ?>);
		background-size: 100% 100%;
		height: 32px;
		width: 32px;
		margin-top: 9px;
		margin-bottom: 9px;
	}
	#wall { 
		background-image: url(<?php echo path.'/ressources/img/ico/wallA.png'; ?>);
		background-size: 100% 100%;
		height: 32px;
		width: 44px;
		margin-top: 9px;
		margin-bottom: 9px;
	}
</style>

<div class="topnav">

	<div class="main"><?php echo '<span><a href="'.path.'/wall"><div id="wall"></div></a></span>' ?></div>
	<div class="main"><?php echo '<span><a href="'.path.'/gallery"><div id="gallery"></div></a></span>' ?></div>
</div>
	<?php include docroot.'/'.path.'/searchbar.php'; ?>
<div class="topnav">

	<div class="social"><?php echo '<span><a href="'.path.'/friends"><div id="friends"></div></a></span>' ?></div>
	<div class="social"><?php echo '<span><a href="'.path.'/favorites"><div id="favorites"></div></a></span>' ?></div>
</div>