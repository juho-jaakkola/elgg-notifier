<?php

$personal_settings_desc = elgg_echo('notifier:settings:desc');

// Settings for personal notifications
$personal_label = elgg_echo('notifier:settings:enable_personal');
$personal_desc = elgg_echo('notifier:settings:enable_personal:desc');
$personal_options = array(
	'name' => 'params[enable_personal]',
	'value' => 1,
);
if ($vars['entity']->enable_personal) {
	$personal_options['checked'] = true;
}
$personal_input = elgg_view('input/checkbox', $personal_options);

// Settings for friend collections
$collections_label = elgg_echo('notifier:settings:enable_collections');
$collections_desc = elgg_echo('notifier:settings:enable_collections:desc');
$collections_options = array(
	'name' => 'params[enable_collections]',
	'value' => 1,
);
if ($vars['entity']->enable_collections) {
	$collections_options['checked'] = true;
}
$collections_input = elgg_view('input/checkbox', $collections_options);

// Settings for new group members
$group_settings_desc = elgg_echo('notifier:settings:groups:desc');
$groups_label = elgg_echo('notifier:settings:enable_groups');
$groups_desc = elgg_echo('notifier:settings:enable_groups:desc');
$groups_options = array(
	'name' => 'params[enable_groups]',
	'value' => 1,
);
if ($vars['entity']->enable_groups) {
	$groups_options['checked'] = true;
}
$groups_input = elgg_view('input/checkbox', $groups_options);

echo <<<HTML
	<br />
	<h3>$personal_settings_desc</h3>
	<hr />
	<div>
		$personal_input
		<label>$personal_label</label>
		<div class="elgg-text-help">$personal_desc</div>
	</div>
	<div>
		$collections_input
		<label>$collections_label</label>
		<div class="elgg-text-help">$collections_desc</div>
	</div>
	<br />
	<h3>$group_settings_desc</h3>
	<hr />
	<div>
		$groups_input
		<label>$groups_label</label>
		<div class="elgg-text-help">$groups_desc</div>
	</div>
HTML;
