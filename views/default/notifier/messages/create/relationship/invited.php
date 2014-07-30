<?php

$notification = elgg_extract('notification', $vars);
$group = elgg_extract('target', $vars);
$subject = elgg_extract('subject', $vars);

$recipient = $notification->getOwnerEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'is_trusted' => true,
));

if ($group->isPublicMembership() && $group->access_id !== $group->group_acl) {
	// This is a public group that anyone can join,
	// so take user directly to the group page.
	$group_link = elgg_view('output/url', array(
		'href' => $group->getURL(),
		'text' => $group->getDisplayName(),
		'is_trusted' => true,
	));

	$subtitle = elgg_echo('groups:notifications:invitation', array($subject_link, $group_link));
} else {
	// Group is either hidden or requires an invitation to
	// join, so display a link to the group invitations page.
	$invitations_link = elgg_view('output/url', array(
		'href' => "groups/invitations/{$recipient->username}",
		'text' => elgg_echo('groups:invitation'),
	));

	$subtitle = elgg_echo('groups:notifications:invitation:hidden', array($invitations_link, $subject_link));
}

echo $subtitle;
