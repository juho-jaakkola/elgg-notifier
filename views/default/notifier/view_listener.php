<?php
/**
 * Add a view listener that marks notifications read when displaying the entity
 *
 * @uses $vars['entity'] An elgg entity which the views counter will be added
 * @uses $vars['entity_guid'] An elgg entity guid that may be used instead of $vars['entity']
 */

$override = false;

$options = array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'relationship' => ElggNotification::HAS_OBJECT,
	'inverse_relationship' => true,
);

if (elgg_in_context('profile')) {
	// User profile doesn't have a full view so override the full_view check
	$override = true;
	// Parameters are not available so use page owner as target
	$options['relationship_guid'] = elgg_get_page_owner_guid();

	// In notification "You have a new friend" the actor is the new friend
	$options['relationship'] = ElggNotification::HAS_ACTOR;
	$options['metadata_name_value_pairs'] = array(
		'name' => 'event',
		'value' => 'create:relationship:friend'
	);
} else {
	$options['relationship_guid'] = (get_input('entity_guid')) ? get_input('entity_guid') : $vars['entity']->guid;
}

// Thewire doesn't have a full view so override the full_view check
if ($vars['entity'] && in_array($vars['entity']->getSubtype(), array('thewire', 'comment', 'discussion_reply'))) {
	$override = true;
}

// Mark notification read only if user is looking at the full view
if ($options['relationship_guid'] && ($vars['full_view'] || $override)) {
	// Get notifications related to the entity
	$notifications = elgg_get_entities_from_relationship($options);

	// Mark all the notifications related to this entity as "read"
	if ($notifications) {
		foreach ($notifications as $item) {
			$item->markRead();
		}
	}
}