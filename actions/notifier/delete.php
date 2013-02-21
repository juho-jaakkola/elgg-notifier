<?php
/**
 * Delete notifier notification entity
 *
 * @package Notifier
 */

$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

if (elgg_instanceof($entity, 'object', 'notification') && $entity->canEdit()) {
	if ($entity->delete()) {
		system_message(elgg_echo('notifier:message:deleted'));
	} else {
		register_error(elgg_echo('notifier:error:cannot_delete'));
	}
} else {
	register_error(elgg_echo('notifier:error:not_found'));
}

forward(REFERER);