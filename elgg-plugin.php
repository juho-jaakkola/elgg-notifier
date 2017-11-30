<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'notification',
			'class' => 'ElggNotification',
		],
	],
	'actions' => [
		'notifier/dismiss' => [],
		'notifier/dismiss_one' => [],
		'notifier/clear' => [],
		'notifier/delete' => [],
	],
];
