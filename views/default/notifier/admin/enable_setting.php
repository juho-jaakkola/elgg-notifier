<?php

$setting = elgg_extract('setting', $vars);
$count = elgg_extract('count', $vars);

$enable_desc = elgg_echo("notifier:admin:not_using_$setting", array($count));

$link = elgg_view('output/url', array(
	'text' => elgg_echo('notifier:admin:activate'),
	'href' => '#',
	'id' => "notifier-enable-$setting",
	'data-operation' => $setting,
	'class' => 'elgg-button elgg-button-action',
));

echo <<<HTML
	<div class="elgg-border-plain pvl phm mvl">
		<p>$enable_desc</p>
		<div class="elgg-notifier-progressbar" data-total="$count" id="notifier-progressbar-$setting"></div>
		$link
	</div>
HTML;
