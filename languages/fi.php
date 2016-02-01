<?php

return array(
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
	'river:mention:object:comment' => '%s mainitsi sinut kohteessa %s',

	// This is used to create messages like "Lisa and George like your post"
	'notifier:two_subjects' => '%s ja %s',
	// This is used to create messages like "Lisa and 5 others like your post"
	'notifier:multiple_subjects' => '%s ja %s muuta',

	// Likes plugin
	'likes:notifications:summary' => '%s tykkää kohteesta %s',
	'likes:notifications:summary:2' => '%s ja %s tykkäävät kohteesta %s',
	'likes:notifications:summary:n' => '%s tykkäävät kohteesta %s',

	// Friends
	'friend:notifications:summary' => '%s lisäsi sinut ystäväkseen',
	'friend:notifications:summary:2' => '%s ja %s lisäsivät sinut ystäväkseen',
	'friend:notifications:summary:n' => '%s lisäsivät sinut ystäväkseen',

	// Comments
	'comment:notifications:summary' => '%s kommentoi kohdetta %s',
	'comment:notifications:summary:2' => '%s ja %s kommentoivat kohdetta %s',
	'comment:notifications:summary:n' => '%s kommentoivat kohdetta %s',

	// Groups
	'groups:notifications:invitation' => '%s on kutsunut sinut ryhmään %s',
	'groups:notifications:invitation:hidden' => 'Sinulla on uusi %s henkilöltä %s',
	'groups:notifications:membership_request' => '%s on anonut jäsenyyttä ryhmään %s',
	'groups:invitation' => 'ryhmäkutsu',
);
