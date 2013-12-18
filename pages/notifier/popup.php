<?php
/**
 * Display a notifications list that can be added to the notifications popup.
 */

$notifications = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => 'DESC'
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
} else {
	$link = elgg_echo('notifier:none');
}

$settings_link = elgg_view('output/url', array(
	'href' => 'notifications/personal',
	'text' => elgg_echo('settings'),
	'class' => 'float-alt',
	'is_trusted' => true,
));

echo "$notification_list $link $settings_link";