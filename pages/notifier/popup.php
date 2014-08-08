<?php
/**
 * Display a notifications list that can be added to the notifications popup.
 */

$notifications = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => 'DESC'
	),
));

if ($notifications) {
	// Use "widgets" context to tell that we're displaying the popup instead of a full list
	elgg_push_context('widgets');

	$list = '';
	foreach ($notifications as $notification) {
		$list_item = elgg_view_list_item($notification, array(
			'full_view' => false,
		));

		$list .= "<li id=\"elgg-object-{$notification->guid}\" class=\"elgg-item elgg-item-object elgg-item-object-notification\">$list_item</li>";
	}

	elgg_pop_context();
} else {
	$list = null;
}

echo $list;
