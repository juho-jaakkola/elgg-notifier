<?php
/**
 * Create an empty popup module to be populated on demand via XHR request.
 */

$dismiss_link = elgg_view('output/url', array(
	'href' => 'action/notifier/dismiss',
	'text' => elgg_view_icon('checkmark'),
	'title' => elgg_echo('notifier:dismiss_all'),
	'class' => 'float-alt hidden',
	'id' => 'notifier-dismiss-all',
	'is_action' => true,
	'is_trusted' => true,
));

$title = elgg_echo('notifier:notifications');
$header = "<h3 class=\"float\">$title</h3>$dismiss_link";

$vars = array(
	'class' => 'hidden elgg-notifier-popup',
	'id' => 'notifier-popup',
	'header' => $header
);

echo elgg_view_module('popup', '', '', $vars);