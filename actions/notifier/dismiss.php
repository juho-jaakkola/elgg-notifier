<?php
/**
 * Mark all notifications read
 *
 * @package Notifier
 */

$notifications = notifier_get_unread();

foreach ($notifications as $item) {
	$item->markRead();
}

system_message(elgg_echo('notifier:message:dismissed_all'));