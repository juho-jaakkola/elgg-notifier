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
	elgg_add_admin_notice('notifier_no_target', $notice);

	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}

if (empty($target->title)) {
	$text = elgg_get_excerpt($target->description, 20);
} else {
	$text = $target->title;
}

// Route through notifier page handler to update notification status
$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => $text,
	'is_trusted' => true,
));

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'is_trusted' => true,
));

$subtitle = elgg_echo($notification->title, array($subject_link, $target_link));

$time = elgg_view_friendly_time($notification->time_created);

if (elgg_in_context('widgets')) {
	// Do not show the delete link in widget view
	$metadata = '';
} else {
	// Use link instead of entity menu since we don't want any links besides delete
	$metadata = elgg_view('output/confirmlink', array(
		'name' => 'delete',
		'href' => "action/notifier/delete?guid={$notification->getGUID()}",
		'text' => elgg_view_icon('delete'),
		'class' => 'float-alt',
	));
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
