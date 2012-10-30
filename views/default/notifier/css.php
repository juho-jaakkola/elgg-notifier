
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