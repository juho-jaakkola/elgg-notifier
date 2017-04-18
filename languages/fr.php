<?php

return array(
	'notifier:' => "",
	'notifier:notification' => "Notification",
	'notifier:notifications' => "Notifications",
	'notifier:view:all' => "Voir toutes les notifications",
	'notifier:all' => "Toutes les notifications",
	'notifier:none' => "Pas de notifications",
	'notifier:unreadcount' => "Notifications non lues (%s)",
	'notification:method:notifier' => "Notifier",
	'notifier:dismiss_all' => "Rejeter la totalité",
	'notifier:clear_all' => "Tout effacer",
	'notifier:deleteconfirm' => "Cela supprime toutes les notifications, y compris celles non lus. Etes-vous sûr de vouloir continuer ?",

	'item:object:notification' => "Notifications",

	// System messages
	'notifier:message:dismissed_all' => "Toutes les notifications rejetées avec succès",
	'notifier:message:deleted_all' => "Toutes les notifications effacés avec succès",
	'notifier:message:deleted' => "Notifications supprimées",

	// Error messages
	'notifier:error:not_found' => "Cette notification n'a pas été trouvée",
	'notifier:error:target_not_found' => "Le contenu n'a pas été trouvé, il a donc probablement été supprimé.",
	'notifier:error:cannot_delete' => "Impossible de supprimer la notification",

	// River strings that are not available in Elgg core
	'river:comment:object:groupforumtopic' => "%s a répondu sur le sujet de discussion %s",
	'river:mention:object:comment' => "%s vous a mentionné dans %s",

	// This is used to create messages like "Lisa and George aiment votre post"
	'notifier:two_subjects' => "%s et %s",
	// This is used to create messages like "Lisa and 5 autres aiment votre post"
	'notifier:multiple_subjects' => "%s et %s autres utilisateurs",

	// Likes plugin
	'likes:notifications:summary' => "%s aiment votre post %s",
	'likes:notifications:summary:2' => "%s et %s aiment votre post %s",
	'likes:notifications:summary:n' => "%s aiment votre post %s",

	// Friends
	'friend:notifications:summary' => "%s a fait de vous un contact",
	'friend:notifications:summary:2' => "%s et %s ont fait de vous un contact",
	'friend:notifications:summary:n' => "%s ont fait de vous un contact",

	// Comments
	'comment:notifications:summary' => "%s a commenté %s",
	'comment:notifications:summary:2' => "%s et %s ont commenté %s",
	'comment:notifications:summary:n' => "%s a commenté %s",

	// Groups
	'groups:notifications:invitation' => "%s vous a invité dans le groupe %s",
	'groups:notifications:invitation:hidden' => "Vous avez une nouvelle %s de %s",
	'groups:notifications:membership_request' => "%s a demandé à être membre de ce groupe %s",
	'groups:invitation' => "Invitation de groupe",	
);
