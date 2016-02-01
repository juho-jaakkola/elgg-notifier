<?php
/**
 * Notifier
 *
 * @package Notifier
 */

elgg_register_event_handler('init', 'system', 'notifier_init');

/**
 * Initialize the plugin
 *
 * @return void
 */
function notifier_init () {
	notifier_set_view_listener();

	// Add hidden popup module to topbar
	elgg_extend_view('page/elements/topbar', 'notifier/popup');

	elgg_require_js('notifier/notifier');

	// Must always have lightbox loaded because views needing it come via AJAX
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');

	elgg_register_page_handler('notifier', 'notifier_page_handler');

	// Add css
	elgg_extend_view('elgg.css', 'notifier/notifier.css');

	elgg_register_notification_method('notifier');
	elgg_register_plugin_hook_handler('send', 'notification:notifier', 'notifier_notification_send');

	elgg_register_plugin_hook_handler('route', 'friendsof', 'notifier_read_friends_notification');

	elgg_register_event_handler('create', 'relationship', 'notifier_relationship_notifications');
	elgg_register_event_handler('delete', 'relationship', 'notifier_read_group_invitation_notification');

	// Hook handler for cron that removes old messages
	elgg_register_plugin_hook_handler('cron', 'daily', 'notifier_cron');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'notifier_topbar_menu_setup');

	elgg_register_event_handler('create', 'user', 'notifier_enable_for_new_user');
	elgg_register_event_handler('join', 'group', 'notifier_enable_for_new_group_member');

	$action_path = elgg_get_plugins_path() . 'notifier/actions/notifier/';
	elgg_register_action('notifier/dismiss', $action_path . 'dismiss.php');
	elgg_register_action('notifier/clear', $action_path . 'clear.php');
	elgg_register_action('notifier/delete', $action_path . 'delete.php');
}

/**
 * Add notifier icon to topbar menu
 *
 * The menu item opens a popup module defined in view notifier/popup
 *
 * @param string         $hook   Hook name
 * @param string         $type   Hook type
 * @param ElggMenuItem[] $return Array of menu items
 * @param array          $params Hook parameters
 * @return ElggMenuItem[] $return
 */
function notifier_topbar_menu_setup ($hook, $type, $return, $params) {
	if (!elgg_is_logged_in()) {
		return $return;
	}

	// Get amount of unread notifications
	$count = (int)notifier_count_unread();

	$text = elgg_view_icon('globe');
	$tooltip = elgg_echo("notifier:unreadcount", array($count));

	if ($count > 0) {
		if ($count > 99) {
			// Don't allow the counter to grow endlessly
			$count = '99+';
		}
		$hidden = '';
	} else {
		$hidden = 'class="hidden"';
	}

	$text .= "<span id=\"notifier-new\" $hidden>$count</span>";

	$item = ElggMenuItem::factory(array(
		'name' => 'notifier',
		'href' => '#notifier-popup',
		'text' => $text,
		'priority' => 600,
		'title' => $tooltip,
		'rel' => 'popup',
		'id' => 'notifier-popup-link'
	));

	$return[] = $item;

	return $return;
}

/**
 * Dispatches notifier pages
 *
 * URLs take the form of
 *  All notifications:          notifier/all
 *  Subjects of a notification: notifier/subjects/<notification guid>
 *
 * @param array $segments Array of URL segments
 * @return bool Was the page handled successfully
 */
function notifier_page_handler($segments) {
	gatekeeper();

	$page = array_shift($segments);

	switch ($page) {
		default :
		case 'all' :
		case 'popup':
			echo elgg_view_resource('notifier/list');
			return true;

		case 'subjects':
			$guid = array_shift($segments);
			echo elgg_view_resource('notifier/subjects', array(
				'guid' => $guid,
			));
			return true;
	}

	return false;
}

/**
 * Create a notification
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has the notification been sent
 * @param array  $params Hook parameters
 * @return bool Was the notification handled successfully
 */
function notifier_notification_send($hook, $type, $result, $params) {
	$notification = $params['notification'];
	/* @var Elgg_Notifications_Notification $notification */
	$event = $params['event'];
	/* @var Elgg_Notifications_Event $event */

	if (!$event) {
		// Plugin is calling notify_user() so stop here and let
		// the NotificationService handle the notification later.
		return false;
	}

	$ia = elgg_set_ignore_access(true);

	$action = $event->getAction();
	$object = $event->getObject();
	$string = "river:{$action}:{$object->getType()}:{$object->getSubtype()}";
	$recipient = $notification->getRecipient();
	$actor = $event->getActor();
	switch ($object->getType()) {
		case 'annotation':
			// Get the entity that was annotated
			$entity = $object->getEntity();
			break;
		case 'relationship':
			$entity = get_entity($object->guid_two);
			break;
		default:
			if ($object instanceof ElggComment) {
				// Use comment's container as notification target
				$entity = $object->getContainerEntity();

				// Check the action because this isn't necessarily a new comment,
				// but e.g. someone being mentioned in a comment
				if ($action == 'create') {
					$string = "river:comment:{$entity->getType()}:{$entity->getSubtype()}";
				}

				// TODO How about discussion replies?
			} else {
				// This covers all other entities
				$entity = $object;
			}
	}

	if ($object->getType() == 'annotation' || $object->getType() == 'relationship' || ($object instanceof ElggComment && $action == 'create')) {
		// Check if similar notification already exists
		$existing = notifier_get_similar($event->getDescription(), $entity, $recipient);
		if ($existing) {
			// Update the existing notification
			$existing->setSubject($actor);
			$existing->markUnread();
			// time_created must be used because time_updated gets updated
			// automatically and it won't therefore match the time_created
			// of the object triggering the notification
			$existing->time_created = $object->time_created;
			return $existing->save();
		}
	}

	// If the river string is not available, fall back to summary or subject
	if ($string == elgg_echo($string)) {
		if ($notification->summary) {
			$string = $notification->summary;
		} else {
			$string = $notification->subject;
		}
	}

	$note = new ElggNotification();
	$note->title = $string;
	$note->owner_guid = $recipient->getGUID();
	$note->container_guid = $recipient->getGUID();
	$note->event = $event->getDescription();
	// The notification may be being created later than the event took
	// place, so use the original time_created instead of time()
	$note->time_created = $object->time_created;

	if ($note->save()) {
		$note->setSubject($actor);
		$note->setTarget($entity);
	}

	elgg_set_ignore_access($ia);

	if ($note) {
		return true;
	}
}

/**
 * Get the count of all unread notifications
 *
 * @return integer
 */
function notifier_count_unread () {
	return notifier_get_unread(array('count' => true));
}

/**
 * Count all notifications
 *
 * @return int
 */
function notifier_count_all() {
	return elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'notification',
		'owner_guid' => (int) elgg_get_logged_in_user_guid(),
		'count' => true,
	));
}

/**
 * Get all unread messages for logged in users
 *
 * @param array $options Options passed to elgg_get_entities_from_metadata
 * @return ElggNotification[]
 */
function notifier_get_unread ($options = array()) {
	$defaults = array(
		'type' => 'object',
		'subtype' => 'notification',
		'limit' => false,
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'metadata_name_value_pairs' => array(
			'name' => 'status',
			'value' => 'unread'
		)
	);

	$options = array_merge($defaults, $options);

	return elgg_get_entities_from_metadata($options);
}

/**
 * Remove over week old notifications that have been read
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $return Old stdOut contents
 * @param array  $params Array containing the time when cron was triggered
 * @return void
 */
function notifier_cron ($hook, $type, $return, $params) {
	// One week ago
	$time = time() - 60 * 60 * 24 * 7;

	$options = array(
		'type' => 'object',
		'subtype' => 'notification',
		'wheres' => array("e.time_created < $time"),
		'metadata_name_value_pairs' => array(
			'name' => 'status',
			'value' => 'read'
		),
		'limit' => false
	);

	$ia = elgg_set_ignore_access(true);
	$notifications = elgg_get_entities_from_metadata($options);

	$options['count'] = true;
	$count = elgg_get_entities_from_metadata($options);

	foreach ($notifications as $notification) {
		$notification->delete();
	}

	echo "<p>Removed $count notifications.</p>";

	elgg_set_ignore_access($ia);
}

/**
 * Add view listener to views that may be the targets of notifications
 *
 * @return void
 */
function notifier_set_view_listener () {
	$dbprefix = elgg_get_config('dbprefix');
	$types = get_data("SELECT * FROM {$dbprefix}entity_subtypes");

	// These subtypes do not have notifications so they can be skipped
	$skip = array(
		'plugin',
		'widget',
		'admin_notice',
		'notification',
		'messages',
		'reported_content',
		'site_notification'
	);

	foreach ($types as $type) {
		if (in_array($type->subtype, $skip)) {
			continue;
		}

		elgg_extend_view("object/{$type->subtype}", 'notifier/view_listener');
	}

	// Some manual additions
	elgg_extend_view('profile/wrapper', 'notifier/view_listener');
}

/**
 * Enable notifier by default for new users according to plugin settings.
 *
 * We do this using 'create, user' event instead of 'register, user' plugin
 * hook so that it affects also users created by an admin.
 *
 * @param string   $event 'create'
 * @param string   $type  'user'
 * @param ElggUser $user  The user that was created
 * @return boolean
 */
function notifier_enable_for_new_user ($event, $type, $user) {
	$personal = (boolean) elgg_get_plugin_setting('enable_personal', 'notifier');
	$collections = (boolean) elgg_get_plugin_setting('enable_collections', 'notifier');

	if ($personal) {
		$prefix = "notification:method:notifier";
		$user->$prefix = true;
	}

	if ($collections) {
		/**
		 * This function is triggered before invite code is checked so it's
		 * enough just to add the setting. Notifications plugin will take care
		 * of adding the 'notifynotifier' relationship in case user was invited.
		 */
		$user->collections_notifications_preferences_notifier = '-1';
	}

	$user->save();

	return true;
}

/**
 * Enable notifier as notification method when joining a group.
 *
 * @param string $event  'join'
 * @param string $type   'group'
 * @param array  $params Array containing ElggUser and ElggGroup
 */
function notifier_enable_for_new_group_member ($event, $type, $params) {
	$group = $params['group'];
	$user = $params['user'];

	$enabled = (boolean) elgg_get_plugin_setting('enable_groups', 'notifier');

	if ($enabled) {
		if (elgg_instanceof($group, 'group') && elgg_instanceof($user, 'user')) {
			add_entity_relationship($user->guid, 'notifynotifier', $group->guid);
		}
	}
}

/**
 * Get existing notifications that match the given parameters.
 *
 * This can be used when we want to update an old notification.
 * E.g. "A likes X" and "B likes X" become "A and B like X".
 *
 * @param string     $event_name String like "action:type:subtype"
 * @param ElggEntity $entity     Entity being notified about
 * @param ElggUser   $recipient  User being notified
 * @return ElggNotification|null
 */
function notifier_get_similar($event_name, $entity, $recipient) {
	$db_prefix = elgg_get_config('dbprefix');
	$ia = elgg_set_ignore_access(true);

	$object_relationship = ElggNotification::HAS_OBJECT;

	// Notification (guid_one) has relationship 'hasObject' to target (guid_two)
	$options = array(
		'type' => 'object',
		'subtype' => 'notification',
		'owner_guid' => $recipient->guid,
		'metadata_name_value_pairs' => array(
			'name' => 'event',
			'value' => $event_name,
		),
		'joins' => array(
			"JOIN {$db_prefix}entity_relationships er ON e.guid = er.guid_one", // Object relationship
		),
		'wheres' => array(
			"er.guid_two = {$entity->guid}",
			"er.relationship = '$object_relationship'",
		),
	);

	$notification = elgg_get_entities_from_metadata($options);

	if ($notification) {
		$notification = $notification[0];
	}

	elgg_set_ignore_access($ia);

	return $notification;
}

/**
 * Mark unread friend notifications as read.
 *
 * This hook is triggered when user goes to the "friendsof/<username>" page.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $return Array containing 'identifier' and 'segments'
 * @param array  $params This is empty
 * @return void
 */
function notifier_read_friends_notification ($hook, $type, $return, $params) {
	// Get unread notifications that match the friending event
	$options = array(
		'metadata_name_value_pairs' => array(
			'name' => 'event',
			'value' => 'create:relationship:friend',
		)
	);

	$notifications = notifier_get_unread($options);

	foreach ($notifications as $note) {
		$note->markRead();
	}
}

/**
 * Create notifications about new relationships
 *
 * The Elgg 1.9 notifications system does not yet process relationships
 * so we create the notifications manually on the 'create', 'relationship'
 * event instead.
 *
 * @param string           $event  'create'
 * @param string           $type   'relationship'
 * @param ElggRelationship $object The created relationships
 * @return boolean Always returns true
 */
function notifier_relationship_notifications ($event, $type, $object) {
	$guid_one = $object->guid_one;
	$guid_two = $object->guid_two;
	$relationship = $object->relationship;

	switch ($relationship) {
		case 'friend':
			// Notification about a new friend
			$actor = get_user($guid_one);
			$recipient = get_user($guid_two);
			$target = $recipient;
			$string = 'friend:notifications:summary';
			break;
		case 'invited':
			// Notification about a group membership invitation
			$actor = elgg_get_logged_in_user_entity(); // User who invited
			$target = get_entity($guid_one); // The group
			$recipient = get_user($guid_two); // The invited user
			$string = 'groups:notifications:invitation';
			break;
		case 'membership_request':
			// Notification about a group membership invitation
			$actor = get_user($guid_one); // User who requested
			$target = get_entity($guid_two); // The group
			$recipient = get_user($target->owner_guid); // The group_owner
			$string = 'groups:notifications:membership_request';
			break;
		default;
			return true;
	}

	if (!$actor) {
		return true;
	}

	if (!$recipient) {
		return true;
	}

	if (!$target) {
		return true;
	}

	$ia = elgg_set_ignore_access(true);

	$event_name = "create:relationship:{$relationship}";

	$note = notifier_get_similar($event_name, $target, $recipient);

	if (!$note) {
		$note = new ElggNotification();
		$note->title = $string;
		$note->owner_guid = $recipient->guid;
		$note->container_guid = $recipient->guid;
		$note->event = $event_name;
		$note->save();

		$note->setTarget($target);
	} else {
		// Mark the existing notification as unread
		$note->markUnread();
	}

	$note->setSubject($actor);

	elgg_set_ignore_access($ia);

	// Returning false would delete the relationship
	return true;
}

/**
 * Delete notification about a group invitation when user accepts/deletes it
 *
 * @param string           $event  'delete'
 * @param string           $type   'relationship'
 * @param ElggRelationship $object The relationship being deleted
 * @return boolean
 */
function notifier_read_group_invitation_notification($event, $type, $object) {
	$relationship = $object->relationship;

	// Proceed only if the relationship is an invitation
	if ($relationship != 'invited' && $relationship != 'membership_request') {
		return true;
	}

	// The group may be hidden, so ignore access
	$ia = elgg_set_ignore_access(true);
	if ($relationship === 'invited') {
		$group = get_entity($object->guid_one);
	} else {
		$group = get_entity($object->guid_two);
	}
	elgg_set_ignore_access($ia);

	// Proceed only if the invitation is for a group
	if (!$group instanceof ElggGroup) {
		return true;
	}

	$dbprefix = elgg_get_config('dbprefix');
	$has_object = ElggNotification::HAS_OBJECT;
	$options = array(
		'joins' => array("JOIN {$dbprefix}entity_relationships er ON e.guid = er.guid_one"),
		'wheres' => array("er.relationship = '{$has_object}' AND er.guid_two = {$group->guid}"),
	);

	// Get unread notifications
	$notifications = notifier_get_unread($options);

	foreach ($notifications as $note) {
		if ($note->event === "create:relationship:$relationship") {
			$note->markRead();
		}
	}

	// Returning true means that the relationship deletion can now proceed
	return true;
}
