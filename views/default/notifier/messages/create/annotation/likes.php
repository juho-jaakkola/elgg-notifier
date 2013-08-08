<?php
/**
 * Special view that combines multiple likes into one notification message
 */

$subjects = $vars['subjects'];
$target_link = $vars['target_link'];
$subject_count = count($subjects);

$subject = $subjects[0];
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'is_trusted' => true,
));

if ($subject_count == 2) {
	$subject2 = $subjects[1];
	$subject2_link = elgg_view('output/url', array(
		'href' => $subject2->getURL(),
		'text' => $subject2->name,
		'is_trusted' => true,
	));

	$subtitle = elgg_echo('likes:notifications:summary:2', array($subject_link, $subject2_link, $target_link));
} else {
	$subtitle = elgg_echo('likes:notifications:summary:n', array($subject_link, $subject_count, $target_link));
}

echo $subtitle;