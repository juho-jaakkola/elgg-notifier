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
	'link_class' => 'elgg-button elgg-button-delete',
	'data-confirm' => elgg_echo('notifier:deleteconfirm'),
	'is_action' => true,
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
$params['content'] = elgg_view('lists/notifications');

$body = elgg_view_layout('content', $params);
echo elgg_view_page($params['title'], $body);
