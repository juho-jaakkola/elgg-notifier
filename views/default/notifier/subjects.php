<?php
/**
 * Serve up list of notification subjects
 */

$guid = (int) get_input('guid');

$notification = get_entity($guid);

if ($notification) {
	echo elgg_view_entity_list($notification->getSubjects());
} else {
	echo elgg_echo('noaccess');
}
