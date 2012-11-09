<?php

/**
 * Display all notifications of logged in user
 */
function notifier_get_page_content_list () {
	$params = array();
	
	$params['title'] = elgg_echo('notifier:all');
	$params['filter'] = '';
	
	$notifications = elgg_list_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'notification',
		'limit' => 20,
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'order_by_metadata' => array(
			'name' => 'status',
			'direction' => DESC
		),
	));
	
	if ($notifications) {
		$params['content'] = $notifications;
	} else { 
		$params['content'] = elgg_echo('notifier:none');
	}
	
	return $params;
}

function notifier_route_to_entity ($guid) {
	$notification = get_entity($guid);
	
	if (!elgg_instanceof($notification, 'object', 'notification')) {
		register_error(elgg_echo('notifier:error:not_found'));
		forward();
	}
	
	if ($notification->target_type === 'entity') {
		$target = get_entity($notification->target);
		
		if (!elgg_instanceof($target)) {
			// The target was not found. It has propably been deleted
			
			// The notification is not needed anymore
			$notification->delete();
			
			register_error(elgg_echo('notifier:error:target_not_found'));
			forward();
		}
	} else {
		$annotation = elgg_get_annotation_from_id($notification->target);
		
		if (!$annotation) {
			// The notification is not needed anymore
			$notification->delete();
			
			register_error(elgg_echo('notifier:error:target_not_found'));
			forward();
		}

		$target = get_entity($annotation->entity_guid);
	}

	// Mark that the user has read the notification
	$notification->status = 'read';
	$notification->save();
	
	forward($target->getURL());
}
