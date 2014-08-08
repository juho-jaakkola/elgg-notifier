<?php
/**
 * Display a list of all notifications
 */

// Link to notification settings
elgg_register_menu_item('title', array(
	'name' => 'notification-settings',
	'href' => 'notifications/personal',
	'text' => elgg_echo('settings'),
	'link_class' => 'elgg-button elgg-button-action',
));

// Link to remove all notifications
elgg_register_menu_item('title', array(
	'name' => 'notification-delete',
	'href' => 'action/notifier/clear',
	'text' => elgg_echo('notifier:clear_all'),
	'link_class' => 'elgg-button elgg-button-delete elgg-requires-confirmation',
	'is_action' => true,
	'rel' => elgg_echo('notifier:deleteconfirm'),
));

// Link to dismiss all unread notifications
elgg_register_menu_item('title', array(
	'name' => 'notification-dismiss',
	'href' => 'action/notifier/dismiss',
	'text' => elgg_echo('notifier:dismiss_all'),
	'link_class' => 'elgg-button elgg-button-submit',
	'is_action' => true,
));

$params = array();

$params['title'] = elgg_echo('notifier:all');
$params['filter'] = '';

$notifications = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'limit' => 20,
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'full_view' => false,
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => 'DESC'
	),
	'list_class' => 'elgg-list-notifier',
));

if ($notifications) {
	$params['content'] = $notifications;
} else {
	$none_text = elgg_echo('notifier:none');
	$content = "<span class=\"notifier-none\">$none_text</span><ul class=\"elgg-list elgg-list-notifier\"></ul>";

	$params['content'] = $content;
}

$body = elgg_view_layout('content', $params);
echo elgg_view_page($params['title'], $body);
