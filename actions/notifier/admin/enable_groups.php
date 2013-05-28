<?php
/**
 * Enable notifier as a notification method for a batch of group
 * members who don't have it enabled.
 */

$dbprefix = elgg_get_config('dbprefix');

$memberships = get_data(
"SELECT em.guid_one as user_guid, em.guid_two as group_guid FROM {$dbprefix}entities e
INNER JOIN {$dbprefix}entity_relationships em ON e.guid = em.guid_one
LEFT OUTER JOIN {$dbprefix}entity_relationships en ON e.guid = en.guid_one
AND en.relationship = 'notifynotifier'
AND em.guid_two = en.guid_two
WHERE e.type = 'user'
AND em.relationship = 'member'
AND en.relationship IS NULL
LIMIT 10
"
);

foreach ($memberships as $membership) {
	add_entity_relationship($membership->user_guid, 'notifynotifier', $membership->group_guid);
}

$result = new stdClass;
$result->count = count($memberships);
echo json_encode($result);