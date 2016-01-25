<?php

/**
 * Create an empty popup module to be populated on demand via XHR request.
 */

if (!elgg_is_logged_in) {
	return;
}

elgg_require_js('notifier/notifier');

// Must always have lightbox loaded because views needing it come via AJAX
elgg_load_js('lightbox');
elgg_load_css('lightbox');

$list = elgg_format_element('div', [
	'id' => 'notifier-messages'
		]);

$links = array();

$links[] = elgg_view('output/url', array(
	'href' => 'action/notifier/dismiss',
	'text' => elgg_view_icon('check-square-o'),
	'title' => elgg_echo('notifier:dismiss_all'),
	'id' => 'notifier-dismiss-all',
	'class' => 'hidden',
	'is_action' => true,
	'is_trusted' => true,
		));

$links[] = elgg_view('output/url', array(
	'href' => 'notifier/all',
	'text' => elgg_echo('notifier:view:all'),
	'id' => 'notifier-view-all',
	'class' => 'hidden',
	'is_trusted' => true,
		));
$links[] = elgg_view('output/url', array(
	'href' => 'notifications/personal',
	'text' => elgg_view_icon('cog'),
	'title' => elgg_echo('settings'),
	'is_trusted' => true,
		));

$buttonbank = '';
foreach ($links as $link) {
	$buttonbank .= elgg_format_element('div', [], $link);
}

$footer = elgg_format_element('div', ['class' => 'elgg-foot'], $buttonbank);
$body = $list . $footer;

echo elgg_format_element('div', [
	'class' => 'elgg-module elgg-module-popup elgg-notifier-popup hidden',
	'id' => 'notifier-popup'
		], $body);
