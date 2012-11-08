<?php
/**
 * ElggNotification view.
 *
 * @package Notifier
 */

$notification = $vars['entity'];

$entity = get_entity((int)$notification->target);

if (!$entity) {
	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}

$title = $CONFIG->register_objects[$entity->getType()][$entity->getSubtype()];

if (elgg_instanceof($notification, 'object')) {
	$metadata = elgg_view('navigation/menu/metadata', $vars);
}

$owner_link = '';
$owner = $notification->getOwnerEntity();
if ($owner) {
	$owner_link = elgg_view('output/url', array(
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
	));
}

// Route through notifier page handler to update notification status
$entity_link = elgg_view('output/url', array(
	'href' => "notifier/view/{$notification->getGUID()}",
	'text' => $entity->title,
	'is_trusted' => true,
));

$icon = elgg_view_entity_icon($owner, 'tiny');

$date = elgg_view_friendly_time($notification->time_created);

$by_user = elgg_echo('byline', array($owner_link));

$subtitle = "$title $entity_link $by_user $date";

if ($notification->status === 'unread') {
	$vars['class'] = 'elgg-notifier-unread';
}

$params = array(
	'entity' => $notification,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
