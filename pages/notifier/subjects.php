<?php
/**
 * Display a list of notification subjects
 */

$notification = get_entity(get_input('guid'));

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
