<?php

return array(
	'notifier:' => '',
	'notifier:notification' => 'Melding',
	'notifier:notifications' => 'Meldingen',
	'notifier:view:all' => 'Bekijk alle meldingen',
	'notifier:all' => 'Alle meldingen',
	'notifier:none' => 'Geen meldingen',
	'notifier:unreadcount' => 'Ongelezen meldingen (%s)',
	'notification:method:notifier' => 'Notifier',
	'notifier:dismiss_all' => 'Markeer alles als gelezen',
	'notifier:clear_all' => 'Verwijder alles',
	'notifier:deleteconfirm' => 'Dit verwijderd alle meldingen, ook de ongelezen. Ben je zeker dat je wil doorgaan?',

	'item:object:notification' => 'Notifier meldingen',

	// System messages
	'notifier:message:dismissed_all' => 'Alle meldingen zijn gemarkeerd als gelezen',
	'notifier:message:deleted_all' => 'Alle meldingen zijn verwijderd',
	'notifier:message:deleted' => 'Melding verwijderd',

	// Error messages
	'notifier:error:not_found' => 'Deze melding werd niet gevonden',
	'notifier:error:target_not_found' => 'We konden de inhoud niet vinden, dus het is waarschijnlijk verwijderd.',
	'notifier:error:cannot_delete' => 'Kan de melding niet verwijderen',

	// River strings that are not available in Elgg core
	'river:comment:object:groupforumtopic' => '%s antwoordde op discussie onderwerp %s', 
	'river:mention:object:comment' => '%s vernoemde je in %s',

	// This is used to create messages like "Lisa and George like your post"
	'notifier:two_subjects' => '%s en %s',
	// This is used to create messages like "Lisa and 5 others like your post"
	'notifier:multiple_subjects' => '%s en %s andere gebruikers',

	// Likes plugin
	'likes:notifications:summary' => '%s vindt jouw post leuk %s',
	'likes:notifications:summary:2' => '%s en %s vinden jouw post leuk %s',
	'likes:notifications:summary:n' => '%s vindt jouw post leuk %s',

	// Friends
	'friend:notifications:summary' => '%s voegde je toe als vriend',
	'friend:notifications:summary:2' => '%s en %s voegden je toe als vriend',
	'friend:notifications:summary:n' => '%s voegde je toe als vriend',

	// Comments
	'comment:notifications:summary' => '%s antwoordde %s',
	'comment:notifications:summary:2' => '%s en %s antwoordde %s',
	'comment:notifications:summary:n' => '%s antwoordde %s',

	// Groups
	'groups:notifications:invitation' => '%s nodigde je uit voor de groep %s',
	'groups:notifications:invitation:hidden' => 'Je hebt een nieuwe %s van %s',
	'groups:notifications:membership_request' => '%s vroeg het lidmaatschap aan voor de groep %s',
	'groups:invitation' => 'groep uitnodiging',
);