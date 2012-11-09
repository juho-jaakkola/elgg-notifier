<?php
/**
 * ElggNotification view.
 *
 * @package Notifier
 */

$notification = $vars['entity'];

if ($notification->target_type === 'entity') {
	$target = get_entity($notification->target);
} else {
	$target = elgg_get_annotation_from_id($notification->target);
}

if (!$target) {
	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}

$date = elgg_view_friendly_time($notification->time_created);

if (elgg_instanceof($target, 'object')) {
	//$title = $CONFIG->register_objects[$target->getType()][$target->getSubtype()];
	
	// Route through notifier page handler to update notification status
	$target_link = elgg_view('output/url', array(
		'href' => "notifier/view/{$notification->getGUID()}",
		'text' => $target->title,
		'is_trusted' => true,
	));
	
	$subject = $target->getOwnerEntity();

	$subject_link = '';
	if ($subject) {
		$subject_link = elgg_view('output/url', array(
			'href' => $subject->getURL(),
			'text' => $subject->name,
			'is_trusted' => true,
		));
	}

	$type = $target->getType();
	$subtype = $target->getSubtype();
	$subtitle = elgg_echo("river:create:$type:$subtype", array($subject_link, $target_link));
} elseif ($notification->target_type === 'annotation') {
	$subject = get_entity($target->owner_guid);
	$entity = get_entity($target->entity_guid);
	
	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
	));
	
	$entity_link = elgg_view('output/url', array(
		'href' => "notifier/view/{$notification->getGUID()}",
		'text' => $entity->title,
	));
	
	$type = $entity->getType();
	$subtype = $entity->getSubtype();
	$subtitle = elgg_echo("river:comment:$type:$subtype", array($subject_link, $entity_link));
} else {
	$subject = get_entity($target->owner_guid);

	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
	));

	$entity = get_entity($target->entity_guid);
	$entity_link = elgg_view('output/url', array(
		'href' => "notifier/view/{$notification->getGUID()}",
		'text' => $entity->title,
	));

	$subtitle = elgg_echo($notification->title, array($subject_link, $entity_link));
}

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
	'subtitle' => $subtitle,
	//'content' => $notification->description,
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
