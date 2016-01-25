<?php

/**
 * Display a list of notification subjects
 */

elgg_ajax_gatekeeper();

$guid = elgg_extract('guid', $vars);
$notification = get_entity($guid);

if (!$notification instanceof ElggNotification) {
	forward('', '403');
}

$content = elgg_view_entity_list($notification->getSubjects());

echo elgg_format_element('div', [
	'class' => 'notifier-lightbox-wrapper',
		], $content);
