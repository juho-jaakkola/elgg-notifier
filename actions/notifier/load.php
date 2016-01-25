<?php

elgg_ajax_gatekeeper();

echo json_encode(array(
	'list' => elgg_view('lists/notifications'),
	'unread' => (int) notifier_count_unread(),
	'count' => (int) notifier_count_all(),
));

