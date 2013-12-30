<?php
/**
 * Notifier
 *
 * @package Notifier
 */

elgg_register_event_handler('init', 'system', 'notifier_init');

function notifier_init () {
	elgg_register_library('elgg:notifier', elgg_get_plugins_path() . 'notifier/lib/notifier.php');

	notifier_set_view_listener();

	// Add hidden popup module to topbar
	elgg_extend_view('page/elements/topbar', 'notifier/popup');

	// Register the notifier's JavaScript
	$notifier_js = elgg_get_simplecache_url('js', 'notifier/notifier');
	elgg_register_simplecache_view('js/notifier/notifier');
	elgg_register_js('elgg.notifier', $notifier_js);
	elgg_load_js('elgg.notifier');

	elgg_register_page_handler('notifier', 'notifier_page_handler');

	// add to the main css
	elgg_extend_view('css/elgg', 'notifier/css');

	register_notification_handler('notifier', 'notifier_notify_handler');

	// Hook handler for cron that removes old messages
	elgg_register_plugin_hook_handler('cron', 'daily', 'notifier_cron');
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'notifier_object_notifications');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'notifier_topbar_menu_setup');
	elgg_register_plugin_hook_handler('action', 'friends/add', 'notifier_friend_notifications');

	elgg_register_event_handler('create', 'annotation', 'notifier_annotation_notifications');
	elgg_register_event_handler('delete', 'all', 'notifier_delete_event_listener');
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
 */
function notifier_topbar_menu_setup ($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		// get unread notifications
		$num_notifications = (int)notifier_count_unread();

		$text = '<span class="elgg-icon elgg-icon-attention"></span>';
		$tooltip = elgg_echo("notifier:unreadcount", array($num_notifications));

		if ($num_notifications != 0) {
			$text .= "<span id=\"notifier-new\">$num_notifications</span>";
		}

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
	}

	return $return;
}

/**
 * Displays a list of all notifications
 */
function notifier_page_handler ($page) {
	gatekeeper();

	elgg_load_library('elgg:notifier');

	$params = notifier_get_page_content_list();

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);

	return true;
}

/**
 * Dummy handler to enable notifier as a new notification method.
 *
 * The actual notifications are created by intercepting the notification
 * process with plugin hooks. This function is required because all
 * notification methods must have a callable handler function.
 */
function notifier_notify_handler() {}

/**
 * Get the count of all unread notifications
 */
function notifier_count_unread () {
	return notifier_get_unread(array('count' => true));
}

/**
 * Get all unread messages
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
		),
		'order_by_metadata' => array(
			'name' => 'status',
			'direction' => DESC
		),
	);

	$options = array_merge($defaults, $options);

	return elgg_get_entities_from_metadata($options);
}

/**
 * Notify user about new content
 *
 * This intercepts the notification process already before the call to
 * notify_user() is done. This is because we need more detailed info
 * than the notify_user() function can provide. After creating a new
 * notifier we can return false because there is no need to continue
 * to the notify_user() call.
 *
 * @param string $hook    Hook name
 * @param string $type    Hook type
 * @param string $message Message body of the notification
 * @param array  $params  Parameters about the created entity
 * @return false|string
 */
function notifier_object_notifications($hook, $type, $message, $params) {
	// Create notification only if user has chosen it as notification method
	if ($params['method'] === 'notifier') {
		$entity = $params['entity'];
		$to_entity = $params['to_entity'];

		$type = $entity->getType();
		$subtype = $entity->getSubtype();

		// Do not notify about messages
		if ($subtype == 'messages') {
			return false;
		}

		// Use river string as the content of the notification
		$title = "river:create:$type:$subtype";

		notifier_add_notification(array(
			'title' => $title,
			'user_guid' => $to_entity->getGUID(),
			'target_guid' => $entity->getGUID(),
			'subject_guid' => $entity->getOwnerGUID()
		));

		notifier_handle_mentions($entity, 'object');

		// Notification has been created. No need to continue.
		return false;
	}

	return $message;
}

/**
 * Notify when user is added as someone's friend
 */
function notifier_friend_notifications ($hook, $type, $return, $params) {
	$friend_guid = get_input('friend');
	$user_guid = elgg_get_logged_in_user_guid();

	if ($friend_guid) {
		// Having the logged in user as target is illogical but this way
		// we can search by target_guid in view notifier/view_listener
		notifier_add_notification(array(
			'title' => 'friend:newfriend:subject',
			'user_guid' => $friend_guid,
			'target_guid' => $user_guid,
			'subject_guid' => $user_guid,
		));
	}

	return $return;
}

/**
 * Handle annotation notifications
 *
 * @param string         $event
 * @param string         $type
 * @param ElggAnnotation $annotation
 * @return boolean
 */
function notifier_annotation_notifications($event, $type, $annotation) {
	$supported_types = array('generic_comment', 'group_topic_post', 'likes', 'messageboard');

	if (!in_array($annotation->name, $supported_types)) {
		return true;
	}

	$entity = $annotation->getEntity();
	$owner_guid = $entity->getOwnerGUID();

	$subject_guid = $annotation->owner_guid;

	// Do not notify about own annotations
	if ($subject_guid != $owner_guid) {
		// Check if user has enabled notifier for personal notifications
		$metadata = elgg_get_metadata(array(
			'metadata_name' => 'notification:method:notifier',
			'guid' => $owner_guid
		));

		if (!empty($metadata[0]->value)) {
			$target_guid = $entity->getGUID();

			switch ($annotation->name) {
				case 'likes':
					$title = 'likes:notifications:subject';
					break;
				case 'messageboard':
					$title = 'river:messageboard:user:default';
					$target_guid = $owner_guid;
					break;
				default:
					// We'll assume it's a comment
					$type = $entity->getType();
					$subtype = $entity->getSubtype();
					$title = "river:comment:$type:$subtype";
					break;
			}

			notifier_add_notification(array(
				'title' => $title,
				'user_guid' => $owner_guid,
				'target_guid' => $target_guid,
				'subject_guid' => $subject_guid
			));
		}
	}

	notifier_handle_mentions($annotation, 'annotation');

	notifier_handle_comment_tracker($annotation);

	notifier_handle_group_topic_replies($annotation);

	return TRUE;
}

/**
 * Create a notification for each @username tag
 *
 * @param object $object The content that was created
 * @param string $type   Type of content (annotation|object)
 */
function notifier_handle_mentions ($object, $type) {
	// This feature requires the mentions plugin
	if (!elgg_is_active_plugin('mentions')) {
		return false;
	}

	global $CONFIG;

	if ($type == 'annotation' && $object->name != 'generic_comment' && $object->name != 'group_topic_post') {
		return NULL;
	}

	// excludes messages - otherwise an endless loop of notifications occur!
	if ($object->getSubtype() == "messages") {
		return NULL;
	}

	$fields = array(
		'name', 'title', 'description', 'value'
	);

	// store the guids of notified users so they only get one notification per creation event
	$notified_guids = array();

	foreach ($fields as $field) {
		$content = $object->$field;
		// it's ok in in this case if 0 matches == FALSE
		if (preg_match_all(mentions_get_regex(), $content, $matches)) {
			// match against the 2nd index since the first is everything
			foreach ($matches[1] as $username) {

				if (!$user = get_user_by_username($username)) {
					continue;
				}

				if ($type == 'annotation') {
					if ($parent = get_entity($object->entity_guid)) {
						$access = has_access_to_entity($parent, $user);
						$target_guid = $parent->getGUID();
					} else {
						continue;
					}
				} else {
					$access = has_access_to_entity($object, $user);
					$target_guid = $object->getGUID();
				}

				// Override access
				// @todo What does the has_access_to_entity() do?
				$access = true;

				if ($user && $access && !in_array($user->getGUID(), $notified_guids)) {
					// if they haven't set the notification status default to sending.
					$notification_setting = elgg_get_plugin_user_setting('notify', $user->getGUID(), 'mentions');

					if (!$notification_setting && $notification_setting !== FALSE) {
						$notified_guids[] = $user->getGUID();
						continue;
					}

					// @todo Is there need to know what the type of the target is?
					$type_key = "mentions:notification_types:$type";
					if ($subtype = $object->getSubtype()) {
						$type_key .= ":$subtype";
					}
					$type_str = elgg_echo($type_key);

					$title = 'mentions:notification:subject';

					notifier_add_notification(array(
						'title' => $title,
						'user_guid' => $user->guid,
						'target_guid' => $target_guid,
						'subject_guid' => $object->owner_guid
					));
				}
			}
		}
	}
}

/**
 * Create notifications for users tracking content with comment_tracker plugin.
 *
 * @param object $annotation The annotation that was created
 */
function notifier_handle_comment_tracker ($annotation) {
	// This feature requires the comment_tracker plugin
	if (!elgg_is_active_plugin('comment_tracker')) {
		return false;
	}

	if ($annotation->name != 'generic_comment') {
		return false;
	}

	$options = array(
		'relationship' => COMMENT_TRACKER_RELATIONSHIP,
		'relationship_guid' => $annotation->entity_guid,
		'inverse_relationship' => true,
		'types' => 'user',
		'limit' => 0
	);

	$users = elgg_get_entities_from_relationship($options);

	foreach ($users as $user) {
		// Make sure user is real
		// Do not notify the author of comment
		if ($user instanceof ElggUser && $user->guid != $annotation->owner_guid) {
			if ($user->guid == $entity->owner_guid) {
				// user is the owner of the entity being commented on
				continue;
			}

			$site_guid = elgg_get_site_entity()->getGUID();
			// comment_tracker doesn't keep track of used methods but blocked methods instead
			if (check_entity_relationship($user->guid, 'block_comment_notifynotifier', $site_guid))	{
				continue;
			}

			$target = get_entity($annotation->entity_guid);
			$type = $target->getType();
			$subtype = $target->getSubtype();
			$title = "river:comment:$type:$subtype";

			notifier_add_notification(array(
				'title' => $title,
				'user_guid' => $user->getGUID(),
				'target_guid' => $target->getGUID(),
				'subject_guid' => $annotation->owner_guid
			));
		}
	}
}

/**
 * Create notifications of group discussion replies.
 *
 * Notify all group members who have subscribed to group notifications
 * using notifier as a notification method.
 *
 * @param object $reply The reply that was posted
 */
function notifier_handle_group_topic_replies ($reply) {
	if ($reply->name != 'group_topic_post') {
		return false;
	}

	$topic = $reply->getEntity();

	$interested_users = elgg_get_entities_from_relationship(array(
		'relationship' => 'notifynotifier',
		'relationship_guid' => $topic->getContainerGUID(),
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => 0,
	));

	if ($interested_users) {
		foreach ($interested_users as $user) {
			// Do not notify the user who posted the reply
			if ($user->getGUID() == elgg_get_logged_in_user_guid()) {
				continue;
			}

			// The owner of the topic has already been notified
			if ($user->getGUID() == $topic->getOwnerGUID()) {
				continue;
			}

			notifier_add_notification(array(
				'title' => 'river:reply:object:groupforumtopic',
				'user_guid' => $user->getGUID(),
				'target_guid' => $topic->getGUID(),
				'subject_guid' => $reply->owner_guid
			));
		}
	}
}

/**
 * Add a new notification if similar not already exists
 *
 * @uses int $options['user_guid']    GUID of the user being notified
 * @uses int $options['target_guid']  GUID of the entity being acted on
 * @uses int $options['subject_guid'] GUID of the user acting on target
 * @uses string $options['title']     Translation string of the action
 */
function notifier_add_notification ($options) {
	$user_guid = $options['user_guid'];
	$target_guid = $options['target_guid'];
	$subject_guid = $options['subject_guid'];
	$title = $options['title'];

	$db_prefix = elgg_get_config('dbprefix');
	$ia = elgg_set_ignore_access(true);

	// Check if the same notification already exists
	$notifiers = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'notification',
		'owner_guid' => $user_guid,
		'joins' => array(
			"JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid"
		),
		'wheres' => array("title = '$title'"),
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'target_guid',
				'value' => $target_guid,
			),
			array(
				'name' => 'subject_guid',
				'value' => $subject_guid
			),
			array(
				'name' => 'status',
				'value' => 'unread',
			)
		),
	));

	if (empty($notifiers)) {
		$notification = new ElggNotification();
		$notification->title = $title;
		$notification->owner_guid = $user_guid;
		$notification->container_guid = $user_guid;
		$notification->setSubjectGUID($subject_guid);
		$notification->setTargetGUID($target_guid);
		$notification->save();
	}

	elgg_set_ignore_access($ia);
}

/**
 * Remove over week old notifications that have been read
 */
function notifier_cron ($hook, $entity_type, $returnvalue, $params) {
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
 */
function notifier_set_view_listener () {
	$dbprefix = elgg_get_config('dbprefix');
	$types = get_data("SELECT * FROM {$dbprefix}entity_subtypes");

	// These subtypes do not have notifications so they can be skipped
	$skip = array('plugin', 'widget', 'admin_notice', 'notification', 'messages', 'reported_content');

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
 * Delete related notifications when notification subject or target is deleted
 *
 * @param string   $event       The event type (delete)
 * @param string   $object_type The type of the object being deleted (object,
 * 								group, user, annotation, relationship, metadata)
 * @param stdClass $object      The object being deleted
 * @return boolean
 */
function notifier_delete_event_listener ($event, $object_type, $object) {
	// This currently supports only ElggEntities
	if (!$object instanceof ElggEntity) {
		return true;
	}

	// Notifications don't have notifications
	if ($object->getSubtype() == 'notification') {
		return true;
	}

	// Override access so we can delete notifications
	// not belonging to currently logged in user
	$ia = elgg_set_ignore_access(true);

	switch ($object_type) {
		case 'user':
			// Notifications triggered by the user being deleted
			$notifications = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'notification',
				'limit' => false,
				'metadata_name_value_pairs' => array(
					array(
						'name' => 'subject_guid',
						'value' => $object->getGUID()
					)
				)
			));
			break;
		case 'object':
			// Notifications which have the entity being deleted as their target
			$notifications = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'notification',
				'limit' => false,
				'metadata_name_value_pairs' => array(
					array(
						'name' => 'target_guid',
						'value' => $object->getGUID()
					)
				)
			));
			break;
		default:
			// No notifications to delete
			elgg_set_ignore_access($ia);
			return true;
	}

	foreach ($notifications as $item) {
		$item->delete();
	}

	elgg_set_ignore_access($ia);

	return true;
}

/**
 * Enable notifier by default for new users according to plugin settings.
 *
 * We do this using 'create, user' event instead of 'register, user' plugin
 * hook so that it affects also users created by an admin.
 *
 * @param  string   $event 'create'
 * @param  string   $type  'user'
 * @param  ElggUser $user
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