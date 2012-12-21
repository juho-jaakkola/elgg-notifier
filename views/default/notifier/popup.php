<?php
/**
 * Create a popup module with latest notifications.
 */

$vars = array(
	'class' => 'hidden elgg-notifier-popup',
	'id' => 'notifier-popup',
);

$title = elgg_echo('notifier:notifications');

$notifications = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => DESC
	),
	'pagination' => false
));

if ($notifications) {
	// Link to all notifications
	$link = elgg_view('output/url', array(
		'href' => 'notifier/all',
		'text' => elgg_echo('notifier:view:all'),
		'class' => 'float',
		'id' => 'notifier-view-all',
	));
} else {
	$link = elgg_echo('notifier:none');
}

$settings_link = elgg_view('output/url', array(
	'href' => 'notifications/personal',
	'text' => elgg_echo('settings')
));

$body = <<<HTML
	$notifications
	$link
	<span class="float-alt">$settings_link</span>
HTML;

$content = elgg_view_module('popup', $title, $body, $vars);

echo $content;