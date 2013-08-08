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

	// Likes plugin
	'likes:notifications:summary' => '%s tykkää kohteesta %s',
	'likes:notifications:summary:2' => '%s ja %s tykkäävät kohteesta %s',
	'likes:notifications:summary:n' => '%s ja %s muuta tykkäävät kohteesta %s',

	// Plugin settings
	'notifier:settings:desc' => 'Oletusasetukset uusille käyttäjille',
	'notifier:settings:enable_personal' => 'Henkilökohtaiset ilmoitukset',
	'notifier:settings:enable_personal:desc' => 'Käyttäjä saa ilmoituksen, kun joku suorittaa toiminnon (tykkäys, kommentointi, tms) hänen luomalleen sisällölle.',
	'notifier:settings:enable_collections' => 'Ilmoitukset ystävien toiminnasta',
	'notifier:settings:enable_collections:desc' => 'Käyttäjä saa ilmoituksen, kun joku hänen ystävistään luo uutta sisältöä.',
	'notifier:settings:groups:desc' => 'Oletusasetukset ryhmien uusille jäsenille',
	'notifier:settings:enable_groups' => 'Ryhmien ilmoitukset',
	'notifier:settings:enable_groups:desc' => 'Käyttäjä saa ilmoituksen, kun joku lisää uutta sisältöä ryhmään, jossa hän on jäsenenä.',

	// Admin panel
	'admin:notifier' => 'Notifier',
	'admin:notifier:enable' => 'Ilmoitusten käyttöönotto',
	'notifier:admin:enable:description_link' => 'tänne',
	'notifier:admin:enable:description' => 'Nämä asetukset vaikuttavat vain uusiin käyttäjiin. Jos haluat aktivoida ilmoitukset myös vanhoille käyttäjille, siirry <u>%s</u>.',
	'notifier:admin:enable:warning' => "<strong>Varoitus!</strong> Ilmoitusten käyttäminen saattaa aiheuttaa paljon tallennuksia tietokantaan. Tästä syystä toiminto ei sovi suurille sivustoille.",
	'notifier:admin:not_using_personal' => "Sivustolla on %s käyttäjää, jotka eivät ole aktivoineet henkilökohtaisia ilmoituksia.",
	'notifier:admin:not_using_collections' => "Sivustolla on %s käyttäjää, jotka eivät ole aktivoineet ilmoituksia ystävien toiminnasta.",
	'notifier:admin:not_using_groups' => "Sivustolla on %s ryhmän jäsenyyttä, joihin ei ole aktivoitu ilmoituksia ryhmän toiminnasta.",
	'notifier:admin:activate' => 'Aktivoi',
	'notifier:admin:all_enabled' => 'Kaikki ilmoitukset on jo otettu käyttöön!',
);

add_translation('fi', $lang);