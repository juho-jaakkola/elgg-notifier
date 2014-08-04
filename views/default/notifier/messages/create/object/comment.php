<?php
/**
 * Display multiple comments in one notification message
 */

$notification = $vars['entity'];
$subjects = $notification->getSubjects();
$subject_count = count($subjects);
$target = elgg_extract('target', $vars);

if (elgg_instanceof($target, 'object', 'comment')) {
	$vars['target'] = $target->getContainerEntity();
}

if ($subject_count === 1) {
	$subtitle = elgg_view('notifier/messages/create/default', $vars);
} else {
	$target_link = elgg_view('output/url', array(
		'href' => $target->getURL(),
		'text' => $notification->getTargetName(),
		'is_trusted' => true,
	));

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

		$subjects_string = elgg_echo('notifier:two_subjects', array($subject_link, $subject2_link));
	} else {
		// One of the users is mentioned by name so remove that user from the count
		$subject_count--;
		$subjects = elgg_echo('notifier:multiple_subjects', array($subject->name, $subject_count));

		$guid = $notification->getGUID();
		$subjects_string = elgg_view('output/url', array(
			'href' => "notifier/subjects/$guid",
			'text' => $subjects,
			'is_trusted' => true,
			'class' => 'elgg-lightbox',
		));
	}

	$subtitle = elgg_echo('comment:notifications:summary:n', array($subjects_string, $target_link));
}

echo $subtitle;
