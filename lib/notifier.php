<?php

/**
 * Display all notifications of logged in user
 */
function notifier_get_page_content_list () {
	$params = array();
	
	$params['title'] = elgg_echo('notifier:all');
	$params['filter'] = '';
	
	$notifications = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'notification',
		'limit' => 20,
		'owner_guid' => elgg_get_logged_in_user_guid()
	));
	
	if ($notifications) {
		$params['content'] = $notifications;
	} else { 
		$params['content'] = elgg_echo('notifier:none');
	}
	
	return $params;
}
