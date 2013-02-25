<?php
/**
 * Add a view listener that marks notifications read when displaying the entity
 * 
 * @uses $vars['entity'] An elgg entity which the views counter will be added
 * @uses $vars['entity_guid'] An elgg entity guid that may be used instead of $vars['entity'] 
 */

$target_guid = (get_input('entity_guid')) ? (get_input('entity_guid')) : ($vars['entity']->guid);

$user_guid = elgg_get_logged_in_user_guid();

$override = false;

if (elgg_in_context('profile')) {
	// User profile doesn't have a full view so override the full_view check
	$override = true;
	// Parameters are not available so use page owner as target
	$target_guid = elgg_get_page_owner_guid();
}

// Thewire doesn't have a full view so override the full_view check
if ($vars['entity'] && $vars['entity']->getSubtype() == 'thewire') {
	$override = true;
}

// Mark notification read only if user is looking at the full view
if ($target_guid && ($vars['full_view'] || $vars['full'] || $override)) {
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
				'value' => $target_guid
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