<?php
/**
 * ElggNotification view.
 *
 * @package Notifier
 */

$notification = $vars['entity'];

if ($notification->target_type === 'entity') {
	$target = get_entity($notification->target);
	$subject = $target->getOwnerEntity();
} else {
	$annotation = elgg_get_annotation_from_id($notification->target);
	$target = get_entity($annotation->entity_guid);
	$subject = get_entity($annotation->owner_guid);
}

if (!$target) {
	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}
// Route through notifier page handler to update notification status
$target_link = elgg_view('output/url', array(
	'href' => "notifier/view/{$notification->getGUID()}",
	'text' => $target->title,
	'is_trusted' => true,
));

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'is_trusted' => true,
));

$type = $target->getType();
$subtype = $target->getSubtype();

if ($notification->target_type === 'entity') {
	$subtitle = elgg_echo("river:create:$type:$subtype", array($subject_link, $target_link));
} elseif ($notification->target_type === 'annotation') {
	$subtitle = elgg_echo("river:comment:$type:$subtype", array($subject_link, $target_link));
} else {
	$subtitle = elgg_echo($notification->title, array($subject_link, $target_link));
}

$time = elgg_view_friendly_time($notification->time_created);

if (elgg_instanceof($notification, 'object')) {
	$metadata = elgg_view('navigation/menu/metadata', $vars);
}

$icon = elgg_view_entity_icon($subject, 'tiny');

if ($notification->status === 'unread') {
	$vars['class'] = 'elgg-notifier-unread';
}

$params = array(
	'entity' => $notification,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => "$subtitle $time",
	//'content' => $notification->description,
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
