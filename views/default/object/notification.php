<?php
/**
 * ElggNotification view.
 *
 * @package Notifier
 */

$notification = $vars['entity'];

$target = $notification->getTarget();
$subjects = $notification->getSubjects();

if (!$target || empty($subjects)) {
	// Add admin notice to help trace the reason of invalid notifications
	$title = $notification->title;
	$event = $notification->event;
	$subject = $subject->username;
	$user = $notification->getOwnerEntity()->username;
	$notice = "Failed to view notification $title ($event) from user $subject to user $user";
	elgg_add_admin_notice('notifier_no_target', $notice);

	// The entity to notify about doesn't exist anymore so delete the notification
	$notification->delete();
	return false;
}

$display_name = $target->getDisplayName();
if (!empty($display_name)) {
	$text = $display_name;
} else {
	if (!empty($target->description)) {
		$text = elgg_get_excerpt($target->description, 20);
	} else {
		$text = elgg_echo('unknown');
	}
}

$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => $text,
	'is_trusted' => true,
));

$subject = $subjects[0];
$event_view = str_replace(':', '/', $notification->event);
$view = "notifier/messages/$event_view";

if (count($subjects) > 1 && elgg_view_exists($view)) {
	// Use special view for this notification type
	$subtitle = elgg_view($view, array(
		'entity' => $notification,
		'target_link' => $target_link
	));
} else {
	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'is_trusted' => true,
	));

	$subtitle = elgg_echo($notification->title, array($subject_link, $target_link));
}

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
