<?php

$notification = elgg_extract('notification', $vars);
$target = elgg_extract('target', $vars);
$subject = elgg_extract('subject', $vars);

// Check whether the string has already been translated
if (strpos(elgg_echo($notification->title), '%s') !== false) {
	// Use a separate link for subject and target

	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'is_trusted' => true,
	));

	$target_link = elgg_view('output/url', array(
		'href' => $target->getURL(),
		'text' => $notification->getTargetName(),
		'is_trusted' => true,
	));

	$subtitle = elgg_echo($notification->title, array($subject_link, $target_link));
} else {
	// Use the whole notification subject as a link text
	$subtitle = elgg_view('output/url', array(
		'href' => $target->getURL(),
		'text' => $notification->title,
		'is_trusted' => true,
	));
}

echo $subtitle;
