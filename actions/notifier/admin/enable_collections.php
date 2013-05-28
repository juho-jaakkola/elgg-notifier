<?php
/**
 * Enable notifier as a notification method for a batch of users.
 */

$dbprefix = elgg_get_config('dbprefix');

$collections_name_metastring_id = get_metastring_id('collections_notifications_preferences_notifier');
$users = elgg_get_entities(array(
	'types' => array('user'),
	'wheres' => array(
		"NOT EXISTS (
			SELECT 1 FROM {$dbprefix}metadata md
			WHERE md.entity_guid = e.guid
			AND md.name_id = $collections_name_metastring_id
		)",
	),
));

foreach ($users as $user) {
	// Enable notifier as notification method
	$user->collections_notifications_preferences_notifier = -1;

	$options = array(
		'relationship' => 'friend',
		'relationship_guid' => $user->guid,
		'type' => 'user',
	);

	// Subscribe user to receive notifier notifications from each friend
	$batch = new ElggBatch('elgg_get_entities_from_relationship', $options);
	foreach ($batch as $friend) {
		add_entity_relationship($user->guid, 'notifynotifier', $friend->guid);
	}
}

$result = new stdClass;
$result->count = count($users);
echo json_encode($result);