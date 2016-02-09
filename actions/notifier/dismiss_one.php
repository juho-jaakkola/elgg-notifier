<?php
/**
 * Mark a notifications read
 *
 * @package Notifier
 */

$guid = get_input('guid');
$item = get_entity($guid);

if ($item instanceof ElggNotification) {
	$item->markRead();
}