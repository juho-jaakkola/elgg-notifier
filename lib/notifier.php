<?php

/**
 * Display all notifications of the logged in user
 */
function notifier_get_page_content_list () {
	$params = array();

	$params['title'] = elgg_echo('notifier:all');
	$params['filter'] = '';

	elgg_register_menu_item('title', array(
		'name' => 'notification-settings',
		'href' => 'notifications/personal',
		'text' => elgg_echo('settings'),
		'class' => 'elgg-button elgg-button-action',
	));

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