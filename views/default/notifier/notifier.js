// <script>

/**
 * Javascript for the notifier plugin
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

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
	function popupHandler(hook, type, params, options) {
		if (!params.target.hasClass('elgg-notifier-popup')) {
			return;
		}

		// Due to elgg.ui.popup's design, there's no way to verify in a click handler whether the
		// click will actually be closing the popup after this hook. This is the only chance to verify
		// whether the popup is visible or not.
		if (params.target.is(':visible')) {
			// user clicked the icon to close, we're just letting it close
			return;
		}

		populatePopup();

		options.my = 'left top';
		options.at = 'left bottom+5px';
		options.collision = 'fit fit';
		return options;
	}

	/**
	 * Dismiss all unread notifications and then remove related UI elements.
	 *
	 * @return void
	 */
	function dismissAll() {
		// start delete on server but don't block
		elgg.action(this.href);

		// Remove notification count from topbar icon
		$('#notifier-new').remove();

		// Remove highlighting from the unread notifications one by one
		function dismiss() {
			var $unread = $('.elgg-notifier-unread:first');
			if ($unread.length) {
				$unread.removeClass('elgg-notifier-unread');
			} else {
				clearInterval(dismissing);
				// close popup
				$('body').trigger('click');
			}
		}

		var dismissing = setInterval(dismiss, 100);
		dismiss();

		// Fade and remove "Dismiss all" button
		$('#notifier-dismiss-all').fadeOut().remove();

		return false;
	}

	/**
	 * Fetch notifications and display them in the popup module.
	 *
	 * @return void
	 */
	function populatePopup() {
		var $loader = $('<div>').addClass('elgg-ajax-loader');

		elgg.action('notifier/load', {
			beforeSend: function () {
				$('#notifier-messages').html($loader);
			},
			complete: function () {
				$loader.remove();
			},
			success: function (response) {

				if (response.status !== 0) {
					return;
				}

				// Populate the list
				$('#notifier-messages').html(response.output.list);

				// Toggle the "View all" link
				if (response.output.count > 0) {
					$('#notifier-view-all').removeClass('hidden');
				} else {
					$('#notifier-view-all').addClass('hidden');
				}

				// Toggle the "Dismiss all" icon
				if (response.output.unread > 0) {
					$('#notifier-dismiss-all').removeClass('hidden');
					$('#notifier-new').text(response.output.unread).removeClass('hidden');
				} else {
					$('#notifier-dismiss-all').addClass('hidden');
					$('#notifier-new').text(response.output.unread).addClass('hidden');
				}

				// Bind lightbox to the new links
				elgg.ui.lightbox.bind(".elgg-lightbox");
			}
		});
	}

	$('#notifier-dismiss-all').on('click', dismissAll);

	elgg.register_hook_handler('getOptions', 'ui.popup', popupHandler);
});
