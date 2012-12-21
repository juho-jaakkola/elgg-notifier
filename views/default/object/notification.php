<?php
/**
 * ElggNotification view.
 *
 * @package Notifier
 */

$notification = $vars['entity'];

$target = $notification->getTargetEntity();
$subject = $notification->getSubjectEntity();

if (!$target || !$subject) {
	// Add admin notice to help trace the reason of invalid notifications
	$title = $notification->title;
	$subject = $subject->username;
	$user = $notification->getOwnerEntity()->username;
	$notice = "Failed to view notification $title from user $subject to user $user";
	elgg_add_admin_notice('notifier_no_targer', $notice);

	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}

// Route through notifier page handler to update notification status
$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
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

$subtitle = elgg_echo($notification->title, array($subject_link, $target_link));

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
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);
