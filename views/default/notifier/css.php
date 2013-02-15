
#notificationstable td.notifiertogglefield {
	width: 50px;
	text-align: center;
	vertical-align: middle;
}

#notificationstable td.notifiertogglefield input {
	margin-right: 36px;
	margin-top: 2px;
}

#notificationstable td.notifiertogglefield a {
	width: 46px;
	height: 18px;
	cursor: pointer;
	display: block;
	outline: none;
}

#notificationstable td.notifiertogglefield a.notifiertoggleOff {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 25px -72px;
}

#notificationstable td.notifiertogglefield a.notifiertoggleOn {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 25px -54px;
}

/* Style the popup module */
#notifier-popup {
	width: 345px;
	position: absolute;
}

.elgg-notifier-unread {
	background: #EDF5FF;
}

/* count of unread notifications in topbar icon */
#notifier-new {
	color: white;
	background-color: red;

	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;

	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);

	position: absolute;
	text-align: center;
	top: 0px;
	left: 26px;
	min-width: 16px;
	height: 16px;
	font-size: 10px;
	font-weight: bold;
}