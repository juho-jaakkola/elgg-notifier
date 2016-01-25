<?php

elgg_push_context('widgets');
echo elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'notification',
	'owner_guid' => (int) elgg_get_logged_in_user_guid(),
	'order_by_metadata' => array(
		'name' => 'status',
		'direction' => 'DESC'
	),
	'list_class' => 'elgg-list-notifier',
	'full_view' => false,
	'pagination' => (!elgg_is_xhr()),
));

elgg_pop_context();
