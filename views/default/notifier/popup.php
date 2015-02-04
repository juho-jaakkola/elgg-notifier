<?php
/**
 * Create an empty popup module to be populated on demand via XHR request.
 */

$dismiss_link = elgg_view('output/url', array(
	'href' => 'action/notifier/dismiss',
	'text' => elgg_view_icon('checkmark'),
	'title' => elgg_echo('notifier:dismiss_all'),
	'class' => 'float-alt',
	'id' => 'notifier-dismiss-all',
	'is_action' => true,
	'is_trusted' => true,
));

$title = elgg_echo('notifier:notifications');
$header = "<h3 class=\"float\">$title</h3>$dismiss_link";

$list = '<ul class="elgg-list elgg-list-entity elgg-list-notifier"></ul>';

$none_text = elgg_echo('notifier:none');
$none = "<span class=\"notifier-none\">$none_text</span>";

// Link to all notifications
$show_all_link = elgg_view('output/url', array(
	'href' => 'notifier/all',
	'text' => elgg_echo('notifier:view:all'),
	'class' => 'float',
	'id' => 'notifier-view-all',
	'is_trusted' => true,
));

$settings_link = elgg_view('output/url', array(
	'href' => 'notifications/personal',
	'text' => elgg_echo('settings'),
	'class' => 'float-alt',
	'is_trusted' => true,
));

$body = $list . $none . $show_all_link . $settings_link;

$vars = array(
	'class' => 'hidden elgg-notifier-popup',
	'id' => 'notifier-popup',
	'header' => $header
);

echo elgg_view_module('popup', '', $body, $vars);
