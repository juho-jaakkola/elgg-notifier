<?php

$lang = array(
	'notifier:' => '',
	'notifier:notification' => 'Notification',
	'notifier:notifications' => 'Notifications',
	'notifier:view:all' => 'View all notifications',
	'notifier:all' => 'All notifications',
	'notifier:none' => 'No notifications',
	'notifier:unreadcount' => 'Unread notifications (%s)',
	'notification:method:notifier' => 'Notifier',
	'notifier:dismiss_all' => 'Dismiss all',
	'notifier:clear_all' => 'Clear all',
	'notifier:deleteconfirm' => 'This removes all notifications including the unread ones. Are you sure you want to continue?',

	// System messages
	'notifier:message:dismissed_all' => 'All notifications dismissed succesfully',
	'notifier:message:deleted_all' => 'All notifications cleared succesfully',

	// Error messages
	'notifier:error:not_found' => 'This notification was not found',
	'notifier:error:target_not_found' => 'The content was not found, so it has propably been deleted.',

	// River strings that are not available in Elgg core
	'river:comment:object:groupforumtopic' => '%s replied on the discussion topic %s',
);

add_translation('en', $lang);