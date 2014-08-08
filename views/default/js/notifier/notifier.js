// <script>

/**
 * Javascript for the notifier plugin
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

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
	var popupHandler = function(hook, type, params, options) {
		if (params.target.hasClass('elgg-notifier-popup')) {
			options.my = 'left top';
			options.at = 'left bottom';
			options.collision = 'fit none';
			return options;
		}
		return null;
	};

	/**
	 * Dismiss all unread notifications and then remove related UI elements.
	 *
	 * @param {Object} e
	 * @return void
	 */
	var dismissAll = function(e) {
		elgg.action($(this).attr('href'), {
			success: function() {
				// Remove highlighting from the unread notifications
				$('.elgg-notifier-unread').each(function() {
					$(this).removeClass('elgg-notifier-unread');
				});
				// Remove notification count from topbar icon
				$('#notifier-new').remove();
				// Remove "Dismiss all" button
				$('#notifier-dismiss-all').remove();
			}
		});

		e.preventDefault();
	};

	/**
	 * Fetch notifications and display them in the popup module.
	 *
	 * @param {Object} e
	 * @return void
	 */
	var popup = function(e) {
		elgg.get('notifier/popup', {
			success: function(output) {
				if (output) {
					// Add the received <li> elements into the list
					$('#notifier-popup > .elgg-body > ul').html(output);

					// Hide the "No notifications" texts
					$('.notifier-none').attr('hidden', '');

					// Display the "View all" link
					$('#notifier-view-all').removeAttr('hidden');

					// Check if there are unread notifications
					$('.elgg-notifier-unread').each(function() {
						// Display the "Dismiss all" icon
						$('#notifier-dismiss-all').removeAttr('hidden');

						return false;
					});

					// Check if there are links that trigger a lightbox
					$('#notifier-popup .elgg-lightbox').each(function() {
						// Bind lightbox to the new links
						elgg.ui.lightbox.bind(".elgg-lightbox");
						return false;
					});
				} else {
					// Hide the "Dismiss all" icon
					$('#notifier-dismiss-all').attr('hidden', '');

					// Hide the "View all" link
					$('#notifier-view-all').attr('hidden', '');

					// Display the "No notifications" text
					$('.notifier-none').removeAttr('hidden');
				}
			}
		});

		e.preventDefault();
	};

	$('#notifier-dismiss-all').live('click', dismissAll);
	$('#notifier-popup-link').live('click', popup);

	elgg.register_hook_handler('getOptions', 'ui.popup', popupHandler);
});
