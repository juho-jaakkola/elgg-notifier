<?php
/**
 * Create a popup module that displays latest notifications.
 */

$notifications = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => DESC
	),
));

if ($notifications) {
	// Use "widgets" context to tell that we're displaying the popup instead of a full list
	elgg_push_context('widgets');
	$notification_list = elgg_view_entity_list($notifications, array(
		'full_view' => false,
		'pagination' => false,
	));
	elgg_pop_context();

	// Link to all notifications
	$link = elgg_view('output/url', array(
		'href' => 'notifier/all',
		'text' => elgg_echo('notifier:view:all'),
		'class' => 'float',
		'id' => 'notifier-view-all',
		'is_trusted' => true,
	));

	$dismiss_link = '';
	// Display dismiss link only if there are unread notifications
	foreach ($notifications as $notification) {
		if ($notification->status == 'unread') {
			$dismiss_link = elgg_view('output/url', array(
				'href' => 'action/notifier/dismiss',
				'text' => elgg_view_icon('checkmark'),
				'title' => elgg_echo('notifier:dismiss_all'),
				'class' => 'float-alt',
				'id' => 'notifier-dismiss-all',
				'is_action' => true,
				'is_trusted' => true,
			));
			break;
		}
	}
} else {
	$link = elgg_echo('notifier:none');
}

$settings_link = elgg_view('output/url', array(
	'href' => 'notifications/personal',
	'text' => elgg_echo('settings'),
	'class' => 'float-alt',
	'is_trusted' => true,
));

$title = elgg_echo('notifier:notifications');
$header = "<h3 class=\"float\">$title</h3>$dismiss_link";

$body = "$notification_list $link $settings_link";

$vars = array(
	'class' => 'hidden elgg-notifier-popup',
	'id' => 'notifier-popup',
	'header' => $header
);

$content = elgg_view_module('popup', '', $body, $vars);

echo $content;