<?php
/**
 * Add a view listener that marks notifications read when displaying the entity
 * 
 * @uses $vars['entity'] An elgg entity which the views counter will be added
 * @uses $vars['entity_guid'] An elgg entity guid that may be used instead of $vars['entity'] 
 */

$entity_guid = (get_input('entity_guid')) ? (get_input('entity_guid')) : ($vars['entity']->guid);

// Mark notification read only if user is looking at the full view
if ($entity_guid && ($vars['full_view'] || $vars['full'])) {
	$user_guid = elgg_get_logged_in_user_guid();

	// Get unread notifications related to the entity
	$notifications = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'notification',
		'owner_guid' => $user_guid,
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'status',
				'value' => 'unread'
			),
			array(
				'name' => 'target_guid',
				'value' => $entity_guid
			)
		)
	));

	// Mark all the notifications related to this entity as "read"
	if ($notifications) {
		foreach($notifications as $item) {
			$item->markRead();
		}
	}
}