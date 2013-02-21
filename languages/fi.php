<?php

$lang = array(
	'notifier:' => '',
	'notifier:notification' => 'Ilmoitus',
	'notifier:notifications' => 'Ilmoitukset',
	'notifier:view:all' => 'Näytä kaikki',
	'notifier:all' => 'Kaikki ilmoitukset',
	'notifier:none' => 'Ei ilmoituksia',
	'notifier:unreadcount' => 'Lukemattomat ilmoitukset (%s)',
	'notification:method:notifier' => 'Ilmoitukset',
	'notifier:dismiss_all' => 'Merkitse luetuiksi',
	'notifier:clear_all' => 'Tyhjennä',
	'notifier:deleteconfirm' => 'Tämä poistaa kaikki ilmoitukset riippumatta siitä, oletko lukenut ne. Haluatko varmasti jatkaa?',

	'item:object:notification' => 'Notifier-ilmoitukset',

	// System messages
	'notifier:message:dismissed_all' => 'Merkittiin kaikki ilmoitukset luetuiksi',
	'notifier:message:deleted_all' => 'Poistettiin kaikki ilmoitukset',
	'notifier:message:deleted' => 'Poistettiin ilmoitus',

	// Error messages
	'notifier:error:not_found' => 'Ilmoitusta ei löytynyt',
	'notifier:error:target_not_found' => 'Ilmoituksen kohdetta ei löytynyt, joten se on todennäköisesti poistettu.',
	'notifier:error:cannot_delete' => 'Ilmoituksen poistaminen epäonnistui',

	// River strings that are not available in Elgg core
	'river:comment:object:groupforumtopic' => '%s vastasi keskusteluun %s',
);

add_translation('fi', $lang);