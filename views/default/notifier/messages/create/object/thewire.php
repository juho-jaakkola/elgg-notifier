<?php
/**
 * Custom notification template for The Wire notifications
 *
 * The Wire posts do not have a title, and an extract taken from the
 * post contents would be confusing. Therefore the message simply
 * says: "X posted to The Wire".
 */

$notification = elgg_extract('notification', $vars);
$target = elgg_extract('target', $vars);
$subject = elgg_extract('subject', $vars);

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'is_trusted' => true,
));

$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => elgg_echo('thewire:wire'),
	'is_trusted' => true,
));

echo elgg_echo('river:create:object:thewire', array($subject_link, $target_link));
