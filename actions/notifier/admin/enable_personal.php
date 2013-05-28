<?php
/**
 * Enable notifier as a notification method for a batch of users.
 */
$dbprefix = elgg_get_config('dbprefix');

$name_metastring_id = get_metastring_id('notification:method:notifier');

$users = elgg_get_entities(array(
    'types' => array('user'),
    'wheres' => array(
        "NOT EXISTS (
	        SELECT 1 FROM {$dbprefix}metadata md
	        WHERE md.entity_guid = e.guid
	        AND md.name_id = $name_metastring_id
		)"
    ),
));

foreach ($users as $user) {
	$metastring_name = "notification:method:notifier";
	$user->$metastring_name = true;
}

$result = new stdClass;
$result->count = count($users);
echo json_encode($result);