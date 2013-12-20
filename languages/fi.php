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

	// Plugin settings
	'notifier:settings:desc' => 'Oletusasetukset uusille käyttäjille',
	'notifier:settings:enable_personal' => 'Henkilökohtaiset ilmoitukset',
	'notifier:settings:enable_personal:desc' => 'Käyttäjä saa ilmoituksen, kun joku suorittaa toiminnon (tykkäys, kommentointi, tms) hänen luomalleen sisällölle.',
	'notifier:settings:enable_collections' => 'Ilmoitukset ystävien toiminnasta',
	'notifier:settings:enable_collections:desc' => 'Käyttäjä saa ilmoituksen, kun joku hänen ystävistään luo uutta sisältöä.',
	'notifier:settings:groups:desc' => 'Oletusasetukset ryhmien uusille jäsenille',
	'notifier:settings:enable_groups' => 'Ryhmien ilmoitukset',
	'notifier:settings:enable_groups:desc' => 'Käyttäjä saa ilmoituksen, kun joku lisää uutta sisältöä ryhmään, jossa hän on jäsenenä.',
);

add_translation('fi', $lang);