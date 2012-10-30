<?php
/**
 * Notifier
 * 
 * @package Notifier
 */

function notifier_init () {
	//elgg_register_library('elgg:notifier', elgg_get_plugins_path() . 'notifier/lib/notifier.php');
	
	$notifications = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'notification',
		'limit' => false,
	));
	
	elgg_dump($notifications);
	
	elgg_register_page_handler('notifier', 'notifier_page_handler');

	// add to the main css
	elgg_extend_view('css/elgg', 'notifier/css');

	register_notification_handler('notifier', 'notifier_notify_handler');
	
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'notifier_message_body', 1);
	elgg_register_event_handler('create', 'annotation', 'notifier_comment_notifications');
	
	if (elgg_is_logged_in()) {
		// Add hidden popup module to topbar
		elgg_extend_view('page/elements/topbar', 'notifier/popup');

		// get unread notifications
		$num_notifications = (int)notifier_count_unread();
		
		$text = '<span class="elgg-icon elgg-icon-attention"></span>';
		$tooltip = elgg_echo("notifier:unreadcount", array($num_notifications));
		
		if ($num_notifications != 0) {
			$text .= "<span class=\"messages-new\">$num_notifications</span>";
		}

		// This link opens the popup module
		elgg_register_menu_item('topbar', array(
			'name' => 'notifier',
			'href' => '#notifier-popup',
			'text' => $text,
			'priority' => 600,
			'title' => $tooltip,
			'rel' => 'popup',
			'id' => 'notifier-popup-link',
		));
	}
}

function notifier_page_handler ($page) {
	//elgg_load_library('elgg:notifier');
	
	// make a URL segment available in page handler script
	echo notifier_view_page();
	return true;
}

/**
 * Notification handler
 *
 * @param ElggEntity $from
 * @param ElggUser   $to
 * @param string     $subject
 * @param string     $message
 * @param array      $params
 * @return bool
 */
function notifier_notify_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL) {

	return false;

	if (!$from) {
		throw new NotificationException(elgg_echo('NotificationException:MissingParameter', array('from')));
	}

	if (!$to) {
		throw new NotificationException(elgg_echo('NotificationException:MissingParameter', array('to')));
	}

	echo "<pre>";
	var_dump($from);
	echo "<hr />";
	var_dump($to);
	echo "<hr />";
	var_dump($subject);
	echo "<hr />";
	var_dump($message);
	echo "<hr />";
	var_dump($params);
	echo "</pre>";
	exit;

	$ia = elgg_set_ignore_access(true);
	$notification = new ElggNotification();
	$notification->title = $subject;
	$notification->description = $message;
	$notification->owner_guid = $to->getGUID();
	$notification->container_guid = $to->getGUID();
	$notification->access_id = ACCESS_PRIVATE;
	//$notification->save();
	elgg_set_ignore_access($ia);
	
	elgg_echo($notification);

	return true;
}

/**
 * @todo
 */
function notifier_count_unread () {
	return elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'notification',
		'limit' => false,
		'count' => true
	));
}

/**
 * Set the notification message body
 * 
 * @param string $hook    Hook name
 * @param string $type    Hook type
 * @param string $message The current message body
 * @param array  $params  Parameters about the blog posted
 * @return string
 */
function notifier_message_body($hook, $type, $message, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	
	global $CONFIG;
	$subject = $CONFIG->register_objects[$entity->getType()][$entity->getSubtype()];
	
	//$message = elgg_echo('notifier:message', array($subject));

	/*
	echo "<pre>";
	var_dump($subject);
	echo "<pre>";
	var_dump($hook);
	echo "<hr />";
	var_dump($type);
	echo "<hr />";
	var_dump($message);
	echo "<hr />";
	var_dump($params);
	echo "</pre>";
	exit;
	*/
	
	$ia = elgg_set_ignore_access(true);
	$notification = new ElggObject();
	$notification->subtype = 'notification';
	$notification->title = $subject;
	$notification->description = $message;
	$notification->owner_guid = $to_entity->getGUID();
	$notification->container_guid = $to_entity->getGUID();
	$notification->access_id = ACCESS_PRIVATE;
	$notification->target = $entity->getGUID();
	$guid = $notification->save();
	elgg_set_ignore_access($ia);
	
	/*
	echo "<pre>";
	var_dump($subject);
	echo "<pre>";
	var_dump($hook);
	echo "<hr />";
	var_dump($type);
	echo "<hr />";
	var_dump($message);
	echo "<hr />";
	var_dump($params);
	echo "</pre>";
	exit;
	*/
	
	return $message;
}

/**
 * Manage comment notifications
 */ 
function notifier_comment_notifications($event, $type, $annotation) {
	if ($annotation->name == "generic_comment" || $annotation->name == "group_topic_post") {
		
		$subject = elgg_echo('generic_comments:text');
		$message = elgg_echo('river:comment:object:default');
		
		$entity = get_entity($annotation->entity_guid);
		$to_entity = $entity->getOwnerGUID(); 
		

		
			
		$ia = elgg_set_ignore_access(true);
		$notification = new ElggObject();
		$notification->subtype = 'notification';
		$notification->title = $subject;
		$notification->description = $message;
		$notification->owner_guid = $to_entity;
		$notification->container_guid = $to_entity;
		$notification->access_id = ACCESS_PRIVATE;
		$notification->target = $entity->getGUID();
		$guid = $notification->save();
		elgg_set_ignore_access($ia);
	}
	
	/*
	echo "<pre>";
	var_dump($event);
	echo "<hr />";
	var_dump($type);
	echo "<hr />";
	var_dump($annotation);
	echo "<hr />";
	echo "</pre>";
	exit;
	*/

	return TRUE;
}

elgg_register_event_handler('init', 'system', 'notifier_init');