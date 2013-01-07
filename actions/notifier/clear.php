<?php
/**
 * Delete all notifications regardless if they're read or not
 *
 * @package Notifier
 */

$user_guid = elgg_get_logged_in_user_guid();

$notifications = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => $user_guid,
	'limit' => false
));

foreach ($notifications as $item) {
	$item->delete();
}

system_message(elgg_echo('notifier:message:deleted_all'));