<?php
/**
 * These features can be used to enable notifier as notification
 * method for all site users at once.
 * 
 */

elgg_load_js('elgg.notifier.admin');

$dbprefix = elgg_get_config('dbprefix');

/**
 * Personal notifications
 * 
 * This query ignores users who have explicitly disabled notifier
 */
$options = array(
	'types' => array('user'),
	'count' => true,
);
$personal_name_metastring_id = elgg_get_metastring_id('notification:method:notifier');
if ($personal_name_metastring_id) {
	// Exclude users who already have the setting (either on or off)
	$options['wheres'] = array(
		"NOT EXISTS (
			SELECT 1 FROM {$dbprefix}metadata md
			WHERE md.entity_guid = e.guid
			AND md.name_id = $personal_name_metastring_id
		)"
	);
}
$personal_count = elgg_get_entities($options);
if ($personal_count) {
	echo elgg_view('notifier/admin/enable_setting', array(
		'setting' => 'personal',
		'count' => $personal_count
	));
}

/**
 * Friend collection notifications
 */
$options = array(
	'types' => array('user'),
	'count' => true,
);
$collections_name_metastring_id = elgg_get_metastring_id('collections_notifications_preferences_notifier');
if ($collections_name_metastring_id) {
	// Exclude users who already have the setting (either on or off)
	$options['wheres'] = array(
		"NOT EXISTS (
			SELECT 1 FROM {$dbprefix}metadata md
			WHERE md.entity_guid = e.guid
			AND md.name_id = $collections_name_metastring_id
		)",
	);
}
$collections_count = elgg_get_entities($options);
if ($collections_count) {
	echo elgg_view('notifier/admin/enable_setting', array(
		'setting' => 'collections',
		'count' => $collections_count
	));
}

/**
 * Group notifications
 * 
 * em = entity membership
 * en = entity notification
 */
$memberships = get_data(
"SELECT count(em.relationship) count FROM {$dbprefix}entities e
INNER JOIN {$dbprefix}entity_relationships em ON e.guid = em.guid_one
LEFT OUTER JOIN {$dbprefix}entity_relationships en ON e.guid = en.guid_one
AND en.relationship = 'notifynotifier'
AND em.guid_two = en.guid_two
WHERE e.type = 'user'
AND em.relationship = 'member'
AND en.relationship IS NULL
"
);

$groups_count = $memberships[0]->count;

if ($groups_count) {
	echo elgg_view('notifier/admin/enable_setting', array(
		'setting' => 'groups',
		'count' => $groups_count
	));
}

if (!$personal_count && !$collections_count && !$groups_count) {
	echo elgg_echo('notifier:admin:all_enabled');
}