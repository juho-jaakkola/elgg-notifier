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

	'item:object:notification' => 'Notifier items',

	// System messages
	'notifier:message:dismissed_all' => 'All notifications dismissed successfully',
	'notifier:message:deleted_all' => 'All notifications cleared successfully',
	'notifier:message:deleted' => 'Notification deleted successfully',

	// Error messages
	'notifier:error:not_found' => 'This notification was not found',
	'notifier:error:target_not_found' => 'The content was not found, so it has propably been deleted.',
	'notifier:error:cannot_delete' => 'Cannot delete notification',

	// River strings that are not available in Elgg core
	'river:comment:object:groupforumtopic' => '%s replied on the discussion topic %s',

	// Admin panel
	'notifier:settings:warning' => "<strong>Warning!</strong> Having notifier enabled for many users may result in a lot of writes to the database. Therefore it doesn't scale well for large sites.",
	'notifier:settings:desc' => 'Default notification settings for new users',
	'notifier:settings:enable_personal' => 'Personal notifications',
	'notifier:settings:enable_personal:desc' => "A notification is added when an action (comment, like, etc.) is performed on user's content.",
	'notifier:settings:enable_collections' => 'Friends',
	'notifier:settings:enable_collections:desc' => "A notification is added when any of user's friends create new content.",
	'notifier:settings:groups:desc' => 'Default notification setting for new group members',
	'notifier:settings:enable_groups' => 'Group notifications',
	'notifier:settings:enable_groups:desc' => 'A notification is added when new content is created to a group where user has a membership.',
);

add_translation('en', $lang);