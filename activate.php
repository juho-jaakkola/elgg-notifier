<?php
/**
 * Register the ElggNotication class for the object/notification subtype
 */

if (get_subtype_id('object', 'notification')) {
	update_subtype('object', 'notification', 'ElggNotification');
} else {
	add_subtype('object', 'notification', 'ElggNotification');
}
