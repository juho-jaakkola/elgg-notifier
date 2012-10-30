<?php
/**
 * Create a popup module with latest notifications.
 */

$vars = array(
	'class' => 'hidden elgg-notifier-popup',
	'id' => 'notifier-popup',
);

$title = elgg_echo('notifier:notifications');

$notifications = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
));

// Link to all notifications
$all_notifications_link = elgg_view('output/url', array(
	'href' => 'notifier/all',
	'text' => elgg_echo('notifier:all'),
	'class' => 'float',
	'id' => 'notifier-view-all',
));

$none_message = elgg_echo('notifier:none'); 

$body = <<<HTML
	$notifications
	$all_notifications_link
	<span id="notifier-messages-none" class="hidden">$none_message</span>
HTML;

$content = elgg_view_module('popup', $title, $body, $vars);

echo $content;