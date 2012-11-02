<?php
/**
 * notifier JavaScript extension for elgg.js
 */
?>
elgg.provide('elgg.notifier');

elgg.notifier.init = function() {
	
};

/**
 * Mark notification read
 *
 * @todo Finish this function
 */
elgg.notifier.markNotificationRead = function() {
	notification.animate({backgroundColor: '#ffffff'}, 1000).removeClass('elgg-notifier-unread');
};

/**
 * Repositions the notifier popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.notifierPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-notifier-popup')) {
		options.my = 'left top';
		options.at = 'left bottom';
		return options;
	}
	return null;
};

elgg.register_hook_handler('init', 'system', elgg.notifier.init);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.notifierPopupHandler);
