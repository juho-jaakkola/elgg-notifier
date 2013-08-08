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

	// Likes plugin
	'likes:notifications:summary' => '%s likes your post %s',
	'likes:notifications:summary:2' => '%s and %s like your post %s',
	'likes:notifications:summary:n' => '%s and %s other users like your post %s',

	// Plugin settings
	'notifier:settings:desc' => 'Default notification settings for new users',
	'notifier:settings:enable_personal' => 'Personal notifications',
	'notifier:settings:enable_personal:desc' => "A notification is added when an action (comment, like, etc.) is performed on user's content.",
	'notifier:settings:enable_collections' => 'Friends',
	'notifier:settings:enable_collections:desc' => "A notification is added when any of user's friends create new content.",
	'notifier:settings:groups:desc' => 'Default notification setting for new group members',
	'notifier:settings:enable_groups' => 'Group notifications',
	'notifier:settings:enable_groups:desc' => 'A notification is added when new content is added to a group that the user is a member of.',

	// Admin panel
	'admin:notifier' => 'Notifier',
	'admin:notifier:enable' => 'Enable notifications',
	'notifier:admin:enable:description_link' => 'here',
	'notifier:admin:enable:description' => 'These settings affect only new users. You can enable notifier also for existing users <u>%s</u>.',
	'notifier:admin:enable:warning' => "<strong>Warning!</strong> Having notifier enabled for all users may result in a lot of writes to the database. Therefore it doesn't scale well for large sites.",
	'notifier:admin:not_using_personal' => "There are %s users who haven't enabled notifier for personal notifications.",
	'notifier:admin:not_using_collections' => "There are %s users who haven't enabled notifier for friend collections.",
	'notifier:admin:not_using_groups' => "There are %s group memberships that do not have notifier as a notification method.",
	'notifier:admin:activate' => 'Enable',
	'notifier:admin:all_enabled' => 'All notifications have already been enabled!',
);

add_translation('en', $lang);