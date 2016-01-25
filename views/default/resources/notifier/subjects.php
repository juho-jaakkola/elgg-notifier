<?php
/**
 * Display a list of notification subjects
 */

$guid = elgg_extract('guid', $vars);
$notification = get_entity($guid);

if ($notification) {
	$content = elgg_view_entity_list($notification->getSubjects());
} else {
	$content = elgg_echo('noaccess');
}

echo <<<HTML
<div class="notifier-lightbox-wrapper">
	$content
</div>
HTML;
