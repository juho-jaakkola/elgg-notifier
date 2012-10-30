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

$title = $notification->title;
if (!$title) {
	$title = $notification->name;
}
if (!$title) {
	$title = get_class($notification);
}

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

$entity_link = elgg_view('output/url', array(
	'href' => $entity->getURL(),
	'text' => $entity->title,
	'is_trusted' => true,
));

$icon = elgg_view_entity_icon($owner, 'tiny');

$date = elgg_view_friendly_time($notification->time_created);

$by_user = elgg_echo('byline', array($owner_link));

$subtitle = "$title $entity_link $by_user $date";

$params = array(
	'entity' => $notification,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
